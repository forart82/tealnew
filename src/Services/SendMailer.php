<?php


namespace App\Services;

use Swift_Message;
use App\Entity\User;
use App\Repository\EmailsRepository;
use Symfony\Bridge\Twig\Mime\TemplatedEmail;
use Symfony\Component\Mailer\MailerInterface;

class SendMailer
{
    private $messages;

    public function __construct(EmailsRepository $emailsRepository)
    {
        $this->messages = $emailsRepository;
    }

    public function sendMailer(User $user, MailerInterface $mailer, string $token): TemplatedEmail
    {
        // TODO: add posibility to send default language message
        if ($this->messages->findOneByLanguage($user->getLanguage())) {
            $message = $this->messages->findOneByLanguage($user->getLanguage())->getMessage();
            $message = preg_replace('/{prenom}/', $user->getFirstname(), $message);
            $message = preg_replace('/{nom}/', $user->getLastname(), $message);
            $message = preg_replace('/{lien}/', $token, $message);
            $email = (new TemplatedEmail())
                ->from('contact@esprit-teal.com')
                ->to($user->getEmail())
                ->subject('Invitation tealfinder')
                ->text($message);
            $mailer->send($email);
        }

        return $email;

    }
}
