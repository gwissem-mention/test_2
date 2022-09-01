<?php

namespace App\Tests\Behat;

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
    public function purgeDatabase(): void
    {
        $purger = new ORMPurger($this->entityManager);
        $purger->setPurgeMode(ORMPurger::PURGE_MODE_DELETE);
        $purger->purge();
    }
}
