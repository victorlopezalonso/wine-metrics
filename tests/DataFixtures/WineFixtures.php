<?php

namespace App\Tests\DataFixtures;

use App\Wine\Domain\Entity\Wine;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class WineFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        $wine = new Wine(
            id: 1,
            name: 'existing_wine',
            year: 2020,
        );

        $manager->persist($wine);

        $manager->flush();
    }
}
