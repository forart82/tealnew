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
  private $clientid;
  private $clientecret;
  private $url;

  public function __construct()
  {
    $this->clientid = "86qa1cgyg80rud";
    $this->clientecret = "s1QNIeC55rSWoXuQ";
    $this->url = "";
  }
  /**
   * @Route("/", name="oauth")
   */
  public function index()
  {
    return $this->render('o_auth/index.html.twig', []);
  }

    /**
   * @Route("/oauthlinkedincompany", name="oauthlinkedincompany")
   */
   public function oauthLinkedinCompany(UrlGeneratorInterface $urlGeneratorInterface,HttpClientInterface $httpClientInterface)
  {

    $response=$httpClientInterface->request("GET", "https://www.linkedin.com/oauth/v2/accessToken?grant_type=client_credentials&client_id=86qa1cgyg80rud&client_secret=s1QNIeC55rSWoXuQ",
    [
      'headers' => [
        'Host' => 'www.linkedin.com',
        'Content-Type' => 'application/x-www-form-urlencoded',
      ],
    ]);
    dump($response->toArray());
  }



  // /**
  //  * @Route("/oauthgit", name="oauthgit")
  //  */
  // public function oauthGit(UrlGeneratorInterface $urlGeneratorInterface)
  // {


  //   $url = $urlGeneratorInterface->generate('oauthaftergit', [], UrlGeneratorInterface::ABSOLUTE_URL);
  //   return new RedirectResponse('https://github.com/login/oauth/authorize?&client_id=f76f338e1f05302348e5&redirect_uri=' . $url);
  // }

  // /**
  //  * @Route("/oauthaftergit", name="oauthaftergit")
  //  */
  // public function oauthafterGit(Request $request, HttpClientInterface $httpClientInterface): Response
  // {

  //   $code = $request->get('code');
  //   $url = sprintf(
  //     "https://github.com/login/oauth/access_token?client_id=%s&client_secret=%s;code=%s",
  //     $this->clientid,
  //     $this->clientecret,
  //     $code
  //   );

  //   $response = $httpClientInterface->request('POST', $url, [
  //     'headers' => [
  //       'Accept' => 'application/json',
  //     ],
  //   ]);
  //   $token = $response->toArray()['access_token'];
  //   dump($token);
  //   $response = $httpClientInterface->request('GET', 'https://api.github.com/user', [
  //     'headers' => [
  //       'Authorization' => 'token ' . $token,
  //     ],
  //   ]);

  //   $data = $response->toArray();
  //   dd($data);

  //   return new Response('<body>Hallo<body>');
  // }

  // /**
  //  * @Route("/oauthlinkedin", name="oauthlinkedin")
  //  */
  // public function oauthLinkedin(UrlGeneratorInterface $urlGeneratorInterface)
  // {


  //   $this->url = $urlGeneratorInterface->generate('oauthafterlinkedin', [], UrlGeneratorInterface::ABSOLUTE_URL);

  //   return new RedirectResponse("https://www.linkedin.com/oauth/v2/authorization?&response_type=code&client_id={$this->clientid}&redirect_uri="
  //     . $this->url . "&scope=r_liteprofile%20r_emailaddress");
  // }

  //   /**
  //  * @Route("/oauthlinkedincompany", name="oauthlinkedincompany")
  //  */
  // public function oauthLinkedinCompany(UrlGeneratorInterface $urlGeneratorInterface,HttpClientInterface $httpClientInterface)
  // {


  //   $url= $urlGeneratorInterface->generate('oauthafterlinkedincompany', [], UrlGeneratorInterface::ABSOLUTE_URL);

  //   $response=$httpClientInterface->request("POST",
  //   "https://www.linkedin.com/oauth/v2/accessToken?grant_type=client_credentials&client_id=86qa1cgyg80rud&client_secret=s1QNIeC55rSWoXuQ",
  //   [
  //     'headers' => [
  //       'Host' => 'www.linkedin.com',
  //       'Content-Type' => 'application/x-www-form-urlencoded',
  //     ],
  //   ]);
  //   dump($response->toArray());
  // }

  // /**
  //  * @Route("/oauthafterlinkedin", name="oauthafterlinkedin")
  //  */
  // public function oauthafterLinkedin(Request $request, HttpClientInterface $httpClientInterface, UrlGeneratorInterface $urlGeneratorInterface): Response
  // {
  //   $this->url = $urlGeneratorInterface->generate('oauthafterlinkedin', [], UrlGeneratorInterface::ABSOLUTE_URL);
  //   $code = $request->get('code');
  //   $response = $httpClientInterface->request(
  //     'POST',
  //     "https://www.linkedin.com/oauth/v2/accessToken?grant_type=authorization_code&code={$code}&redirect_uri={$this->url}&client_id={$this->clientid}&client_secret={$this->clientecret}",
  //     [
  //       'headers' => [
  //         'Host' => 'www.linkedin.com',
  //         'Content-Type' => 'application/x-www-form-urlencoded',
  //       ],
  //     ]
  //   );
  //   dump($response);

  //   $token = $response->toArray();

  //   $response = $httpClientInterface->request('GET', 'https://api.linkedin.com/v2/me', [
  //     'headers' => [
  //       'Host' => 'api.linkedin.com',
  //       'Connection' => 'Keep-Alive',
  //       'Authorization' => 'Bearer ' . $token['access_token'],
  //     ],
  //   ]);

  //   $data = $response->toArray();
  //   dd($data);

  //   return new Response('<body>Hallo<body>');
  // }
  // /**
  //  * @Route("/oauthafterlinkedincompany", name="oauthafterlinkedincompany")
  //  */
  // public function oauthafterLinkedinCompany(Request $request, HttpClientInterface $httpClientInterface, UrlGeneratorInterface $urlGeneratorInterface): Response
  // {
  //   $this->url = $urlGeneratorInterface->generate('oauthafterlinkedincompany', [], UrlGeneratorInterface::ABSOLUTE_URL);
  //   $code = $request->get('code');
  //   $response = $httpClientInterface->request(
  //     'POST',
  //     "https://www.linkedin.com/oauth/v2/accessToken?grant_type=authorization_code&code={$code}&redirect_uri={$this->url}&client_id={$this->clientid}&client_secret={$this->clientecret}",
  //     [
  //       'headers' => [
  //         'Host' => 'www.linkedin.com',
  //         'Content-Type' => 'application/x-www-form-urlencoded',
  //       ],
  //     ]
  //   );
  //   dump($response);

  //   $token = $response->toArray();

  //   $response = $httpClientInterface->request('GET', 'https://api.linkedin.com/v2/me', [
  //     'headers' => [
  //       'Host' => 'api.linkedin.com',
  //       'Connection' => 'Keep-Alive',
  //       'Authorization' => 'Bearer ' . $token['access_token'],
  //     ],
  //   ]);

  //   $data = $response->toArray();
  //   dd($data);

  //   return new Response('<body>Hallo<body>');
  // }

  // /**
  //  * @Route("/linkedinorganization", name="linkedinorganization")
  //  */
  // public function linkedinOrganization(Request $request, HttpClientInterface $httpClientInterface, UrlGeneratorInterface $urlGeneratorInterface): Response
  // {

  //   $response = $httpClientInterface->request('GET', 'https://api.linkedin.com/v2/company',
  //   [
  //     'headers' => [
  //       'Host' => 'www.linkedin.com',
  //       'Content-Type' => 'application/x-www-form-urlencoded',
  //     ],
  //   ]);
  //   dd($request,$response->toArray());
  // }
}
