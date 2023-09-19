<?php

declare(strict_types=1);

namespace App\Referential\Entity;

use App\Referential\Repository\NaturePlaceRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: NaturePlaceRepository::class)]
#[ORM\Index(fields: ['label'], name: 'nature_place_label_idx')]
class NaturePlace
{
    #[ORM\Id]
    #[ORM\Column(type: Types::INTEGER)]
    #[ORM\GeneratedValue]
    private ?int $id = null;

    #[ORM\Column]
    private string $label;

    #[ORM\Column(nullable: true)]
    private ?string $labelThesaurus;

    /**
     * @var Collection<int, NaturePlace>
     */
    #[ORM\OneToMany(mappedBy: 'parent', targetEntity: NaturePlace::class)]
    private Collection $children;

    #[ORM\ManyToOne(targetEntity: NaturePlace::class, inversedBy: 'children')]
    #[ORM\JoinColumn(name: 'parent_id', referencedColumnName: 'id', nullable: true)]
    private ?NaturePlace $parent;

    public function __construct(
        string $label,
        string $labelThesaurus = null,
        NaturePlace $parent = null,
    ) {
        $this->label = $label;
        $this->labelThesaurus = $labelThesaurus;
        $this->parent = $parent;
        $this->children = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getLabel(): string
    {
        return $this->label;
    }

    public function getLabelThesaurus(): ?string
    {
        return $this->labelThesaurus;
    }

    public function getParent(): ?NaturePlace
    {
        return $this->parent;
    }

    /**
     * @return Collection<int, NaturePlace>
     */
    public function getChildren(): Collection
    {
        return $this->children;
    }
}
