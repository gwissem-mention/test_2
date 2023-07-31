<?php

declare(strict_types=1);

namespace App\Oodrive\FolderRotation;

class FolderNameGenerator
{
    public function generateName(): string
    {
        return uniqid();
    }
}
