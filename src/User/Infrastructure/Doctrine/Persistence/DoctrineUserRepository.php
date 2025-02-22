<?php

namespace App\User\Infrastructure\Doctrine\Persistence;

use App\Shared\Domain\Pagination\Page;
use App\Shared\Infrastructure\Doctrine\Pagination\DoctrinePagination;
use App\User\Domain\Entity\User;
use App\User\Domain\Exception\UserAlreadyExistsException;
use App\User\Domain\Repository\UserRepositoryInterface;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<User>
 */
class DoctrineUserRepository extends ServiceEntityRepository implements UserRepositoryInterface
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, User::class);
    }

    public function save(User $user): void
    {
        $this->getEntityManager()->persist($user);
        $this->getEntityManager()->flush();
    }

    public function create(User $user): void
    {
        if (null !== $this->findByEmail($user->email)) {
            throw new UserAlreadyExistsException();
        }

        $user->password = password_hash((string) $user->password, PASSWORD_DEFAULT);

        $this->save($user);
    }

    public function findById(int $id): ?User
    {
        return $this->find(['id' => $id]);
    }

    public function findByEmail(string $email): ?User
    {
        return $this->findOneBy(['email' => $email]);
    }

    public function all(Page $page): DoctrinePagination
    {
        $query = $this->createQueryBuilder('users')
            ->orderBy('users.id', 'asc')
            ->getQuery();

        return new DoctrinePagination($query, $page);
    }

    public function delete(User $user): void
    {
        $this->getEntityManager()->remove($user);
        $this->getEntityManager()->flush();
    }
}
