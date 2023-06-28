<?php

namespace App\Messenger\InformationCenter;

class InfocentreMessage
{
    /**
     * @param array<mixed> $data
     */
    public function __construct(readonly private array $data = [])
    {
    }

    /**
     * @return array<mixed>
     */
    public function getData(): array
    {
        return $this->data;
    }
}
