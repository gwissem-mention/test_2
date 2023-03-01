<?php

declare(strict_types=1);

namespace App\Command\CRON;

use App\Notification\ComplaintNotification;
use App\Repository\ComplaintRepository;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

#[AsCommand(
    name: 'app:cron:deadline-notification',
    description: 'Create a notification for the supervisors and the assigned agent of a complaint, if this one is not processed before it\'s deadline.',
)]
class ComplaintDeadlineNotificationCron extends Command
{
    public function __construct(
        private readonly ManagerRegistry $doctrine,
        private readonly ComplaintRepository $complaintRepository,
        private readonly ComplaintNotification $complaintNotification
    ) {
        parent::__construct(self::getDefaultName());
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);
        $io->title('Create deadline notifications');

        $notifiableComplaints = $this->complaintRepository->getNotifiableComplaintsForProcessingDeadline();
        $notifiableComplaintsCount = $this->complaintRepository->countNotifiableComplaintsForProcessingDeadline();

        if (0 === $notifiableComplaintsCount) {
            $io->text('Nothing to do.');

            return self::SUCCESS;
        }

        $manager = $this->doctrine->getManager();

        $io->note(sprintf('Found %d complaints.', $notifiableComplaintsCount));

        $this->complaintNotification->setComplaintDeadlineExceededNotification($notifiableComplaints, $io);

        $io->success('Done!');

        $manager->flush();

        return self::SUCCESS;
    }
}
