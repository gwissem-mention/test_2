<?php

declare(strict_types=1);

namespace App\Referential\Entity;

use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity]
class Country
{
    #[ORM\Id]
    #[ORM\Column(type: Types::INTEGER)]
    #[ORM\GeneratedValue]
    private int $id;

    #[ORM\Column(type: Types::STRING)]
    private string $code;

    #[ORM\Column(type: Types::STRING)]
    private string $label;

    #[ORM\Column(type: Types::INTEGER, nullable: true)]
    private ?int $inseeCode;

    public function __construct(string $code, string $label, int $inseeCode = null)
    {
        $this->code = $code;
        $this->label = $label;
        $this->inseeCode = $inseeCode;
    }

    public function getCode(): string
    {
        return $this->code;
    }

    public function getLabel(): string
    {
        return $this->label;
    }

    public function getInseeCode(): ?int
    {
        return $this->inseeCode;
    }
}
