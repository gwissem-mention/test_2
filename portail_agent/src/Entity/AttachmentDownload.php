<?php

declare(strict_types=1);

namespace App\Entity;

use App\Repository\AttachmentDownloadRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: AttachmentDownloadRepository::class)]
class AttachmentDownload
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\ManyToOne(inversedBy: 'attachmentDownloads')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Complaint $complaint;

    #[ORM\Column]
    private ?\DateTimeImmutable $downloadedAt;

    #[ORM\Column(nullable: true)]
    private ?\DateTimeImmutable $cleanedAt = null;

    public function __construct(Complaint $complaint)
    {
        $this->complaint = $complaint;
        $this->downloadedAt = new \DateTimeImmutable('now');
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getComplaint(): ?Complaint
    {
        return $this->complaint;
    }

    public function setComplaint(?Complaint $complaint): static
    {
        $this->complaint = $complaint;

        return $this;
    }

    public function getDownloadedAt(): ?\DateTimeImmutable
    {
        return $this->downloadedAt;
    }

    public function setDownloadedAt(\DateTimeImmutable $downloadedAt): static
    {
        $this->downloadedAt = $downloadedAt;

        return $this;
    }

    public function getCleanedAt(): ?\DateTimeImmutable
    {
        return $this->cleanedAt;
    }

    public function setCleanedAt(?\DateTimeImmutable $cleanedAt): static
    {
        $this->cleanedAt = $cleanedAt;

        return $this;
    }
}
