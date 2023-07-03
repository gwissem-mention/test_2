<?php

declare(strict_types=1);

namespace App\Command\CRON;

use App\Oodrive\OodriveCleaner;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

#[AsCommand(
    name: 'app:cron:oodrive:clean-up:declaration',
    description: 'Clean up declaration oodrive files for complaints older than X period',
)]
class OodriveCleanUpDeclarationCron extends Command
{
    public function __construct(
        private readonly OodriveCleaner $oodriveCleaner
    ) {
        parent::__construct(self::getDefaultName());
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);
        $io->title('Clean up declaration oodrive files for complaints older than X period');

        $nbComplaintsCleaned = $this->oodriveCleaner->declarationCleaner();

        $io->note(sprintf('%s complaints cleaned up.', $nbComplaintsCleaned));

        $io->success('You have clean up complaints successfully!');

        return Command::SUCCESS;
    }
}
