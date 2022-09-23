<?php

declare(strict_types=1);

namespace App\Controller\File;

use App\Entity\File;
use App\Form\DropzoneType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class FileUploadController extends AbstractController
{
    #[Route('/envois-fichiers', name: 'file_upload')]
    public function __invoke(Request $request, EntityManagerInterface $entityManager): JsonResponse|Response
    {
        $form = $this->createForm(DropzoneType::class);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            /** @var array<File> $files */
            $files = $form->getData();
            foreach ($files as $file) {
                $entityManager->persist($file);
            }
            $entityManager->flush();

            return $this->json(['redirect_url' => $this->generateUrl('file_upload')]);
        }

        return $this->render('file_upload.html.twig', [
            'form' => $form->createView(),
            'files' => $entityManager->getRepository(File::class)->findAll(),
        ]);
    }
}
