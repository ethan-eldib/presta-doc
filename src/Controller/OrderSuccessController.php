<?php

namespace App\Controller;

use App\Classes\Cart;
use App\Classes\Mail;
use App\Entity\Order;
use App\Service\EmailService;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class OrderSuccessController extends AbstractController
{

    private $manager;

    public function __construct(EntityManagerInterface $manager)
    {
        $this->manager = $manager;
    }

    /**
     * @Route("/commande/merci/{stripeSessionId}", name="order_success")
     */
    public function index($stripeSessionId, Cart $cart, EmailService $email): Response
    {

        $order = $this->manager->getRepository(Order::class)->findOneBy([
            'stripeSessionId' => $stripeSessionId
        ]);

        if (!$order || $order->getUser() != $this->getUser()) {
            return $this->redirectToRoute('homepage');
        }

        if (!$order->getIsPaid()) {
            // On vide la session Cart
            $cart->remove();

            // Passer le statut isPaid à true
            $order->setIsPaid(1);
            $this->manager->flush();

            // Envoyer un email au client pour la confirmation de la commande
            $email->sendEmail(
                $order->getUser()->getEmail(),
                'noreply@presta-doc.fr',
                'emails/order_success.html.twig',
                'Votre commande Presta-Doc réf: '. $order->getReference(),
                [
                    $order->getUser()->getLastName(),
                    $order->getUser()->getFirstName()
                ]
            );
        }

        return $this->render('order_success/index.html.twig', [
            'order' => $order
        ]);
    }
}
