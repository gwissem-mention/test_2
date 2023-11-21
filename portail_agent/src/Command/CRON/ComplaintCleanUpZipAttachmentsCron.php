<?php

declare(strict_types=1);

namespace App\Command\CRON;

use App\Complaint\ComplaintCleaner;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

#[AsCommand(
    name: 'app:cron:complaint:clean-up:zip-attachments',
    description: 'Clean up complaint zip attachments files for download older than X period',
)]
class ComplaintCleanUpZipAttachmentsCron extends Command
{
    public function __construct(
        private readonly ComplaintCleaner $complaintCleaner
    ) {
        parent::__construct(self::getDefaultName());
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);
        $io->title('Clean up complaint zip attachments files for download older than X period');

        $nbAttachmentsCleaned = $this->complaintCleaner->zipAttachmentsCleaner();

        $io->note(sprintf('%s attachments cleaned up.', $nbAttachmentsCleaned));

        $io->success('You have clean up zip attachments successfully!');

        return Command::SUCCESS;
    }
}
