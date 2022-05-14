<?php

/*
 * (c) Alexandr Timofeev <tim31al@gmail.com>
 */

namespace App\Message;

class ClickMessage
{
    private string $url;
    private string $datetime;

    public function __construct(string $url)
    {
        $this->url = $url;
        $this->datetime = (new \DateTime())->format('Y-m-d H:i:s');
    }

    public function getUrl(): string
    {
        return $this->url;
    }

    public function getDatetime(): string
    {
        return $this->datetime;
    }
}
