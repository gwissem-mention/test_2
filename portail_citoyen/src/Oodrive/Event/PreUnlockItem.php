<?php

declare(strict_types=1);

namespace App\Oodrive\Event;

class PreUnlockItem
{
    public const NAME = 'oodrive.item.pre_unlock';

    public function __construct(
        private readonly string $itemId,
    ) {
    }

    public function getItemId(): string
    {
        return $this->itemId;
    }
}
