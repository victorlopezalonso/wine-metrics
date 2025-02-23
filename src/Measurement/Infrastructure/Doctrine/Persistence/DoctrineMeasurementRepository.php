<?php

namespace App\Measurement\Infrastructure\Doctrine\Persistence;

use App\Measurement\Domain\Entity\Measurement;
use App\Measurement\Domain\Repository\MeasurementRepositoryInterface;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

class DoctrineMeasurementRepository extends ServiceEntityRepository implements MeasurementRepositoryInterface
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Measurement::class);
    }

    public function save(Measurement $entity): void
    {
        $this->getEntityManager()->persist($entity);
        $this->getEntityManager()->flush();
    }

    public function findById(int $id): ?Measurement
    {
        return $this->find(['id' => $id]);
    }

    public function all(): array
    {
        return $this->findAll();
    }

    public function delete(Measurement $entity): void
    {
        $this->getEntityManager()->remove($entity);
        $this->getEntityManager()->flush();
    }
}
