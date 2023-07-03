<?php

declare(strict_types=1);

namespace App\Oodrive\Event;

use App\Oodrive\DTO\File;

class PostGetChildrenFiles
{
    public const NAME = 'oodrive.item.post_get_children_files';

    /**
     * @param File[] $childrenFiles
     */
    public function __construct(
        private readonly string $folderId,
        private readonly array $childrenFiles,
    ) {
    }

    public function getFolderId(): string
    {
        return $this->folderId;
    }

    /**
     * @return File[]
     */
    public function getChildrenFiles(): array
    {
        return $this->childrenFiles;
    }
}
