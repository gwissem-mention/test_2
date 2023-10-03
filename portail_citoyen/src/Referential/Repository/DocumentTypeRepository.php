<?php

declare(strict_types=1);

namespace App\Referential\Repository;

use App\Referential\Entity\DocumentType;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<DocumentType>
 *
 * @method DocumentType|null find($id, $lockMode = null, $lockVersion = null)
 * @method DocumentType|null findOneBy(array $criteria, array $orderBy = null)
 * @method DocumentType[]    findAll()
 * @method DocumentType[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class DocumentTypeRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, DocumentType::class);
    }

    public function save(DocumentType $entity, bool $flush = true): void
    {
        $this->getEntityManager()->persist($entity);

        if (true === $flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(DocumentType $entity, bool $flush = true): void
    {
        $this->getEntityManager()->remove($entity);

        if (true === $flush) {
            $this->getEntityManager()->flush();
        }
    }

    /**
     * @return array<string, int>
     */
    public function getDocumentTypesChoices(): array
    {
        $choices = [];

        foreach ($this->findAll() as $documentType) {
            $choices[$documentType->getLabel()] = $documentType->getId();
        }

        return $choices;
    }
}
