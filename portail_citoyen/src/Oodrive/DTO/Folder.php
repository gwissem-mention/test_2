<?php

declare(strict_types=1);

namespace App\Oodrive\DTO;

class Folder
{
    private string $id;

    private string $name;

    private int $childrenFolderCount;

    private bool $isDir;

    private string $parentId;

    private \DateTimeImmutable $creationDate;

    /**
     * @param array{
     *     'id': string,
     *     'name': string|null,
     *     'childFolderCount': int|null,
     *     'isDir': bool|null,
     *     'parentId': string|null,
     *     'creationDate': string|null
     * } $payload
     */
    public function __construct(array $payload)
    {
        $this->id = $payload['id'];
        $this->name = $payload['name'] ?? '';
        $this->childrenFolderCount = $payload['childFolderCount'] ?? 0;
        $this->isDir = $payload['isDir'] ?? true;
        $this->parentId = $payload['parentId'] ?? '';
        $this->creationDate = new \DateTimeImmutable($payload['creationDate'] ?? 'now');
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

    public function getParentId(): string
    {
        return $this->parentId;
    }

    public function getCreationDate(): \DateTimeImmutable
    {
        return $this->creationDate;
    }
}
