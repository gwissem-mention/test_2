<?php

declare(strict_types=1);

namespace App\Entity;

use App\Repository\RightDelegationRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: RightDelegationRepository::class)]
class RightDelegation
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\OneToOne(inversedBy: 'rightDelegation')]
    #[ORM\JoinColumn(nullable: false)]
    private ?User $delegatingAgent = null;

    /** @var Collection<int, User> */
    #[ORM\OneToMany(mappedBy: 'delegationGained', targetEntity: User::class)]
    private Collection $delegatedAgents;

    #[ORM\Column(type: Types::DATE_IMMUTABLE)]
    private ?\DateTimeImmutable $startDate = null;

    #[ORM\Column(type: Types::DATE_IMMUTABLE)]
    private ?\DateTimeImmutable $endDate = null;

    public function __construct()
    {
        $this->delegatedAgents = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getDelegatingAgent(): ?User
    {
        return $this->delegatingAgent;
    }

    public function setDelegatingAgent(?User $delegatingAgent): static
    {
        $this->delegatingAgent = $delegatingAgent;

        return $this;
    }

    /**
     * @return Collection<int, User>
     */
    public function getDelegatedAgents(): Collection
    {
        return $this->delegatedAgents;
    }

    public function addDelegatedAgent(User $delegatedAgent): static
    {
        if (!$this->delegatedAgents->contains($delegatedAgent)) {
            $this->delegatedAgents->add($delegatedAgent);
        }

        return $this;
    }

    public function removeDelegatedAgent(User $delegatedAgent): static
    {
        $this->delegatedAgents->removeElement($delegatedAgent);

        return $this;
    }

    public function getStartDate(): ?\DateTimeImmutable
    {
        return $this->startDate;
    }

    public function setStartDate(?\DateTimeImmutable $startDate): static
    {
        $this->startDate = $startDate;

        return $this;
    }

    public function getEndDate(): ?\DateTimeImmutable
    {
        return $this->endDate;
    }

    public function setEndDate(?\DateTimeImmutable $endDate): static
    {
        $this->endDate = $endDate;

        return $this;
    }
}
