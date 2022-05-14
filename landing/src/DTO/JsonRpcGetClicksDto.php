<?php

/*
 * (c) Alexandr Timofeev <tim31al@gmail.com>
 */

namespace App\DTO;

class JsonRpcGetClicksDto extends JsonRpcDto
{
    public const METHOD = 'get-clicks';

    public function __construct(int $limit, int $offset, int $id)
    {
        $params = [
            'limit' => $limit,
            'offset' => $offset,
        ];

        parent::__construct(static::METHOD, $params, $id);
    }
}
