<?php

declare(strict_types=1);

namespace App\Controller\File;

use App\Entity\Complaint;
use App\Entity\User;
use App\Logger\ApplicationTracesLogger;
use App\Logger\ApplicationTracesMessage;
use League\Flysystem\FilesystemException;
use League\Flysystem\FilesystemOperator;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\ResponseHeaderBag;
use Symfony\Component\HttpFoundation\StreamedResponse;
use Symfony\Component\Routing\Annotation\Route;

class FileDownloadController extends AbstractController
{
    /**
     * @throws FilesystemException
     */
    #[Route('/telecharger-piece-jointe/{id}/{name}', name: 'file_download', requirements: ['id' => '\d+', 'name' => '.+'])]
    public function __invoke(Complaint $complaint, string $name, FilesystemOperator $defaultStorage, ApplicationTracesLogger $logger, Request $request): StreamedResponse
    {
        $this->denyAccessUnlessGranted('COMPLAINT_VIEW', $complaint);
        /** @var User $user */
        $user = $this->getUser();

        $logger->log(ApplicationTracesMessage::message(
            ApplicationTracesMessage::DOWNLOAD,
            $complaint->getDeclarationNumber(),
            $user->getNumber(),
            $request->getClientIp())
        );

        return new StreamedResponse(function () use ($name, $defaultStorage) {
            /** @var resource $outputStream */
            $outputStream = fopen('php://output', 'wb');
            $fileStream = $defaultStorage->readStream($name);
            stream_copy_to_stream($fileStream, $outputStream);
        }, 200, [
            'Content-Type' => $defaultStorage->mimeType($name),
            'Content-Disposition' => ResponseHeaderBag::DISPOSITION_ATTACHMENT,
        ]);
    }
}
