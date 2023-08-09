<?php

declare(strict_types=1);

namespace App\Form\Model\Objects;

use App\Form\Model\FileModel;
use App\Form\Model\Identity\PhoneModel;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;

class ObjectModel
{
    public const STATUS_STOLEN = 1;
    public const STATUS_DEGRADED = 2;

    private ?int $status = null;
    private ?int $category = null;
    private ?string $label = null;
    private ?string $brand = null;
    private ?string $model = null;
    private ?PhoneModel $phoneNumberLine = null;
    private ?string $operator = null;
    private ?string $serialNumber = null;
    private ?string $description = null;
    private ?int $quantity = null;
    private ?string $bank = null;
    private ?string $bankAccountNumber = null;
    private ?string $creditCardNumber = null;
    private ?string $registrationNumber = null;
    private ?string $registrationNumberCountry = null;
    private ?string $insuranceCompany = null;
    private ?string $insuranceNumber = null;
    private ?float $amount = null;
    private ?int $documentType = null;
    private ?string $otherDocumentType = null;
    private ?bool $documentOwned = null;
    private ?DocumentAdditionalInformationModel $documentAdditionalInformation = null;
    private ?bool $stillOnWhenMobileStolen = null;
    private ?bool $keyboardLockedWhenMobileStolen = null;
    private ?bool $pinEnabledWhenMobileStolen = null;
    private ?bool $mobileInsured = null;
    private ?bool $allowOperatorCommunication = null;
    private ?int $registeredVehicleNature = null;
    private ?string $degradationDescription = null;

    /**
     * @var Collection<int, FileModel>
     */
    private Collection $files;

    public function __construct()
    {
        $this->files = new ArrayCollection();
    }

    public function getStatus(): ?int
    {
        return $this->status;
    }

    public function setStatus(?int $status): self
    {
        $this->status = $status;

        return $this;
    }

    public function getCategory(): ?int
    {
        return $this->category;
    }

    public function setCategory(?int $category): self
    {
        $this->category = $category;

        return $this;
    }

    public function getLabel(): ?string
    {
        return $this->label;
    }

    public function setLabel(?string $label): self
    {
        $this->label = $label;

        return $this;
    }

    public function getBrand(): ?string
    {
        return $this->brand;
    }

    public function setBrand(?string $brand): self
    {
        $this->brand = $brand;

        return $this;
    }

    public function getModel(): ?string
    {
        return $this->model;
    }

    public function setModel(?string $model): self
    {
        $this->model = $model;

        return $this;
    }

    public function getPhoneNumberLine(): ?PhoneModel
    {
        return $this->phoneNumberLine;
    }

    public function setPhoneNumberLine(?PhoneModel $phoneNumberLine): self
    {
        $this->phoneNumberLine = $phoneNumberLine;

        return $this;
    }

    public function getOperator(): ?string
    {
        return $this->operator;
    }

    public function setOperator(?string $operator): self
    {
        $this->operator = $operator;

        return $this;
    }

    public function getSerialNumber(): ?string
    {
        return $this->serialNumber;
    }

    public function setSerialNumber(?string $serialNumber): self
    {
        $this->serialNumber = $serialNumber;

        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(?string $description): self
    {
        $this->description = $description;

        return $this;
    }

    public function getQuantity(): ?int
    {
        return $this->quantity;
    }

    public function setQuantity(?int $quantity): self
    {
        $this->quantity = $quantity;

        return $this;
    }

    public function getBank(): ?string
    {
        return $this->bank;
    }

    public function setBank(?string $bank): self
    {
        $this->bank = $bank;

        return $this;
    }

    public function getBankAccountNumber(): ?string
    {
        return $this->bankAccountNumber;
    }

    public function setBankAccountNumber(?string $bankAccountNumber): self
    {
        $this->bankAccountNumber = $bankAccountNumber;

        return $this;
    }

    public function getCreditCardNumber(): ?string
    {
        return $this->creditCardNumber;
    }

    public function setCreditCardNumber(?string $creditCardNumber): self
    {
        $this->creditCardNumber = $creditCardNumber;

        return $this;
    }

    public function getRegistrationNumber(): ?string
    {
        return $this->registrationNumber;
    }

    public function setRegistrationNumber(?string $registrationNumber): self
    {
        $this->registrationNumber = $registrationNumber;

        return $this;
    }

    public function getRegistrationNumberCountry(): ?string
    {
        return $this->registrationNumberCountry;
    }

    public function setRegistrationNumberCountry(?string $registrationNumberCountry): self
    {
        $this->registrationNumberCountry = $registrationNumberCountry;

        return $this;
    }

    public function getInsuranceCompany(): ?string
    {
        return $this->insuranceCompany;
    }

    public function setInsuranceCompany(?string $insuranceCompany): self
    {
        $this->insuranceCompany = $insuranceCompany;

        return $this;
    }

    public function getInsuranceNumber(): ?string
    {
        return $this->insuranceNumber;
    }

    public function setInsuranceNumber(?string $insuranceNumber): self
    {
        $this->insuranceNumber = $insuranceNumber;

        return $this;
    }

    public function getAmount(): ?float
    {
        return $this->amount;
    }

    public function setAmount(?float $amount): self
    {
        $this->amount = $amount;

        return $this;
    }

    public function getDocumentType(): ?int
    {
        return $this->documentType;
    }

    public function setDocumentType(?int $documentType): self
    {
        $this->documentType = $documentType;

        return $this;
    }

    public function getOtherDocumentType(): ?string
    {
        return $this->otherDocumentType;
    }

    public function setOtherDocumentType(?string $otherDocumentType): self
    {
        $this->otherDocumentType = $otherDocumentType;

        return $this;
    }

    public function getDocumentOwned(): ?bool
    {
        return $this->documentOwned;
    }

    public function setDocumentOwned(?bool $documentOwned): self
    {
        $this->documentOwned = $documentOwned;

        return $this;
    }

    public function getDocumentAdditionalInformation(): ?DocumentAdditionalInformationModel
    {
        return $this->documentAdditionalInformation;
    }

    public function setDocumentAdditionalInformation(?DocumentAdditionalInformationModel $documentAdditionalInformation): self
    {
        $this->documentAdditionalInformation = $documentAdditionalInformation;

        return $this;
    }

    /**
     * @return Collection<int, FileModel>
     */
    public function getFiles(): Collection
    {
        return $this->files;
    }

    public function addFile(FileModel $file): self
    {
        if (!$this->files->contains($file)) {
            $this->files->add($file);
        }

        return $this;
    }

    /**
     * @param Collection<int, FileModel> $files
     */
    public function setFiles(Collection $files): self
    {
        $this->files = $files;

        return $this;
    }

    public function removeFile(FileModel $file): self
    {
        if ($this->files->contains($file)) {
            $this->files->removeElement($file);
        }

        return $this;
    }

    public function isStillOnWhenMobileStolen(): ?bool
    {
        return $this->stillOnWhenMobileStolen;
    }

    public function setStillOnWhenMobileStolen(?bool $stillOnWhenMobileStolen): self
    {
        $this->stillOnWhenMobileStolen = $stillOnWhenMobileStolen;

        return $this;
    }

    public function isKeyboardLockedWhenMobileStolen(): ?bool
    {
        return $this->keyboardLockedWhenMobileStolen;
    }

    public function setKeyboardLockedWhenMobileStolen(?bool $keyboardLockedWhenMobileStolen): self
    {
        $this->keyboardLockedWhenMobileStolen = $keyboardLockedWhenMobileStolen;

        return $this;
    }

    public function isPinEnabledWhenMobileStolen(): ?bool
    {
        return $this->pinEnabledWhenMobileStolen;
    }

    public function setPinEnabledWhenMobileStolen(?bool $pinEnabledWhenMobileStolen): self
    {
        $this->pinEnabledWhenMobileStolen = $pinEnabledWhenMobileStolen;

        return $this;
    }

    public function isMobileInsured(): ?bool
    {
        return $this->mobileInsured;
    }

    public function setMobileInsured(?bool $mobileInsured): self
    {
        $this->mobileInsured = $mobileInsured;

        return $this;
    }

    public function isAllowOperatorCommunication(): ?bool
    {
        return $this->allowOperatorCommunication;
    }

    public function setAllowOperatorCommunication(?bool $allowOperatorCommunication): self
    {
        $this->allowOperatorCommunication = $allowOperatorCommunication;

        return $this;
    }

    public function getRegisteredVehicleNature(): ?int
    {
        return $this->registeredVehicleNature;
    }

    public function setRegisteredVehicleNature(?int $registeredVehicleNature): ObjectModel
    {
        $this->registeredVehicleNature = $registeredVehicleNature;

        return $this;
    }

    public function getDegradationDescription(): ?string
    {
        return $this->degradationDescription;
    }

    public function setDegradationDescription(?string $degradationDescription): self
    {
        $this->degradationDescription = $degradationDescription;

        return $this;
    }
}
