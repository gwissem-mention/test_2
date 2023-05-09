<?php

namespace App\Tests\Behat;

use App\DataFixtures\Complaint\ComplaintFixtures;
use App\DataFixtures\QuestionFixtures;
use App\DataFixtures\UserFixtures;
use App\Factory\NotificationFactory;
use Behat\Behat\Context\Context;
use Doctrine\Common\DataFixtures\Executor\ORMExecutor;
use Doctrine\Common\DataFixtures\Loader;
use Doctrine\Common\DataFixtures\Purger\ORMPurger;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\Tools\SchemaTool;
use Faker\Factory;
use Faker\Generator;
use League\Flysystem\FilesystemOperator;
use Symfony\Component\HttpKernel\KernelInterface;

class DatabaseContext implements Context
{
    private static Generator $faker;

    public function __construct(
        protected readonly KernelInterface $kernel,
        protected readonly EntityManagerInterface $entityManager,
        protected readonly NotificationFactory $notificationFactory,
        protected readonly FilesystemOperator $defaultStorage,
    ) {
    }

    /**
     * @BeforeSuite
     */
    public static function initFakerSeed(): void
    {
        self::$faker = Factory::create('fr_FR');
        self::$faker->seed(1);
    }

    /**
     * @BeforeScenario
     */
    public function initDatabase(): void
    {
        $schemaTool = new SchemaTool($this->entityManager);
        $schemaTool->dropDatabase();
        $metadata = $this->entityManager->getMetadataFactory()->getAllMetadata();
        $schemaTool->createSchema($metadata);

        $loader = new Loader();
        $loader->addFixture(new UserFixtures());
        $loader->addFixture(new ComplaintFixtures($this->notificationFactory, $this->defaultStorage, $this->kernel));
        $loader->addFixture(new QuestionFixtures());
        $purger = new ORMPurger();
        $purger->setPurgeMode(ORMPurger::PURGE_MODE_DELETE);

        $executor = new ORMExecutor($this->entityManager, $purger);
        $executor->execute($loader->getFixtures());
    }
}
