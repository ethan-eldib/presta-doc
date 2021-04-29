<?php

namespace App\Controller;

use DateTime;
use Stripe\Stripe;
use App\Classes\Cart;
use App\Entity\Order;
use App\Form\OrderType;
use App\Entity\OrderDetails;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class OrderController extends AbstractController
{

    private $manager;

    public function __construct(EntityManagerInterface $manager)
    {
        $this->manager = $manager;
    }

    /**
     * @Route("/commande", name="order")
     */
    public function index(Cart $cart): Response
    {
        $form = $this->createForm(OrderType::class, null, [
            'user' => $this->getUser()
        ]);

        return $this->render('order/index.html.twig', [
            'form' => $form->createView(),
            'cart' => $cart->getFull()
        ]);
    }

    /**
     * @Route("/commande/recapitulatif", name="order_recap", methods={"POST"})
     */
    public function add(Cart $cart, Request $request): Response
    {
        $form = $this->createForm(OrderType::class, null, [
            'user' => $this->getUser()
        ]);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $date = new \DateTime();
            $billingAddress = $form->get('addresses')->getData();
            $billingAddressContent = $billingAddress->getFirstname() . ' ' . $billingAddress->getLastname();

            if ($billingAddress->getPhone()) {
                $billingAddressContent .= '<br/>' . '<span>Tél</span> : ' . $billingAddress->getPhone();
            }

            if ($billingAddress->getCompany()) {
                $billingAddressContent .= '<br/>' . '<span>Sté</span> : ' . $billingAddress->getCompany();
            }

            $billingAddressContent .= '<br/>' . $billingAddress->getAddress();
            $billingAddressContent .= '<br/>' . $billingAddress->getPostal() . ' ' . $billingAddress->getCity() . ' - ' . $billingAddress->getCountry();

            // Enregistrement de la commande Order()
            $order = new Order();
            $reference = $date->format('dmY').'-'.uniqid();
            $order->setReference($reference);
            $order->setUser($this->getUser());
            $order->setCreatedAt($date);
            $order->setBillingAddress($billingAddressContent);
            $order->setIsPaid(0);

            $this->manager->persist($order);

            // Enregistrement des packs dans la base de données OrderDetails()        
            foreach ($cart->getFull() as $pack) {
                $orderDetails = new OrderDetails();
                $orderDetails->setMyOrder($order);
                $orderDetails->setProduct($pack['pack']->getName());
                $orderDetails->setQuantity($pack['quantity']);
                $orderDetails->setPrice($pack['pack']->getPrice());
                $orderDetails->setTotal($pack['pack']->getPrice() * $pack['quantity']);
                $this->manager->persist($orderDetails);
            }

            $this->manager->flush();

            return $this->render('order/add.html.twig', [
                'cart' => $cart->getFull(),
                'billingAddress' => $billingAddressContent,
                'reference' => $order->getReference()
            ]);
        }

        return $this->redirectToRoute('cart');
    }
}
