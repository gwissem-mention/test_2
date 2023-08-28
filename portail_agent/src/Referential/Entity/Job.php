<?php

declare(strict_types=1);

namespace App\Referential\Entity;

use App\Referential\Repository\JobRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: JobRepository::class)]
#[ORM\Index(fields: ['label'], name: 'job_label_idx')]
class Job
{
    #[ORM\Id]
    #[ORM\Column(type: Types::INTEGER)]
    #[ORM\GeneratedValue]
    private ?int $id = null;

    #[ORM\Column]
    private string $code;

    #[ORM\Column(length: 255)]
    private ?string $label;

    #[ORM\Column(length: 255)]
    private ?string $labelThesaurus;

    public function __construct(string $code, string $label, string $labelThesaurus)
    {
        $this->code = $code;
        $this->label = $label;
        $this->labelThesaurus = $labelThesaurus;
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getCode(): string
    {
        return $this->code;
    }

    public function getLabel(): ?string
    {
        return $this->label;
    }

    public function setLabel(?string $label): self
    {
        $this->label = $label;

        return $this;
    }

    public function getLabelThesaurus(): ?string
    {
        return $this->labelThesaurus;
    }

    public function setLabelThesaurus(?string $labelThesaurus): self
    {
        $this->labelThesaurus = $labelThesaurus;

        return $this;
    }
}