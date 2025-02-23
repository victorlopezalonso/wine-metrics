<?php

namespace App\Tests\Behat\Context;

use App\Tests\DataFixtures\MeasurementFixtures;
use App\Tests\DataFixtures\SensorFixtures;
use App\Tests\DataFixtures\UserFixtures;
use App\Tests\DataFixtures\WineFixtures;
use Behat\Behat\Context\Context;
use Doctrine\Common\DataFixtures\Executor\ORMExecutor;
use Doctrine\Common\DataFixtures\Loader;
use Doctrine\Common\DataFixtures\Purger\ORMPurger;
use Doctrine\DBAL\Exception;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

readonly class BehatContext implements Context
{
    public function __construct(
        private EntityManagerInterface $entityManager,
        private UserPasswordHasherInterface $passwordHasher,
    ) {
    }

    /**
     * @BeforeScenario
     *
     * @throws Exception
     * @throws \Exception
     */
    public function loadFixtures(): void
    {
        $loader = new Loader();

        $loader->addFixture(new UserFixtures($this->passwordHasher));
        $loader->addFixture(new WineFixtures());
        $loader->addFixture(new SensorFixtures());
        $loader->addFixture(new MeasurementFixtures());

        $connection = $this->entityManager->getConnection();
        $databasePlatform = $connection->getDatabasePlatform();

        $connection->executeStatement('SET FOREIGN_KEY_CHECKS=0');

        $schemaManager = $connection->createSchemaManager();
        $tables = $schemaManager->listTableNames();

        foreach ($tables as $table) {
            $connection->executeStatement($databasePlatform->getTruncateTableSQL($table, true));
        }

        $connection->executeStatement('SET FOREIGN_KEY_CHECKS=1');

        $purger = new ORMPurger();
        $purger->setPurgeMode(ORMPurger::PURGE_MODE_DELETE);

        $executor = new ORMExecutor($this->entityManager, $purger);
        $executor->purge();

        $executor->execute($loader->getFixtures());
    }
}
