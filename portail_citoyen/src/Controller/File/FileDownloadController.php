<?php

declare(strict_types=1);

namespace App\Controller\File;

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
    #[Route('/telecharger-piece-jointe/{fileName}/{originalName}', name: 'file_download')]
    public function __invoke(string $fileName, string $originalName, FilesystemOperator $defaultStorage): BinaryFileResponse
    {
        return $this->file(new File(stream_get_meta_data($defaultStorage->readStream($fileName))['uri']), $originalName);
    }
}
