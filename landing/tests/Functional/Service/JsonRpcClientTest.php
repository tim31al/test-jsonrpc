<?php

/*
 * (c) Alexandr Timofeev <tim31al@gmail.com>
 */

namespace App\Tests\Functional\Service;

use App\DTO\JsonRpcAddClickDto;
use App\Service\JsonRpcClient;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

class JsonRpcClientTest extends KernelTestCase
{
    public function testInstance(): void
    {
        $kernel = self::bootKernel();

        $service = static::getContainer()->get(JsonRpcClient::class);
        $this->assertInstanceOf(JsonRpcClient::class, $service);
    }

    public function testLogin(): void
    {
        self::bootKernel();

        /** @var JsonRpcClient $service */
        $service = static::getContainer()->get(JsonRpcClient::class);
        $token = $service->auth();

        $this->assertNotEmpty($token);
    }

    public function testRpcWithoutId(): void
    {
        self::bootKernel();

        /** @var JsonRpcClient $service */
        $service = static::getContainer()->get(JsonRpcClient::class);

        $dto = new JsonRpcAddClickDto('/path/1', new \DateTime());

        $data = $service->rpc($dto);
        $this->assertNull($data);
    }

    public function testRpcWithId(): void
    {
        self::bootKernel();

        /** @var JsonRpcClient $service */
        $service = static::getContainer()->get(JsonRpcClient::class);

        $dto = new JsonRpcAddClickDto('/path/1', new \DateTime(), 1);

        $data = $service->rpc($dto);

        $this->assertIsArray($data);
        $this->assertArrayHasKey('result', $data);
    }
}
