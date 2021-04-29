<?php

namespace App\Controller;

use App\Entity\PackPrestaDoc;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class PackPrestaDocController extends AbstractController
{
    /**
     * @Route("/nos-packs", name="pack_doc")
     */
    public function index(EntityManagerInterface $manager): Response
    {

        $packs = $manager->getRepository(PackPrestaDoc::class)->findAll();

        return $this->render('pack_presta_doc/index.html.twig', [
            'packs' => $packs
        ]);
    }
}
