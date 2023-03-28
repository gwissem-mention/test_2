<?php

namespace App\Oodrive\Event;

use App\Oodrive\DTO\File;
use App\Oodrive\DTO\Folder;

class PostSearch
{
    public const NAME = 'oodrive.search.post_search';

    /**
     * @param array<Folder|File> $results
     */
    public function __construct(private readonly array $results)
    {
    }

    /**
     * @return File[]|Folder[]
     */
    public function getResults(): array
    {
        return $this->results;
    }
}
