<?php

namespace App\Controller\admin\account;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;

class AdminAccountController extends AbstractController
{
    /**
     * Permet de se connecter à la page d'administration
     * 
     * @Route("/admin/login", name="admin_account_login")
     */
    public function login(AuthenticationUtils $utils): Response
    {

        $error = $utils->getLastAuthenticationError();
        $user = $utils->getLastUsername();

        return $this->render('admin/account/login.html.twig', [
            'hasError' => $error !== null,
            'username' => $user
        ]);
    }

    /**
     * Permet de se déconnecter de l'administration
     * 
     * @Route("/admin/logout", name="admin_account_logout")
     * 
     * @return void
     */
    public function logout()
    {
        # code...
    }
}
