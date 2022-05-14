<?php

/*
 * (c) Alexandr Timofeev <tim31al@gmail.com>
 */

namespace App\DTO;

use App\Service\Exception\JsonRpcErrorException;

class ActivityDto
{
    private string $url;
    private int $counter;
    private \DateTimeImmutable $lastVisit;

    /**
     * @throws \App\Service\Exception\JsonRpcErrorException
     */
    public function __construct(string $url, int $counter, string $lastVisit)
    {
        $this->url = $url;
        $this->counter = $counter;
        try {
            $this->lastVisit = new \DateTimeImmutable($lastVisit);
        } catch (\Exception $e) {
            throw new JsonRpcErrorException($e->getMessage());
        }
    }

    public function getUrl(): string
    {
        return $this->url;
    }

    public function getCounter(): int
    {
        return $this->counter;
    }

    public function getLastVisit(): \DateTimeImmutable
    {
        return $this->lastVisit;
    }
}
