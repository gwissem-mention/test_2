<?php

declare(strict_types=1);

namespace App\Referential\Entity;

use App\Referential\Repository\DocumentTypeRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: DocumentTypeRepository::class)]
#[ORM\Index(fields: ['label'], name: 'document_type_label_idx')]
class DocumentType
{
    #[ORM\Id]
    #[ORM\Column(type: Types::INTEGER)]
    #[ORM\GeneratedValue]
    private int $id;

    #[ORM\Column(type: Types::STRING, unique: true)]
    private string $label;

    public function __construct(string $label)
    {
        $this->label = $label;
    }

    public function getId(): int
    {
        return $this->id;
    }

    public function getLabel(): string
    {
        return $this->label;
    }

    public function setLabel(string $label): self
    {
        $this->label = $label;

        return $this;
    }
}
