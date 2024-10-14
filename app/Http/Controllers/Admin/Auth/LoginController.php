<?php

namespace App\Http\Controllers\Admin\Auth;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\ValidationException;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

use App\Services\Auth\Contracts\RegisterServiceInterface;
use App\Models\User;
use App\Http\Controllers\Controller;

use App\Services\Auth\Contracts\LoginServiceInterface;


class LoginController extends Controller
{




    // Вход
    public function login(Request $request, LoginServiceInterface $loginService)
    {
        try {
            $validatedData = $request->validate([
                'email' => 'required|string|email',
                'password' => 'required|string',
            ]);
            // Дальнейшая логика...
        } catch (ValidationException $e) {
            Log::error('Validation Error', $e->errors());
            return response()->json(['errors' => $e->errors()], 422);
        }

        $result = $loginService->login($request->phone, $request->password);
        return response()->json($result);
    }

    // Выход
    public function logout(Request $request)
    {
        $request->user()->tokens()->delete();

        return response()->json([
            'message' => 'Logged out'
        ]);
    }
}
