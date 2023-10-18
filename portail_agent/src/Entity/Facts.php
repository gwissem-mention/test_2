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
    private ?string $startAddressCountry = null;

    #[ORM\Column(length: 255)]
    private ?string $startAddressDepartment = null;

    #[ORM\Column]
    private ?int $startAddressDepartmentNumber = null;

    #[ORM\Column(length: 255)]
    private ?string $startAddressCity = null;
    #[ORM\Column(length: 255)]
    private ?string $startAddressPostalCode = null;

    #[ORM\Column(length: 255)]
    private ?string $startAddressInseeCode = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $endAddressCountry = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $endAddressDepartment = null;

    #[ORM\Column(nullable: true)]
    private ?int $endAddressDepartmentNumber = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $endAddressCity = null;
    #[ORM\Column(length: 255, nullable: true)]
    private ?string $endAddressPostalCode = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $endAddressInseeCode = null;

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

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $website = null;

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

    public function getStartAddressCountry(): ?string
    {
        return $this->startAddressCountry;
    }

    public function setStartAddressCountry(?string $startAddressCountry): self
    {
        $this->startAddressCountry = $startAddressCountry;

        return $this;
    }

    public function getStartAddressDepartment(): ?string
    {
        return $this->startAddressDepartment;
    }

    public function setStartAddressDepartment(?string $startAddressDepartment): self
    {
        $this->startAddressDepartment = $startAddressDepartment;

        return $this;
    }

    public function getStartAddressDepartmentNumber(): ?int
    {
        return $this->startAddressDepartmentNumber;
    }

    public function setStartAddressDepartmentNumber(?int $startAddressDepartmentNumber): self
    {
        $this->startAddressDepartmentNumber = $startAddressDepartmentNumber;

        return $this;
    }

    public function getStartAddressCity(): ?string
    {
        return $this->startAddressCity;
    }

    public function setStartAddressCity(?string $startAddressCity): self
    {
        $this->startAddressCity = $startAddressCity;

        return $this;
    }

    public function getStartAddressPostalCode(): ?string
    {
        return $this->startAddressPostalCode;
    }

    public function setStartAddressPostalCode(?string $startAddressPostalCode): self
    {
        $this->startAddressPostalCode = $startAddressPostalCode;

        return $this;
    }

    public function getStartAddressInseeCode(): ?string
    {
        return $this->startAddressInseeCode;
    }

    public function setStartAddressInseeCode(?string $startAddressInseeCode): self
    {
        $this->startAddressInseeCode = $startAddressInseeCode;

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

    public function hasExactAddress(): bool
    {
        return null !== $this->getStartAddress() && null === $this->getEndAddress();
    }

    public function getWebsite(): ?string
    {
        return $this->website;
    }

    public function setWebsite(?string $website): self
    {
        $this->website = $website;

        return $this;
    }

    public function getEndAddressCountry(): ?string
    {
        return $this->endAddressCountry;
    }

    public function setEndAddressCountry(?string $endAddressCountry): self
    {
        $this->endAddressCountry = $endAddressCountry;

        return $this;
    }

    public function getEndAddressDepartment(): ?string
    {
        return $this->endAddressDepartment;
    }

    public function setEndAddressDepartment(?string $endAddressDepartment): self
    {
        $this->endAddressDepartment = $endAddressDepartment;

        return $this;
    }

    public function getEndAddressDepartmentNumber(): ?int
    {
        return $this->endAddressDepartmentNumber;
    }

    public function setEndAddressDepartmentNumber(?int $endAddressDepartmentNumber): self
    {
        $this->endAddressDepartmentNumber = $endAddressDepartmentNumber;

        return $this;
    }

    public function getEndAddressCity(): ?string
    {
        return $this->endAddressCity;
    }

    public function setEndAddressCity(?string $endAddressCity): self
    {
        $this->endAddressCity = $endAddressCity;

        return $this;
    }

    public function getEndAddressPostalCode(): ?string
    {
        return $this->endAddressPostalCode;
    }

    public function setEndAddressPostalCode(?string $endAddressPostalCode): self
    {
        $this->endAddressPostalCode = $endAddressPostalCode;

        return $this;
    }

    public function getEndAddressInseeCode(): ?string
    {
        return $this->endAddressInseeCode;
    }

    public function setEndAddressInseeCode(?string $endAddressInseeCode): self
    {
        $this->endAddressInseeCode = $endAddressInseeCode;

        return $this;
    }
}
