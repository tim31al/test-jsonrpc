<?php

/*
 *
 * (c) Alexandr Timofeev <tim31al@gmail.com>
 *
 */

return [
    Symfony\Bundle\FrameworkBundle\FrameworkBundle::class => ['all' => true],
    Symfony\Bundle\MakerBundle\MakerBundle::class => ['dev' => true],
    Doctrine\Bundle\DoctrineBundle\DoctrineBundle::class => ['all' => true],
    Doctrine\Bundle\MigrationsBundle\DoctrineMigrationsBundle::class => ['all' => true],
    Symfony\Bundle\SecurityBundle\SecurityBundle::class => ['all' => true],
    Lexik\Bundle\JWTAuthenticationBundle\LexikJWTAuthenticationBundle::class => ['all' => true],
    DAMA\DoctrineTestBundle\DAMADoctrineTestBundle::class => ['test' => true],
    Doctrine\Bundle\FixturesBundle\DoctrineFixturesBundle::class => ['dev' => true, 'test' => true],
    Yoanm\SymfonyJsonRpcHttpServer\JsonRpcHttpServerBundle::class => ['all' => true],
    Yoanm\SymfonyJsonRpcParamsValidator\JsonRpcParamsValidatorBundle::class => ['all' => true],
];
