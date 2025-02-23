<?php

namespace App\Tests\DataFixtures;

use App\Sensor\Domain\Entity\Sensor;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class SensorFixtures extends Fixture
{
    public const EXISTING_SENSOR_REFERENCE = 'existing_sensor';

    public function load(ObjectManager $manager): void
    {
        $sensor = new Sensor(
            id: 1,
            name: 'existing_sensor',
        );

        $manager->persist($sensor);
        $manager->flush();

        $this->addReference(self::EXISTING_SENSOR_REFERENCE, $sensor);
    }
}
