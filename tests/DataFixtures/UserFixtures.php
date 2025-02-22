<?php

namespace App\Tests\DataFixtures;

use App\Shared\Infrastructure\Symfony\Security\SecurityUser;
use App\User\Domain\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class UserFixtures extends Fixture
{
    public function __construct(
        private readonly UserPasswordHasherInterface $passwordHasher,
    ) {
    }

    private function createUser(string $name, string $email, string $password, array $roles): User
    {
        $user = new User(
            name: $name,
            email: $email,
            roles: $roles
        );

        $user->password = $this->passwordHasher->hashPassword(new SecurityUser($user), $password);

        return $user;
    }

    public function load(ObjectManager $manager): void
    {
        $admin = $this->createUser(
            name: 'Admin User',
            email: 'admin@example.com',
            password: 'adminpass',
            roles: ['ROLE_ADMIN']
        );

        $manager->persist($admin);

        $john = $this->createUser(
            name: 'John Doe',
            email: 'john@example.com',
            password: 'password123',
            roles: ['ROLE_USER']
        );

        $manager->persist($john);

        $jane = $this->createUser(
            name: 'Jane Doe',
            email: 'jane@example.com',
            password: 'password456',
            roles: ['ROLE_USER']
        );

        $manager->persist($jane);

        $manager->flush();
    }
}
