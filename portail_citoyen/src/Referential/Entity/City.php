<?php

namespace App\Referential\Entity;

use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity]
#[ORM\Index(fields: ['label'], name: 'label_idx')]
#[ORM\Index(fields: ['postCode'], name: 'postCode_idx')]
class City
{
    #[ORM\Id]
    #[ORM\Column(type: Types::INTEGER)]
    #[ORM\GeneratedValue]
    private int $id;

    #[ORM\Column(type: Types::STRING)]
    private string $label;

    #[ORM\Column(type: Types::STRING)]
    private string $department;

    #[ORM\Column(type: Types::STRING)]
    private string $postCode;

    #[ORM\Column(type: Types::STRING)]
    private string $inseeCode;

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

    public function getDepartment(): string
    {
        return $this->department;
    }

    public function setDepartment(string $department): self
    {
        $this->department = $department;

        return $this;
    }

    public function getPostCode(): string
    {
        return $this->postCode;
    }

    public function setPostCode(string $postCode): self
    {
        $this->postCode = $postCode;

        return $this;
    }

    public function getInseeCode(): string
    {
        return $this->inseeCode;
    }

    public function setInseeCode(string $inseeCode): self
    {
        $this->inseeCode = $inseeCode;

        return $this;
    }

    public function getLabelAndPostCode(): string
    {
        return $this->label.' ('.$this->postCode.')';
    }

    public function __toString(): string
    {
        return $this->getLabelAndPostCode();
    }
}
