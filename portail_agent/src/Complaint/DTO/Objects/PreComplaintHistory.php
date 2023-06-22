<?php

declare(strict_types=1);

namespace App\Complaint\DTO\Objects;

use App\Entity\Complaint;
use App\Referential\Entity\Service;

final class PreComplaintHistory
{
    private const FOLDER_CREATED = 0;

    private function __construct(
        private string $number,
        private ?string $firstName,
        private ?string $lastName,
        private ?\DateTimeImmutable $complaintDate,
        private int $flag,
        private mixed $file,
        private ?string $activeUniteMail = null,
        private ?string $activeMailDepartement = null,
        private ?string $contactEmail = null,
        private ?string $prejudiceObject = null,
        private ?\DateTimeImmutable $deletionDate = null
    ) {
    }

    /**
     * @param array{complaint:Complaint, file: string|false, service: Service, prejudiceObject: string|null} $data
     */
    public static function new(array $data): self
    {
        return new self(
            $data['complaint']->getDeclarationNumber(),
            $data['complaint']->getIdentity()?->getFirstname(),
            $data['complaint']->getIdentity()?->getLastname(),
            $data['complaint']->getCreatedAt(),
            self::FOLDER_CREATED,
            $data['file'],
            $data['service']->getEmail(),
            $data['service']->getHomeDepartmentEmail(),
            $data['complaint']->getIdentity()?->getEmail(),
            $data['prejudiceObject'],
            null
        );
    }

    public function getNumber(): string
    {
        return $this->number;
    }

    public function getFirstName(): ?string
    {
        return $this->firstName;
    }

    public function getLastName(): ?string
    {
        return $this->lastName;
    }

    public function getComplaintDate(): ?\DateTimeImmutable
    {
        return $this->complaintDate;
    }

    public function getFlag(): int
    {
        return $this->flag;
    }

    public function getFile(): mixed
    {
        return $this->file;
    }

    public function getActiveUniteMail(): ?string
    {
        return $this->activeUniteMail;
    }

    public function getActiveMailDepartement(): ?string
    {
        return $this->activeMailDepartement;
    }

    public function getContactEmail(): ?string
    {
        return $this->contactEmail;
    }

    public function getPrejudiceObject(): ?string
    {
        return $this->prejudiceObject;
    }

    public function getDeletionDate(): ?\DateTimeImmutable
    {
        return $this->deletionDate;
    }
}
