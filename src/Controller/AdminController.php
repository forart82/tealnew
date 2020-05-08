<?php

namespace App\Controller;

use App\Form\AdminCreateUserType;
use App\Repository\CompanyRepository;
use App\Repository\UserRepository;
use App\Services\SendMailer;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/admin")
 */
class AdminController extends AbstractController
{
  private $userRepository;
  private $sessionInterface;

  public function __construct(
    RequestStack $requestStack,
    CompanyRepository $companyRepository,
    UserRepository $userRepository,
    SessionInterface $sessionInterface
  ) {
    // TODO Find solution for problem when anyonym user try to charge site.
    $this->request=$requestStack->getCurrentRequest();
    $this->companyRepository = $companyRepository;
    $this->userRepository=$userRepository;
    $this->sessionInterface=$sessionInterface;
  }

  /**
   * @Route("/", name="admin", methods={"GET"})
   */
  public function company(): Response
  {
    $form=$this->createForm(AdminCreateUserType::class);
    $this->sessionInterface->set('last_route','admin');
    $company = $this->userRepository->findByCompany($this->getUser()->getCompany());
    return $this->render('admin/admin.html.twig', [
      "element_teal" => $company,
      'form'=>$form->createView(),
    ]);
  }
}
