<?php

declare(strict_types=1);

/*
 *
 * (c) Alexandr Timofeev <tim31al@gmail.com>
 *
 */

namespace App\Command;

use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Question\ChoiceQuestion;
use Symfony\Component\Console\Question\Question;
use Symfony\Component\Console\Style\SymfonyStyle;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

#[AsCommand(
    name: 'app:user:create',
    description: 'Добавить пользователя',
)]
class UserCreateCommand extends Command
{
    private EntityManagerInterface $entityManager;
    private UserPasswordHasherInterface $passwordHasher;

    public function __construct(EntityManagerInterface $entityManager, UserPasswordHasherInterface $passwordHasher)
    {
        parent::__construct();

        $this->entityManager = $entityManager;
        $this->passwordHasher = $passwordHasher;
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);

        $helper = $this->getHelper('question');
        $question = new Question('Email пользователя: ');

        $username = $helper->ask($input, $output, $question);

        if (!$username) {
            $io->error('Email обязательно');

            return Command::SUCCESS;
        }

        $this->checkUser($username);

        $question = new Question('Пароль: ');
        $plainPassword = $helper->ask($input, $output, $question);

        if (!$plainPassword) {
            $io->error('Пароль обязательно');

            return Command::SUCCESS;
        }

        $question = new ChoiceQuestion(
            'Выберите роль',
            // choices can also be PHP objects that implement __toString() method
            ['user', 'editor', 'admin'],
            0
        );
        $role = $helper->ask($input, $output, $question);

        $this->addUser($username, $plainPassword, $role);

        $io->success('Пользователь добавлен');

        return Command::SUCCESS;
    }

    private function addUser($username, $password, $role): void
    {
        $userRole = User::ROLES[$role];
        $roles = [$userRole];

        $user = new User();
        $user
            ->setEmail($username)
            ->setRoles($roles)
        ;

        $hash = $this->passwordHasher->hashPassword($user, $password);
        $user->setPassword($hash);

        $this->entityManager->persist($user);
        $this->entityManager->flush();
    }

    private function checkUser(string $email)
    {
        $user = $this->entityManager->getRepository(User::class)
            ->findOneBy(['email' => $email]);
        if ($user) {
            throw new \InvalidArgumentException('User with email '.$email.' exists');
        }
    }
}
