<?php

declare(strict_types=1);

namespace App\Oodrive\Event;

use App\Oodrive\DTO\Folder;

class PostGetChildrenFolders
{
    public const NAME = 'oodrive.item.post_get_children_folders';

    /**
     * @param Folder[] $childrenFolders
     */
    public function __construct(
        private readonly string $folderId,
        private readonly array $childrenFolders,
    ) {
    }

    public function getFolderId(): string
    {
        return $this->folderId;
    }

    /**
     * @return Folder[]
     */
    public function getChildrenFolders(): array
    {
        return $this->childrenFolders;
    }
}
