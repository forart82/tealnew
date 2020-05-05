<?php


namespace App\Services;

use App\Entity\User;
use App\Repository\EmailsRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bridge\Twig\Mime\TemplatedEmail;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;

class SendMailer
{
    private $messages;
    private $container;
    private $mailer;

    public function __construct(
        EmailsRepository $emailsRepository,
        ContainerInterface $container = null,
        MailerInterface $mailer
        )
    {
        $this->messages = $emailsRepository;
        $this->container=$container;
        $this->mailer=$mailer;

    }

    public function invitation(User $user, EntityManagerInterface $entityManagerInterface): bool
    {
        dump($user->getToken());
        // TODO: add posibility to send default language message
        if ($this->messages->findOneByLanguage($user->getLanguage())) {
            $this->setUserOff($user, $entityManagerInterface);
            $token = $user->getToken();
            $email = $user->getEmail();
            $host = $this->container->get('request_stack')->getCurrentRequest()->getHttpHost();
            $token = 'https://' . $host . '/fr/verification/' . $email . '/' . $token;
            $message = $this->messages->findOneByLanguage($user->getLanguage())->getMessage();
            $message = preg_replace('/{prenom}/', $user->getFirstname(), $message);
            $message = preg_replace('/{nom}/', $user->getLastname(), $message);
            $message = preg_replace('/{lien}/', $token, $message);
            $email = (new TemplatedEmail())
                ->from('contact@esprit-teal.com')
                ->to($user->getEmail())
                ->subject('Invitation tealfinder')
                ->htmlTemplate('email/invitation.html.twig')
                ->context([
                    'message'=>$message
                    ]);
            // $this->mailer->send($email);
            return true;
        }
        return false;
    }

    public function setUserOff(User $user, EntityManagerInterface $entityManagerInterface):void
    {
        $user->setIsNew(1);
        $entityManagerInterface->persist($user);
        $entityManagerInterface->flush();
    }
}
