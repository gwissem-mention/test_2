<?php

declare(strict_types=1);

namespace App\Entity;

use App\Entity\FactsObjects\AbstractObject;
use App\Repository\ComplaintRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: ComplaintRepository::class)]
class Complaint
{
    public const STATUS_APPOINTMENT_PENDING = 'pel.appointment.pending';
    public const STATUS_ASSIGNMENT_PENDING = 'pel.assignment.pending';
    public const STATUS_ASSIGNED = 'pel.assigned';
    public const STATUS_CLOSED = 'pel.closed';
    public const STATUS_MP_DECLARANT = 'pel.mp.declarant';
    public const STATUS_ONGOING_LRP = 'pel.ongoing.lrp';
    public const STATUS_REASSIGNMENT_PENDING = 'pel.reassignment.pending';
    public const STATUS_REJECTED = 'pel.rejected';
    public const STATUS_UNIT_REASSIGNMENT_PENDING = 'pel.unit.reassignment.pending';

    public const REFUSAL_REASON_REORIENTATION_APPONTMENT = 1;
    public const REFUSAL_REASON_REORIENTATION_OTHER_SOLUTION = 2;
    public const REFUSAL_REASON_ABSENCE_PENAL_OFFENSE = 3;
    public const REFUSAL_REASON_INSUFISANT_QUALITY_TO_ACT = 4;
    public const REFUSAL_REASON_VICTIM_CARENCE = 5;
    public const REFUSAL_REASON_OTHER = 6;

    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column]
    private ?bool $test = null;
//
//    #[ORM\Column]
//    private ?\DateTimeImmutable $start = null;
//
//    #[ORM\Column]
//    private ?\DateTimeImmutable $finish = null;
//
//    #[ORM\Column(length: 255)]
//    private ?string $declarantIp = null;
//
//    #[ORM\Column]
//    private ?int $TcHome = null;
//
//    #[ORM\Column]
//    private ?int $TcFacts = null;
//
//    #[ORM\Column]
//    private ?int $unitCodeTcFacts = null;

    #[ORM\Column]
    private ?\DateTimeImmutable $createdAt = null;

    #[ORM\Column]
    private ?\DateTimeImmutable $processingDeadline = null;

    #[ORM\Column(nullable: true)]
    private ?bool $deadlineNotified = false;

//    #[ORM\Column]
//    private ?bool $claimsLegalAction = null;

    #[ORM\Column]
    private ?\DateTimeImmutable $appointmentDate = null;

//    #[ORM\Column]
//    private ?string $contactWindow = null;
//
//    #[ORM\Column]
//    private ?string $contactPeriod = null;

    #[ORM\Column(length: 255)]
    private ?string $status = null;

    #[ORM\Column(length: 255, unique: true)]
    private ?string $declarationNumber = null;

    #[ORM\Column]
    private ?bool $optinNotification = null;

    #[ORM\OneToOne(cascade: ['persist', 'remove'])]
    #[ORM\JoinColumn(nullable: false)]
    private ?Identity $identity = null;

    #[ORM\OneToOne(cascade: ['persist', 'remove'])]
    #[ORM\JoinColumn(nullable: true)]
    private ?Identity $personLegalRepresented = null;

    #[ORM\OneToOne(cascade: ['persist', 'remove'])]
    #[ORM\JoinColumn(nullable: false)]
    private ?Facts $facts = null;

    /** @var Collection<int, AbstractObject> */
    #[ORM\OneToMany(mappedBy: 'complaint', targetEntity: AbstractObject::class, cascade: [
        'persist',
        'remove',
    ], orphanRemoval: true)]
    private Collection $objects;

    #[ORM\OneToOne(cascade: ['persist', 'remove'])]
    #[ORM\JoinColumn(nullable: false)]
    private ?AdditionalInformation $additionalInformation = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $alert = null;

    #[ORM\Column(nullable: true)]
    private ?int $refusalReason = null;

    #[ORM\Column(type: Types::TEXT, nullable: true)]
    private ?string $refusalText = null;

    #[ORM\ManyToOne(inversedBy: 'complaints')]
    #[ORM\JoinColumn(nullable: true)]
    private ?User $assignedTo = null;

    /** @var Collection<int, Comment> */
    #[ORM\OneToMany(mappedBy: 'complaint', targetEntity: Comment::class, cascade: [
        'persist',
        'remove',
    ], orphanRemoval: true)]
    #[ORM\OrderBy(['publishedAt' => 'ASC'])]
    private Collection $comments;

    #[ORM\Column(length: 255)]
    private ?string $unitAssigned = null;

    public function __construct()
    {
        $this->objects = new ArrayCollection();
        $this->comments = new ArrayCollection();
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

    public function isOptinNotification(): ?bool
    {
        return $this->optinNotification;
    }

    public function setOptinNotification(bool $optinNotification): self
    {
        $this->optinNotification = $optinNotification;

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
     * @return Collection<int, AbstractObject>
     */
    public function getObjects(): Collection
    {
        return $this->objects;
    }

    public function addObject(AbstractObject $abstractObject): self
    {
        if (!$this->objects->contains($abstractObject)) {
            $this->objects->add($abstractObject);
            $abstractObject->setComplaint($this);
        }

        return $this;
    }

    public function removeObject(AbstractObject $abstractObject): self
    {
        if ($this->objects->removeElement($abstractObject)) {
            // set the owning side to null (unless already changed)
            if ($abstractObject->getComplaint() === $this) {
                $abstractObject->setComplaint(null);
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

    public function getRefusalReason(): ?int
    {
        return $this->refusalReason;
    }

    public function setRefusalReason(?int $refusalReason): self
    {
        $this->refusalReason = $refusalReason;

        return $this;
    }

    public function getRefusalText(): ?string
    {
        return $this->refusalText;
    }

    public function setRefusalText(?string $refusalText): self
    {
        $this->refusalText = $refusalText;

        return $this;
    }

    public function getAssignedTo(): ?User
    {
        return $this->assignedTo;
    }

    public function setAssignedTo(?User $assignedTo): self
    {
        $this->assignedTo = $assignedTo;

        return $this;
    }

    /**
     * @return Collection<int, Comment>
     */
    public function getComments(): Collection
    {
        return $this->comments;
    }

    public function addComment(Comment $comment): self
    {
        if (!$this->comments->contains($comment)) {
            $this->comments->add($comment);
            $comment->setComplaint($this);
        }

        return $this;
    }

    public function removeComment(Comment $comment): self
    {
        if ($this->comments->removeElement($comment)) {
            // set the owning side to null (unless already changed)
            if ($comment->getComplaint() === $this) {
                $comment->setComplaint(null);
            }
        }

        return $this;
    }

    public function getUnitAssigned(): ?string
    {
        return $this->unitAssigned;
    }

    public function setUnitAssigned(string $unitAssigned): self
    {
        $this->unitAssigned = $unitAssigned;

        return $this;
    }

    public function isTest(): ?bool
    {
        return $this->test;
    }

    public function setTest(?bool $test): self
    {
        $this->test = $test;

        return $this;
    }

    public function getProcessingDeadline(): ?\DateTimeImmutable
    {
        return $this->processingDeadline;
    }

    public function setProcessingDeadline(?\DateTimeImmutable $processingDeadline): self
    {
        $this->processingDeadline = $processingDeadline;

        return $this;
    }

    public function isDeadlineNotified(): ?bool
    {
        return $this->deadlineNotified;
    }

    public function setDeadlineNotified(?bool $deadlineNotified): self
    {
        $this->deadlineNotified = $deadlineNotified;

        return $this;
    }

    public function getPersonLegalRepresented(): ?Identity
    {
        return $this->personLegalRepresented;
    }

    public function setPersonLegalRepresented(?Identity $personLegalRepresented): self
    {
        $this->personLegalRepresented = $personLegalRepresented;

        return $this;
    }
}
