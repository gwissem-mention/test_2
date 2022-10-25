<?php

declare(strict_types=1);

namespace App\Form\Factory;

use App\Enum\Civility;
use App\Form\Model\Identity\CivilStateModel;
use App\Form\Model\Identity\ContactInformationModel;
use App\Form\Model\Identity\IdentityModel;
use App\Form\Model\LocationModel;
use App\Thesaurus\TownAndDepartmentThesaurusProviderInterface;
use Symfony\Component\Form\DataTransformerInterface;

class IdentityModelFactory
{
    public function __construct(
        private readonly TownAndDepartmentThesaurusProviderInterface $townAndDepartmentThesaurusProvider,
        private readonly DataTransformerInterface $townToTownAndDepartmentTransformer,
    ) {
    }

    public function createFromFranceConnect(
        string $givenName,
        string $familyName,
        string $birthDate,
        string $gender,
        string $birthPlace,
        string $birthCountry,
        string $email,
        ?string $usageName = null
    ): IdentityModel {
        $identity = new IdentityModel();
        $civilState = new CivilStateModel();
        $birthLocation = new LocationModel();
        $birthLocation->setCountry($birthCountry);

        if (!empty($birthPlace)) {
            $towns = $this->townAndDepartmentThesaurusProvider->getChoices();

            /** @var string|false $townKey */
            $townKey = array_search(
                $birthPlace,
                array_combine(array_keys($towns), array_column($towns, 'code_insee')),
                true
            );

            if (is_string($townKey)) {
                /** @var array<string, mixed> $town */
                $town = $towns[$townKey];

                /** @var string $townTransformed */
                $townTransformed = $this->townToTownAndDepartmentTransformer->transform(
                    [$townKey, $town['pel.department']]
                );

                $birthLocation->setFrenchTown($townTransformed);
            }
        }

        $civilState
            ->setCivility('male' === $gender ? Civility::M->value : Civility::Mme->value)
            ->setBirthName($familyName)
            ->setFirstnames($givenName)
            ->setUsageName($usageName)
            ->setBirthDate(new \DateTimeImmutable($birthDate))
            ->setBirthLocation($birthLocation);

        $contactInformation = new ContactInformationModel();
        $contactInformation->setEmail($email);

        return $identity->setCivilState($civilState)->setContactInformation($contactInformation);
    }
}
