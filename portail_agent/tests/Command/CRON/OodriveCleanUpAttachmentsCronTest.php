<?php

declare(strict_types=1);

namespace Command\CRON;

use Symfony\Bundle\FrameworkBundle\Console\Application;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
use Symfony\Component\Console\Tester\CommandTester;

class OodriveCleanUpAttachmentsCronTest extends KernelTestCase
{
    public function test(): void
    {
        $kernel = self::bootKernel();

        $application = new Application($kernel);

        $command = $application->find('app:cron:oodrive:clean-up:attachments');
        $commandTester = new CommandTester($command);
        $commandTester->execute([]);

        $this->assertSame(0, $commandTester->getStatusCode());
        $output = $commandTester->getDisplay();
        $this->assertStringNotContainsString('Error while cleaning up oodrive files for complaint', $output);
        $this->assertStringContainsString('20 complaints cleaned up.', $output);
        $this->assertStringContainsString('You have clean up complaints successfully!', $output);
    }
}
