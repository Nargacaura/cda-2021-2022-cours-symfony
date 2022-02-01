<?php 

namespace App\Service;

use Symfony\Component\Mime\Email;
use Symfony\Component\Mailer\MailerInterface;
use App\Entity\User;

class RegistrationNotifierService
{
    private $mailer;

    public function __construct(MailerInterface $mailer)
    {
        $this->mailer = $mailer;
    }
    public function notifyAdmin(User $user)
    {
        $email = new Email();
        $email
            ->from('noreply@cda-2021-2022.com')
            ->to('admin@cda-2021-2022.com')
            ->subject('Un nouvel utilisateur vient de nous rejoindre')
            ->text('L\'utilisateur '.$user->getPseudonym().' ('.$user->getEmail().') s\'est inscrit.')
        ;
        $this->mailer->send($email);
    }

    public function notifyUser(User $user)
    {
        $email = new Email();
        $email
            ->from('noreply@cda-2021-2022.com')
            ->to($user->getEmail())
            ->subject('Bienvenue!')
            ->text('Félicitations! Vous pouvez maintenant vendre vos objets random. Ou en acheter, on ne sait pas trop ce que vous voulez vraiment faire sur ce site.')
        ;
        $this->mailer->send($email);
    }
}