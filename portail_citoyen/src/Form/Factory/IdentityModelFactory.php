<?php

declare(strict_types=1);

namespace App\Form\Factory;

use App\AppEnum\Civility;
use App\Form\Model\Identity\CivilStateModel;
use App\Form\Model\Identity\ContactInformationModel;
use App\Form\Model\Identity\IdentityModel;
use App\Form\Model\LocationModel;

class IdentityModelFactory
{
    public function createFromFranceConnect(
        string $givenName,
        string $familyName,
        \DateTimeImmutable $birthDate,
        string $gender,
        string $birthPlace,
        string $birthCountry,
        string $email,
        string $usageName = null
    ): IdentityModel {
        $identity = new IdentityModel();
        $civilState = new CivilStateModel();
        $birthLocation = new LocationModel();
        $birthLocation
            ->setCountry(intval($birthCountry))
            ->setFrenchTown($birthPlace);

        $civilState
            ->setCivility('male' === $gender ? Civility::M->value : Civility::Mme->value)
            ->setBirthName($familyName)
            ->setFirstnames($givenName)
            ->setUsageName($usageName)
            ->setBirthDate($birthDate)
            ->setBirthLocation($birthLocation);

        $contactInformation = new ContactInformationModel();
        $contactInformation->setEmail($email);

        return $identity->setCivilState($civilState)->setContactInformation($contactInformation);
    }
}
