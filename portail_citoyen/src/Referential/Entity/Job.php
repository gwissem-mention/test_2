<?php

declare(strict_types=1);

namespace App\Referential\Entity;

use App\Referential\Repository\JobRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: JobRepository::class)]
#[ORM\Index(fields: ['labelMale'], name: 'job_label_male_idx')]
#[ORM\Index(fields: ['labelFemale'], name: 'job_label_female_idx')]
class Job
{
    #[ORM\Id]
    #[ORM\Column(type: Types::INTEGER)]
    #[ORM\GeneratedValue]
    private ?int $id = null;

    #[ORM\Column]
    private string $code;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $labelMale;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $labelFemale;

    public function __construct(string $code, ?string $labelMale = null, ?string $labelFemale = null)
    {
        $this->code = $code;
        $this->labelMale = $labelMale;
        $this->labelFemale = $labelFemale;
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getCode(): string
    {
        return $this->code;
    }

    public function getLabelMale(): ?string
    {
        return $this->labelMale;
    }

    public function getLabelFemale(): ?string
    {
        return $this->labelFemale;
    }

    public function setLabelMale(?string $labelMale): self
    {
        $this->labelMale = $labelMale;

        return $this;
    }

    public function setLabelFemale(?string $labelFemale): self
    {
        $this->labelFemale = $labelFemale;

        return $this;
    }
}
