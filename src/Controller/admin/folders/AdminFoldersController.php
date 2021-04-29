<?php

namespace App\Controller\admin\folders;

use App\Entity\Documents;
use App\Repository\FoldersRepository;
use App\Repository\DocumentsRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class AdminFoldersController extends AbstractController
{

    /**
     * Permet d'afficher la liste des dossiers déposés par les utilisateurs
     * 
     * @Route("/admin/dossiers", name="admin_folders_index", methods={"GET"})
     * 
     */
    public function index(FoldersRepository $foldersRepository): Response
    {
        return $this->render('admin/folders/index.html.twig', [
            'folders' => $foldersRepository->findAll()
        ]);
    }

    /**
     * Permet de voir le contenu d'un dossier
     * 
     * @Route("/admin/document/{id}", name="admin_document_show", methods={"GET"})
     * 
     */
    public function show(FoldersRepository $foldersRepository, DocumentsRepository $documentsRepository, $id): Response
    {
        return $this->render('admin/document/show.html.twig', [
            'documents' => $documentsRepository->findBy(['folders' => $id]),
            'folders' => $foldersRepository->findBy(['id' => $id])
        ]);
    }

    /**
     * Supprime un document 
     * 
     * @Route("/admin/document/delete/{id}", name="admin_document_delete", methods={"DELETE"})
     */
    public function deleteOneDocument(EntityManagerInterface $manager, Documents $document, Request $request)
    {
        $data = json_decode($request->getContent(), true);

        if ($this->isCsrfTokenValid('delete' . $document->getId(), $data['_token'])) {
            $name = $document->getName();
            unlink($this->getParameter('documents_directory') . '/' . $name);

            $manager->remove($document);
            $manager->flush();

            return new JsonResponse([
                'success' => 1
            ]);
        } else {
            return new JsonResponse([
                'error' => 'Le token est invalide'
            ], 400);
        }
    }
}
