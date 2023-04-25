<?php

declare(strict_types=1);

namespace App\Referential\Repository;

use App\Referential\Entity\CityUnit;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<CityUnit>
 *
 * @method CityUnit|null find($id, $lockMode = null, $lockVersion = null)
 * @method CityUnit|null findOneBy(array $criteria, array $orderBy = null)
 * @method CityUnit[]    findAll()
 * @method CityUnit[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class CityUnitRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, CityUnit::class);
    }

    public function save(CityUnit $entity, bool $flush = true): void
    {
        $this->getEntityManager()->persist($entity);

        if (true === $flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(CityUnit $entity, bool $flush = true): void
    {
        $this->getEntityManager()->remove($entity);

        if (true === $flush) {
            $this->getEntityManager()->flush();
        }
    }
}
