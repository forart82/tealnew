<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\UserType;
use App\Form\UserVerificationType;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use App\Services\SendMailer;
use StaticCounter;
use Symfony\Contracts\Translation\TranslatorInterface;


/**
 * @Route("/user")
 */
class UserController extends AbstractController
{
  private $request;
  private $userRepository;
  private $userPasswordEncoderInterface;
  private $entityManagerInterface;
  private $sessionInterface;
  private $translator;
  private $sendMailer;

  public function __construct(
    RequestStack $requestStack,
    UserRepository $userRepository,
    UserPasswordEncoderInterface $userPasswordEncoderInterface,
    EntityManagerInterface $entityManagerInterface,
    SessionInterface $sessionInterface,
    TranslatorInterface $translator,
    SendMailer $sendMailer
  ) {
    $this->request = $requestStack->getCurrentRequest();
    $this->userRepository = $userRepository;
    $this->userPasswordEncoderInterface = $userPasswordEncoderInterface;
    $this->entityManagerInterface = $entityManagerInterface;
    $this->sessionInterface = $sessionInterface;
    $this->translator = $translator;
    $this->sendMailer = $sendMailer;
  }

  /**
   * @Route("/", name="user", methods={"GET"})
   */
  public function index(): Response
  {
    return $this->render('MAIN/INDEX.html.twig', [
      'element_teal' => $this->userRepository->findAll(),
    ]);
  }

  /**
   * @Route("/new", name="user_new", methods={"GET","POST"})
   */
  public function new(): Response
  {
    $user = new User();
    $form = $this->createForm(UserType::class, $user);
    $form->handleRequest($this->request);

    if ($form->isSubmitted() && $form->isValid()) {
      $this->entityManagerInterface->persist($user);
      $this->entityManagerInterface->flush();

      return $this->redirectToRoute('user');
    }

    return $this->render('MAIN/NEW.html.twig', [
      'element_teal' => $user,
      'form' => $form->createView(),
    ]);
  }

  /**
   * @Route("/{id}/show", name="user_show", methods={"GET"})
   */
  public function show(User $user): Response
  {
    return $this->render('MAIN/SHOW.html.twig', [
      'element_teal' => $user,
    ]);
  }

  /**
   * @Route("/{id}/edit", name="user_edit", methods={"GET","POST"})
   */
  public function edit(User $user): Response
  {
    $form = $this->createForm(UserType::class, $user);
    $form->handleRequest($this->request);

    if ($form->isSubmitted() && $form->isValid()) {
      $this->getDoctrine()->getManager()->flush();

      return $this->redirectToRoute('user');
    }

    return $this->render('MAIN/EDIT.html.twig', [
      'element_teal' => $user,
      'form' => $form->createView(),
    ]);
  }
  /**
   * @Route("/expired", name="user_expired")
   */
  public function expired(): Response
  {
    return $this->render('user/expired.html.twig', []);
  }

  /**
   * @Route("/verification/{email}/{token}", name="user_verification", methods={"GET","POST"},
   * defaults={"email"="email@email.com", "token"="empty"})
   */
  public function verification($email, $token): Response
  {
    $user = $this->userRepository->findOneByEmail($email);
    $userToken = null;
    if ($user) {
      $token = preg_replace('/[\W]/', '', $token);
      if ($userToken = $user->getToken()) {
        if ($token == $userToken) {
          if ((date('U') - $user->getIsNew()) > 86400) {
            return $this->redirectToRoute('user_expired');
          }
          // TODO service for ip scan
          $form = $this->createForm(UserVerificationType::class, $user);
          $form->handleRequest($this->request);
          if ($form->isSubmitted() && $form->isValid()) {
            $encoded = $this->userPasswordEncoderInterface->encodePassword($user, $user->getPassword());
            $user->setPassword($encoded);
            $user->setToken("");
            $user->setIsNew(0);
            $this->entityManagerInterface->persist($user);
            $this->entityManagerInterface->flush();
            return $this->redirectToRoute('introduction');
          }
          return $this->render('user/verification.html.twig', [
            'form' => $form->createView(),
          ]);
        }
      }
    }
    return $this->redirectToRoute('app_login');
  }

  /**
   * @Route("/profil", name="user_profil")
   */
  public function profil(): Response
  {
    $user=$this->getUser();
    $form = $this->createForm(UserVerificationType::class, $user);
    $form->handleRequest($this->request);
    if ($form->isSubmitted() && $form->isValid()) {
      $encoded = $this->userPasswordEncoderInterface->encodePassword($user, $user->getPassword());
      $user->setPassword($encoded);
      $this->entityManagerInterface->persist($user);
      $this->entityManagerInterface->flush();
      return $this->redirectToRoute('introduction');
    }
    return $this->render('user/verification.html.twig', [
      'form' => $form->createView(),
    ]);
  }

  /**
   * @Route("/reinvite/{id}", name="user_reinvite", methods={"GET"})
   */
  public function reinvite($id): Response
  {


    if ($this->sendMailer->invitation(
      $this->userRepository->findOneById($id),
      $this->entityManagerInterface,
      $this->request->getHttpHost()
    )) {
      $this->addFlash('success', $this->translator->trans('tEmail ReSend'));
    }
    if ($route = $this->sessionInterface->get('last_route')) {
      return $this->redirectToRoute($route);
    }
    return $this->redirectToRoute('introduction');
  }

  /**
   * @Route("/reinviteall", name="user_reinvite_all")
   */
  public function reinviteAll(): Response
  {
    $users = $this->userRepository->findByCompany($this->getUser()->getCompany());
    foreach ($users as $user) {
      if ($user->getIsNew()) {
        if ($this->sendMailer->invitation(
          $user,
          $this->entityManagerInterface,
          $this->request->getHttpHost()
        )) {
          $this->addFlash('email send', 'success');
        }
      }
    }
    if ($route = $this->sessionInterface->get('last_route')) {
      return $this->redirectToRoute($route);
    }
    return $this->redirectToRoute('introduction');
  }

  /**
   * @Route("/repassword/", name="user_repassword")
   */
  public function repassword(): Response
  {
    $email = $this->request->request->get('email');
    if (filter_var($email, FILTER_VALIDATE_EMAIL)) {
      if ($this->sendMailer->repassword(
        $this->userRepository->findOneByEmail($email),
        $this->entityManagerInterface,
        $this->request->getHttpHost()
      ));
      return $this->redirectToRoute('app_login');
    }
    return $this->render('user/repassword.html.twig');
  }
  /**
   * @Route("/{id}", name="user_delete", methods={"DELETE"})
   */
  public function delete(User $user): Response
  {
    if ($this->isCsrfTokenValid('delete' . $user->getId(), $this->request->request->get('_token'))) {
      $this->entityManagerInterface->remove($user);
      $this->entityManagerInterface->flush();
    }

    return $this->redirectToRoute('user');
  }
}
