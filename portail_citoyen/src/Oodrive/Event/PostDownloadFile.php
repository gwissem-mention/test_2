<?php

declare(strict_types=1);

namespace App\Oodrive\Event;

use App\Oodrive\DTO\File;

class PostDownloadFile
{
    public const NAME = 'oodrive.file.post_download';

    public function __construct(
        private readonly File $file
    ) {
    }

    public function getFile(): File
    {
        return $this->file;
    }
}
