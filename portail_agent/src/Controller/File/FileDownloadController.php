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
use Symfony\Component\HttpFoundation\BinaryFileResponse;
use Symfony\Component\HttpFoundation\File\File;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class FileDownloadController extends AbstractController
{
    /**
     * @throws FilesystemException
     */
    #[Route('/telecharger-piece-jointe/{id}/{name}', name: 'file_download', requirements: ['id' => '\d+', 'name' => '.+'])]
    public function __invoke(Complaint $complaint, string $name, FilesystemOperator $defaultStorage, ApplicationTracesLogger $logger, Request $request): BinaryFileResponse
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

        return $this->file(new File(stream_get_meta_data($defaultStorage->readStream($name))['uri']));
    }
}
