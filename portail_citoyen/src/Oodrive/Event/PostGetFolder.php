<?php

namespace App\Oodrive\Event;

use App\Oodrive\DTO\Folder;

class PostGetFolder
{
    public const NAME = 'oodrive.folder.post_get_folder';

    public function __construct(
        private readonly Folder $folder,
    ) {
    }

    public function getFolder(): Folder
    {
        return $this->folder;
    }
}
