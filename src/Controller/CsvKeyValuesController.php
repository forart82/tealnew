<?php

namespace App\Controller;

use App\Entity\CsvKeyValues;
use App\Form\CsvKeyValuesType;
use App\Repository\CsvKeyValuesRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/csvkeyvalues")
 */
class CsvKeyValuesController extends AbstractController
{
    /**
     * @Route("/", name="csvkeyvalues", methods={"GET"})
     */
    public function index(CsvKeyValuesRepository $csvKeyValuesRepository): Response
    {
        return $this->render('MAIN/INDEX.html.twig', [
            'element_teal' => $csvKeyValuesRepository->findAll(),
        ]);
    }

    /**
     * @Route("/new", name="csvkeyvalues_new", methods={"GET","POST"})
     */
    public function new(Request $request): Response
    {
        $csvKeyValue = new CsvKeyValues();
        $form = $this->createForm(CsvKeyValuesType::class, $csvKeyValue);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($csvKeyValue);
            $entityManager->flush();

            return $this->redirectToRoute('csvkeyvalues');
        }

        return $this->render('MAIN/NEW.html.twig', [
            'element_teal' => $csvKeyValue,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}/show", name="csvkeyvalues_show", methods={"GET"})
     */
    public function show(CsvKeyValues $csvKeyValue): Response
    {
        return $this->render('MAIN/SHOW.html.twig', [
            'element_teal' => $csvKeyValue,
        ]);
    }

    /**
     * @Route("/{id}/edit", name="csvkeyvalues_edit", methods={"GET","POST"})
     */
    public function edit(Request $request, CsvKeyValues $csvKeyValue): Response
    {
        $form = $this->createForm(CsvKeyValuesType::class, $csvKeyValue);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('csvkeyvalues');
        }

        return $this->render('MAIN/EDIT.html.twig', [
            'element_teal' => $csvKeyValue,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="csvkeyvalues_delete", methods={"DELETE"})
     */
    public function delete(Request $request, CsvKeyValues $csvKeyValue): Response
    {
        if ($this->isCsrfTokenValid('delete'.$csvKeyValue->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($csvKeyValue);
            $entityManager->flush();
        }

        return $this->redirectToRoute('csvkeyvalues');
    }
}
