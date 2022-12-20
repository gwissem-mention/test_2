<?php

declare(strict_types=1);

namespace App\Entity;

use App\Repository\ComplaintRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: ComplaintRepository::class)]
class Complaint
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column]
    private ?\DateTimeImmutable $createdAt = null;

    #[ORM\Column]
    private ?\DateTimeImmutable $appointmentDate = null;

    #[ORM\Column(length: 255)]
    private ?string $status = null;

    #[ORM\Column(length: 255)]
    private ?string $declarationNumber = null;

    #[ORM\Column(length: 255)]
    private ?string $assignedAgent = null;

    #[ORM\Column]
    private ?bool $optinNotification = null;

    #[ORM\Column]
    private ?int $commentsNumber = null;

    #[ORM\OneToOne(cascade: ['persist', 'remove'])]
    #[ORM\JoinColumn(nullable: false)]
    private ?Identity $identity = null;

    #[ORM\OneToOne(cascade: ['persist', 'remove'])]
    #[ORM\JoinColumn(nullable: false)]
    private ?Facts $facts = null;

    /** @var Collection<int, FactsObject> */
    #[ORM\OneToMany(mappedBy: 'complaint', targetEntity: FactsObject::class, cascade: [
        'persist',
        'remove',
    ], orphanRemoval: true)]
    private Collection $objects;

    #[ORM\OneToOne(cascade: ['persist', 'remove'])]
    #[ORM\JoinColumn(nullable: false)]
    private ?AdditionalInformation $additionalInformation = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $alert = null;

    public function __construct()
    {
        $this->objects = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getCreatedAt(): ?\DateTimeImmutable
    {
        return $this->createdAt;
    }

    public function setCreatedAt(\DateTimeImmutable $createdAt): self
    {
        $this->createdAt = $createdAt;

        return $this;
    }

    public function getAppointmentDate(): ?\DateTimeImmutable
    {
        return $this->appointmentDate;
    }

    public function setAppointmentDate(\DateTimeImmutable $appointmentDate): self
    {
        $this->appointmentDate = $appointmentDate;

        return $this;
    }

    public function getStatus(): ?string
    {
        return $this->status;
    }

    public function setStatus(string $status): self
    {
        $this->status = $status;

        return $this;
    }

    public function getDeclarationNumber(): ?string
    {
        return $this->declarationNumber;
    }

    public function setDeclarationNumber(string $declarationNumber): self
    {
        $this->declarationNumber = $declarationNumber;

        return $this;
    }

    public function getAssignedAgent(): ?string
    {
        return $this->assignedAgent;
    }

    public function setAssignedAgent(string $assignedAgent): self
    {
        $this->assignedAgent = $assignedAgent;

        return $this;
    }

    public function isOptinNotification(): ?bool
    {
        return $this->optinNotification;
    }

    public function setOptinNotification(bool $optinNotification): self
    {
        $this->optinNotification = $optinNotification;

        return $this;
    }

    public function getCommentsNumber(): ?int
    {
        return $this->commentsNumber;
    }

    public function setCommentsNumber(int $commentsNumber): self
    {
        $this->commentsNumber = $commentsNumber;

        return $this;
    }

    public function getIdentity(): ?Identity
    {
        return $this->identity;
    }

    public function setIdentity(Identity $identity): self
    {
        $this->identity = $identity;

        return $this;
    }

    public function getFacts(): ?Facts
    {
        return $this->facts;
    }

    public function setFacts(Facts $facts): self
    {
        $this->facts = $facts;

        return $this;
    }

    /**
     * @return Collection<int, FactsObject>
     */
    public function getObjects(): Collection
    {
        return $this->objects;
    }

    public function addObject(FactsObject $object): self
    {
        if (!$this->objects->contains($object)) {
            $this->objects->add($object);
            $object->setComplaint($this);
        }

        return $this;
    }

    public function removeObject(FactsObject $object): self
    {
        if ($this->objects->removeElement($object)) {
            // set the owning side to null (unless already changed)
            if ($object->getComplaint() === $this) {
                $object->setComplaint(null);
            }
        }

        return $this;
    }

    public function getAdditionalInformation(): ?AdditionalInformation
    {
        return $this->additionalInformation;
    }

    public function setAdditionalInformation(AdditionalInformation $additionalInformation): self
    {
        $this->additionalInformation = $additionalInformation;

        return $this;
    }

    public function getAlert(): ?string
    {
        return $this->alert;
    }

    public function setAlert(?string $alert): self
    {
        $this->alert = $alert;

        return $this;
    }
}