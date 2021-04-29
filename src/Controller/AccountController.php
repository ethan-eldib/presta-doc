<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\EditPasswordType;
use App\Form\RegistrationType;
use App\Service\EmailService;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;

class AccountController extends AbstractController
{
    /**
     * Affiche le formulaire de connexion
     * 
     * @Route("/connexion", name="account_login")
     * 
     */
    public function login(AuthenticationUtils $utils)
    {
        $error = $utils->getLastAuthenticationError();
        $username = $utils->getLastUsername();

        return $this->render('account/login.html.twig', [
            'hasError' => $error !== null,
            'username' => $username
        ]);

        $this->redirectToRoute('my_account');
    }

    /**
     * Permet de se déconnecter
     * 
     * @Route("/logout", name="account_logout")
     * 
     * @return void
     */
    public function logout()
    {
        # code...
    }

    /**
     * Permet d'afficher le formulaite d'inscription
     * 
     * @Route("/register", name="register")
     *
     * @return Response
     */
    public function register(EmailService $email, Request $request, EntityManagerInterface $manager, UserPasswordEncoderInterface $encoder)
    {
        $user = new User();

        $form = $this->createForm(RegistrationType::class, $user);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $hash = $encoder->encodePassword($user, $user->getHash());
            $user->setHash($hash);

            $manager->persist($user);
            $manager->flush();

            $email->sendEmail(
                $user->getEmail(),
                'noreply@presta-doc.fr',
                'emails/register.html.twig',
                'Bienvenue sur Presta-Doc',
                [
                    $user->getLastName(),
                    $user->getFirstName()
                ]
            );
          
            $this->addFlash(
                'success',
                'Votre inscription a bien été prise en compte, merci. Vous recevrez parallèlement un e-mail de confirmation.'
            );
        }

        return $this->render('account/registration.html.twig', [
            'form' => $form->createView()
        ]);
    }

    /**
     * Permet d'afficher la page d'accueil du compte utilisateur
     *
     * @Route("/mon-compte", name="my_account")
     */
    public function myAccount()
    {
        return $this->render('account/my_account.html.twig');
    }

    /**
     * Permet de modifier son mot de passe
     *
     * @Route("/mon-compte/modifier-mon-mot-de-passe", name="account_password")
     */
    public function editPassword(Request $request, UserPasswordEncoderInterface $encoder, EntityManagerInterface $manager)
    {
        $user = $this->getUser();
        $form = $this->createForm(EditPasswordType::class, $user);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $old_password = $form->get('old_password')->getData();

            if ($encoder->isPasswordValid($user, $old_password)) {
                $new_password = $form->get('new_password')->getData();
                $password = $encoder->encodePassword($user, $new_password);

                $user->setHash($password);
                $manager->flush();

                $this->addFlash(
                    'success',
                    'Votre mot de passe a bien éré mis à jour.'
                );
            } else {
                $this->addFlash(
                    'warning',
                    'Le mot de passe actuel est incorrect.'
                );
            }
        }

        return $this->render('account/edit_password.html.twig', [
            'form' => $form->createView()
        ]);
    }
}
