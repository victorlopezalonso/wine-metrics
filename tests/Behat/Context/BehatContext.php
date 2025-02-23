<?php

namespace App\Tests\Behat\Context;

use App\Tests\DataFixtures\SensorFixtures;
use App\Tests\DataFixtures\UserFixtures;
use Behat\Behat\Context\Context;
use Doctrine\Common\DataFixtures\Loader;
use Doctrine\Common\DataFixtures\Purger\ORMPurger;
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
     */
    public function loadFixtures(): void
    {
        $loader = new Loader();

        $loader->addFixture(new UserFixtures($this->passwordHasher));
        $loader->addFixture(new SensorFixtures());

        $purger = new ORMPurger();
        $purger->setEntityManager($this->entityManager);
        $purger->purge();

        foreach ($loader->getFixtures() as $fixture) {
            $fixture->load($this->entityManager);
        }
    }
}
