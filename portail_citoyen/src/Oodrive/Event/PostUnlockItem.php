<?php

declare(strict_types=1);

namespace App\Oodrive\Event;

class PostUnlockItem
{
    public const NAME = 'oodrive.item.post_unlock';

    public function __construct(
        private readonly string $itemId,
    ) {
    }

    public function getItemId(): string
    {
        return $this->itemId;
    }
}
