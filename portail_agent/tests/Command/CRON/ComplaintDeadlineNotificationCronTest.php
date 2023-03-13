<?php

namespace App\Tests\Command\CRON;

use Symfony\Bundle\FrameworkBundle\Console\Application;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
use Symfony\Component\Console\Tester\CommandTester;

class ComplaintDeadlineNotificationCronTest extends KernelTestCase
{
    public function testSendSessionReminder(): void
    {
        $kernel = self::bootKernel();

        $application = new Application($kernel);

        $command = $application->find('app:cron:deadline-notification');
        $commandTester = new CommandTester($command);
        $commandTester->execute([]);

        $this->assertSame(0, $commandTester->getStatusCode());
        $output = $commandTester->getDisplay();
        $this->assertStringContainsString(' ! [NOTE] Found 180 complaints.', $output);
        $this->assertStringContainsString('[OK] Done!', $output);
    }
}
