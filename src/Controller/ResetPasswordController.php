<?php

namespace App\Controller;

use App\Entity\ResetPassword;
use App\Entity\User;
use App\Form\ResetPasswordType;
use App\Service\EmailService;
use DateTime;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class ResetPasswordController extends AbstractController
{

    private $manager;

    public function __construct(EntityManagerInterface $manager)
    {
        $this->manager = $manager;
    }

    /**
     * Permet de réinitialiser son mot de passe en cas d'oublie
     * 
     * @Route("/mot-de-passe-oublie", name="reset_password")
     */
    public function index(Request $request, EmailService $email): Response
    {
        if ($this->getUser()) {
            return $this->redirectToRoute('homepage');
        }


        if ($request->get('email')) {
            $user = $this->manager->getRepository(User::class)->findOneBy([
                'email' => $request->get('email')
            ]);

            if ($user) {
                // 1 : Enregistrement en BDD de la demande de reset_password (user, token, createdAt)
                $reset_password = new ResetPassword();
                $reset_password->setUser($user);
                $reset_password->setToken(uniqid());
                $reset_password->setCreatedAt(new \DateTime());
                $this->manager->persist($reset_password);
                $this->manager->flush();

                // 2 : Envoyer un email (à l'utilisateur) avec un lien pour mettre à jour son MDP
                $url = $this->generateUrl('update_password', [
                    'token' => $reset_password->getToken()
                ]);

                $email->sendEmail(
                    $user->getEmail(),
                    'noreply@presta-doc.fr',
                    'emails/reset_password.html.twig',
                    'Réinitialisation du mot de passe',
                    [
                        $user->getLastName(),
                        $user->getFirstName(),
                        $url
                    ]
                );

                $this->addFlash(
                    'success',
                    'Un email viens de vous être envoyé avec la procédure de réinitialisation du mot de passe.'
                );
            } else {
                $this->addFlash(
                    'danger',
                    'Adresse email inconnue.'
                );
            }
        }

        return $this->render('account/reset_password/reset_password.html.twig', []);
    }


    /**
     * Permet de modifier son mot de passe sur son espace client
     * 
     * @Route("/modifier-mon-mot-de-passe/{token}", name="update_password")
     * 
     * @return void
     */
    public function update($token, Request $request, UserPasswordEncoderInterface $encoder)
    {
        $reset_password = $this->manager->getRepository(ResetPassword::class)->findOneBy([
            'token' => $token
        ]);

        if (!$reset_password) {
            $this->redirectToRoute('reset_password');
        }

        // On vérifie si le token a éxpiré
        // Vérifier si la date de création = now - 3h
        $now = new \DateTime();
        if ($now > $reset_password->getCreatedAt()->modify('+ 3 hour')) {
            $this->addFlash(
                'danger',
                'Votre demande de réinitialisation du mot de passe a expiré. Merci de renouveller votre demande.'
            );
            return $this->redirectToRoute('reset_password');
        }

        // Rendre une vue avec mot de passe et confirmez votre MPD
        $form = $this->createForm(ResetPasswordType::class);
        $form->handleRequest($request);


        if ($form->isSubmitted() && $form->isValid()) {
            $new_password = $form->get('new_password')->getData();

            // Encodage du nouveau MDP
            $password = $encoder->encodePassword($reset_password->getUser(), $new_password);
            $reset_password->getUser()->setHash($password);

            // On flush en BDD
            $this->manager->flush();

            // Redirection de l'utilisateur vers la page de connexion
            $this->addFlash(
                'success',
                'Votre mot de passe a bien été mis à jour'
            );

            return $this->redirectToRoute('account_login');
        }

        return $this->render('account/reset_password/update_password.html.twig', [
            'form' => $form->createView()
        ]);

    }
}
