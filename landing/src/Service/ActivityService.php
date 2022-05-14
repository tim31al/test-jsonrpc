<?php

/*
 * (c) Alexandr Timofeev <tim31al@gmail.com>
 */

namespace App\Service;

use App\DTO\ActivityDto;
use App\DTO\JsonRpcGetClicksDto;
use App\Service\Exception\JsonRpcClientException;
use Psr\Log\LoggerInterface;

class ActivityService
{
    public const PER_PAGE = 5;

    private JsonRpcClient $client;
    private LoggerInterface $logger;
    private JsonRpcErrorValidator $errorValidator;
    private JsonRpcGetClicksValidator $validator;

    /**
     * @param \App\Service\JsonRpcClient             $client
     * @param \App\Service\JsonRpcErrorValidator     $errorValidator
     * @param \App\Service\JsonRpcGetClicksValidator $validator
     */
    public function __construct(JsonRpcClient $client, LoggerInterface $logger, JsonRpcErrorValidator $errorValidator, JsonRpcGetClicksValidator $validator)
    {
        $this->client = $client;
        $this->logger = $logger;
        $this->errorValidator = $errorValidator;
        $this->validator = $validator;
    }

    /**
     * @throws \App\Service\Exception\JsonRpcErrorException
     *
     * @return array<ActivityDto>
     */
    public function getItems(int $page): array
    {
        $offset = ($page - 1) * static::PER_PAGE;
        $id = 1;

        $dto = new JsonRpcGetClicksDto(static::PER_PAGE, $offset, $id);

        try {
            $data = $this->client->rpc($dto);
            $this->errorValidator->validate($data);
            $this->validator->validate($data);

            $dataClicks = $data['result']['clicks'];
            $mapper = fn ($item) => new ActivityDto($item['url'], $item['counter'], $item['lastVisit']);
            $clicks = array_map($mapper, $dataClicks);

            $allItems = $data['result']['countAll'];
            $pages = $allItems ? ceil($allItems / static::PER_PAGE) : 0;
        } catch (JsonRpcClientException $e) {
            $this->logger->error($e->getMessage());
            $pages = 0;
            $clicks = [];
        }

        return [$pages, $clicks];
    }
}
