<?php

namespace App\Tests\Behat;

use App\AppEnum\Gender;
use App\Entity\User;
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
            'michel-dupont-id',
            'DUPONT',
            'Michel',
            '',
            '1967-03-02',
            '75107',
            '99100',
            Gender::MALE->value,
            'michel.dupont@example.com',
        );

        $this->entityManager->persist($user);
        $this->entityManager->flush();
    }
}
