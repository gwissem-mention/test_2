<?php

declare(strict_types=1);

namespace App\Form\Model\Objects;

use App\Form\Model\Address\AbstractSerializableAddress;
use App\Form\Model\Identity\PhoneModel;

class DocumentAdditionalInformationModel
{
    private ?string $documentOwnerLastName = null;
    private ?string $documentOwnerFirstName = null;
    private ?string $documentOwnerCompany = null;
    private ?PhoneModel $documentOwnerPhone = null;
    private ?string $documentOwnerEmail = null;
    private ?AbstractSerializableAddress $documentOwnerAddress = null;
    private ?string $documentNumber = null;
    private ?string $documentIssuedBy = null;
    private ?\DateTimeInterface $documentIssuedOn = null;
    private ?\DateTimeInterface $documentValidityEndDate = null;

    public function getDocumentOwnerLastName(): ?string
    {
        return $this->documentOwnerLastName;
    }

    public function setDocumentOwnerLastName(?string $documentOwnerLastName): self
    {
        $this->documentOwnerLastName = $documentOwnerLastName;

        return $this;
    }

    public function getDocumentOwnerFirstName(): ?string
    {
        return $this->documentOwnerFirstName;
    }

    public function setDocumentOwnerFirstName(?string $documentOwnerFirstName): self
    {
        $this->documentOwnerFirstName = $documentOwnerFirstName;

        return $this;
    }

    public function getDocumentOwnerCompany(): ?string
    {
        return $this->documentOwnerCompany;
    }

    public function setDocumentOwnerCompany(?string $documentOwnerCompany): self
    {
        $this->documentOwnerCompany = $documentOwnerCompany;

        return $this;
    }

    public function getDocumentOwnerPhone(): ?PhoneModel
    {
        return $this->documentOwnerPhone;
    }

    public function setDocumentOwnerPhone(?PhoneModel $documentOwnerPhone): self
    {
        $this->documentOwnerPhone = $documentOwnerPhone;

        return $this;
    }

    public function getDocumentOwnerEmail(): ?string
    {
        return $this->documentOwnerEmail;
    }

    public function setDocumentOwnerEmail(?string $documentOwnerEmail): self
    {
        $this->documentOwnerEmail = $documentOwnerEmail;

        return $this;
    }

    public function getDocumentOwnerAddress(): ?AbstractSerializableAddress
    {
        return $this->documentOwnerAddress;
    }

    public function setDocumentOwnerAddress(?AbstractSerializableAddress $documentOwnerAddress): self
    {
        $this->documentOwnerAddress = $documentOwnerAddress;

        return $this;
    }

    public function getDocumentNumber(): ?string
    {
        return $this->documentNumber;
    }

    public function setDocumentNumber(?string $documentNumber): self
    {
        $this->documentNumber = $documentNumber;

        return $this;
    }

    public function getDocumentIssuedBy(): ?string
    {
        return $this->documentIssuedBy;
    }

    public function setDocumentIssuedBy(?string $documentIssuedBy): self
    {
        $this->documentIssuedBy = $documentIssuedBy;

        return $this;
    }

    public function getDocumentIssuedOn(): ?\DateTimeInterface
    {
        return $this->documentIssuedOn;
    }

    public function setDocumentIssuedOn(?\DateTimeInterface $documentIssuedOn): self
    {
        $this->documentIssuedOn = $documentIssuedOn;

        return $this;
    }

    public function getDocumentValidityEndDate(): ?\DateTimeInterface
    {
        return $this->documentValidityEndDate;
    }

    public function setDocumentValidityEndDate(?\DateTimeInterface $documentValidityEndDate): self
    {
        $this->documentValidityEndDate = $documentValidityEndDate;

        return $this;
    }
}
