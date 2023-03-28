<?php

namespace App\Oodrive\FolderRotation;

class FolderNameGenerator
{
    public function generateName(): string
    {
        return uniqid();
    }
}
