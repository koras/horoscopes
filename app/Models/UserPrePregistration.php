<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Laravel\Sanctum\HasApiTokens;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;

class UserPrePregistration extends Model
{
    use HasFactory, HasApiTokens, Notifiable;


    protected $table = 'pre_registration';


    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'text',
        'hash',
    ];

    public function getInfo($token)
    {
        return static::where(["hash" => $token])
            ->first();
    }


    public function addItem($data, $hash)
    {
      return  UserPrePregistration::create(
            [
                'text' => $data,
                'hash' => $hash,
            ]
        );
    }

}
