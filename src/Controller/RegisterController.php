<?php

namespace App\Controller;

use App\Form\UserType;
use App\Service\CodeGenerator;
use App\Service\Mailer;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Annotation\Route;

class RegisterController extends AbstractController
{
    #[Route('/register', name: 'blog_register')]
    public function register(
        UserPasswordHasherInterface $passwordHasher,
        Request $request,
        CodeGenerator $codeGenerator,
        Mailer $mailer,
        EntityManagerInterface $entityManager
    ): Response
    {
        $user = new User();
        $form = $this->createForm(
            UserType::class,
            $user
        );

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $user = $form->getData();

            $password = $passwordHasher->hashPassword(
                $user,
                $user->getPlainPassword()
            );

            $user->setPassword($password);
            $user->setConfirmationCode($codeGenerator->getConfirmationCode());

            $entityManager->persist($user);
            $entityManager->flush();

            $mailer->sendConfirmationMessage($user);
        }

        return $this->render('security/register.html.twig', [
            'form' => $form->createView(),
        ]);
    }
}
