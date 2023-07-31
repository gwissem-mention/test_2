<?php

declare(strict_types=1);

namespace Command\CRON;

use Symfony\Bundle\FrameworkBundle\Console\Application;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
use Symfony\Component\Console\Tester\CommandTester;

class AppointmentSalesforceSendCronTest extends KernelTestCase
{
    public function test(): void
    {
        $kernel = self::bootKernel();

        $application = new Application($kernel);

        $command = $application->find('app:cron:appointment-saleforce:send');
        $commandTester = new CommandTester($command);
        $commandTester->execute([]);

        $this->assertSame(0, $commandTester->getStatusCode());
        $output = $commandTester->getDisplay();
        $this->assertStringContainsString('20 complaints processed', $output);
        $this->assertStringContainsString('You have process the complaints successfully!', $output);
    }
}
