<?php

declare(strict_types=1);

namespace App\Referential\Repository;

use App\Referential\Entity\RegisteredVehicleNature;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<RegisteredVehicleNature>
 *
 * @method RegisteredVehicleNature|null find($id, $lockMode = null, $lockVersion = null)
 * @method RegisteredVehicleNature|null findOneBy(array $criteria, array $orderBy = null)
 * @method RegisteredVehicleNature[]    findAll()
 * @method RegisteredVehicleNature[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class RegisteredVehicleNatureRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, RegisteredVehicleNature::class);
    }

    public function save(RegisteredVehicleNature $entity, bool $flush = true): void
    {
        $this->getEntityManager()->persist($entity);

        if (true === $flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(RegisteredVehicleNature $entity, bool $flush = true): void
    {
        $this->getEntityManager()->remove($entity);

        if (true === $flush) {
            $this->getEntityManager()->flush();
        }
    }

    /**
     * @return array<string, int>
     */
    public function getRegisteredVehicleNaturesChoices(): array
    {
        $choices = [];

        foreach ($this->findAll() as $registeredVehicleNature) {
            $choices[ucfirst($registeredVehicleNature->getLabel())] = $registeredVehicleNature->getId();
        }

        return $choices;
    }
}
