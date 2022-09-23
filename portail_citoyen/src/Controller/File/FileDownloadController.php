<?php

declare(strict_types=1);

namespace App\Controller\File;

use App\Entity\File;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Vich\UploaderBundle\Handler\DownloadHandler;

class FileDownloadController extends AbstractController
{
    #[Route('/telecharger-fichier/{fileName}', name: 'file_download')]
    public function __invoke(File $file, DownloadHandler $downloadHandler): Response
    {
        return $downloadHandler->downloadObject($file, 'file', File::class);
    }
}
