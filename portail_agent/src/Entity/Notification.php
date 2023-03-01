<?php

declare(strict_types=1);

namespace App\Entity;

use App\Repository\NotificationRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: NotificationRepository::class)]
class Notification
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column]
    private \DateTimeImmutable $createdAt;

    #[ORM\Column(nullable: true)]
    private ?\DateTimeImmutable $clickedAt = null;

    #[ORM\Column(length: 255)]
    private string $title;

    #[ORM\Column(length: 255)]
    private string $redirectionUrl;

    #[ORM\ManyToOne(inversedBy: 'notifications')]
    #[ORM\JoinColumn(nullable: false)]
    private ?User $user = null;

    #[ORM\Column]
    private ?bool $urgent = null;

    public function __construct(string $title, string $redirectionUrl, bool $urgent = false)
    {
        $this->createdAt = new \DateTimeImmutable();
        $this->title = $title;
        $this->redirectionUrl = $redirectionUrl;
        $this->urgent = $urgent;
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getCreatedAt(): \DateTimeImmutable
    {
        return $this->createdAt;
    }

    public function setCreatedAt(\DateTimeImmutable $createdAt): self
    {
        $this->createdAt = $createdAt;

        return $this;
    }

    public function getClickedAt(): ?\DateTimeImmutable
    {
        return $this->clickedAt;
    }

    public function setClickedAt(?\DateTimeImmutable $clickedAt): self
    {
        $this->clickedAt = $clickedAt;

        return $this;
    }

    public function isClicked(): bool
    {
        return !is_null($this->clickedAt);
    }

    public function getTitle(): string
    {
        return $this->title;
    }

    public function setTitle(string $title): self
    {
        $this->title = $title;

        return $this;
    }

    public function getRedirectionUrl(): string
    {
        return $this->redirectionUrl;
    }

    public function setRedirectionUrl(string $redirectionUrl): self
    {
        $this->redirectionUrl = $redirectionUrl;

        return $this;
    }

    public function getUser(): ?User
    {
        return $this->user;
    }

    public function setUser(?User $user): self
    {
        $this->user = $user;

        return $this;
    }

    public function isUrgent(): ?bool
    {
        return $this->urgent;
    }

    public function setUrgent(bool $urgent): self
    {
        $this->urgent = $urgent;

        return $this;
    }
}
