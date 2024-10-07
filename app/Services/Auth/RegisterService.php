<?php

namespace App\Services\Auth;

use App\Models\User;
use App\Services\Auth\Contracts\RegisterServiceInterface;
use Illuminate\Support\Facades\Hash;

class RegisterService implements RegisterServiceInterface
{

    public function register($name, $phone, $email, $password)
    {
        $user = User::create([
            'name' => $name,
            'phone' => $phone,
            'email' => $email,
            'password' => Hash::make($password),
            'status' => 1,
        ]);

        $token = $user->createToken('auth_token')->plainTextToken;

        return [
            'access_token' => $token,
            'token_type' => 'Bearer',
        ];
    }

}
