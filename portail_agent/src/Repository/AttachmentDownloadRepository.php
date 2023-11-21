<?php

declare(strict_types=1);

namespace App\Repository;

use App\Entity\AttachmentDownload;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<AttachmentDownload>
 *
 * @method AttachmentDownload|null find($id, $lockMode = null, $lockVersion = null)
 * @method AttachmentDownload|null findOneBy(array $criteria, array $orderBy = null)
 * @method AttachmentDownload[]    findAll()
 * @method AttachmentDownload[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class AttachmentDownloadRepository extends ServiceEntityRepository
{
    public function __construct(
        ManagerRegistry $registry,
        private readonly string $complaintAttachmentsCleanUpPeriod
    ) {
        parent::__construct($registry, AttachmentDownload::class);
    }

    public function save(AttachmentDownload $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if (true === $flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(AttachmentDownload $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if (true === $flush) {
            $this->getEntityManager()->flush();
        }
    }

    /**
     * @return array<AttachmentDownload>
     */
    public function findAttachmentsToClean(): array
    {
        $date = new \DateTimeImmutable("-$this->complaintAttachmentsCleanUpPeriod");

        $qb = $this->createQueryBuilder('c')
            ->andWhere('c.downloadedAt < :date')
            ->andWhere('c.cleanedAt IS NULL')
            ->setParameter('date', $date);

        /** @var array<AttachmentDownload> $attachmentDownloads */
        $attachmentDownloads = $qb->getQuery()->getResult();

        return $attachmentDownloads;
    }
}
