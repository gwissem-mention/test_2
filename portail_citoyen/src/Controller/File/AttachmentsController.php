<?php

declare(strict_types=1);

namespace App\Controller\File;

use App\Entity\File;
use App\Form\AttachmentsType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class AttachmentsController extends AbstractController
{
    #[Route('/pieces-complementaires', name: 'attachments')]
    public function __invoke(Request $request, EntityManagerInterface $entityManager): JsonResponse|Response
    {
        $form = $this->createForm(AttachmentsType::class);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            /** @var array<string> $data */
            $data = $form->getData();
            if (array_key_exists('attachments', $data)) {
                /** @var array<File> $files */
                $files = $data['attachments'];
                foreach ($files as $file) {
                    $entityManager->persist($file);
                }
                $entityManager->flush();
            }

            return $this->json(['redirect_url' => $this->generateUrl('attachments')]);
        }

        return $this->render('attachments.html.twig', [
            'form' => $form->createView(),
            'files' => $entityManager->getRepository(File::class)->findAll(),
        ]);
    }
}
