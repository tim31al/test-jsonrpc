<?php

/*
 * (c) Alexandr Timofeev <tim31al@gmail.com>
 */

namespace App\DTO;

use DateTime;

class JsonRpcAddClickDto extends JsonRpcDto
{
    public const METHOD = 'add-click';

    public function __construct(string $url, DateTime $date, int $id = null)
    {
        $params = [
            'url' => $url,
            'date' => $date->format('Y-m-d H:i:s'),
        ];

        parent::__construct(static::METHOD, $params, $id);
    }
}
