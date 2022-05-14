<?php

/*
 *
 * (c) Alexandr Timofeev <tim31al@gmail.com>
 *
 */

namespace App\Method;

use App\Entity\Click;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\Constraints\Collection;
use Symfony\Component\Validator\Constraints\DateTime;
use Symfony\Component\Validator\Constraints\Length;
use Yoanm\JsonRpcParamsSymfonyValidator\Domain\MethodWithValidatedParamsInterface;
use Yoanm\JsonRpcServer\Domain\JsonRpcMethodInterface;

/**
 * Счетчик кликов.
 */
class AddClickMethod implements JsonRpcMethodInterface, MethodWithValidatedParamsInterface
{
    private EntityManagerInterface $em;

    public function __construct(EntityManagerInterface $em)
    {
        $this->em = $em;
    }

    /**
     * {@inheritDoc}
     *
     * @param array|null $paramList {url: string, data: string}
     */
    public function apply(array $paramList = null): Click
    {
        $click = $this->em->getRepository(Click::class)
            ->findOneBy(['url' => $paramList['url']]);

        if (!$click) {
            $click = new Click();
            $click->setUrl($paramList['url']);

            $this->em->persist($click);
        }

        $lastVisit = new \DateTimeImmutable($paramList['date']);

        $click
            ->inc()
            ->setLastVisit($lastVisit)
            ;

        $this->em->flush();

        return $click;
    }

    public function add(int $s): int
    {
        return $s + 1;
    }

    public function getParamsConstraint(): Constraint
    {
        return new Collection([
            'fields' => [
                'url' => new Length(['min' => 1, 'max' => 255]),
                'date' => new DateTime(),
            ],
        ]);
    }
}
