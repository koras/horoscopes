<?php

namespace App\Enums;


enum SmsRequestType: string
{
    case Registration = 'registration';
    case Login = 'restore';

    public static function values(): array
    {
        return array_column(self::cases(), 'value');
    }
}
