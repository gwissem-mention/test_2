<?php

declare(strict_types=1);

namespace App\Referential\Provider\Nationality;

use App\Referential\Entity\Nationality;
use Doctrine\ORM\EntityManagerInterface;

class DatabaseNationalityProvider implements NationalityProviderInterface
{
    public function __construct(private readonly EntityManagerInterface $referentialsEntityManager)
    {
    }

    public function getChoices(): array
    {
        /** @var array<Nationality> $choices */
        $choices = $this->referentialsEntityManager->createQueryBuilder()
            ->select('n')
            ->from(Nationality::class, 'n')
            ->orderBy('n.label', 'ASC')
            ->getQuery()
            ->getResult();

        /** @var array<string, string> $choices */
        $choices = array_reduce($choices, function (array $res, Nationality $item) {
            $res[$item->getLabel()] = $item->getCode();

            return $res;
        }, []);

        return $choices;
    }

    public function getByCode(string $code): Nationality
    {
        /** @var Nationality $nationality */
        $nationality = $this->referentialsEntityManager->getRepository(Nationality::class)->findOneBy(['code' => $code]);

        return $nationality;
    }
}
