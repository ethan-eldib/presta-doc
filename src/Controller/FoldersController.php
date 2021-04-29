<?php

namespace App\Controller;

use App\Entity\Documents;
use App\Entity\Folders;
use App\Form\FoldersType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/dossier")
 */
class FoldersController extends AbstractController
{
    /**
     * Affiche le formulaire pour déposer des documents 
     * 
     * @Route("/dépôt-document", name="folders_new", methods={"GET","POST"})
     */
    public function new(Request $request): Response
    {
        $folder = new Folders();
        $user = $this->getUser();
        $form = $this->createForm(FoldersType::class, $folder);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            // On recupère les documents transmis
            $documents = $form->get('folders')->getData();

            if (!$documents) {
                $this->addFlash(
                    'danger',
                    "Vous n'avez sélectionné aucun fichier."
                );
            } else {
                // On boucle sur les documents
                foreach ($documents as $document) {
                    // On genere un nouveau nom de fichier
                    $file = md5(uniqid()) . '.' . $document->guessExtension();

                    // On copie le fichier dans le dossier uploads
                    $document->move(
                        $this->getParameter('documents_directory'),
                        $file
                    );

                    // On stocke le ou les documents dans la BDD (son nom)
                    $doc = new Documents();
                    $doc->setName($file);
                    $doc->setUser($user);
                    $folder->addDocument($doc);
                }


                $entityManager = $this->getDoctrine()->getManager();
                $entityManager->persist($folder);
                $entityManager->flush();

                $this->addFlash(
                    'success',
                    'Document transmit avec succès, merci.'
                );
            }
        }

        return $this->render('folders/new.html.twig', [
            'folder' => $folder,
            'form' => $form->createView(),
        ]);
    }
}
