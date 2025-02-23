<?php

namespace App\Wine\Infrastructure\Doctrine\Persistence;

use App\Shared\Domain\Pagination\Page;
use App\Shared\Infrastructure\Doctrine\Pagination\DoctrinePagination;
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

    public function all(Page $page, bool $withMeasurements): DoctrinePagination
    {
        $queryBuilder = $this->createQueryBuilder('wines');

        if ($withMeasurements) {
            $queryBuilder
                ->leftJoin('wines.measurements', 'measurements')
                ->addSelect('measurements');
        }

        $query = $queryBuilder
            ->orderBy('wines.name', 'asc')
            ->getQuery();

        return new DoctrinePagination($query, $page);
    }

    public function delete(Wine $entity): void
    {
        $this->getEntityManager()->remove($entity);
        $this->getEntityManager()->flush();
    }
}
