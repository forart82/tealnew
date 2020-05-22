<?php

namespace App\Controller;

use App\Entity\User;
use App\Services\SendMailer;
use App\Services\CreateToken;
use App\Form\AdminCreateUserType;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

/**
 * @Route("/admin")
 */
class AdminController extends AbstractController
{
  private $request;
  private $userRepository;
  private $sessionInterface;
  private $entityManagerInterface;
  private $sendMailer;

  public function __construct(
    RequestStack $requestStack,
    UserRepository $userRepository,
    SessionInterface $sessionInterface,
    EntityManagerInterface $entityManagerInterface,
    SendMailer $sendMailer
  ) {
    // TODO Find solution for problem when anyonym user try to charge site.
    $this->request = $requestStack->getCurrentRequest();
    $this->userRepository = $userRepository;
    $this->sessionInterface = $sessionInterface;
    $this->entityManagerInterface = $entityManagerInterface;
    $this->sendMailer = $sendMailer;
  }

  /**
   * @Route("/", name="admin", methods={"GET","POST"})
   */
  public function company(): Response
  {
    $user = new User();
    $form = $this->createForm(AdminCreateUserType::class, $user);
    $form->handleRequest($this->request);
    if ($form->isSubmitted() && $form->isValid()) {
      $user->setRoles(['ROLE_USER']);
      $user->setIsNew(true);
      $user->setPassword(CreateToken::create());
      $user->setToken(CreateToken::create());
      $user->setLanguage($this->getUser()->getLanguage());
      $user->setCompany($this->getUser()->getCompany());
      $this->entityManagerInterface->persist($user);
      $this->entityManagerInterface->flush();

      $this->sendMailer->invitation(
        $user,
        $this->entityManagerInterface,
        $this->request->getHttpHost()
      );
    }

    $this->sessionInterface->set('last_route', 'admin');
    $company = $this->userRepository->findByCompany($this->getUser()->getCompany());
    return $this->render('admin/admin.html.twig', [
      "element_teal" => $company,
      'form' => $form->createView(),
    ]);
  }

  /**
   * @Route("/changeadmin", name="admin_change_admin", methods={"POST"})
   */
  public function ajaxChangeAdmin(): Response
  {
    if ($this->request->isXmlHttpRequest()) {
      $role = substr($this->request->get('data'), 5);
      dump($role);
      switch ($role) {
        case 'ROLE_SUPER_ADMIN':
          
          break;
        case 'ROLE_ADMIN':
          break;
        case 'ROLE_USER':
          break;
        default:
          break;
      }
    }
    return new JsonResponse();
  }
}
