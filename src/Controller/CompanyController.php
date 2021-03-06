<?php

namespace App\Controller;

use App\Entity\Company;
use App\Form\CompanyType;
use App\Repository\CompanyRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/company")
 */
class CompanyController extends AbstractController
{
    /**
     * @Route("/", name="company", methods={"GET"})
     */
    public function company(CompanyRepository $companyRepository): Response
    {
        return $this->render('MAIN/INDEX.html.twig', [
            'element_teal' => $companyRepository->findAll(),
        ]);
    }

    /**
     * @Route("/new", name="company_new", methods={"GET","POST"})
     */
    public function new(Request $request): Response
    {
        $company = new Company();
        $form = $this->createForm(CompanyType::class, $company);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($company);
            $entityManager->flush();

            return $this->redirectToRoute('company');
        }

        return $this->render('MAIN/NEW.html.twig', [
            'element_teal' => $company,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}/show", name="company_show", methods={"GET"})
     */
    public function show(Company $company): Response
    {
        return $this->render('MAIN/SHOW.html.twig', [
            'element_teal' => $company,
        ]);
    }

    /**
     * @Route("/{id}/edit", name="company_edit", methods={"GET","POST"})
     */
    public function edit(Request $request, Company $company): Response
    {
        $form = $this->createForm(CompanyType::class, $company);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $company->setLogo(file_get_contents($form->get('logo')->getData()));
            $this->getDoctrine()->getManager()->persist($company);
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('company');
        }
        $companyImage = base64_encode(stream_get_contents($company->getLogo()));
        return $this->render('MAIN/EDIT.html.twig', [
            'element_teal' => $company,
            'form' => $form->createView(),
            'companyImage' => $companyImage,
        ]);
    }

    /**
     * @Route("/{id}", name="company_delete", methods={"DELETE"})
     */
    public function delete(Request $request, Company $company): Response
    {
        if ($this->isCsrfTokenValid('delete' . $company->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($company);
            $entityManager->flush();
        }

        return $this->redirectToRoute('company');
    }
}
