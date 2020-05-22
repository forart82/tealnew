<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Contracts\HttpClient\HttpClientInterface;

/**
 * @Route("/oauth")
 */
class OAuthController extends AbstractController
{
  /**
   * @Route("/index", name="index_oauth")
   */
  public function index()
  {
    return $this->render('o_auth/index.html.twig', []);
  }

  /**
   * @Route("/oauth", name="oauth")
   */
  public function oauth(UrlGeneratorInterface $urlGeneratorInterface)
  {


    $url = $urlGeneratorInterface->generate('oauthafter', [], UrlGeneratorInterface::ABSOLUTE_URL);
    return new RedirectResponse('https://github.com/login/oauth/authorize?&client_id=f76f338e1f05302348e5&redirect_uri=' . $url);
  }

  /**
   * @Route("/oauthafter", name="oauthafter")
   */
  public function oauthafter(Request $request, HttpClientInterface $httpClientInterface): Response
  {
    $clientid = "f76f338e1f05302348e5";
    $clientecret = "f586c5ddca32c572be21f706b5bbde5a8cabd065";
    $code = $request->get('code');
    $url = sprintf(
      "https://github.com/login/oauth/access_token?client_id=%s&client_secret=%s;code=%s",
      $clientid,
      $clientecret,
      $code
    );

    $response = $httpClientInterface->request('POST', $url, [
      'headers' => [
        'Accept' => 'application/json',
      ],
    ]);
    $token = $response->toArray()['access_token'];
    dump($token);
    $response = $httpClientInterface->request('GET', 'https://api.github.com/user', [
      'headers' => [
        'Authorization' => 'token ' . $token,
      ],
    ]);

    $data = $response->toArray();
    dd($data);

    return new Response('<body>Hallo<body>');

  }
}

