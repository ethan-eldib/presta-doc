<?php

namespace App\Controller;

use App\Form\ContactType;
use App\Service\EmailService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ContactController extends AbstractController
{
    /**
     * @Route("/me-contacter", name="contact")
     */
    public function index(Request $request, EmailService $email): Response
    {
        $form = $this->createForm(ContactType::class);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $this->addFlash(
                'success',
                "Votre message a été envoyé avec succés, j'y répondrai le plus rapidement possible"
            );

            $email->sendEmail(
                'contact@presta-doc.fr',
                $form->get('email')->getData(),
                'emails/contact.html.twig',
                'Demande de contact Presta-Doc',
                [
                    $form->get('lastName')->getData(),
                    $form->get('firstName')->getData(),
                    $form->get('phone')->getData(),
                    $form->get('email')->getData(),
                    $form->get('content')->getData()
                ]
            );

            return $this->redirect($request->getUri());
        }

        return $this->render('contact/index.html.twig', [
            'form' => $form->createView()
        ]);
    }
}
