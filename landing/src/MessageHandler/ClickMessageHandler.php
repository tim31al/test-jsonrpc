<?php

/*
 * (c) Alexandr Timofeev <tim31al@gmail.com>
 */

namespace App\MessageHandler;

use App\DTO\JsonRpcAddClickDto;
use App\Message\ClickMessage;
use App\Service\Exception\JsonRpcClientException;
use App\Service\JsonRpcClient;
use App\Service\JsonRpcErrorValidator;
use Exception;
use Psr\Log\LoggerInterface;
use Symfony\Component\Messenger\Attribute\AsMessageHandler;

#[AsMessageHandler]
class ClickMessageHandler
{
    private JsonRpcClient $client;
    private LoggerInterface $logger;
    private JsonRpcErrorValidator $validator;

    public function __construct(
        JsonRpcClient $client,
        LoggerInterface $logger,
        JsonRpcErrorValidator $validator
    ) {
        $this->client = $client;
        $this->logger = $logger;
        $this->validator = $validator;
    }

    public function __invoke(ClickMessage $message): void
    {
        $dto = new JsonRpcAddClickDto(
            $message->getUrl(),
            new \DateTime($message->getDatetime())
        );

//        $data = null;

        try {
            $data = $this->client->rpc($dto);
            $this->validator->validate($data);
        } catch (JsonRpcClientException|Exception $e) {
            $this->logger->error($e->getMessage());
        }

//        if (null !== $data && \array_key_exists('error', $data)) {
//            $this->logger->error($data['error']['message']);
//        }
    }
}
