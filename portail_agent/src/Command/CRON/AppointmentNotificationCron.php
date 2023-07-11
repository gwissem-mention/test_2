<?php

declare(strict_types=1);

namespace App\Command\CRON;

use App\Entity\User;
use App\Factory\NotificationFactory;
use App\Repository\ComplaintRepository;
use App\Repository\UserRepository;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

#[AsCommand(
    name: 'app:cron:appointment-notification',
    description: 'Create a notification for the supervisors and the assigned agent of a complaint to reminder of closing of a PV in case of appointment in physical',
)]
class AppointmentNotificationCron extends Command
{
    public function __construct(
        private readonly ManagerRegistry $doctrine,
        private readonly ComplaintRepository $complaintRepository,
        private readonly NotificationFactory $notificationFactory,
        private readonly UserRepository $userRepository,
    ) {
        parent::__construct(self::getDefaultName());
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);
        $io->title('Create reminder notifications');

        $ComplaintsWithAppointmentADayAgo = $this->complaintRepository->getComplaintsWithAppointmentADayAgo();

        if (0 === count($ComplaintsWithAppointmentADayAgo)) {
            $io->text('Nothing to do.');

            return self::SUCCESS;
        }
        $manager = $this->doctrine->getManager();

        foreach ($ComplaintsWithAppointmentADayAgo as $complaint) {
            if ($complaint->getAssignedTo() instanceof User) {
                $this->userRepository->save(
                    $complaint->getAssignedTo()->addNotification(
                        $this->notificationFactory->createForReminderOfClosingOfAPVInCaseOfAppointmentInPhysical($complaint)
                    )
                );
                $complaint->setAppointmentNotificationSentAt(new \DateTimeImmutable());
            }
        }

        $io->success('Done!');

        $manager->flush();

        return self::SUCCESS;
    }
}
