<?php

namespace App\Controller;

use App\Entity\Company;
use App\Form\CompanyType;
use App\Form\AdminCreateUserType;
use App\Repository\CompanyRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/admin")
 */
class AdminController extends AbstractController
{
  private $request;
  private $companyRepository;

  public function __construct(
    RequestStack $requestStack,
    CompanyRepository $companyRepository
  ) {
    $this->request=$requestStack->getCurrentRequest();
    $this->companyRepository = $companyRepository;
  }

  /**
   * @Route("/", name="admin", methods={"GET"})
   */
  public function company(): Response
  {
    $form=$this->createForm(AdminCreateUserType::class);


    $company = $this->getUser()->getCompany();
    return $this->render('admin/admin.html.twig', [
      "element_teal" => $company,
      'form'=>$form->createView(),
    ]);
  }
}
