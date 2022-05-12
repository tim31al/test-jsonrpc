<?php

/*
 *
 * (c) Alexandr Timofeev <tim31al@gmail.com>
 *
 */

namespace App\Tests\Functional;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class JwtAuthenticationAuthTokenTest extends WebTestCase
{
    public function testLoginToken(): void
    {
        $client = static::createClient();
        $content = json_encode(['email' => 'user@example.com', 'password' => 'user']);

        $client->request(
            'POST',
            '/auth',
            [],
            [],
            ['CONTENT_TYPE' => 'application/json'],
            $content
        );

        $data = json_decode($client->getResponse()->getContent(), true);
        $this->assertArrayHasKey('token', $data);
    }
}
