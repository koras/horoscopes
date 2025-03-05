<?php

namespace App\Enums;

enum SmsRequestStatus: string
{
    case New = 'new';
    case Confirmed = 'confirmed';
    case Invalid = 'invalid';
}
