<?php

namespace App\Tests\DataFixtures;

use App\Measurement\Domain\Entity\Measurement;
use App\Sensor\Domain\Entity\Sensor;
use App\Wine\Domain\Entity\Wine;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;

class MeasurementFixtures extends Fixture implements DependentFixtureInterface
{
    public function getDependencies(): array
    {
        return [
            WineFixtures::class,
            SensorFixtures::class,
        ];
    }

    public function load(ObjectManager $manager): void
    {
        $wine = new Measurement(
            wine: $this->getReference(WineFixtures::EXISTING_WINE_REFERENCE, Wine::class),
            sensor: $this->getReference(SensorFixtures::EXISTING_SENSOR_REFERENCE, Sensor::class),
            value: '12.5',
            unit: '°C',
        );

        $manager->persist($wine);

        $manager->flush();
    }
}
