<?php

declare(strict_types=1);

namespace App\Oodrive\Event;

use App\Oodrive\DTO\File;

class PostUploadFile
{
    public const NAME = 'oodrive.file.post_create';

    public function __construct(
        private readonly File $file
    ) {
    }

    public function getFolder(): File
    {
        return $this->file;
    }
}
