<?php

declare(strict_types=1);

namespace App\Entity;

use App\Entity\FactsObjects\AbstractObject;
use App\Repository\ComplaintRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\Common\Collections\ReadableCollection;
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
    public const STATUS_REJECTED = 'pel.rejected';
    public const STATUS_UNIT_REDIRECTION_PENDING = 'pel.unit.redirection.pending';
    public const ALERT_VIOLENCE = 'pel.alert.violence';
    public const ALERT_TSP = 'pel.alert.tsp';
    public const ALERT_REGISTERED_VEHICLE = 'pel.alert.registered.vehicle';
    public const ALERT_TOTAL_AMOUNT = 'pel.alert.total.amount';
    public const ALERT_FOREIGN_VICTIM = 'pel.alert.forein.victim';

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

    #[ORM\Column(type: Types::TEXT, nullable: true)]
    private ?string $appointmentContactInformation = null;

    #[ORM\Column(nullable: true)]
    private ?\DateTimeImmutable $appointmentDate = null;

    #[ORM\Column(nullable: true)]
    private ?\DateTimeImmutable $appointmentTime = null;

    //    #[ORM\Column]
    //    private ?string $contactWindow = null;
    //
    //    #[ORM\Column]
    //    private ?string $contactPeriod = null;

    #[ORM\Column(length: 255)]
    private ?string $status = null;

    #[ORM\Column(length: 255, unique: true)]
    private string $declarationNumber;

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

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $refusalReason = null;

    #[ORM\Column(length: 3000, nullable: true)]
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

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $unitToReassign = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $unitReassignText = null;

    #[ORM\Column]
    private ?bool $unitReassignmentAsked = false;

    #[ORM\OneToOne(cascade: ['persist', 'remove'])]
    #[ORM\JoinColumn(nullable: true)]
    private ?Corporation $corporationRepresented = null;

    #[ORM\Column(nullable: true)]
    private ?int $priority = null;

    #[ORM\Column(length: 255, nullable: true)]
    private string $frontId = '';

    #[ORM\Column]
    private bool $franceConnected = false;

    #[ORM\Column(nullable: true)]
    private ?string $oodriveFolder = null;

    #[ORM\Column(nullable: true)]
    private ?string $oodriveReportFolder = null;

    #[ORM\Column(nullable: true)]
    private ?\DateTimeImmutable $oodriveCleanedUpDeclarationAt = null;

    #[ORM\Column(nullable: true)]
    private ?\DateTimeImmutable $oodriveCleanedUpAttachmentsAt = null;

    #[ORM\Column(nullable: true)]
    private ?\DateTimeImmutable $oodriveCleanedUpReportAt = null;

    #[ORM\Column(nullable: true)]
    private ?\DateTimeImmutable $rejectedAt = null;

    #[ORM\Column(nullable: true)]
    private ?\DateTimeImmutable $closedAt = null;

    #[ORM\Column(nullable: true)]
    private ?\DateTimeImmutable $appointmentNotificationSentAt = null;

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

    public function setAppointmentDate(?\DateTimeImmutable $appointmentDate): self
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

    public function getDeclarationNumber(): string
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

    /**
     * @return ReadableCollection<int, AbstractObject>
     */
    public function getStolenObjects(): ReadableCollection
    {
        return $this->objects->filter(function (AbstractObject $object) {
            return AbstractObject::STATUS_STOLEN === $object->getStatus();
        });
    }

    /**
     * @return ReadableCollection<int, AbstractObject>
     */
    public function getDegradedObjects(): ReadableCollection
    {
        return $this->objects->filter(function (AbstractObject $object) {
            return AbstractObject::STATUS_DEGRADED === $object->getStatus();
        });
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

    public function getRefusalReason(): ?string
    {
        return $this->refusalReason;
    }

    public function setRefusalReason(?string $refusalReason): self
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

    public function getUnitToReassign(): ?string
    {
        return $this->unitToReassign;
    }

    public function setUnitToReassign(?string $unitToReassign): self
    {
        $this->unitToReassign = $unitToReassign;

        return $this;
    }

    public function getUnitReassignText(): ?string
    {
        return $this->unitReassignText;
    }

    public function setUnitReassignText(?string $unitReassignText): self
    {
        $this->unitReassignText = $unitReassignText;

        return $this;
    }

    public function getCorporationRepresented(): ?Corporation
    {
        return $this->corporationRepresented;
    }

    public function setCorporationRepresented(?Corporation $corporationRepresented): self
    {
        $this->corporationRepresented = $corporationRepresented;

        return $this;
    }

    public function getPriority(): ?int
    {
        return $this->priority;
    }

    public function setPriority(?int $priority): self
    {
        $this->priority = $priority;

        return $this;
    }

    public function getAppointmentContactInformation(): ?string
    {
        return $this->appointmentContactInformation;
    }

    public function setAppointmentContactInformation(?string $appointmentContactInformation): self
    {
        $this->appointmentContactInformation = $appointmentContactInformation;

        return $this;
    }

    public function getAppointmentTime(): ?\DateTimeImmutable
    {
        return $this->appointmentTime;
    }

    public function setAppointmentTime(?\DateTimeImmutable $appointmentTime): self
    {
        $this->appointmentTime = $appointmentTime;

        return $this;
    }

    public function setFrontId(string $frontId): self
    {
        $this->frontId = $frontId;

        return $this;
    }

    public function getFrontId(): string
    {
        return $this->frontId;
    }

    public function isFranceConnected(): bool
    {
        return $this->franceConnected;
    }

    public function setFranceConnected(bool $franceConnected): self
    {
        $this->franceConnected = $franceConnected;

        return $this;
    }

    public function isUnitReassignmentAsked(): ?bool
    {
        return $this->unitReassignmentAsked;
    }

    public function setUnitReassignmentAsked(bool $unitReassignmentAsked): self
    {
        $this->unitReassignmentAsked = $unitReassignmentAsked;

        return $this;
    }

    public function getOodriveFolder(): ?string
    {
        return $this->oodriveFolder;
    }

    public function setOodriveFolder(?string $oodriveFolder): self
    {
        $this->oodriveFolder = $oodriveFolder;

        return $this;
    }

    public function getOodriveCleanedUpDeclarationAt(): ?\DateTimeImmutable
    {
        return $this->oodriveCleanedUpDeclarationAt;
    }

    public function setOodriveCleanedUpDeclarationAt(?\DateTimeImmutable $oodriveCleanedUpDeclarationAt): self
    {
        $this->oodriveCleanedUpDeclarationAt = $oodriveCleanedUpDeclarationAt;

        return $this;
    }

    public function getOodriveCleanedUpReportAt(): ?\DateTimeImmutable
    {
        return $this->oodriveCleanedUpReportAt;
    }

    public function setOodriveCleanedUpReportAt(?\DateTimeImmutable $oodriveCleanedUpReportAt): self
    {
        $this->oodriveCleanedUpReportAt = $oodriveCleanedUpReportAt;

        return $this;
    }

    public function getClosedAt(): ?\DateTimeImmutable
    {
        return $this->closedAt;
    }

    public function setClosedAt(?\DateTimeImmutable $closedAt): self
    {
        $this->closedAt = $closedAt;

        return $this;
    }

    public function getRejectedAt(): ?\DateTimeImmutable
    {
        return $this->rejectedAt;
    }

    public function setRejectedAt(?\DateTimeImmutable $rejectedAt): self
    {
        $this->rejectedAt = $rejectedAt;

        return $this;
    }

    public function getOodriveCleanedUpAttachmentsAt(): ?\DateTimeImmutable
    {
        return $this->oodriveCleanedUpAttachmentsAt;
    }

    public function setOodriveCleanedUpAttachmentsAt(?\DateTimeImmutable $oodriveCleanedUpAttachmentsAt): self
    {
        $this->oodriveCleanedUpAttachmentsAt = $oodriveCleanedUpAttachmentsAt;

        return $this;
    }

    public function getOodriveReportFolder(): ?string
    {
        return $this->oodriveReportFolder;
    }

    public function setOodriveReportFolder(?string $oodriveReportFolder): self
    {
        $this->oodriveReportFolder = $oodriveReportFolder;

        return $this;
    }

    public function getAppointmentNotificationSentAt(): ?\DateTimeImmutable
    {
        return $this->appointmentNotificationSentAt;
    }

    public function setAppointmentNotificationSentAt(?\DateTimeImmutable $appointmentNotificationSentAt): self
    {
        $this->appointmentNotificationSentAt = $appointmentNotificationSentAt;

        return $this;
    }
}
