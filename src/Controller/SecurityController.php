<?php

namespace App\Controller;

use App\Services\CheckLanguage;
use App\Repository\LanguageRepository;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Config\Definition\Exception\Exception;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;

class SecurityController extends AbstractController
{
    private $languageRepository;
    private $parameterBagInterface;
    public function __construct(
        LanguageRepository $languageRepository,
        ParameterBagInterface $parameterBagInterface
    )
    {
        $this->languageRepository = $languageRepository;
        $this->parameterBagInterface = $parameterBagInterface;
    }
    /**
     * @Route("/login", name="app_login")
     */
    public function login(AuthenticationUtils $authenticationUtils): Response
    {
        // get the login error if there is one
        $error = $authenticationUtils->getLastAuthenticationError();

        // last username entered by the user
        $lastUsername = $authenticationUtils->getLastUsername();
        return $this->render('security/login.html.twig', ['last_username' => $lastUsername, 'error' => $error]);
    }
    /**
     * @Route("/logout", name="app_logout")
     */
    public function logout(): void
    {
        throw new Exception('This method can be blank - it will be intercepted by the logout key on your firewall');
    }
    /**
     * @Route("/", name="language")
     */
    public function language(): Response
    {
        $checkLanguage=new CheckLanguage(
            $this->languageRepository,
            $this->parameterBagInterface
        );
        return $this->redirect($checkLanguage->doLangue());
    }
}
