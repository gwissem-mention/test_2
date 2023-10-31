<?php

declare(strict_types=1);

namespace App\Controller\File;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\BinaryFileResponse;
use Symfony\Component\HttpFoundation\File\File;
use Symfony\Component\Routing\Annotation\Route;

class TmpFileDownloadController extends AbstractController
{
    #[Route('/telecharger-piece-jointe-temp/{fileName}/{originalName}', name: 'tmp_file_download')]
    public function __invoke(string $fileName, string $originalName): BinaryFileResponse
    {
        return $this->file(new File(sys_get_temp_dir().'/'.$fileName), $originalName);
    }
}
