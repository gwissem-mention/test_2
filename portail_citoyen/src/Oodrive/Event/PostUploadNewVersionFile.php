<?php

declare(strict_types=1);

namespace App\Oodrive\Event;

use App\Oodrive\DTO\File;

class PostUploadNewVersionFile
{
    public const NAME = 'oodrive.file.post_upload_new_version';

    public function __construct(
        private readonly File $file,
    ) {
    }

    public function getFile(): File
    {
        return $this->file;
    }
}
