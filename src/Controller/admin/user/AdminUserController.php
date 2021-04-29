<?php

namespace App\Controller\admin\user;

use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class AdminUserController extends AbstractController
{
    private $manager;

    public function __construct(EntityManagerInterface $manager)
    {
        $this->manager = $manager;
    }

    /**
     * Permet d'afficher la liste des utilisateurs
     * 
     * @Route("/admin/utilisateurs", name="admin_user")
     */
    public function index(): Response
    {
        $users = $this->manager->getRepository(User::class)->findAll();

        return $this->render('admin/user/admin_user.html.twig', [
            'users' => $users
        ]);
    }

}
