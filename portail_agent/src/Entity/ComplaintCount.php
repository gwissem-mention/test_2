<?php

declare(strict_types=1);

namespace App\Entity;

use App\Repository\ComplaintCountRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: ComplaintCountRepository::class)]
class ComplaintCount
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255, unique: true)]
    private string $year;

    #[ORM\Column]
    private int $count;

    public function __construct()
    {
        $this->year = date('Y');
        $this->count = 0;
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getYear(): string
    {
        return $this->year;
    }

    public function setYear(string $year): self
    {
        $this->year = $year;

        return $this;
    }

    public function getCount(): int
    {
        return $this->count;
    }

    public function setCount(int $count): self
    {
        $this->count = $count;

        return $this;
    }
}
