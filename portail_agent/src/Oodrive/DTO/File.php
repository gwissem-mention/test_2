<?php

declare(strict_types=1);

namespace App\Oodrive\DTO;

class File
{
    private string $id;

    private string $name;

    private bool $isDir;

    /**
     * @param array{
     *     'id': string,
     *     'name': string|null,
     *     'isDir': bool|null
     *     }$payload
     */
    public function __construct(array $payload)
    {
        $this->id = $payload['id'];
        $this->name = $payload['name'] ?? '';
        $this->isDir = $payload['isDir'] ?? false;
    }

    public function getId(): string
    {
        return $this->id;
    }

    public function getName(): string
    {
        return $this->name;
    }

    /** @return array<string, string> */
    public function getPayload(): array
    {
        return [
            'name' => $this->name,
        ];
    }

    public function isDir(): bool
    {
        return $this->isDir;
    }
}
