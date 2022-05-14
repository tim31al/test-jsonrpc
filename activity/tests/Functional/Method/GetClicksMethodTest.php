<?php

/*
 *
 * (c) Alexandr Timofeev <tim31al@gmail.com>
 *
 */

namespace App\Tests\Functional\Method;

use App\Tests\HelperTrait;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class GetClicksMethodTest extends WebTestCase
{
    use HelperTrait;

    public function testGetClicksLimit2(): void
    {
        $data = [
            'jsonrpc' => '2.0',
            'method' => 'get-clicks',
            'id' => 1,
        ];

        $client = $this->createAuthenticatedClient();
        $data['params'] = [
            'limit' => 2,
            'offset' => 0,
        ];

        $client->request(
            'POST',
            '/',
            [],
            [],
            ['CONTENT_TYPE' => 'application/json'],
            json_encode($data)
        );

        $content = $this->getRequestData($client);

        $this->assertCount(2, $content['result']['clicks']);
        $this->assertSame(9, $content['result']['countAll']);
    }

    public function testGetClicksLimit2Offset2(): void
    {
        $data = [
            'jsonrpc' => '2.0',
            'method' => 'get-clicks',
            'id' => 1,
        ];

        $client = $this->createAuthenticatedClient();
        $data['params'] = [
            'limit' => 2,
            'offset' => 2,
        ];

        $client->request(
            'POST',
            '/',
            [],
            [],
            ['CONTENT_TYPE' => 'application/json'],
            json_encode($data)
        );

        $content = $this->getRequestData($client);

        $this->assertCount(2, $content['result']);

        $this->assertSame('/path/7', $content['result']['clicks'][0]['url'],);
        $this->assertSame(2, $content['result']['clicks'][0]['counter']);
        $this->assertSame('/path/6', $content['result']['clicks'][1]['url']);
    }

    /**
     * @dataProvider clickDataProvider
     */
    public function testValidateParams(array $params, string $expectedKey): void
    {
        $client = $this->createAuthenticatedClient();

        $data = array_merge([
            'jsonrpc' => '2.0',
            'method' => 'get-clicks',
            'id' => 1,
        ], $params);

        $client->request(
            'POST',
            '/',
            [],
            [],
            ['CONTENT_TYPE' => 'application/json'],
            json_encode($data)
        );
        $this->assertResponseIsSuccessful();

        $data = $this->getRequestData($client);

        $this->assertArrayHasKey($expectedKey, $data);
    }

    public function clickDataProvider(): array
    {
        return [
            [['params' => ['limit' => 'path']], 'error'],
            [['params' => ['limit' => 'str', 'offset' => 10]], 'error'],
            [['params' => ['limit' => 101, 'offset' => 2]], 'error'],
            [['params' => ['limit' => 0, 'offset' => 2]], 'error'],
            [['params' => []], 'result'],
            [['params' => ['limit' => 10]], 'result'],
            [['params' => ['offset' => 10]], 'result'],
            [['params' => ['limit' => 5, 'offset' => 2]], 'result'],
        ];
    }
}
