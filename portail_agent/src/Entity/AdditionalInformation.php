<?php

declare(strict_types=1);

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity]
class AdditionalInformation
{
    use AlertTrait;

    public const CCTV_PRESENT_YES = 1;
    public const CCTV_PRESENT_NO = 2;
    public const CCTV_PRESENT_DONT_KNOW = 3;

    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column]
    private ?int $cctvPresent = null;

    #[ORM\Column(nullable: true)]
    private ?bool $cctvAvailable = null;

    #[ORM\Column]
    private ?bool $suspectsKnown = null;

    #[ORM\Column(type: Types::TEXT, nullable: true)]
    private ?string $suspectsKnownText = null;

    #[ORM\Column]
    private ?bool $witnessesPresent = null;

    #[ORM\Column]
    private ?bool $fsiVisit = null;

    #[ORM\Column(nullable: true)]
    private ?bool $observationMade = null;

    /** @var Collection<int, Witness> */
    #[ORM\OneToMany(mappedBy: 'additionalInformation', targetEntity: Witness::class, cascade: [
        'persist',
        'remove',
    ], orphanRemoval: true)]
    private Collection $witnesses;

    public function __construct()
    {
        $this->witnesses = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getCctvPresent(): ?int
    {
        return $this->cctvPresent;
    }

    public function setCctvPresent(int $cctvPresent): self
    {
        $this->cctvPresent = $cctvPresent;

        return $this;
    }

    public function isCctvAvailable(): ?bool
    {
        return $this->cctvAvailable;
    }

    public function setCctvAvailable(?bool $cctvAvailable): self
    {
        $this->cctvAvailable = $cctvAvailable;

        return $this;
    }

    public function isSuspectsKnown(): ?bool
    {
        return $this->suspectsKnown;
    }

    public function setSuspectsKnown(bool $suspectsKnown): self
    {
        $this->suspectsKnown = $suspectsKnown;

        return $this;
    }

    public function getSuspectsKnownText(): ?string
    {
        return $this->suspectsKnownText;
    }

    public function setSuspectsKnownText(?string $suspectsKnownText): self
    {
        $this->suspectsKnownText = $suspectsKnownText;

        return $this;
    }

    public function isWitnessesPresent(): ?bool
    {
        return $this->witnessesPresent;
    }

    public function setWitnessesPresent(bool $witnessesPresent): self
    {
        $this->witnessesPresent = $witnessesPresent;

        return $this;
    }

    public function isFsiVisit(): ?bool
    {
        return $this->fsiVisit;
    }

    public function setFsiVisit(bool $fsiVisit): self
    {
        $this->fsiVisit = $fsiVisit;

        return $this;
    }

    public function isObservationMade(): ?bool
    {
        return $this->observationMade;
    }

    public function setObservationMade(?bool $observationMade): self
    {
        $this->observationMade = $observationMade;

        return $this;
    }

    /**
     * @return Collection<int, Witness>
     */
    public function getWitnesses(): Collection
    {
        return $this->witnesses;
    }

    public function addWitness(Witness $witness): self
    {
        if (!$this->witnesses->contains($witness)) {
            $this->witnesses->add($witness);
            $witness->setAdditionalInformation($this);
        }

        return $this;
    }

    public function removeWitness(Witness $witness): self
    {
        if ($this->witnesses->removeElement($witness)) {
            if ($witness->getAdditionalInformation() === $this) {
                $witness->setAdditionalInformation(null);
            }
        }

        return $this;
    }
}
