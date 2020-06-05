<?php

namespace App\Controller;

use App\Entity\Language;
use App\Form\LanguageType;
use App\Interfaces\ChangeList;
use App\Services\ChangeListValues;
use App\Repository\LanguageRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\RequestStack;

/**
 * @Route("/language")
 */
class LanguageController extends AbstractController implements ChangeList
{
    private $request;
    private $entityManagerInterface;

    public function __construct(
        RequestStack $requestStack,
        EntityManagerInterface $entityManagerInterface
    ) {
        $this->request = $requestStack->getCurrentRequest();
        $this->entityManagerInterface = $entityManagerInterface;
    }

    /**
     * @Route("/", name="language", methods={"GET"})
     */
    public function index(LanguageRepository $languageRepository): Response
    {
        return $this->render('MAIN/INDEX.html.twig', [
            'element_teal' => $languageRepository->findAll(),
        ]);
    }

    /**
     * @Route("/new", name="language_new", methods={"GET","POST"})
     */
    function new(Request $request): Response
    {
        $language = new Language();
        $form = $this->createForm(LanguageType::class, $language);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($language);
            $entityManager->flush();

            return $this->redirectToRoute('language');
        }

        return $this->render('MAIN/NEW.html.twig', [
            'element_teal' => $language,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}/show", name="language_show", methods={"GET"})
     */
    public function show(Language $language): Response
    {
        return $this->render('MAIN/SHOW.html.twig', [
            'element_teal' => $language,
        ]);
    }

    /**
     * @Route("/{id}/edit", name="language_edit", methods={"GET","POST"})
     */
    public function edit(Request $request, Language $language): Response
    {
        $form = $this->createForm(LanguageType::class, $language);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('language');
        }

        return $this->render('MAIN/EDIT.html.twig', [
            'element_teal' => $language,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="language_delete", methods={"DELETE"})
     */
    public function delete(Request $request, Language $language): Response
    {
        if ($this->isCsrfTokenValid('delete' . $language->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($language);
            $entityManager->flush();
        }

        return $this->redirectToRoute('language');
    }

    /**
     * @Route("/changelist", name="language_change_list")
     */
    public function changeList(): Response
    {
        if ($this->request->isXmlHttpRequest()) {
            $data = $this->request->get("data");
            if (!empty($data['entity'])) {
                $obj = new ChangeListValues($this->entityManagerInterface);
                $obj->changeValues($data);
                return new JsonResponse($data);
            }
        }
        return new JsonResponse();
    }
}
