<?php

namespace App\Services\Auth;

use App\Models\User;
use App\Models\UserPrePregistration;
use App\Services\Contracts\SmsServiceInterface;
use Illuminate\Support\Str;
use App\Services\Auth\Contracts\RegisterServiceInterface;
use Illuminate\Support\Facades\Hash;

class RegisterService implements RegisterServiceInterface
{
    private $preRegistration;

    private $user;
    private $smsService;

    public function __construct(SmsServiceInterface $smsService)
    {
        $this->smsService = $smsService;
        $this->preRegistration = new UserPrePregistration();
        $this->user = new User();

    }

    public function StepOne($name, $phone, $email, $password)
    {
        // капчу проверяем

        $data = [
            'name' => $name,
            'phone' => $phone,
            'email' => $email,
            'password' => Hash::make($password),
            'status' => 1,
        ];
        $randomHash = Hash::make(Str::random(10));
        // сохраняем данные
        $this->preRegistration->addItem(serialize($data), $randomHash);
        // отправляем код
        $this->smsService->send(null, 'registration', "Регистрация на платформе: ", $phone, $randomHash);
        return [
            'data' => [
                'token' => $randomHash,
                'token_type' => 'hash',
            ],
            'error' => false
        ];
    }


    public function register($token, $code)
    {
        $confirm = $this->smsService->getCodeHash( $code, $token);
       // dd($code,$confirm);
        $userInfo = $this->preRegistration->getInfo($token);

        if( $confirm && $userInfo){
           $info = unserialize($userInfo->text);
        //   dd($info);
            $user = $this->user->createUser($info['name'],$info['phone'],$info['email'],$info['password'], 1);
           // dd($user );
            $token = $user->createToken('auth_token')->plainTextToken;
            return [
                'access_token' => $token,
                'token_type' => 'Bearer',
                'user' => $user,
                'error'=> false,
            ];
        }
        return [
            'error'=> true,
            'text' => 'data not correct',
        ];
    }

}
