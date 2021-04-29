<?php

namespace App\Controller\admin;

use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class AdminDashboardController extends AbstractController
{
    /**
     * @Route("/admin", name="admin_dashboard")
     */
    public function index(EntityManagerInterface $manager): Response
    {
        $users = $manager->createQuery('SELECT COUNT(u) FROM App\Entity\User u')->getSingleScalarResult();
        $documents = $manager->createQuery('SELECT COUNT(d.name) FROM App\Entity\Documents d')->getSingleScalarResult();
        $orders = $manager->createQuery('SELECT COUNT(o) FROM App\Entity\Order o')->getSingleScalarResult();

        return $this->render('admin/dashboard.html.twig', [
            'stats' => compact('users', 'documents', 'orders')
        ]);
    }
}
