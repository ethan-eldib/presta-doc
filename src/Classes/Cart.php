<?php

namespace App\Classes;

use App\Entity\PackPrestaDoc;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Session\SessionInterface;

class Cart
{

    private $session;

    private $manager;

    public function __construct(SessionInterface $session, EntityManagerInterface $manager)
    {
        $this->session = $session;
        $this->manager = $manager;
    }

    /**
     * Allows you to add 1 pack to my cart
     *
     * @param number $id
     * @return void
     */
    public function add($id)
    {
        $cart = $this->session->get('cart', []);

        if (!empty($cart[$id])) {
            $cart[$id]++;
        } else {
            $cart[$id] = 1;
        }

        $this->session->set('cart', $cart);
    }

    public function decrease($id)
    {
        $cart = $this->session->get('cart', []);

        if ($cart[$id] > 1) {
            $cart[$id]--;
        } else {
            unset($cart[$id]);
        }

        return $this->session->set('cart', $cart);
    }

    public function get()
    {
        return $this->session->get('cart');
    }

    /**
     * Return function remove
     *
     * @return void
     */
    public function remove()
    {
        return $this->session->remove('cart');
    }

    /**
     * Process and return the suppression of a pack by its id
     *
     * @param [type] $id
     * @return void
     */
    public function delete($id)
    {
        $cart = $this->session->get('cart', []);

        unset($cart[$id]);

        return $this->session->set('cart', $cart); // Allows to set the new cart after delete
    }

    public function getFull()
    {
        $cartFull = [];

        if ($this->get()) {
            foreach ($this->get() as $id => $quantity) {
                $pack_object = $this->manager->getRepository(PackPrestaDoc::class)->findOneBy([
                    'id' => $id
                ]);

                if (!$pack_object) {
                    $this->delete($id);
                    continue;
                }

                $cartFull[] = [
                    'pack' => $pack_object,
                    'quantity' => $quantity
                ];
            }
        }

        return $cartFull;
    }
}
