<?php

declare(strict_types=1);

namespace App\Oodrive\DTO;

class Folder
{
    private string $id;

    private string $name;

    private string $parentId;

    private int $childrenFileCount;

    private int $childrenFolderCount;

    private bool $isDir;

    /**
     * @param array{
     *     'id': string,
     *     'name': string|null,
     *     'parentId': string|null,
     *     'childFileCount': int|null,
     *     'childFolderCount': int|null,
     *     'isDir': bool|null
     * } $payload
     */
    public function __construct(array $payload)
    {
        $this->id = $payload['id'];
        $this->name = $payload['name'] ?? '';
        $this->parentId = $payload['parentId'] ?? '';
        $this->childrenFileCount = $payload['childFileCount'] ?? 0;
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

    public function getParentId(): string
    {
        return $this->parentId;
    }

    public function setParentId(string $parentId): self
    {
        $this->parentId = $parentId;

        return $this;
    }

    public function getChildrenFileCount(): int
    {
        return $this->childrenFileCount;
    }

    public function getChildrenFolderCount(): int
    {
        return $this->childrenFolderCount;
    }

    public function isDir(): bool
    {
        return $this->isDir;
    }

    /** @return array<string, string> */
    public function getPayload(): array
    {
        return [
            'parentId' => $this->parentId,
        ];
    }
}
