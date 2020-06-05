<?php

namespace App\Controller;

use App\Entity\Subject;
use App\Form\SubjectType;
use App\Interfaces\ChangeList;
use App\Services\ChangeListValues;
use App\Repository\SubjectRepository;
use App\Repository\SvgRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

/**
 * @Route("/subject")
 */
class SubjectController extends AbstractController implements ChangeList
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
     * @Route("/", name="subject", methods={"GET"})
     */
    public function index(SubjectRepository $subjectRepository): Response
    {
        return $this->render('MAIN/INDEX.html.twig', [
            'element_teal' => $subjectRepository->findAll(),
        ]);
    }

    /**
     * @Route("/new", name="subject_new", methods={"GET","POST"})
     */
    public function new(Request $request): Response
    {
        $subject = new Subject();
        $form = $this->createForm(SubjectType::class, $subject);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($subject);
            $entityManager->flush();

            return $this->redirectToRoute('subject');
        }

        return $this->render('MAIN/NEW.html.twig', [
            'element_teal' => $subject,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}/show", name="subject_show", methods={"GET"})
     */
    public function show(Subject $subject): Response
    {
        return $this->render('MAIN/SHOW.html.twig', [
            'element_teal' => $subject,
        ]);
    }

    /**
     * @Route("/{id}/edit", name="subject_edit", methods={"GET","POST"})
     */
    public function edit(Request $request, Subject $subject): Response
    {
        $form = $this->createForm(SubjectType::class, $subject);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('subject');
        }

        return $this->render('MAIN/EDIT.html.twig', [
            'element_teal' => $subject,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="subject_delete", methods={"DELETE"})
     */
    public function delete(Request $request, Subject $subject): Response
    {
        if ($this->isCsrfTokenValid('delete' . $subject->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($subject);
            $entityManager->flush();
        }

        return $this->redirectToRoute('subject');
    }

    /**
     * @Route("/changelist", name="subject_change_list")
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
