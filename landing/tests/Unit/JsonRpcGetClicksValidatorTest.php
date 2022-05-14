<?php

/*
 * (c) Alexandr Timofeev <tim31al@gmail.com>
 */

namespace App\Tests\Unit;

use App\Service\Exception\JsonRpcErrorException;
use App\Service\JsonRpcGetClicksValidator;
use PHPUnit\Framework\TestCase;
use Psr\Log\LoggerInterface;

class JsonRpcGetClicksValidatorTest extends TestCase
{
    private const ERROR_DATA = [
        'jsonrpc' => '2.0',
        'id' => 1,
        'error' => [
            'code' => -32000,
        ],
    ];

    private JsonRpcGetClicksValidator $validator;
    private LoggerInterface $logger;

    protected function setUp(): void
    {
        $logger = $this->createMock(LoggerInterface::class);
        $logger->method('error');

        $this->logger = $logger;

        $this->validator = new JsonRpcGetClicksValidator($logger);
        parent::setUp();
    }

    public function testInstance(): void
    {
        $this->assertInstanceOf(JsonRpcGetClicksValidator::class, $this->validator);
    }

    public function testExpectException(): void
    {
        $this->expectException(JsonRpcErrorException::class);
        $this->validator->validate(static::ERROR_DATA);
        $this->logger->expects($this->once())
            ->method('error');
    }

    public function testError(): void
    {
        $error = false;
        $message = null;

        try {
            $this->validator->validate(static::ERROR_DATA);
        } catch (JsonRpcErrorException $e) {
            $error = true;
            $message = $e->getMessage();
        }

        $this->assertTrue($error);
        $this->assertSame(JsonRpcGetClicksValidator::ERROR_MESSAGE, $message);
    }

    public function testValid(): void
    {
        $data = [
            'jsonrpc' => '2.0',
            'id' => 1,
            'result' => [
                'clicks' => [
                    ['url' => '/', 'counter' => 1, 'lastVisit' => '2000-01-01 01:01:01'],
                    ['url' => '/path', 'counter' => 11, 'lastVisit' => '2001-01-01 01:01:01'],
                ],
                'countAll' => 2,
            ],
        ];

        $error = false;

        try {
            $this->validator->validate($data);
            $this->logger->expects($this->never())
                ->method('error');
        } catch (JsonRpcErrorException $e) {
            $error = true;
            echo $e->getMessage();
        }

        $this->assertFalse($error);
    }
}
