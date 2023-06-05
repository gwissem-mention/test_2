<?php

declare(strict_types=1);

namespace App\Command\CRON;

use App\Complaint\ComplaintFetcher;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

#[AsCommand(
    name: 'app:cron:complaint:fetch',
    description: 'Fetch Complaints from Oodrive',
)]
class ComplaintFetchCron extends Command
{
    public function __construct(private readonly ComplaintFetcher $complaintFetcher)
    {
        parent::__construct(self::getDefaultName());
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);
        $io->title('Fetch complaints');

        $complaintsFetchedCount = $this->complaintFetcher->fetch();

        $io->note(sprintf('%s complaints fetched.', $complaintsFetchedCount));

        $io->success('You have fetch new complaints successfully!');

        return Command::SUCCESS;
    }
}
