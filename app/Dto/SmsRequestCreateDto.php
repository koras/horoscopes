<?php

namespace App\Dto;

class SmsRequestCreateDto
{
    public function __construct(
        public readonly string $code,
        public readonly int $userId,
        public readonly string $phone,
        public readonly string $message,
        public readonly string $confirmedHash,
        public readonly string $type,
        public readonly int $expiredTime,
    ) {
    }
}
