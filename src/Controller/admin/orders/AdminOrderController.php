<?php

namespace App\Controller\admin\orders;

use App\Entity\Order;
use App\Repository\OrderRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class AdminOrderController extends AbstractController
{
    /**
     * @Route("/admin/commandes", name="admin_order")
     */
    public function index(OrderRepository $orderRepository): Response
    {
        return $this->render('admin/orders/index.html.twig', [
            'orders' => $orderRepository->findAll()
        ]);
    }

    /**
     * Supprime un dossier 
     * 
     * @Route("/admin/commandes/supprimer/{id}", name="admin_folder_delete", methods={"GET"})
     */
    public function deleteOneFolder(EntityManagerInterface $manager, $id)
    {
        $order = $manager->getRepository(Order::class)->findOneBy([
            'id' => $id
        ]);

        $manager->remove($order);
        $manager->flush();

        $this->addFlash(
            'success',
            'Le dossier a bien été supprimé'
        );

        return $this->redirectToRoute('admin_order');
    }
}
