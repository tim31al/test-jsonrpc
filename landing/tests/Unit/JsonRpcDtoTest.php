<?php

/*
 * (c) Alexandr Timofeev <tim31al@gmail.com>
 */

namespace App\Tests\Unit;

use App\DTO\JsonRpcDto;
use PHPUnit\Framework\TestCase;

class JsonRpcDtoTest extends TestCase
{
    public function testCreate(): void
    {
        $dto = new JsonRpcDto('run', ['name']);
        $this->assertInstanceOf(JsonRpcDto::class, $dto);
    }

    public function testJsonWithoutId(): void
    {
        $dto = new JsonRpcDto('run', ['name' => 'name']);
        $str = json_encode($dto);

        $this->assertSame(
            '{"jsonrpc":"2.0","method":"run","params":{"name":"name"}}',
            $str
        );
    }

    public function testStringWithId(): void
    {
        $dto = new JsonRpcDto('run', ['name' => 'name', 'age' => 2], 1);
        $str = (string) $dto;

        $this->assertSame(
            '{"jsonrpc":"2.0","method":"run","params":{"name":"name","age":2},"id":1}',
            $str
        );
    }
}
