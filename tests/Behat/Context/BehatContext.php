<?php

namespace App\Tests\Behat\Context;

use App\Tests\DataFixtures\SensorFixtures;
use App\Tests\DataFixtures\UserFixtures;
use App\Tests\DataFixtures\WineFixtures;
use Behat\Behat\Context\Context;
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
     */
    public function loadFixtures(): void
    {
        $loader = new Loader();

        $loader->addFixture(new UserFixtures($this->passwordHasher));
        $loader->addFixture(new WineFixtures());
        $loader->addFixture(new SensorFixtures());

        $this->entityManager->getConnection()->executeStatement('SET FOREIGN_KEY_CHECKS=0');

        $purger = new ORMPurger();
        $purger->setEntityManager($this->entityManager);
        $purger->setPurgeMode(ORMPurger::PURGE_MODE_TRUNCATE);
        $purger->purge();

        $this->entityManager->getConnection()->executeStatement('SET FOREIGN_KEY_CHECKS=1');

        foreach ($loader->getFixtures() as $fixture) {
            $fixture->load($this->entityManager);
        }
    }
}
