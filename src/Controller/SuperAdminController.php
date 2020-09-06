<?php

namespace App\Controller;

use App\Entity\Company;
use App\Entity\User;
use App\Form\CreateClientType;
use App\Services\CreateResults;
use App\Services\CreateToken;
use App\Services\SendMailer;
use App\Services\Statics\UniqueId;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\Routing\Annotation\Route;


/**
 * @Route("/superadmin")
 */
class SuperAdminController extends AbstractController
{
    private $entityManagerInterface;
    private $request;
    private $sendMailer;
    private $createResults;

    public function __construct(
        EntityManagerInterface $entityManagerInterface,
        RequestStack $requestStack,
        SendMailer $sendMailer,
        CreateResults $createResults
    ) {
        $this->entityManagerInterface = $entityManagerInterface;
        $this->request = $requestStack->getCurrentRequest();
        $this->sendMailer = $sendMailer;
        $this->createResults = $createResults;
    }

    /**
     * @Route("/", name="super_admin_create_client")
     */
    public function createClient()
    {
        $user = new User();
        $form = $this->createForm(CreateClientType::class, $user);

        $form->handleRequest($this->request);
        if ($form->isSubmitted() && $form->isValid()) {
            $company = new Company();
            $company->setName($form->get('company')['name']->getData())
                ->setLanguage($form->get('company')['language']->getData())
                ->setLogo(file_get_contents($form->get('company')['logo']->getData()))
                ->setMatricule($user->getEmail() . '-' . $company->getName());

            $user->setRoles(['ROLE_ADMIN'])
                ->setIsNew(true)
                ->setLanguage($company->getLanguage())
                ->setCompany($company)
                ->setPassword(UniqueId::createId());

            $this->entityManagerInterface->persist($company);
            $this->entityManagerInterface->persist($user);
            $this->entityManagerInterface->flush();

            // Create Results
            $this->createResults->create($this->entityManagerInterface, $user);

            // Send Email
            $this->sendMailer->invitation(
                $user,
                $this->entityManagerInterface,
                $this->request->getHttpHost()
            );
        }



        return $this->render('super_admin/createclient.html.twig', [
            "form" => $form->createView(),
        ]);
    }
}
