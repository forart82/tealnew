<?php

namespace App\Controller;

use App\Entity\Svg;
use App\Form\SvgType;
use App\Repository\SvgRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Contracts\Translation\TranslatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;


/**
 * @Route("/svg")
 */
class SvgController extends AbstractController
{
    public function __construct(TranslatorInterface $translator)
    {
        $this->translator = $translator;
    }

    /**
     * @Route("/", name="svg", methods={"GET"})
     */
    public function index(SvgRepository $svgRepository): Response
    {
        return $this->render('MAIN/INDEX.html.twig', [
            'element_teal' => $svgRepository->findAll(),
        ]);
    }

    /**
     * @Route("/new", name="svg_new", methods={"GET","POST"})
     */
    public function new(Request $request): Response
    {
        $svg = new Svg();
        $form = $this->createForm(SvgType::class, $svg);
        $form->handleRequest($request);


        if ($form->isSubmitted() && $form->isValid()) {
            if ($form->get('svg')->getData()) {
                $svgFile = file_get_contents($form->get('svg')->getData());
                $color = $form->get('svgColor')->getData();
                $svgFile = preg_replace('/fill="[#0-9a-zA-z]+"/', '', $svgFile);
                $svgFile = preg_replace('/<svg/', '<svg fill="' . $color . '"', $svgFile);
                $svg->setSvg($svgFile);
                $entityManager = $this->getDoctrine()->getManager();
                $entityManager->persist($svg);
                $entityManager->flush();
            } else {
                $this->addFlash('error', $this->translator->trans('tSvgNeeded'));
                return $this->render('MAIN/NEW.html.twig', [
                    'element_teal' => $svg,
                    'form' => $form->createView(),
                ]);
            }


            return $this->redirectToRoute('svg');
        }

        return $this->render('MAIN/NEW.html.twig', [
            'element_teal' => $svg,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}/show", name="svg_show", methods={"GET"})
     */
    public function show(Svg $svg): Response
    {
        return $this->render('MAIN/SHOW.html.twig', [
            'element_teal' => $svg,
        ]);
    }

    /**
     * @Route("/{id}/edit", name="svg_edit", methods={"GET","POST"})
     */
    public function edit(Request $request, Svg $svg): Response
    {
        $form = $this->createForm(SvgType::class, $svg);
        $form->handleRequest($request);

        // TODO save db content to svg file and load it into form
        if ($form->isSubmitted() && $form->isValid()) {
            if (!$form->get('svg')->getData()) {
                $svgFile = $svg->getSvg();
            } else {
                $svgFile = file_get_contents($form->get('svg')->getData());
            }
            $color = $svg->getSvgColor();
            $svgFile = preg_replace('/fill="[#0-9a-zA-z]+"/', '', $svgFile);
            $svgFile = preg_replace('/<svg/', '<svg fill="' . $color . '"', $svgFile);
            $svg->setSvg($svgFile);
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($svg);
            $entityManager->flush();

            return $this->redirectToRoute('svg');
        }

        return $this->render('MAIN/EDIT.html.twig', [
            'element_teal' => $svg,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="svg_delete", methods={"DELETE"})
     */
    public function delete(Request $request, Svg $svg): Response
    {
        if ($this->isCsrfTokenValid('delete' . $svg->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($svg);
            $entityManager->flush();
        }

        return $this->redirectToRoute('svg');
    }
}
