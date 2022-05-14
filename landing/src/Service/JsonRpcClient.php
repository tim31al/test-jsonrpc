<?php

/*
 * (c) Alexandr Timofeev <tim31al@gmail.com>
 */

namespace App\Service;

use App\DTO\JsonRpcDtoInterface;
use App\Service\Exception\JsonRpcClientException;
use Exception;
use Psr\Cache\InvalidArgumentException;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Contracts\Cache\CacheInterface;
use Symfony\Contracts\Cache\ItemInterface;
use Symfony\Contracts\HttpClient\Exception\ClientExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\DecodingExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\RedirectionExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\ServerExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\TransportExceptionInterface;
use Symfony\Contracts\HttpClient\HttpClientInterface;
use Symfony\Contracts\HttpClient\ResponseInterface;

class JsonRpcClient
{
    private HttpClientInterface $client;
    private CacheInterface $cache;
    private string $rpcUrl;
    private string $rpcUser;
    private string $rpcPass;
    private string $tokenKey;
    private int $tokenTtl;

    public function __construct(
        HttpClientInterface $client,
        CacheInterface $cache,
        string $rpcUrl,
        string $rpcUser,
        string $rpcPass,
        string $tokenKey,
        int $tokenTtl
    ) {
        $this->client = $client;
        $this->cache = $cache;
        $this->rpcUrl = $rpcUrl;
        $this->rpcUser = $rpcUser;
        $this->rpcPass = $rpcPass;
        $this->tokenKey = $tokenKey;
        $this->tokenTtl = $tokenTtl;
    }

    /**
     * Запрос токена.
     *
     * @throws \App\Service\Exception\JsonRpcClientException
     */
    public function auth(): string
    {
        try {
            $response = $this->client->request('POST', $this->rpcUrl.'/auth', [
                'headers' => [
                    'Content-Type' => 'application/json',
                ],
                'body' => json_encode([
                    'email' => $this->rpcUser,
                    'password' => $this->rpcPass,
                ]),
            ]);

            $data = $response->toArray();

            if (!isset($data['token'])) {
                $message = isset($data['error']) ?? 'Error auth';
                throw new Exception($message);
            }

            return $data['token'];
        } catch (TransportExceptionInterface|ClientExceptionInterface|DecodingExceptionInterface|RedirectionExceptionInterface|ServerExceptionInterface|Exception $e) {
            throw new JsonRpcClientException($e->getMessage());
        }
    }

    /**
     * @throws \App\Service\Exception\JsonRpcClientException
     */
    public function rpc(JsonRpcDtoInterface $dto): ?array
    {
        try {
            $token = $this->getToken();
            $response = $this->getResponse($token, $dto);

            if (Response::HTTP_UNAUTHORIZED === $response->getStatusCode()) {
                $token = $this->getToken();
                $response = $this->getResponse($token, $dto);
            }

            $content = $response->getContent();

            if (!$content) {
                return null;
            }

            return json_decode($content, true);
        } catch (
            TransportExceptionInterface|ClientExceptionInterface|
        RedirectionExceptionInterface|ServerExceptionInterface|
        InvalidArgumentException $e) {
            throw new JsonRpcClientException($e->getMessage());
        }
    }

    /**
     * @throws \Symfony\Contracts\HttpClient\Exception\TransportExceptionInterface
     */
    private function getResponse(string $token, JsonRpcDtoInterface $dto): ResponseInterface
    {
        return $this->client->request('POST', $this->rpcUrl, [
            'auth_bearer' => $token,
            'headers' => [
                'Content-Type' => 'application/json',
            ],
            'body' => (string) $dto,
        ]);
    }

    /**
     * @throws \Psr\Cache\InvalidArgumentException
     */
    private function getToken(): string
    {
        return $this->cache->get($this->tokenKey, function (ItemInterface $item) {
            $token = $this->auth();
            $item->set($token);
            $item->expiresAfter($this->tokenTtl);

            return $token;
        });
    }
}
