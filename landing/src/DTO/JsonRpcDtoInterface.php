<?php

/*
 * (c) Alexandr Timofeev <tim31al@gmail.com>
 */

namespace App\DTO;

interface JsonRpcDtoInterface extends \JsonSerializable
{
    public function __toString(): string;
}
