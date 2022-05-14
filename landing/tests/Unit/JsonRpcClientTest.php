<?php

/*
 * (c) Alexandr Timofeev <tim31al@gmail.com>
 */

namespace App\Tests\Unit;

use App\DTO\JsonRpcAddClickDto;
use App\Service\Exception\JsonRpcClientException;
use App\Service\JsonRpcClient;
use PHPUnit\Framework\TestCase;
use Symfony\Component\HttpClient\HttpClient;
use Symfony\Contracts\Cache\CacheInterface;

class JsonRpcClientTest extends TestCase
{
    private JsonRpcClient $client;

    protected function setUp(): void
    {
        $client = HttpClient::create();
        $cache = $this->createMock(CacheInterface::class);
        $cache
            ->method('get')
            ->willReturn('token');

        $this->client = new JsonRpcClient(
            $client, $cache, 'http://webserver-activity:123', 'user', 'pass', 'key', 100
        );
    }

    public function testInstance(): void
    {
        $this->assertInstanceOf(JsonRpcClient::class, $this->client);
    }

    public function testAuthException(): void
    {
        $this->expectException(JsonRpcClientException::class);
        $this->client->auth();
    }

    public function testRequestException(): void
    {
        $dto = new JsonRpcAddClickDto('/path/1', new \DateTime());

        $this->expectException(JsonRpcClientException::class);
        $data = $this->client->rpc($dto);
        $this->assertNull($data);
    }
}
