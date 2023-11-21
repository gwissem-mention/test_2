<?php

declare(strict_types=1);

namespace App\Tests\Command\CRON;

use Symfony\Bundle\FrameworkBundle\Console\Application;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
use Symfony\Component\Console\Tester\CommandTester;

class ComplaintCleanUpZipAttachmentsCronTest extends KernelTestCase
{
    public function test(): void
    {
        $kernel = self::bootKernel();

        $application = new Application($kernel);

        $command = $application->find('app:cron:complaint:clean-up:zip-attachments');
        $commandTester = new CommandTester($command);
        $commandTester->execute([]);

        $this->assertSame(0, $commandTester->getStatusCode());
        $output = $commandTester->getDisplay();
        $this->assertStringNotContainsString('Error while cleaning up zip attachments files for complaint', $output);
        $this->assertStringContainsString('2 attachments cleaned up.', $output);
        $this->assertStringContainsString('You have clean up zip attachments successfully!', $output);
    }
}
