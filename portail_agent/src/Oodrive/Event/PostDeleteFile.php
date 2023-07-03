<?php

declare(strict_types=1);

namespace App\Oodrive\Event;

use App\Oodrive\DTO\File;

class PostDeleteFile
{
    public const NAME = 'oodrive.folder.post_delete_file';

    public function __construct(
        private readonly File $file,
    ) {
    }

    public function getFile(): File
    {
        return $this->file;
    }
}
