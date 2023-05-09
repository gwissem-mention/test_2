<?php

declare(strict_types=1);

namespace App\Entity\FactsObjects;

use App\Entity\AlertTrait;
use App\Entity\Complaint;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\ORM\Mapping\DiscriminatorColumn;
use Doctrine\ORM\Mapping\DiscriminatorMap;
use Doctrine\ORM\Mapping\Entity;
use Doctrine\ORM\Mapping\InheritanceType;
use Doctrine\ORM\Mapping\Table;

#[Entity]
#[Table(name: 'object')]
#[InheritanceType('JOINED')]
#[DiscriminatorColumn(name: 'discr', type: 'string')]
#[DiscriminatorMap([
    'administrative_document' => AdministrativeDocument::class,
    'multimedia_object' => MultimediaObject::class,
    'payment_method' => PaymentMethod::class,
    'simple_object' => SimpleObject::class,
    'vehicle' => Vehicle::class,
])]
abstract class AbstractObject
{
    use AlertTrait;

    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(nullable: true)]
    private ?float $amount = null;
    //
    // #[ORM\Column]
    // private ?bool $belongsToTheVictim = null;
    //
    // #[ORM\OneToOne(cascade: ['persist', 'remove'])]
    // private ?Identity $victimIdentity = null;

    #[ORM\ManyToOne(inversedBy: 'objects')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Complaint $complaint = null;

    // #[ORM\Column]
    // private ?bool $thiefFromVehicle = null;

    /** @var array<string>|null */
    #[ORM\Column(type: Types::SIMPLE_ARRAY, nullable: true)]
    private ?array $files = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getAmount(): ?float
    {
        return $this->amount;
    }

    public function setAmount(?float $amount): self
    {
        $this->amount = $amount;

        return $this;
    }

    public function getComplaint(): ?Complaint
    {
        return $this->complaint;
    }

    public function setComplaint(?Complaint $complaint): self
    {
        $this->complaint = $complaint;

        return $this;
    }

    /**
     * @return array<string>|null
     */
    public function getFiles(): ?array
    {
        return $this->files;
    }

    /**
     * @param array<string>|null $files
     */
    public function setFiles(?array $files): self
    {
        $this->files = $files;

        return $this;
    }

    public function addFile(string $file): self
    {
        $this->files[] = $file;

        return $this;
    }
}
