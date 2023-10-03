<?php

declare(strict_types=1);

namespace App\Referential\Repository;

use App\Referential\Entity\PaymentCategory;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<PaymentCategory>
 *
 * @method PaymentCategory|null find($id, $lockMode = null, $lockVersion = null)
 * @method PaymentCategory|null findOneBy(array $criteria, array $orderBy = null)
 * @method PaymentCategory[]    findAll()
 * @method PaymentCategory[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class PaymentCategoryRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, PaymentCategory::class);
    }

    public function save(PaymentCategory $entity, bool $flush = true): void
    {
        $this->getEntityManager()->persist($entity);

        if (true === $flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(PaymentCategory $entity, bool $flush = true): void
    {
        $this->getEntityManager()->remove($entity);

        if (true === $flush) {
            $this->getEntityManager()->flush();
        }
    }

    /**
     * @return array<string, string>
     */
    public function getPaymentCategoriesChoices(): array
    {
        $choices = [];

        foreach ($this->findAll() as $paymentCategory) {
            $choices[$paymentCategory->getLabel()] = $paymentCategory->getCode();
        }

        return $choices;
    }
}
