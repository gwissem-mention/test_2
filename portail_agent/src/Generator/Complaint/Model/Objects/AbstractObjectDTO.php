<?php

declare(strict_types=1);

namespace App\Generator\Complaint\Model\Objects;

use App\Entity\FactsObjects\AbstractObject;

abstract class AbstractObjectDTO
{
    // protected string $identityVictim;
    // protected ?string $identityMarriedName = null;
    // protected ?string $identityLastname = null;
    // protected ?string $identityFirstName = null;
    // protected ?string $identityBirthDate = null;
    // protected ?string $identityBirthCountry = null;
    // protected ?string $identityBirthDepartment = null;
    // protected ?string $identityBirthPostalCode = null;
    // protected ?string $identityBirthCity = null;
    // protected ?string $identityBirthInseeCode = null;
    // protected ?string $identityCountry = null;
    // protected ?string $identityDepartment = null;
    // protected ?string $identityPostalCode = null;
    // protected ?string $identityCity = null;
    // protected ?string $identityInseeCode = null;
    // protected ?string $identityStreetNumber = null;
    // protected ?string $identityStreetType = null;
    // protected ?string $identityStreetName = null;
    // protected ?string $identityBirthDepartmentNumber = null;
    // protected ?string $identityDepartmentNumber = null;
    // protected string $theftFromVehicle;

    protected function getStatusAsString(int $status): string
    {
        return sprintf('Statut : %s', AbstractObject::STATUS_STOLEN === $status ? 'volé' : 'dégradé');
    }
}
