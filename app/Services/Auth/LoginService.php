<?php

namespace App\Services\Auth;

use App\Models\User;
use App\Services\Auth\Contracts\LoginServiceInterface;
use Illuminate\Support\Facades\Hash;

class LoginService implements LoginServiceInterface
{
    public function login($email, $password)
    {
        $user = User::where(['email' => $email])->first();

        if (!$user || !Hash::check($password, $user->password)) {
            return response()->json([
                'error' => true,
                'test' => 'The provided credentials are incorrect.',
            ]);
        }

        return [
            'access_token' => $user->createToken('auth_token')->plainTextToken,
            'token_type' => 'Bearer',
        ];
    }

}
