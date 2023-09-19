<?php

declare(strict_types=1);

namespace App\Referential\Repository;

use App\Referential\Entity\NaturePlace;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<NaturePlace>
 *
 * @method NaturePlace|null find($id, $lockMode = null, $lockVersion = null)
 * @method NaturePlace|null findOneBy(array $criteria, array $orderBy = null)
 * @method NaturePlace[]    findAll()
 * @method NaturePlace[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class NaturePlaceRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, NaturePlace::class);
    }

    public function save(NaturePlace $entity, bool $flush = true): void
    {
        $this->getEntityManager()->persist($entity);

        if (true === $flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(NaturePlace $entity, bool $flush = true): void
    {
        $this->getEntityManager()->remove($entity);

        if (true === $flush) {
            $this->getEntityManager()->flush();
        }
    }

    /**
     * @return array<string, int>
     */
    public function getNaturePlacesChoices(int $parent = null): array
    {
        $choices = [];

        $naturePlaces = $this->findBy(['parent' => $parent]);

        foreach ($naturePlaces as $naturePlace) {
            $choices[$naturePlace->getLabel()] = (int) $naturePlace->getId();
        }

        return $choices;
    }
}
