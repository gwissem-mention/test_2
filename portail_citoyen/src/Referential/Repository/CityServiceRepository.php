<?php

declare(strict_types=1);

namespace App\Referential\Repository;

use App\Referential\Entity\CityService;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<CityService>
 *
 * @method CityService|null find($id, $lockMode = null, $lockVersion = null)
 * @method CityService|null findOneBy(array $criteria, array $orderBy = null)
 * @method CityService[]    findAll()
 * @method CityService[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class CityServiceRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, CityService::class);
    }

    public function save(CityService $entity, bool $flush = true): void
    {
        $this->getEntityManager()->persist($entity);

        if (true === $flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(CityService $entity, bool $flush = true): void
    {
        $this->getEntityManager()->remove($entity);

        if (true === $flush) {
            $this->getEntityManager()->flush();
        }
    }
}
