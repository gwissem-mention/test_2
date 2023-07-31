<?php

declare(strict_types=1);

namespace App\Command\CRON;

use App\Repository\ComplaintRepository;
use App\Salesforce\Messenger\AppointmentInformations\AppointmentInformationsMessage;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;
use Symfony\Component\Messenger\MessageBusInterface;

#[AsCommand(
    name: 'app:cron:appointment-saleforce:send',
    description: 'Create salesforce notifications for appointments',
)]
class AppointmentSalesforceSendCron extends Command
{
    private const BATCH_SIZE = 20;

    public function __construct(
        private readonly ComplaintRepository $complaintRepository,
        private readonly MessageBusInterface $bus,
        private readonly EntityManagerInterface $manager,
    ) {
        parent::__construct(self::getDefaultName());
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);
        $io->title('Create salesforce notifications for appointments');

        $complaints = $this->complaintRepository->findComplaintsForAppointmentNow();

        $row = 0;
        foreach ($complaints as $complaint) {
            ++$row;
            $this->bus->dispatch(new AppointmentInformationsMessage((int) $complaint->getId()));
            $complaint->setAppointmentSalesforceNotificationSentAt(new \DateTimeImmutable());

            if (($row % self::BATCH_SIZE) === 0) {
                $this->manager->flush();
                $this->manager->clear();
            }
        }

        $this->manager->flush();

        $io->note(sprintf('%s complaints processed', $row));

        $io->success('You have process the complaints successfully!');

        return self::SUCCESS;
    }
}
