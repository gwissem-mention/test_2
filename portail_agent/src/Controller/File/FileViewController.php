<?php

declare(strict_types=1);

namespace App\Controller\File;

use App\Entity\Complaint;
use League\Flysystem\FilesystemException;
use League\Flysystem\FilesystemOperator;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\BinaryFileResponse;
use Symfony\Component\HttpFoundation\File\File;
use Symfony\Component\HttpFoundation\ResponseHeaderBag;
use Symfony\Component\Routing\Annotation\Route;

class FileViewController extends AbstractController
{
    /**
     * @throws FilesystemException
     */
    #[Route('/voir-piece-jointe/{id}/{name}', name: 'file_view', requirements: ['id' => '\d+', 'name' => '.+'])]
    public function __invoke(Complaint $complaint, string $name, FilesystemOperator $defaultStorage): BinaryFileResponse
    {
        $this->denyAccessUnlessGranted('COMPLAINT_VIEW', $complaint);

        return $this->file(new File(stream_get_meta_data($defaultStorage->readStream($name))['uri']), disposition: ResponseHeaderBag::DISPOSITION_INLINE);
    }
}
