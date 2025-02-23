<?php

namespace App\Tests\DataFixtures;

use App\Sensor\Domain\Entity\Sensor;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class SensorFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        $sensor = new Sensor(
            name: 'existing_sensor',
        );

        $manager->persist($sensor);

        $manager->flush();
    }
}
