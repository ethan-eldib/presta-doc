<?php

namespace App\Controller;

use App\Classes\Cart;
use App\Entity\Address;
use App\Form\BillingAddressType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class AccountBillingAddressController extends AbstractController
{

    private $manager;

    public function __construct(EntityManagerInterface $manager)
    {
        $this->manager = $manager;
    }

    /**
     * @Route("/mon-compte/mes-adresses-de-facturation", name="billing_address")
     */
    public function index(): Response
    {
        return $this->render('account/billing_address.html.twig');
    }

    /**
     * @Route("/mon-compte/ajouter-une-adresse", name="add_billing_address")
     */
    public function add(Cart $cart, Request $request): Response
    {

        $address = new Address();
        $form = $this->createForm(BillingAddressType::class, $address);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $address->setUser($this->getUser());

            $this->manager->persist($address);
            $this->manager->flush();

            if ($cart->get()) {
                return $this->redirectToRoute('order');
            } else {
                return $this->redirectToRoute('billing_address');
            }
        }

        return $this->render('account/add_billing_address.html.twig', [
            'form' => $form->createView()
        ]);
    }

    /**
     * @Route("/mon-compte/modifier-une-adresse/{id}", name="edit_billing_address")
     */
    public function edit(Request $request, $id): Response
    {

        $address = $this->manager->getRepository(Address::class)->findOneBy([
            'id' => $id
        ]);

        if (!$address || $address->getUser() != $this->getUser()) {
            return $this->redirectToRoute('billing_address');
        }

        $form = $this->createForm(BillingAddressType::class, $address);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->manager->flush();

            return $this->redirectToRoute('billing_address');
        }

        return $this->render('account/edit_billing_address.html.twig', [
            'form' => $form->createView()
        ]);
    }

    /**
     * @Route("/mon-compte/supprimer-une-adresse/{id}", name="delete_billing_address")
     */
    public function delete($id): Response
    {
        $address = $this->manager->getRepository(Address::class)->findOneBy([
            'id' => $id
        ]);

        if ($address && $address->getUser() == $this->getUser()) {
            $this->manager->remove($address);
            $this->manager->flush();
        }

        return $this->redirectToRoute('billing_address');
        $this->addFlash(
            'success',
            'Votre adresse a bien été supprimée'
        );
    }
}
