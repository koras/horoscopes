<?php

namespace App\Services\Contracts;

interface TelegramServiceInterface
{

    public function send($messages);
}
