<?php

namespace App\Entity\Listener;

use App\Entity\Complaint;
use App\Entity\ComplaintCount;
use App\Generator\ComplaintNumber\ComplaintNumberGeneratorInterface;
use App\Repository\ComplaintCountRepository;
use Doctrine\DBAL\LockMode;
use Doctrine\ORM\EntityManagerInterface;

class ComplaintEntityListener
{
    public function __construct(private readonly EntityManagerInterface $entityManager, private readonly ComplaintCountRepository $complaintCountRepository, private readonly ComplaintNumberGeneratorInterface $complaintNumberGenerator)
    {
    }

    public function prePersist(Complaint $complaint): void
    {
        $year = date('Y');
        $complaintCount = $this->complaintCountRepository->findOneBy(['year' => $year]);

        if (!$complaintCount instanceof ComplaintCount) {
            $complaintCount = $this->createCounterForCurrentYear();
        }

        if ($complaintCount instanceof ComplaintCount) {
            $this->entityManager->beginTransaction();
            $this->complaintCountRepository->find($complaintCount->getId(), LockMode::PESSIMISTIC_WRITE);
            $complaintCount->setCount($complaintCount->getCount() + 1);
            $this->complaintCountRepository->save($complaintCount, true);
            $this->entityManager->commit();

            /** @var string $declarationNumber */
            $declarationNumber = $this->complaintNumberGenerator->generate($complaintCount->getCount());
            $complaint->setDeclarationNumber($declarationNumber);
        }
    }

    private function createCounterForCurrentYear(): ?ComplaintCount
    {
        $this->complaintCountRepository->save(new ComplaintCount(), true);

        return $this->complaintCountRepository->findOneBy(['year' => date('Y')]);
    }
}
