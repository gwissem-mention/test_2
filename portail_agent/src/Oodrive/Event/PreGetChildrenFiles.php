<?php

declare(strict_types=1);

namespace App\Oodrive\Event;

class PreGetChildrenFiles
{
    public const NAME = 'oodrive.item.pre_get_children_files';

    public function __construct(
        private readonly string $folderId,
    ) {
    }

    public function getFolderId(): string
    {
        return $this->folderId;
    }
}
