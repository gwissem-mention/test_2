<?php

declare(strict_types=1);

namespace App\Referential\Entity;

use App\AppEnum\Institution;
use App\Referential\Repository\PersonnelRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: PersonnelRepository::class)]
#[ORM\Index(fields: ['appellation'], name: 'personnel_appellation_idx')]
class Personnel
{
    private const MATRICULE_PREFIX_GN = 3000000;

    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255, unique: true)]
    private string $personnelId;

    #[ORM\Column(length: 255)]
    private string $appellation;

    #[ORM\Column(length: 255)]
    private string $serviceCode;

    #[ORM\Column(length: 255, unique: true, nullable: true)]
    private ?string $matricule;

    public function __construct(string $personnelId, string $appellation, string $serviceCode, string $matricule = null)
    {
        $this->personnelId = $personnelId;
        $this->appellation = $appellation;
        $this->serviceCode = $serviceCode;
        $this->matricule = $matricule;
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getPersonnelId(): string
    {
        return $this->personnelId;
    }

    public function getAppellation(): string
    {
        return $this->appellation;
    }

    public function getServiceCode(): string
    {
        return $this->serviceCode;
    }

    public function getMatricule(): ?string
    {
        return $this->matricule;
    }

    public function getInstitution(): Institution
    {
        return $this->serviceCode > self::MATRICULE_PREFIX_GN ? Institution::GN : Institution::PN;
    }
}
