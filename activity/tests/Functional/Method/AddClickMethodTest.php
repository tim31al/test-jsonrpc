<?php

/*
 *
 * (c) Alexandr Timofeev <tim31al@gmail.com>
 *
 */

namespace App\Tests\Functional\Method;

use App\Tests\HelperTrait;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\HttpFoundation\Response;

class AddClickMethodTest extends WebTestCase
{
    use HelperTrait;

    public function testUnauthorized(): void
    {
        $client = static::createClient();
        $client->request('POST', '/');

        $this->assertResponseStatusCodeSame(Response::HTTP_UNAUTHORIZED);
    }

    public function testAddManyClicks(): void
    {
        $data = [
            'jsonrpc' => '2.0',
            'method' => 'add-click',
            'id' => 1,
        ];

        $client = $this->createAuthenticatedClient();

        foreach (range(1, 9) as $i) {
            $data['params'] = [
                    'url' => '/news',
                    'date' => '2022-05-10 05:05:0'.$i,
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

            $this->assertSame($content['result']['counter'], $i);
        }
    }

    /**
     * @dataProvider clickDataProvider
     */
    public function testValidateParams(array $params, string $expectedKey): void
    {
        $client = $this->createAuthenticatedClient();

        $data = array_merge([
            'jsonrpc' => '2.0',
            'method' => 'add-click',
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
            [['params' => ['url' => '/path']], 'error'],
            [['params' => ['url' => 1]], 'error'],
            [['params' => ['url' => '', 'date' => '2022-05-12 10:05:02']], 'error'],
            [['params' => ['url' => '/path', 'date' => 2]], 'error'],
            [['params' => ['url' => '/path', 'date' => '2022-05-12']], 'error'],
            [['params' => ['url' => '/', 'date' => '2022-05-12 10:05:02']], 'result'],
            [['params' => ['url' => '/path', 'date' => '2022-05-12 10:05:02']], 'result'],
        ];
    }
}
