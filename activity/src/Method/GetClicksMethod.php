<?php

/*
 *
 * (c) Alexandr Timofeev <tim31al@gmail.com>
 *
 */

namespace App\Method;

use App\Repository\ClickRepository;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\Constraints\Collection;
use Symfony\Component\Validator\Constraints\LessThanOrEqual;
use Symfony\Component\Validator\Constraints\Optional;
use Symfony\Component\Validator\Constraints\Positive;
use Symfony\Component\Validator\Constraints\PositiveOrZero;
use Yoanm\JsonRpcParamsSymfonyValidator\Domain\MethodWithValidatedParamsInterface;
use Yoanm\JsonRpcServer\Domain\JsonRpcMethodInterface;

class GetClicksMethod implements JsonRpcMethodInterface, MethodWithValidatedParamsInterface
{
    private ClickRepository $repository;
    private int $maxItems;

    public function __construct(ClickRepository $repository, int $maxItems)
    {
        $this->repository = $repository;
        $this->maxItems = $maxItems;
    }

    public function apply(array $paramList = null): array
    {
        $clicks = $this->repository
            ->findBy(
                [],
                ['counter' => 'desc', 'lastVisit' => 'desc'],
                $paramList['limit'] ?? $this->maxItems,
                $paramList['offset'] ?? 0
            );
        $count = $this->repository->count([]);

        return [
            'clicks' => $clicks,
            'countAll' => $count,
        ];
    }

    public function getParamsConstraint(): Constraint
    {
        return new Collection([
            'fields' => [
                'limit' => new Optional([
                    new Positive(),
                    new LessThanOrEqual($this->maxItems),
                ]),
                'offset' => new Optional(
                    new PositiveOrZero()
                ),
            ],
        ]);
    }
}
