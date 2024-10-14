<?php

namespace App\Services\External;

use App\Services\Contracts\TelegramServiceInterface;

class Telegram implements TelegramServiceInterface
{
    public function send($messages)
    {
    // кролик
        $apiTokenPublic = config('telegram.token_public');
        $data = [
            'chat_id' => config('telegram.chat_id_public'),
            'text' => $messages
        ];
      // dd("https://api.telegram.org/bot$apiTokenPublic/sendMessage?" . http_build_query($data));
        file_get_contents("https://api.telegram.org/bot$apiTokenPublic/sendMessage?" . http_build_query($data));
    }
}
