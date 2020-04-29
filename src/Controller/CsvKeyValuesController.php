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
     * @Route("/", name="csv_key_values_index", methods={"GET"})
     */
    public function index(CsvKeyValuesRepository $csvKeyValuesRepository): Response
    {
        return $this->render('csv_key_values/csv_key_values.html.twig', [
            'element_teal' => $csvKeyValuesRepository->findAll(),
        ]);
    }

    /**
     * @Route("/new", name="csv_key_values_new", methods={"GET","POST"})
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

            return $this->redirectToRoute('csv_key_values_index');
        }

        return $this->render('csv_key_values/new.html.twig', [
            'element_teal' => $csvKeyValue,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="csv_key_values_show", methods={"GET"})
     */
    public function show(CsvKeyValues $csvKeyValue): Response
    {
        return $this->render('element_teal/show.html.twig', [
            'element_teal' => $csvKeyValue,
        ]);
    }

    /**
     * @Route("/{id}/edit", name="csv_key_values_edit", methods={"GET","POST"})
     */
    public function edit(Request $request, CsvKeyValues $csvKeyValue): Response
    {
        $form = $this->createForm(CsvKeyValuesType::class, $csvKeyValue);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('csv_key_values_index');
        }

        return $this->render('csv_key_values/edit.html.twig', [
            'element_teal' => $csvKeyValue,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="csv_key_values_delete", methods={"DELETE"})
     */
    public function delete(Request $request, CsvKeyValues $csvKeyValue): Response
    {
        if ($this->isCsrfTokenValid('delete'.$csvKeyValue->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($csvKeyValue);
            $entityManager->flush();
        }

        return $this->redirectToRoute('csv_key_values_index');
    }
}
