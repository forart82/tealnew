<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\AdminCreateUserType;
use App\Interfaces\ChangeList;
use App\Repository\UserRepository;
use App\Services\ChangeListValues;
use App\Services\CreateToken;
use App\Services\GetRoles;
use App\Services\SendMailer;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/admin")
 */
class AdminController extends AbstractController implements ChangeList
{
    private $request;
    private $userRepository;
    private $sessionInterface;
    private $entityManagerInterface;
    private $sendMailer;

    public function __construct(
        RequestStack $requestStack,
        UserRepository $userRepository,
        SessionInterface $sessionInterface,
        EntityManagerInterface $entityManagerInterface,
        SendMailer $sendMailer
    ) {
        // TODO Find solution for problem when anyonym user try to charge site.
        $this->request = $requestStack->getCurrentRequest();
        $this->userRepository = $userRepository;
        $this->sessionInterface = $sessionInterface;
        $this->entityManagerInterface = $entityManagerInterface;
        $this->sendMailer = $sendMailer;
    }

    /**
     * @Route("/", name="admin", methods={"GET","POST"})
     */
    public function company(): Response
    {
        $user = new User();
        $form = $this->createForm(AdminCreateUserType::class, $user);
        $form->handleRequest($this->request);
        if ($form->isSubmitted() && $form->isValid()) {
            $user->setRoles(["ROLE_USER"]);
            $user->setIsNew(true);
            $user->setPassword(CreateToken::create());
            $user->setToken(CreateToken::create());
            $user->setLanguage($this->getUser()->getLanguage());
            $user->setCompany($this->getUser()->getCompany());
            $this->entityManagerInterface->persist($user);
            $this->entityManagerInterface->flush();

            $this->sendMailer->invitation(
                $user,
                $this->entityManagerInterface,
                $this->request->getHttpHost()
            );
        }

        $this->sessionInterface->set("last_route", "admin");
        $company = $this->userRepository->findByCompany($this->getUser()->getCompany());
        return $this->render("admin/admin.html.twig", [
            "element_teal" => $company,
            "form" => $form->createView(),
        ]);
    }

    /**
     * @Route("/changeadmin", name="admin_change_admin", methods={"POST"})
     */
    public function ajaxChangeAdmin(): Response
    {

        if ($this->request->isXmlHttpRequest()) {
            $data = $this->request->get('data');
            $oldClass = substr($data["oldClass"], 15);
            $eid = $data["eid"];
            $roles = new GetRoles();
            if ($selectedUser = $this->userRepository->findOneByEid($eid)) {
                $roles = $roles->getRoles($this->getUser()->getRoles()[0], $this->getParameter("security.role_hierarchy.roles"));
                $newClass = $roles[0];
                foreach ($roles as $key => $role) {
                    if ($oldClass == $role && $key + 1 < count($roles)) {
                        $newClass = $roles[$key + 1];
                    }
                }
                $selectedUser->setRoles([$newClass]);
                $this->entityManagerInterface->flush();
                return new JsonResponse([
                    "color" => "#00aaaa",
                    "newClass" => "list-teal-admin{$newClass}",
                    "oldClass" => "list-teal-admin{$oldClass}",
                    "eid" => $eid,
                ]);
            }
        }
        return new JsonResponse();
    }

    /**
     * @Route("/changelist", name="admin_change_list")
     */
    public function changeList(): Response
    {
        if ($this->request->isXmlHttpRequest()) {
            $data = $this->request->get("data");
            if (!empty($data['entity'])) {
                $repository = strtolower($data['entity']) . 'Repository';
                $obj = new ChangeListValues($this->entityManagerInterface);
                $obj->changeValues(
                    $this->$repository,
                    $data
                );
                return new JsonResponse($data);
            }
        }
        return new JsonResponse();
    }
}
