<?php

namespace App\Oodrive\DTO;

class Folder
{
    private string $id;

    private string $name;

    private int $childrenFolderCount;

    private bool $isDir;

    /**
     * @param array{
     *     'id': string,
     *     'name': string|null,
     *     'childFolderCount': int|null,
     *     'isDir': bool|null
     * } $payload
     */
    public function __construct(array $payload)
    {
        $this->id = $payload['id'];
        $this->name = $payload['name'] ?? '';
        $this->childrenFolderCount = $payload['childFolderCount'] ?? 0;
        $this->isDir = $payload['isDir'] ?? true;
    }

    public function getId(): string
    {
        return $this->id;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function getChildrenFolderCount(): int
    {
        return $this->childrenFolderCount;
    }

    public function isDir(): bool
    {
        return $this->isDir;
    }
}
