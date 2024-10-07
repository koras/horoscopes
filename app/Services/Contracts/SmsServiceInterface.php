<?php

namespace App\Contracts\Services;

use App\Contracts\Models\UserInterface;

interface SmsServiceInterface
{

    /**
     * @param  $user
     * @param string $message
     * @param string $type
     * @return int
     */
    public function send($user, string $type, string $message, ?string $phone = null): array;



    /**
     * @param $user
     * @param $type
     * @param $code
     * @param $hash
     * @return bool
     */
    public function confirmed($user, $type, $code): bool;
}
