<?php

/*
 * (c) Alexandr Timofeev <tim31al@gmail.com>
 */

use App\Service\Exception\JsonRpcClientAuthException;
use App\Service\Exception\JsonRpcClientException;

require_once dirname(__DIR__).'/vendor/autoload.php';

$cl = \Symfony\Component\HttpClient\HttpClient::create();

$token = 'eyJ0eXAiOiJKV1QiLCJhbGciOiJSUzI1NiJ9.eyJpYXQiOjE2NTIzNDA3MzUsImV4cCI6MTY1MjM0NDMzNSwicm9sZXMiOlsiUk9MRV9VU0VSIl0sInVzZXJuYW1lIjoidXNlcjFAZXhhbXBsZS5jb20ifQ.Ou4fZy2QeeKLh_tPTKWITKI6yyP_zGbCrpkUGWqOc0IaB-XQPtfRAnErFwyzqN8AGAyVTUbUx-M2hQUSJsZVH0BME3nPADF7Cf52rKQZGMNJ1FPr1kJskb0EJZy1TJmKaxZFtrh-E0_vthhUN3hunMlnF6Ll-7A43okHWf0RQKBOQucmjX0OgpNE_7sf1cfIeWJ1C-rWuqMVDsJEbvvqrRbs4hUbyqqIye5RNGBYoXRBvzbP3r2W9vgmGTHX7l4U5KFDB5SHwIsdWWRNvcdmSpUUiiHQPKmFJmq9tHm0opLaE6We7yGudQmBtk26GNIiKRxx7bqghMjhDi3jxkF8Zg';

$dto = new \App\DTO\JsonRpcAddClickDto('/data/1', new DateTime());

function getToken($cl): string
{
    $client = new \App\Service\JsonRpcClient(
        $cl, 'http://webserver-activity', 'user@example.com', 'user'
    );

    return $client->auth();
}

/**
 * @throws \App\Service\Exception\JsonRpcClientAuthException
 * @throws \App\Service\Exception\JsonRpcClientException
 */
function getData($cl, $token, $dto): ?array
{
    $client = new \App\Service\JsonRpcClient(
        $cl, 'http://webserver-activity', 'user@example.com', 'user'
    );

    return $client->rpc($token, $dto);
}

$arr = [];

try {
    $arr = getData($cl, $token, $dto);
} catch (JsonRpcClientException $e) {
    echo 'JsonRpcClientExc', \PHP_EOL;
    echo $e->getMessage(), \PHP_EOL;
} catch (JsonRpcClientAuthException $e) {
    echo $e->getMessage(), \PHP_EOL;

    try {
        $token = getToken($cl);
        $arr = getData($cl, $token, $dto);
    } catch (JsonRpcClientException $exception) {
        echo 'client exc: '.$exception->getMessage(), \PHP_EOL;
    } catch (JsonRpcClientAuthException $e) {
    }
}

var_dump($arr);
