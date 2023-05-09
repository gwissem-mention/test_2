<?php

declare(strict_types=1);

namespace App\Controller\File;

use App\Entity\Complaint;
use League\Flysystem\FilesystemException;
use League\Flysystem\FilesystemOperator;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\BinaryFileResponse;
use Symfony\Component\HttpFoundation\File\File;
use Symfony\Component\Routing\Annotation\Route;

class FileDownloadController extends AbstractController
{
    /**
     * @throws FilesystemException
     */
    #[Route('/telecharger-piece-jointe/{id}/{name}', name: 'file_download', requirements: ['id' => '\d+', 'name' => '.+'])]
    public function __invoke(Complaint $complaint, string $name, FilesystemOperator $defaultStorage): BinaryFileResponse
    {
        $this->denyAccessUnlessGranted('COMPLAINT_VIEW', $complaint);

        return $this->file(new File(stream_get_meta_data($defaultStorage->readStream($name))['uri']));
    }
}
