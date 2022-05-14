<?php

/*
 * (c) Alexandr Timofeev <tim31al@gmail.com>
 */

namespace App\DTO;

class JsonRpcDto implements JsonRpcDtoInterface
{
    public const JSON_RPC_VERSION = '2.0';

    private string $method;
    private array $params;
    private ?int $id;

    public function __construct(string $method, array $params, int $id = null)
    {
        $this->method = $method;
        $this->params = $params;
        $this->id = $id;
    }

    public function __toString(): string
    {
        return json_encode($this);
    }

    public function jsonSerialize(): array
    {
        $data = [
            'jsonrpc' => static::JSON_RPC_VERSION,
            'method' => $this->method,
            'params' => $this->params,
        ];

        if (null !== $this->id) {
            $data['id'] = $this->id;
        }

        return $data;
    }
}
