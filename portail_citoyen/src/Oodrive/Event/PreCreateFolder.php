<?php

declare(strict_types=1);

namespace App\Oodrive\Event;

class PreCreateFolder
{
    public const NAME = 'oodrive.folder.pre_create';

    public function __construct(
        private readonly string $folderName,
        private readonly string $parentId,
    ) {
    }

    public function getFolderName(): string
    {
        return $this->folderName;
    }

    public function getParentId(): string
    {
        return $this->parentId;
    }
}
