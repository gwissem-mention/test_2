<?php

declare(strict_types=1);

namespace App\Tests\Logger;

use App\AppEnum\Institution;
use App\Entity\User;
use App\Logger\ApplicationTracesLogger;
use PHPUnit\Framework\TestCase;
use Psr\Log\LoggerInterface;
use Symfony\Bundle\SecurityBundle\Security;

class ApplicationTracesLoggerTest extends TestCase
{
    public function testLogGN(): void
    {
        $mockLogger = $this->createMock(LoggerInterface::class);
        $mockLogger->expects($this->once())->method('info')->with('Test GN');

        $mockSecurity = $this->createMock(Security::class);
        $mockSecurity
            ->method('getUser')
            ->willReturn(new User('1234', Institution::GN));

        $logger = new ApplicationTracesLogger($mockLogger, $mockLogger, $mockSecurity);
        $logger->log('Test GN');
    }

    public function testLogPN(): void
    {
        $mockLogger = $this->createMock(LoggerInterface::class);
        $mockLogger->expects($this->once())->method('info')->with('Test PN');

        $mockSecurity = $this->createMock(Security::class);
        $mockSecurity
            ->method('getUser')
            ->willReturn(new User('5678', Institution::PN));

        $logger = new ApplicationTracesLogger($mockLogger, $mockLogger, $mockSecurity);
        $logger->log('Test PN');
    }
}
