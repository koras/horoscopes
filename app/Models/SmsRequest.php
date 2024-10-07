<?php

namespace App\Models;
use App\Models\Contracts\SmsRequestInterface;
use App\Dto\SmsRequestCreateDto;
use App\Enums\SmsRequestStatus;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasOne;

class SmsRequest extends Model implements SmsRequestInterface
{

    use HasFactory;

    protected $casts = [
        'confirmed_date' => 'datetime:Y-m-d H:i:s',
        'expired_date' => 'datetime:Y-m-d H:i:s',
        'sent_status_date' => 'datetime:Y-m-d H:i:s',
        'created_at' => 'datetime:Y-m-d H:i:s',
        'updated_at' => 'datetime:Y-m-d H:i:s',
    ];

    protected $fillable = [
        'code',
        'user_id',
        'phone',
        'status',
        'confirmed_date',
        'expired_date',
        'message',
        'external_id',
        'sent_status',
        'sent_status_date',
        'type',
    ];

    public function user(): HasOne
    {
        return $this->hasOne(User::class, 'id', 'user_id');
    }

    /**
     * @param string $code
     * @param int $userId
     * @param string $type
     * @return SmsRequest|null
     */
    public function getCode(string $code, int $userId,  string $type): ?SmsRequest
    {
        return  SmsRequest::where('user_id', $userId)->with('user')
            ->where('created_at', '>=', now()->subMinutes(5)->toDateTimeString())
            ->where('status', SmsRequestStatus::New->value)
            ->where('type', $type)
            ->where('code', $code)
            ->orderBy('id', 'desc')
            ->first();
    }

    /**
     * @param SmsRequest $smsRequest
     * @return void
     */
    public function setConfirmed(SmsRequest $smsRequest): void
    {
        $smsRequest->status = SmsRequestStatus::Confirmed->value;
        $smsRequest->confirmedDate = now();
        $smsRequest->save();
    }
    /**
     *
     *
     * @param SmsRequestCreateDto $smsDto
     * @return SmsRequest
     */
    public function saveSms(SmsRequestCreateDto $smsDto): SmsRequest
    {
        return SmsRequest::create([
            'code' => $smsDto->code,
            'user_id' => $smsDto->userId,
            'phone' => $smsDto->phone,
            'status' => SmsRequestStatus::New->value,
            'expired_date' => now()->addSeconds($smsDto->expiredTime),
            'confirmed_hash'=>$smsDto->confirmedHash,
            'message' => $smsDto->message,
            'type' => $smsDto->type,
        ]);
    }
    /**
     * @param string $type
     * @param int $userId
     * @return void
     */
    public function blockedOldRequests(string $type, int $userId): void
    {
        SmsRequest::where(
            [
                'type' => $type,
                'user_id' => $userId,
                'status' => SmsRequestStatus::New->value
            ]
        )
            ->update([
                'status' => SmsRequestStatus::Invalid->value
            ]);
    }

    /**
     * @param int $userId
     * @param string $type
     * @param string $code
     * @return bool
     */
    public function confirmed(int $userId, string $type, string $code): bool
    {

        return  SmsRequest::where(
            [
                'type' => $type,
                'code' =>  $code,
                'user_id' => $userId,
                'status' => SmsRequestStatus::New->value
            ]
        )
            ->update([
                'status' => SmsRequestStatus::Confirmed->value
            ]);
    }
}
