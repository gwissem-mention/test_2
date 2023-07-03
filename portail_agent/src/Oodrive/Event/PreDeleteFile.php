<?php

declare(strict_types=1);

namespace App\Oodrive\Event;

use App\Oodrive\DTO\File;

class PreDeleteFile
{
    public const NAME = 'oodrive.folder.pre_delete_file';

    public function __construct(
        private readonly File $file,
    ) {
    }

    public function getFile(): File
    {
        return $this->file;
    }
}
