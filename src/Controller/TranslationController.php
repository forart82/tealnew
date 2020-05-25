<?php

namespace App\Controller;

use App\Entity\Translation;
use App\Form\TranslationType;
use App\Services\ChangeListValues;
use App\Interfaces\ChangeList;
use App\Repository\KeytextRepository;
use App\Repository\TranslationRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Contracts\Translation\TranslatorInterface;
use Doctrine\ORM\EntityManagerInterface;




/**
 * @Route("/translation")
 */
class TranslationController extends AbstractController implements ChangeList
{

    private $request;
    private $translator;
    private $entityManagerInterface;
    private $translationRepository;
    private $keytextRepository;

    public function __construct(
        RequestStack $requestStack,
        TranslatorInterface $translator,
        EntityManagerInterface $entityManagerInterface,
        TranslationRepository $translationRepository,
        KeytextRepository $keytextRepository
    ) {
        $this->request = $requestStack->getCurrentRequest();
        $this->translator = $translator;
        $this->entityManagerInterface = $entityManagerInterface;
        $this->translationRepository = $translationRepository;
        $this->keytextRepository = $keytextRepository;
    }

    /**
     * @Route("/", name="translation", methods={"GET"})
     */
    public function index(TranslationRepository $translationRepository): Response
    {
        return $this->render('MAIN/INDEX.html.twig', [
            'element_teal' => $translationRepository->findAll(),
        ]);
    }

    /**
     * @Route("/new", name="translation_new", methods={"GET","POST"})
     */
    public function new(Request $request): Response
    {
        $translation = new Translation();
        $form = $this->createForm(TranslationType::class, $translation);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($translation);
            $entityManager->flush();

            return $this->redirectToRoute('translation');
        }

        return $this->render('MAIN/NEW.html.twig', [
            'element_teal' => $translation,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}/show", name="translation_show", methods={"GET"})
     */
    public function show(Translation $translation): Response
    {
        return $this->render('MAIN/SHOW.html.twig', [
            'element_teal' => $translation,
        ]);
    }

    /**
     * @Route("/{id}/edit", name="translation_edit", methods={"GET","POST"})
     */
    public function edit(Request $request, Translation $translation): Response
    {
        $form = $this->createForm(TranslationType::class, $translation);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('translation');
        }

        return $this->render('MAIN/EDIT.html.twig', [
            'element_teal' => $translation,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="translation_delete", methods={"DELETE"})
     */
    public function delete(Request $request, Translation $translation): Response
    {
        if ($this->isCsrfTokenValid('delete' . $translation->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($translation);
            $entityManager->flush();
        }

        return $this->redirectToRoute('translation');
    }

    /**
     * @Route("/changelist", name="admin_change_list")
     */
    public function changeList(): Response
    {
        if ($this->request->isXmlHttpRequest()) {
            $data = $this->request->get("data");
            $obj = new ChangeListValues($this->entityManagerInterface);
            $obj->changeValues(
                [
                    'Translation' => $this->translationRepository,
                    'Keytext' => $this->keytextRepository,
                ],
                $data
            );
            return new JsonResponse($data);
        }
        return new JsonResponse();
    }
}
