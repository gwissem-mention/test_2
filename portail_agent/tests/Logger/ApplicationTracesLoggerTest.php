<?php

declare(strict_types=1);

namespace App\Tests\Logger;

use App\AppEnum\Institution;
use App\Entity\User;
use App\Logger\ApplicationTracesLogger;
use App\Logger\ApplicationTracesMessage;
use PHPUnit\Framework\TestCase;
use Psr\Log\LoggerInterface;
use Symfony\Bundle\SecurityBundle\Security;

class ApplicationTracesLoggerTest extends TestCase
{
    public function testLoginGN(): void
    {
        $user = new User('1234', Institution::GN);

        $mockSecurity = $this->createMock(Security::class);
        $mockSecurity
            ->method('getUser')
            ->willReturn($user);

        $mockLogger = $this->createMock(LoggerInterface::class);
        $mockLogger->expects($this->once())->method('info')->with(
            '{"horodatage":"'.(new \DateTime())->format('Y-m-d H:i:s').'","IP":"127.0.0.1","matricule":"1234","action":"CONNEXION"}'.PHP_EOL
        );

        $logger = new ApplicationTracesLogger($mockLogger, $mockLogger, $mockSecurity);
        $logger->log(ApplicationTracesMessage::message(
            ApplicationTracesMessage::LOGIN,
            null,
            $user->getNumber(),
            '127.0.0.1'
        ), $user);
    }

    public function testLoginPN(): void
    {
        $user = new User('1234', Institution::PN);

        $mockSecurity = $this->createMock(Security::class);
        $mockSecurity
            ->method('getUser')
            ->willReturn($user);

        $mockLogger = $this->createMock(LoggerInterface::class);
        $mockLogger->expects($this->once())->method('info')->with(
            '{"horodatage":"'.(new \DateTime())->format('Y-m-d H:i:s').'","IP":"127.0.0.1","matricule":"1234","action":"CONNEXION"}'.PHP_EOL
        );

        $logger = new ApplicationTracesLogger($mockLogger, $mockLogger, $mockSecurity);
        $logger->log(ApplicationTracesMessage::message(
            ApplicationTracesMessage::LOGIN,
            null,
            $user->getNumber(),
            '127.0.0.1'
        ), $user);
    }

    public function testLogoutGN(): void
    {
        $user = new User('1234', Institution::GN);

        $mockSecurity = $this->createMock(Security::class);
        $mockSecurity
            ->method('getUser')
            ->willReturn($user);

        $mockLogger = $this->createMock(LoggerInterface::class);
        $mockLogger->expects($this->once())->method('info')->with(
            '{"horodatage":"'.(new \DateTime())->format('Y-m-d H:i:s').'","IP":"127.0.0.1","matricule":"1234","action":"DECONNEXION"}'.PHP_EOL
        );

        $logger = new ApplicationTracesLogger($mockLogger, $mockLogger, $mockSecurity);
        $logger->log(ApplicationTracesMessage::message(
            ApplicationTracesMessage::LOGOUT,
            null,
            $user->getNumber(),
            '127.0.0.1'
        ), $user);
    }

    public function testLogoutPN(): void
    {
        $user = new User('1234', Institution::PN);

        $mockSecurity = $this->createMock(Security::class);
        $mockSecurity
            ->method('getUser')
            ->willReturn($user);

        $mockLogger = $this->createMock(LoggerInterface::class);
        $mockLogger->expects($this->once())->method('info')->with(
            '{"horodatage":"'.(new \DateTime())->format('Y-m-d H:i:s').'","IP":"127.0.0.1","matricule":"1234","action":"DECONNEXION"}'.PHP_EOL
        );

        $logger = new ApplicationTracesLogger($mockLogger, $mockLogger, $mockSecurity);
        $logger->log(ApplicationTracesMessage::message(
            ApplicationTracesMessage::LOGOUT,
            null,
            $user->getNumber(),
            '127.0.0.1'
        ), $user);
    }
}
