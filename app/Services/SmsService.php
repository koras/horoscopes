<?php

namespace App\Services;

use App\Services\Contracts\TelegramServiceInterface;
use Illuminate\Support\Str;
use App\Dto\SmsRequestCreateDto;
use App\Enums\SmsRequestType;
use App\Services\Contracts\SmsServiceInterface;
use App\Services\Contracts\SmsRequestServiceInterface;
use App\Models\Contracts\SmsRequestInterface;
use Illuminate\Support\Facades\Storage;

class SmsService implements SmsServiceInterface
{

    const TYPE_NOTIFICATION = [
        'registration' => 60,
        'restore' => 60,
        'order' => 60,
        'change_phone' => 60,
        'cherry' => 60,
        'Cherry' => 60,
        'cherryFunds'=>60
    ];

    public function __construct(
        // @todo потом это переедет в адаптер
        private readonly SmsRequestInterface   $smsRequest,
        private readonly TelegramServiceInterface $telegramService
    )
    {
    }


    /**
     * Проставя отправка смс
     * @param  $user
     * @param string $message
     * @param string $type
     * @return void
     */
    public function send($user, string $type, string $message, ?string $phone = null): array
    {
        $hash = static::getHash();
        $code = $this->generateCode(4);
        $message = $this->getMessage($type, $code, $message);
        $this->smsRequest->blockedOldRequests($type, $user->id);
        $phone = $phone ?? $user->phone;


        $this->smsRequest->saveSms(
            new SmsRequestCreateDto(
                code: $code,
                userId: $user->id,
                phone: $phone,
                message: $message,
                confirmedHash: $hash,
                type: $type,
                expiredTime: self::TYPE_NOTIFICATION[$type]
            )
        );

        $externalId = $this->strategy($phone, $message);
        return [
            'externalId' => $externalId,
            'expiredTime' => self::TYPE_NOTIFICATION[$type],
            'confirmed_hash' => $hash
        ];
    }

    /**
     * Смена номера телефона
     * @param  $user
     * @param string $message
     * @param string $type
     * @return void
     */
    public function changePhone($userId, $phone, string $type, string $message): array
    {

        $hash = static::getHash();
        $code = $this->generateCode(4);
        $message = $this->getMessage($type, $code, $message);
        $this->smsRequest->blockedOldRequests($type, $userId);
        $this->smsRequest->saveSms(
            new SmsRequestCreateDto(
                code: $code,
                userId: $userId,
                phone: $phone,
                message: $message,
                confirmedHash: $hash,
                type: $type,
                expiredTime: self::TYPE_NOTIFICATION[$type]
            )
        );
        $externalId = $this->strategy($phone, $message);
        return [
            'externalId' => $externalId,
            'expiredTime' => self::TYPE_NOTIFICATION[$type],
            'confirmed_hash' => $hash
        ];
    }

    private function strategy($phone, $message): int
    {
        $externalId = 0;
        switch (config('app.env', 'testing')) {
            case "develop":
                {
                 //   echo "$phone: $message";
                 //   $externalId = rand(100000, 200000);
                }
               break;
            case "dev": {
                $this->telegramService->send("$phone: $message");
            }
                break;
            case "testing":
                {
                    // для автотестов через файл
                    Storage::disk('local')->put('test/code_phone.txt', "$phone: $message");
                    $externalId = rand(100000, 200000);
                }
                break;
            case "production":
                {
                    // через смс почтарь
                    $externalId = 0;
                }
                break;
            default:{
                $this->telegramService->send("$phone: $message");
            }
        }
        return $externalId;
    }


    private function generateCode($number = 4): string
    {
        return rand(1000, 9999);
    }

    private function getMessage($type, $code, $message): string
    {
        switch ($type) {
            case  'registration' :
                $message = 'Код подтверждения ' . $code;
                break;
            case  'change_phone' :
                {
                    $message = 'Код подтверждения ' . $code;
                }
                break;
            case  'restore' :
                $message = "Код подтверждения $code";
                break;
            case  'order' :
                {
                    $message = "Код подтверждения $code";
                }
            default:{
                $message = "Код подтверждения $code";
            }
        }
        return $message;
    }

    /**
     * @param $user
     * @param $type
     * @param $code
     * @param $hash
     * @return bool
     */
    public function confirmed($user, $type, $code): bool
    {
        return $this->smsRequest->confirmed($user->id, $type, $code);
    }

    /**
     * @return string
     */
    public static function getHash(): string
    {
        return md5(Str::random());
    }

}
