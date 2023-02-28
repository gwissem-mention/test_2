<?php

declare(strict_types=1);

namespace App\Entity\FactsObjects;

use App\Entity\AlertTrait;
use App\Entity\Complaint;
use App\Entity\Identity;
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
}
