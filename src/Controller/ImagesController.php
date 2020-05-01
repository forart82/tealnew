<?php

namespace App\Controller;

use App\Entity\Images;
use App\Form\ImagesType;
use App\Repository\ImagesRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Contracts\Translation\TranslatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

/**
 * @Route("/images")
 */
class ImagesController extends AbstractController
{
    public function __construct(TranslatorInterface $translator)
    {
        $this->translator = $translator;
    }

    /**
     * @Route("/", name="images_index", methods={"GET"})
     */
    public function index(ImagesRepository $imagesRepository): Response
    {
        return $this->render('MAIN/INDEX.html.twig', [
            'element_teal' => $imagesRepository->findAll(),
        ]);
    }

    /**
     * @Route("/new", name="images_new", methods={"GET","POST"})
     */
    public function new(Request $request): Response
    {
        $image = new Images();
        $form = $this->createForm(ImagesType::class, $image);
        $form->handleRequest($request);


        if ($form->isSubmitted() && $form->isValid()) {
            if ($form->get('svg')->getData()) {
                $svg = $image->getSvg();
                $color = $image->getSvgColor();
                $svg = preg_replace('/fill="[#0-9a-zA-z]+"/', '', $svg);
                $svg = preg_replace('/<svg/', '<svg fill="' . $color . '"', $svg);
                $entityManager = $this->getDoctrine()->getManager();
                $entityManager->persist($image);
                $entityManager->flush();
            } else {
                $this->addFlash('error', $this->translator->trans('tImageNeeded'));
                return $this->render('MAIN/NEW.html.twig', [
                    'element_teal' => $image,
                    'form' => $form->createView(),
                ]);
            }


            return $this->redirectToRoute('images_index');
        }

        return $this->render('MAIN/NEW.html.twig', [
            'element_teal' => $image,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="images_show", methods={"GET"})
     */
    public function show(Images $image): Response
    {
        return $this->render('MAIN/SHOW.html.twig', [
            'element_teal' => $image,
        ]);
    }

    /**
     * @Route("/{id}/edit", name="images_edit", methods={"GET","POST"})
     */
    public function edit(Request $request, Images $image): Response
    {
        $form = $this->createForm(ImagesType::class, $image);
        $form->handleRequest($request);

        // TODO save db content to svg file and load it into form
        if ($form->isSubmitted() && $form->isValid()) {
            if (!$form->get('svg')->getData()) {
                $svg = $image->getSvg();
            } else {
                $svg = file_get_contents($form->get('svg')->getData());
            }
            $color = $image->getSvgColor();
            $svg = preg_replace('/fill="[#0-9a-zA-z]+"/', '', $svg);
            $svg = preg_replace('/<svg/', '<svg fill="' . $color . '"', $svg);
            $image->setSvg($svg);
            // file_get_contents()
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('images_index');
        }

        return $this->render('MAIN/EDIT.html.twig', [
            'element_teal' => $image,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="images_delete", methods={"DELETE"})
     */
    public function delete(Request $request, Images $image): Response
    {
        if ($this->isCsrfTokenValid('delete' . $image->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($image);
            $entityManager->flush();
        }

        return $this->redirectToRoute('images_index');
    }
}
