<?php

namespace App\Service;

use App\Entity\User;
use Symfony\Bridge\Twig\Mime\TemplatedEmail;
use Symfony\Component\Mailer\MailerInterface;

class Mailer
{
    public const FROM_ADDRESS = 'a.kolesnov@yandex.ru';

    public function sendConfirmationMessage(MailerInterface $mailer, User $user): void
    {
        $email = (new TemplatedEmail())
            ->from(self::FROM_ADDRESS)
            ->to($user->getEmail())
            ->subject('Вы успешно прошли регистрацию!')
            ->htmlTemplate('security/confirmation.html.twig')
            ->context(['user' => $user]);

        $mailer->send($email);
    }
}