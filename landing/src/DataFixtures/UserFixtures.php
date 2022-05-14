<?php

/*
 * (c) Alexandr Timofeev <tim31al@gmail.com>
 */

namespace App\DataFixtures;

use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class UserFixtures extends Fixture
{
    private UserPasswordHasherInterface $passwordHasher;

    public function __construct(UserPasswordHasherInterface $passwordHasher)
    {
        $this->passwordHasher = $passwordHasher;
    }

    public function load(ObjectManager $manager): void
    {
        $user = new User();
        $user
            ->setEmail('admin@example.com')
            ->setRoles(['ROLE_ADMIN'])
            ;

        $password = 'admin';
        $hash = $this->passwordHasher->hashPassword($user, $password);
        $user->setPassword($hash);

        $manager->persist($user);

        $manager->flush();
    }
}
