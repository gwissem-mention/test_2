<?php

declare(strict_types=1);

namespace App\Oodrive\Event;

class PreGetFolder
{
    public const NAME = 'oodrive.folder.pre_get_folder';

    public function __construct(
        private readonly string $folderId,
    ) {
    }

    public function getFolderId(): string
    {
        return $this->folderId;
    }
}
