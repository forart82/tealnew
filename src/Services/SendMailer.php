<?php


namespace App\Services;

use App\Entity\User;
use App\Repository\EmailsRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bridge\Twig\Mime\TemplatedEmail;
use Symfony\Component\Mailer\MailerInterface;

class SendMailer
{
    private $emailsRepository;
    private $mailer;

    public function __construct(
        EmailsRepository $emailsRepository,
        MailerInterface $mailer
        )
    {
        $this->emailsRepository = $emailsRepository;
        $this->mailer=$mailer;

    }

    public function invitation(User $user, EntityManagerInterface $entityManagerInterface, $host): bool
    {
        // TODO: add posibility to send default language message
        if ($this->emailsRepository->findOneByLanguage($user->getLanguage())) {
            $token = CreateToken::create();
            $this->setUserStatusZero($user, $entityManagerInterface,$token);
            $email = $user->getEmail();
            $token = 'https://' . $host . '/fr/user/verification/' . $email . '/' . $token;
            $message = $this->emailsRepository->findOneByLanguage($user->getLanguage())->getMessage();
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
            $this->mailer->send($email);
            return true;
        }
        return false;
    }
    public function repassword(User $user, EntityManagerInterface $entityManagerInterface, $host): bool
    {
        // TODO: add posibility to send default language message
        if ($this->emailsRepository->findOneByLanguage($user->getLanguage())) {
            $token = CreateToken::create();
            $this->setUserStatusZero($user, $entityManagerInterface,$token);
            $email = $user->getEmail();
            // TODO : Find way tu use context for all values nom, prenom, lien, message.
            $token = '<a id="link" href="https://' . $host . '/fr/user/verification/' . $email . '/' . $token.'">Click Me!</a>';
            $message = $this->emailsRepository->findOneByLanguage($user->getLanguage())->getMessage();
            $message = preg_replace('/{prenom}/', $user->getFirstname(), $message);
            $message = preg_replace('/{nom}/', $user->getLastname(), $message);
            $message = preg_replace('/{lien}/', $token, $message);
            $email = (new TemplatedEmail())
                ->from('contact@esprit-teal.com')
                ->to($user->getEmail())
                ->subject('Invitation tealfinder')
                ->htmlTemplate('email/invitation.html.twig')
                ->context([
                    'message' => $message,
                ]);
            $this->mailer->send($email);
            return true;
        }
        return false;
    }
    public function setUserStatusZero(User $user, EntityManagerInterface $entityManagerInterface,$token):void
    {
        $user->setIsNew(time());
        $user->setPassword(CreateToken::create());
        $user->setToken($token);
        $entityManagerInterface->persist($user);
        $entityManagerInterface->flush();
    }
}
