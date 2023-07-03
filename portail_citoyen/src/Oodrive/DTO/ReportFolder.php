<?php

declare(strict_types=1);

namespace App\Oodrive\DTO;

class ReportFolder
{
    private string $id;
    private \DateTimeImmutable $creationDate;
    /** @var array<File> */
    private array $files;

    /**
     * @param array<File> $files
     */
    public function __construct(string $id, \DateTimeImmutable $creationDate, array $files)
    {
        $this->id = $id;
        $this->creationDate = $creationDate;
        $this->files = $files;
    }

    public function getId(): string
    {
        return $this->id;
    }

    public function getCreationDate(): \DateTimeImmutable
    {
        return $this->creationDate;
    }

    /**
     * @return array<File>
     */
    public function getFiles(): array
    {
        return $this->files;
    }
}
