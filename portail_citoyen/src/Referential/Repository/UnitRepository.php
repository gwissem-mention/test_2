<?php

declare(strict_types=1);

namespace App\Referential\Repository;

use App\AppEnum\Institution;
use App\Referential\Entity\CityUnit;
use App\Referential\Entity\Unit;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\Query;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Unit>
 *
 * @method Unit|null find($id, $lockMode = null, $lockVersion = null)
 * @method Unit|null findOneBy(array $criteria, array $orderBy = null)
 * @method Unit[]    findAll()
 * @method Unit[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class UnitRepository extends ServiceEntityRepository
{
    private const EARTH_RADIUS = 6371000;
    private const RADIUS = 75000;

    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Unit::class);
    }

    public function save(Unit $entity, bool $flush = true): void
    {
        $this->getEntityManager()->persist($entity);

        if (true === $flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(Unit $entity, bool $flush = true): void
    {
        $this->getEntityManager()->remove($entity);

        if (true === $flush) {
            $this->getEntityManager()->flush();
        }
    }

    /**
     * @return array<Unit>
     */
    public function findForMap(float $lat, float $lng, string $inseeCode, int $limit, int $radius = self::RADIUS): array
    {
        $deltaDeg = ($radius / self::EARTH_RADIUS) / M_PI * 180;
        $latMin = $lat - $deltaDeg;
        $latMax = $lat + $deltaDeg;
        $lngMin = $lng - $deltaDeg;
        $lngMax = $lng + $deltaDeg;

        /** @var array<Unit> $units */
        $units = $this->createQueryBuilder('u')
            ->addSelect(
                '( 3959 * acos(cos(radians(:lat))'.
                '* cos( radians( CAST(u.latitude as decimal)) )'.
                '* cos( radians( CAST(u.longitude as decimal) )'.
                '- radians(:lng) )'.
                '+ sin( radians(:lat) )'.
                '* sin( radians( CAST(u.latitude as decimal) ) ) ) ) as HIDDEN distance'
            )
            ->leftJoin(CityUnit::class, 'cu', Query\Expr\Join::WITH, 'u.code = cu.unitCode and cu.cityCode = :inseeCode')
            ->where('CAST(u.latitude as decimal) BETWEEN :latMin AND :latMax')
            ->andWhere('CAST(u.longitude as decimal) BETWEEN :lngMin AND :lngMax')
            ->andWhere('u.institutionCode = :institution_pn OR u.openingHours NOT IN (:opening_hours_empty)')
            ->orderBy('cu.cityCode', 'ASC')
            ->addOrderBy('distance', 'ASC')
            ->setMaxResults($limit)
            ->setParameter('lat', $lat)
            ->setParameter('lng', $lng)
            ->setParameter('latMin', $latMin)
            ->setParameter('latMax', $latMax)
            ->setParameter('lngMin', $lngMin)
            ->setParameter('lngMax', $lngMax)
            ->setParameter('inseeCode', $inseeCode)
            ->setParameter('institution_pn', Institution::PN->value)
            ->setParameter('opening_hours_empty', ["Pas d''accueil du public", "Pas d'accueil du public", 'Non renseigné dans Pulsar', ''])
            ->getQuery()
            ->getResult();

        return $units;
    }
}
