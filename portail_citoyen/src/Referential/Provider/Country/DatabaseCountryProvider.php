<?php

namespace App\Referential\Provider\Country;

use App\Referential\Entity\Country;
use Doctrine\ORM\EntityManagerInterface;

class DatabaseCountryProvider implements CountryProviderInterface
{
    public function __construct(private readonly EntityManagerInterface $referentialsEntityManager)
    {
    }

    public function getChoices(): array
    {
        /** @var array<Country> $choices */
        $choices = $this->referentialsEntityManager->createQueryBuilder()
            ->select('c')
            ->from(Country::class, 'c')
            ->orderBy('c.label', 'ASC')
            ->getQuery()
            ->getResult();

        /** @var array<string, string> $choices */
        $choices = array_reduce($choices, function (array $res, Country $item) {
            $res[$item->getLabel()] = $item->getInseeCode();

            return $res;
        }, []);

        return $choices;
    }

    public function getByCode(string $code): Country
    {
        /** @var Country $countryByCode */
        $countryByCode = $this->referentialsEntityManager->getRepository(Country::class)->findOneBy(['code' => $code]);

        return $countryByCode;
    }

    public function getByInseeCode(string $inseeCode): Country
    {
        /** @var Country $countryByInseeCode */
        $countryByInseeCode = $this->referentialsEntityManager->getRepository(Country::class)->findOneBy(['inseeCode' => $inseeCode]);

        return $countryByInseeCode;
    }
}
