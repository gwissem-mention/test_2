<?php

namespace App\Notification;

use App\Entity\Complaint;
use App\Entity\Facts;
use App\Entity\FactsObjects\Vehicle;
use App\Entity\User;
use App\Factory\NotificationFactory;
use App\Repository\UserRepository;
use Symfony\Component\Console\Style\SymfonyStyle;

class ComplaintNotification
{
    public function __construct(
        private readonly UserRepository $userRepository,
        private readonly NotificationFactory $notificationFactory
    ) {
    }

    public function setSupervisorNotifications(Complaint $complaint): void
    {
        /** @var string $unit */
        $unit = $complaint->getUnitAssigned();
        $supervisors = $this->userRepository->getSupervisorsByUnit($unit);

        $this->setNotificationForViolence($complaint, $supervisors);
        $this->setNotificationForRobberyAndDegradation($complaint, $supervisors);
        $this->setNotificationForStolenRegisteredVehicle($complaint, $supervisors);
    }

    /**
     * @param Complaint[] $complaints
     */
    public function setComplaintDeadlineExceededNotification(array $complaints, SymfonyStyle $io = null): void
    {
        foreach ($complaints as $complaint) {
            /** @var string $unit */
            $unit = $complaint->getUnitAssigned();
            $supervisors = $this->userRepository->getSupervisorsByUnit($unit);

            foreach ($supervisors as $supervisor) {
                $this->userRepository->save(
                    $supervisor->addNotification($this->notificationFactory->createForComplaintWithDeadlineExeeded($complaint)), true
                );
                if (!is_null($io)) {
                    $io->comment(sprintf('Complaint "%s", supervisor "%s (%s)" has been notified.',
                        $complaint->getDeclarationNumber(),
                        $supervisor->getAppellation(),
                        $supervisor->getNumber()
                    ));
                }
            }

            if ($agent = $complaint->getAssignedTo()) {
                $this->userRepository->save(
                    $agent->addNotification($this->notificationFactory->createForComplaintWithDeadlineExeeded($complaint)), true
                );
                if (!is_null($io)) {
                    $io->comment(sprintf('Complaint "%s", agent "%s (%s)" has been notified.',
                        $complaint->getDeclarationNumber(),
                        $agent->getAppellation(),
                        $agent->getNumber()
                    ));
                }
            }
            $complaint->setDeadlineNotified(true);
        }
    }

    /**
     * @param User[] $supervisors
     */
    private function setNotificationForViolence(Complaint $complaint, array $supervisors): void
    {
        if ($complaint->getAdditionalInformation()?->isVictimOfViolence()) {
            foreach ($supervisors as $supervisor) {
                $this->userRepository->save(
                    $supervisor->addNotification($this->notificationFactory->createForComplaintWithViolence($complaint)), true
                );
            }
        }
    }

    /**
     * @param User[] $supervisors
     */
    private function setNotificationForRobberyAndDegradation(Complaint $complaint, array $supervisors): void
    {
        /** @var array<int> $natures */
        $natures = $complaint->getFacts()?->getNatures();
        if (in_array(Facts::NATURE_ROBBERY, $natures) && in_array(Facts::NATURE_DEGRADATION, $natures)) {
            foreach ($supervisors as $supervisor) {
                $this->userRepository->save(
                    $supervisor->addNotification($this->notificationFactory->createForComplaintWithRobberyAndDegradation($complaint)), true
                );
            }
        }
    }

    /**
     * @param User[] $supervisors
     */
    private function setNotificationForStolenRegisteredVehicle(Complaint $complaint, array $supervisors): void
    {
        $objects = $complaint->getObjects();
        foreach ($objects as $object) {
            if ($object instanceof Vehicle && $object->isRegistered()) {
                foreach ($supervisors as $supervisor) {
                    $this->userRepository->save(
                        $supervisor->addNotification($this->notificationFactory->createForComplaintWithStolenRegisteredVehicle($complaint)), true
                    );
                }
            }
        }
    }
}
