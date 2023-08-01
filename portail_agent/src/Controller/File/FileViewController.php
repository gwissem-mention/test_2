<?php

declare(strict_types=1);

namespace App\Controller\File;

use App\Entity\Complaint;
use League\Flysystem\FilesystemException;
use League\Flysystem\FilesystemOperator;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\ResponseHeaderBag;
use Symfony\Component\HttpFoundation\StreamedResponse;
use Symfony\Component\Routing\Annotation\Route;

class FileViewController extends AbstractController
{
    /**
     * @throws FilesystemException
     */
    #[Route('/voir-piece-jointe/{id}/{name}', name: 'file_view', requirements: ['id' => '\d+', 'name' => '.+'])]
    public function __invoke(Complaint $complaint, string $name, FilesystemOperator $defaultStorage): StreamedResponse
    {
        $this->denyAccessUnlessGranted('COMPLAINT_VIEW', $complaint);

        return new StreamedResponse(function () use ($name, $defaultStorage) {
            /** @var resource $outputStream */
            $outputStream = fopen('php://output', 'wb');
            $fileStream = $defaultStorage->readStream($name);
            stream_copy_to_stream($fileStream, $outputStream);
        }, 200, [
            'Content-Type' => $defaultStorage->mimeType($name),
            'Content-Disposition' => ResponseHeaderBag::DISPOSITION_INLINE,
        ]);
    }
}
