<?php

declare(strict_types=1);

namespace App\Tests\Command\CRON;

use Symfony\Bundle\FrameworkBundle\Console\Application;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
use Symfony\Component\Console\Tester\CommandTester;

class AppointmentNotificationCronTest extends KernelTestCase
{
    public function testClosingReminderPV(): void
    {
        $kernel = self::bootKernel();

        $application = new Application($kernel);

        $command = $application->find('app:cron:appointment-notification');
        $commandTester = new CommandTester($command);
        $commandTester->execute([]);

        $this->assertSame(0, $commandTester->getStatusCode());
        $output = $commandTester->getDisplay();
        $this->assertStringContainsString('[OK] Done!', $output);
    }
}
