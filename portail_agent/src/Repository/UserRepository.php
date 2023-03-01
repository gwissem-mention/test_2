<?php

declare(strict_types=1);

namespace App\Repository;

use App\Entity\User;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<User>
 *
 * @method User|null find($id, $lockMode = null, $lockVersion = null)
 * @method User|null findOneBy(array $criteria, array $orderBy = null)
 * @method User[]    findAll()
 * @method User[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 * @method User|null findOneByIdentifier($identifier)
 */
class UserRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, User::class);
    }

    public function save(User $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if (true === $flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(User $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if (true === $flush) {
            $this->getEntityManager()->flush();
        }
    }

    /**
     * @return array<int, User>
     */
    public function getSupervisorsByUnit(string $unit): array
    {
        $users = $this->createQueryBuilder('u')
            ->andWhere('u.serviceCode = :unit')
            ->setParameter('unit', $unit)
            ->getQuery()
            ->getResult()
        ;

        $result = [];
        /** @var User[] $users */
        foreach ($users as $user) {
            if (in_array('ROLE_SUPERVISOR', $user->getRoles())) {
                $result[] = $user;
            }
        }

        return $result;
    }
}
