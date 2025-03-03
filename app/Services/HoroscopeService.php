<?php

namespace App\Services;

use App\Contracts\Services\HoroscopeServiceInterface;


class HoroscopeService implements HoroscopeServiceInterface
{
    private const SODIAC = [
        'aquarius',
        'aries',
        'cancer',
        'capricorn',
        'gemini',
        'leo',
        'libra',
        'pisces',
        'sagittarius',
        'scorpio',
        'taurus',
        'virgo',
    ];

    public function getCurrent()
    {
            return [123123];
    }

}
