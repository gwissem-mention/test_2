<?php

namespace App\Tests\Behat;

use App\DataFixtures\ComplaintFixtures;
use App\DataFixtures\NotificationFixtures;
use App\DataFixtures\QuestionFixtures;
use App\DataFixtures\UserFixtures;
use App\Factory\NotificationFactory;
use Behat\Behat\Context\Context;
use Doctrine\Common\DataFixtures\Executor\ORMExecutor;
use Doctrine\Common\DataFixtures\Loader;
use Doctrine\Common\DataFixtures\Purger\ORMPurger;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\Tools\SchemaTool;
use Symfony\Component\HttpKernel\KernelInterface;

class DatabaseContext implements Context
{
    public function __construct(
        protected readonly KernelInterface $kernel,
        protected readonly EntityManagerInterface $entityManager,
        protected readonly NotificationFactory $notificationFactory)
    {
        $schemaTool = new SchemaTool($this->entityManager);
        $schemaTool->dropDatabase();
        $metadata = $this->entityManager->getMetadataFactory()->getAllMetadata();
        $schemaTool->createSchema($metadata);
    }

    /**
     * @BeforeScenario
     */
    public function initDatabase(): void
    {
        $loader = new Loader();
        $loader->addFixture(new UserFixtures());
        $loader->addFixture(new ComplaintFixtures());
        $loader->addFixture(new NotificationFixtures($this->notificationFactory));
        $loader->addFixture(new QuestionFixtures());

        $purger = new ORMPurger();
        $purger->setPurgeMode(ORMPurger::PURGE_MODE_DELETE);

        $executor = new ORMExecutor($this->entityManager, $purger);
        $executor->execute($loader->getFixtures());
    }
}
