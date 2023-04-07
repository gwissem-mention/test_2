<?php

namespace App\Oodrive\DTO;

class File
{
    private string $id;

    private string $name;

    /**
     * @param array<string> $payload
     */
    public function __construct(array $payload)
    {
        $this->id = $payload['id'];
        $this->name = $payload['name'];
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
}
