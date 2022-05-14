<?php

/*
 * (c) Alexandr Timofeev <tim31al@gmail.com>
 */

namespace App\Tests\Unit;

use App\Service\Exception\JsonRpcErrorException;
use App\Service\JsonRpcErrorValidator;
use PHPUnit\Framework\TestCase;

class JsonRpcErrorValidatorTest extends TestCase
{
    private JsonRpcErrorValidator $validator;

    protected function setUp(): void
    {
        $this->validator = new JsonRpcErrorValidator();
        parent::setUp();
    }

    public function testInstance(): void
    {
        $this->assertInstanceOf(JsonRpcErrorValidator::class, $this->validator);
    }

    public function testNotErrorEmptyData(): void
    {
        $data = null;

        $error = false;
        try {
            $this->validator->validate($data);
        } catch (JsonRpcErrorException $e) {
            $error = true;
        }

        $this->assertFalse($error);
    }

    public function testNotError(): void
    {
        $data = [
            'jsonrpc' => '2.0',
            'id' => 1,
            'result' => [
                'name' => 'name',
            ],
        ];

        $error = false;
        try {
            $this->validator->validate($data);
        } catch (JsonRpcErrorException $e) {
            $error = true;
        }

        $this->assertFalse($error);
    }

    public function testError(): void
    {
        $data = [
            'jsonrpc' => '2.0',
            'id' => 1,
            'error' => [
                'code' => -32000,
                'message' => 'Invalid arg',
            ],
        ];

        $error = false;
        $message = null;

        try {
            $this->validator->validate($data);
        } catch (JsonRpcErrorException $e) {
            $error = true;
            $message = $e->getMessage();
        }

        $this->assertTrue($error);
        $this->assertSame('code: -32000, message: Invalid arg', $message);
    }

    public function testErrorWithData(): void
    {
        $data = [
            'jsonrpc' => '2.0',
            'id' => 1,
            'error' => [
                'code' => -32000,
                'message' => 'Invalid arg',
                'data' => [
                    'errors' => [],
                ],
            ],
        ];

        $error = false;
        $message = null;

        try {
            $this->validator->validate($data);
        } catch (JsonRpcErrorException $e) {
            $error = true;
            $message = $e->getMessage();
        }

        $this->assertTrue($error);
        $this->assertSame('code: -32000, message: Invalid arg', $message);
    }
}
