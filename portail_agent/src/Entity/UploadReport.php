<?php

declare(strict_types=1);

namespace App\Entity;

use App\Repository\UploadReportRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: UploadReportRepository::class)]
class UploadReport
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private string $oodriveId;

    #[ORM\Column]
    private int $timestamp;

    #[ORM\Column]
    private int $size;

    #[ORM\Column]
    private string $type;

    #[ORM\Column]
    private string $originName;

    #[ORM\ManyToOne(inversedBy: 'uploadReports')]
    #[ORM\JoinColumn(nullable: false)]
    private Complaint $complaint;

    public function __construct(string $oodriveId, int $timestamp, int $size, string $type, string $originName, Complaint $complaint)
    {
        $this->oodriveId = $oodriveId;
        $this->timestamp = $timestamp;
        $this->size = $size;
        $this->type = $type;
        $this->originName = $originName;
        $complaint->addUploadReport($this);
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getOodriveId(): string
    {
        return $this->oodriveId;
    }

    public function getTimestamp(): int
    {
        return $this->timestamp;
    }

    public function getSize(): int
    {
        return $this->size;
    }

    public function getType(): string
    {
        return $this->type;
    }

    public function getOriginName(): string
    {
        return $this->originName;
    }

    public function setComplaint(Complaint $complaint): UploadReport
    {
        $this->complaint = $complaint;

        return $this;
    }

    public function getComplaint(): Complaint
    {
        return $this->complaint;
    }
}
