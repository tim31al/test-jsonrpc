<?php

declare(strict_types=1);

/*
 *
 * (c) Alexandr Timofeev <tim31al@gmail.com>
 *
 */

namespace App\DataFixtures;

use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\PasswordHasher\Hasher\PasswordHasherFactoryInterface;

class AppFixtures extends Fixture
{
    private PasswordHasherFactoryInterface $passwordHasherFactory;

    public function __construct(PasswordHasherFactoryInterface $passwordHasherFactory)
    {
        $this->passwordHasherFactory = $passwordHasherFactory;
    }

    public function load(ObjectManager $manager): void
    {
        $hash = $this->passwordHasherFactory
            ->getPasswordHasher(User::class)
            ->hash('user');

        $admin = new User();
        $admin
            ->setEmail('user@example.com')
            ->setPassword($hash);

        $manager->persist($admin);

        $manager->flush();
    }
}
