<?php

namespace App\Sensor\Infrastructure\Doctrine\Persistence;

use App\Sensor\Domain\Entity\Sensor;
use App\Sensor\Domain\Repository\SensorRepositoryInterface;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

class DoctrineSensorRepository extends ServiceEntityRepository implements SensorRepositoryInterface
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Sensor::class);
    }

    public function save(Sensor $entity): void
    {
        $this->getEntityManager()->persist($entity);
        $this->getEntityManager()->flush();
    }

    public function findById(int $id): ?Sensor
    {
        return $this->find(['id' => $id]);
    }

    public function findByName(string $name): ?Sensor
    {
        return $this->findOneBy(['name' => $name]);
    }

    public function all(): array
    {
        return $this->findBy([], ['name' => 'ASC']);
    }

    public function delete(Sensor $entity): void
    {
        $this->getEntityManager()->remove($entity);
        $this->getEntityManager()->flush();
    }
}
