<?php

declare(strict_types=1);

namespace App\Entity;

use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity]
class Facts
{
    use AlertTrait;

    public const NATURE_ROBBERY = 1;
    public const NATURE_DEGRADATION = 2;
    public const NATURE_OTHER = 3;

    public const EXACT_HOUR_KNOWN_NO = 0;
    public const EXACT_HOUR_KNOWN_YES = 1;
    public const EXACT_HOUR_KNOWN_DONT_KNOW = 2;

    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    /** @var int[]|null */
    #[ORM\Column(type: Types::SIMPLE_ARRAY, nullable: true)]
    private ?array $natures = null;

    #[ORM\Column(type: Types::TEXT)]
    private ?string $description = null;

    #[ORM\Column]
    private ?bool $exactPlaceUnknown = null;

    #[ORM\Column(length: 255)]
    private ?string $place = null;

    #[ORM\Column]
    private ?bool $exactDateKnown = null;

    #[ORM\Column(type: Types::DATE_MUTABLE)]
    private ?\DateTimeInterface $startDate = null;

    #[ORM\Column(type: Types::DATE_MUTABLE, nullable: true)]
    private ?\DateTimeInterface $endDate = null;

    #[ORM\Column(length: 255)]
    private ?string $startAddress = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $endAddress = null;

    #[ORM\Column(length: 255)]
    private ?string $country = null;

    #[ORM\Column(length: 255)]
    private ?string $department = null;

    #[ORM\Column]
    private ?int $departmentNumber = null;

    #[ORM\Column(length: 255)]
    private ?string $city = null;
    #[ORM\Column(length: 255)]
    private ?string $postalCode = null;

    #[ORM\Column(length: 255)]
    private ?string $inseeCode = null;

    #[ORM\Column]
    private ?int $exactHourKnown = null;

    #[ORM\Column(type: Types::TIME_MUTABLE, nullable: true)]
    private ?\DateTimeInterface $startHour = null;

    #[ORM\Column(type: Types::TIME_MUTABLE, nullable: true)]
    private ?\DateTimeInterface $endHour = null;

    #[ORM\Column(type: Types::TEXT, nullable: true)]
    private ?string $addressAdditionalInformation = null;

    #[ORM\Column(nullable: true)]
    private ?bool $victimOfViolence = null;

    #[ORM\Column(type: Types::TEXT, nullable: true)]
    private ?string $victimOfViolenceText = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $callingPhone = null;

    //    #[ORM\Column]
    //    private ?bool $noOrientation = null;
    //
    //    #[ORM\Column(length: 255)]
    //    private ?string $orientation = null;

    //    #[ORM\Column]
    //    private ?bool $physicalPrejudice = null;
    //
    //    #[ORM\Column(length: 255)]
    //    private ?string $physicalPrejudiceDescription = null;
    //
    //    #[ORM\Column(length: 255)]
    //    private ?bool $otherPrejudice = null;
    //
    //    #[ORM\Column(length: 255)]
    //    private ?string $otherPrejudiceDescription = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    /**
     * @return int[]|null
     */
    public function getNatures(): ?array
    {
        return $this->natures;
    }

    /**
     * @param int[]|null $natures
     */
    public function setNatures(?array $natures): self
    {
        $this->natures = $natures;

        return $this;
    }

    public function getPlace(): ?string
    {
        return $this->place;
    }

    public function setPlace(string $place): self
    {
        $this->place = $place;

        return $this;
    }

    public function getStartAddress(): ?string
    {
        return $this->startAddress;
    }

    public function setStartAddress(?string $startAddress): self
    {
        $this->startAddress = $startAddress;

        return $this;
    }

    public function getEndAddress(): ?string
    {
        return $this->endAddress;
    }

    public function setEndAddress(?string $endAddress): self
    {
        $this->endAddress = $endAddress;

        return $this;
    }

    public function getStartHour(): ?\DateTimeInterface
    {
        return $this->startHour;
    }

    public function setStartHour(?\DateTimeInterface $startHour): self
    {
        $this->startHour = $startHour;

        return $this;
    }

    public function getEndHour(): ?\DateTimeInterface
    {
        return $this->endHour;
    }

    public function setEndHour(?\DateTimeInterface $endHour): self
    {
        $this->endHour = $endHour;

        return $this;
    }

    public function getAddressAdditionalInformation(): ?string
    {
        return $this->addressAdditionalInformation;
    }

    public function setAddressAdditionalInformation(?string $addressAdditionalInformation): self
    {
        $this->addressAdditionalInformation = $addressAdditionalInformation;

        return $this;
    }

    public function isExactDateKnown(): ?bool
    {
        return $this->exactDateKnown;
    }

    public function setExactDateKnown(?bool $exactDateKnown): self
    {
        $this->exactDateKnown = $exactDateKnown;

        return $this;
    }

    public function getStartDate(): ?\DateTimeInterface
    {
        return $this->startDate;
    }

    public function setStartDate(?\DateTimeInterface $startDate): self
    {
        $this->startDate = $startDate;

        return $this;
    }

    public function getEndDate(): ?\DateTimeInterface
    {
        return $this->endDate;
    }

    public function setEndDate(?\DateTimeInterface $endDate): self
    {
        $this->endDate = $endDate;

        return $this;
    }

    public function getExactHourKnown(): ?int
    {
        return $this->exactHourKnown;
    }

    public function setExactHourKnown(?int $exactHourKnown): self
    {
        $this->exactHourKnown = $exactHourKnown;

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

    public function getCountry(): ?string
    {
        return $this->country;
    }

    public function setCountry(?string $country): self
    {
        $this->country = $country;

        return $this;
    }

    public function getDepartment(): ?string
    {
        return $this->department;
    }

    public function setDepartment(?string $department): self
    {
        $this->department = $department;

        return $this;
    }

    public function getDepartmentNumber(): ?int
    {
        return $this->departmentNumber;
    }

    public function setDepartmentNumber(?int $departmentNumber): self
    {
        $this->departmentNumber = $departmentNumber;

        return $this;
    }

    public function getCity(): ?string
    {
        return $this->city;
    }

    public function setCity(?string $city): self
    {
        $this->city = $city;

        return $this;
    }

    public function getPostalCode(): ?string
    {
        return $this->postalCode;
    }

    public function setPostalCode(?string $postalCode): self
    {
        $this->postalCode = $postalCode;

        return $this;
    }

    public function getInseeCode(): ?string
    {
        return $this->inseeCode;
    }

    public function setInseeCode(?string $inseeCode): self
    {
        $this->inseeCode = $inseeCode;

        return $this;
    }

    public function isExactPlaceUnknown(): ?bool
    {
        return $this->exactPlaceUnknown;
    }

    public function setExactPlaceUnknown(?bool $exactPlaceUnknown): self
    {
        $this->exactPlaceUnknown = $exactPlaceUnknown;

        return $this;
    }

    public function isVictimOfViolence(): ?bool
    {
        return $this->victimOfViolence;
    }

    public function setVictimOfViolence(?bool $victimOfViolence): self
    {
        $this->victimOfViolence = $victimOfViolence;

        return $this;
    }

    public function getVictimOfViolenceText(): ?string
    {
        return $this->victimOfViolenceText;
    }

    public function setVictimOfViolenceText(?string $victimOfViolenceText): self
    {
        $this->victimOfViolenceText = $victimOfViolenceText;

        return $this;
    }

    public function getCallingPhone(): ?string
    {
        return $this->callingPhone;
    }

    public function setCallingPhone(?string $callingPhone): self
    {
        $this->callingPhone = $callingPhone;

        return $this;
    }
}
