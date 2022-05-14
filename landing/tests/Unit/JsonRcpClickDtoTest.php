<?php

/*
 * (c) Alexandr Timofeev <tim31al@gmail.com>
 */

namespace App\Tests\Unit;

use App\DTO\JsonRpcAddClickDto;
use PHPUnit\Framework\TestCase;

class JsonRcpClickDtoTest extends TestCase
{
    public function testCreate(): void
    {
        $dto = new JsonRpcAddClickDto('run', new \DateTime());
        $this->assertInstanceOf(JsonRpcAddClickDto::class, $dto);
    }

    public function testStringWithoutId(): void
    {
        $url = '/path/1';
        $date = new \DateTime('-1 day');

        $dto = new JsonRpcAddClickDto($url, $date);
        $str = (string) $dto;

        $expected = sprintf(
            '{"jsonrpc":"2.0","method":"add-click","params":{"url":"\/path\/1","date":"%s"}}',
            $date->format('Y-m-d H:i:s')
        );

        $this->assertSame($expected, $str);
    }

    public function testStringWithId(): void
    {
        $date = new \DateTime('-1 day');

        $dto = new JsonRpcAddClickDto('/news/234-title', $date, 22);
        $str = (string) $dto;

        $expected = sprintf(
            '{"jsonrpc":"2.0","method":"add-click","params":{"url":"\/news\/234-title","date":"%s"},"id":22}',
            $date->format('Y-m-d H:i:s')
        );

        $this->assertSame($expected, $str);
    }
}
