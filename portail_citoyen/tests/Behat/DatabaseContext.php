<?php

namespace App\Tests\Behat;

use App\Entity\User;
use App\Enum\Gender;
use Behat\Behat\Context\Context;
use Doctrine\Common\DataFixtures\Purger\ORMPurger;
use Doctrine\ORM\EntityManagerInterface;

class DatabaseContext implements Context
{
    public function __construct(protected readonly EntityManagerInterface $entityManager)
    {
    }

    /**
     * @BeforeScenario
     */
    public function initDatabase(): void
    {
        $purger = new ORMPurger($this->entityManager);
        $purger->setPurgeMode(ORMPurger::PURGE_MODE_DELETE);
        $purger->purge();

        $user = new User(
            'jean-dupond-id',
            'DUPOND',
            'Jean',
            '',
            '1982-04-23',
            '75107',
            '99100',
            Gender::MALE->value,
            'jean.dupond@example.org',
        );

        $this->entityManager->persist($user);
        $this->entityManager->flush();
    }
}
