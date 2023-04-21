<?php

declare(strict_types=1);

namespace App\Oodrive\Event;

use App\Oodrive\DTO\Folder;

class PostUpdateFolder
{
    public const NAME = 'oodrive.folder.post_update_folder';

    public function __construct(
        private readonly Folder $folder,
    ) {
    }

    public function getFolder(): Folder
    {
        return $this->folder;
    }
}
