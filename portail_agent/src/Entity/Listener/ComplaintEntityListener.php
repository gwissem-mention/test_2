<?php

namespace App\Entity\Listener;

use App\Entity\Complaint;
use App\Entity\ComplaintCount;
use App\Generator\ComplaintNumber\ComplaintNumberGeneratorInterface;
use App\Notification\ComplaintNotification;
use App\Repository\ComplaintCountRepository;
use App\Salesforce\Messenger\ComplaintWarmup\ComplaintWarmupMessage;
use Doctrine\DBAL\LockMode;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Messenger\MessageBusInterface;

class ComplaintEntityListener
{
    public function __construct(
        private readonly EntityManagerInterface $entityManager,
        private readonly ComplaintCountRepository $complaintCountRepository,
        private readonly ComplaintNumberGeneratorInterface $complaintNumberGenerator,
        private readonly ComplaintNotification $complaintNotification,
        private readonly int $complaintProcessingDeadline,
        private readonly MessageBusInterface $bus
    ) {
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

        $complaint->setProcessingDeadline($complaint->getCreatedAt()?->add(new \DateInterval('P'.$this->complaintProcessingDeadline.'D')));
        $this->prioritizeComplaint($complaint);
    }

    public function postPersist(Complaint $complaint): void
    {
        $this->complaintNotification->setSupervisorNotifications($complaint);
        $this->entityManager->flush();

        $this->bus->dispatch(new ComplaintWarmupMessage((int) $complaint->getId())); // Salesforce email
    }

    private function createCounterForCurrentYear(): ?ComplaintCount
    {
        $this->complaintCountRepository->save(new ComplaintCount(), true);

        return $this->complaintCountRepository->findOneBy(['year' => date('Y')]);
    }

    private function prioritizeComplaint(Complaint $complaint): void
    {
        switch ($complaint->getAlert()) {
            case Complaint::ALERT_VIOLENCE:
                $complaint->setPriority(1);
                break;
            case Complaint::ALERT_TSP:
                $complaint->setPriority(2);
                break;
            case Complaint::ALERT_REGISTERED_VEHICLE:
                $complaint->setPriority(3);
                break;
            case Complaint::ALERT_TOTAL_AMOUNT:
                $complaint->setPriority(4);
                break;
            case Complaint::ALERT_FOREIGN_VICTIM:
                $complaint->setPriority(5);
                break;
        }
    }
}
