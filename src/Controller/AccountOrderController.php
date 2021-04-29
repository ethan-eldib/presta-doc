<?php

namespace App\Controller;

use App\Entity\Order;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class AccountOrderController extends AbstractController
{

    private $manager;

    public function __construct(EntityManagerInterface $manager)
    {
        $this->manager = $manager;
    }

    /**
     * @Route("/mon-compte/mes-commandes", name="account_order")
     */
    public function index(): Response
    {
        $orders = $this->manager->getRepository(Order::class)->findSuccessOrder($this->getUser());

        return $this->render('account/order.html.twig', [
            'orders' => $orders
        ]);
    }

    /**
     * @Route("/mon-compte/ma-commande/{reference}", name="account_order_show")
     */
    public function show($reference): Response
    {
        $order = $this->manager->getRepository(Order::class)->findOneBy([
            'reference' => $reference
        ]);

        if (!$order || $order->getUser() != $this->getUser()) {
            return $this->redirectToRoute('account_order');
        }

        return $this->render('account/order_show.html.twig', [
            'order' => $order
        ]);
    }
}
