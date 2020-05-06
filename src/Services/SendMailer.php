<?php


namespace App\Services;

use App\Entity\User;
use App\Repository\EmailsRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bridge\Twig\Mime\TemplatedEmail;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\HttpFoundation\Request;

class SendMailer
{
    private $emailsRepository;
    private $request;
    private $mailer;

    public function __construct(
        EmailsRepository $emailsRepository,
        Request $request,
        MailerInterface $mailer
        )
    {
        $this->emailsRepository = $emailsRepository;
        $this->request=$request;
        $this->mailer=$mailer;

    }

    public function invitation(User $user, EntityManagerInterface $entityManagerInterface): bool
    {
        // TODO: add posibility to send default language message
        if ($this->emailsRepository->findOneByLanguage($user->getLanguage())) {

            $this->setUserOff($user, $entityManagerInterface);
            $token = $user->getToken();
            $email = $user->getEmail();
            $host = $this->request->getHttpHost();
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

    public function setUserOff(User $user, EntityManagerInterface $entityManagerInterface):void
    {
        $user->setIsNew(1);
        $entityManagerInterface->persist($user);
        $entityManagerInterface->flush();
    }
}
