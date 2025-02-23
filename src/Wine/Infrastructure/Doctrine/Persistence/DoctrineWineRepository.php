<?php

namespace App\Wine\Infrastructure\Doctrine\Persistence;

use App\Wine\Domain\Entity\Wine;
use App\Wine\Domain\Repository\WineRepositoryInterface;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

class DoctrineWineRepository extends ServiceEntityRepository implements WineRepositoryInterface
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Wine::class);
    }

    public function save(Wine $entity): void
    {
        $this->getEntityManager()->persist($entity);
        $this->getEntityManager()->flush();
    }

    public function findById(int $id): ?Wine
    {
        return $this->find(['id' => $id]);
    }

    public function findByNameAndYear(string $name, int $year): ?Wine
    {
        return $this->findOneBy(['name' => $name, 'year' => $year]);
    }

    public function all(): array
    {
        return $this->findAll();
    }

    public function delete(Wine $entity): void
    {
        $this->getEntityManager()->remove($entity);
        $this->getEntityManager()->flush();
    }
}
