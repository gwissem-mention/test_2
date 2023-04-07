<?php

declare(strict_types=1);

namespace App\Form\Model;

class FileModel
{
    private string $name;
    private string $path;
    private string $type;
    private int $size;

    public function __construct(string $name, string $path, string $type, int $size)
    {
        $this->name = $name;
        $this->path = $path;
        $this->type = $type;
        $this->size = $size;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function getPath(): string
    {
        return $this->path;
    }

    public function getType(): string
    {
        return $this->type;
    }

    public function getSize(): int
    {
        return $this->size;
    }
}
