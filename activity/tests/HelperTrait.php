<?php

/*
 *
 * (c) Alexandr Timofeev <tim31al@gmail.com>
 *
 */

namespace App\Tests;

use Symfony\Bundle\FrameworkBundle\KernelBrowser;

trait HelperTrait
{
    protected function getRequestData(KernelBrowser $client): array
    {
        return json_decode($client->getResponse()->getContent(), true);
    }

    protected function createAuthenticatedClient($email = 'user@example.com', $password = 'user'): KernelBrowser
    {
        $client = static::createClient();
        $client->request(
            'POST',
            '/auth',
            [],
            [],
            ['CONTENT_TYPE' => 'application/json'],
            json_encode([
                'email' => $email,
                'password' => $password,
            ]),
        );

        $data = json_decode($client->getResponse()->getContent(), true);

        $client->setServerParameter('HTTP_Authorization', sprintf('Bearer %s', $data['token']));

        return $client;
    }
}
