<?php

declare(strict_types=1);

namespace App\Oodrive\Event;

class PreUploadFile
{
    public const NAME = 'oodrive.file.pre_create';

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
