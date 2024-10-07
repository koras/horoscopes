<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\ValidationException;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

use App\Services\Auth\Contracts\RegisterServiceInterface;
use App\Models\User;

use App\Services\Auth\Contracts\LoginServiceInterface;


class AuthController extends Controller
{
    // создавать работу на много лучше, чем искать работу

    public function register1Step(Request $request, RegisterServiceInterface $registerService)
    {
        try {
            $request->validate([
                'name' => 'required|string|max:255',
                'email' => 'required|string|email|max:255|unique:users',
                'phone' => 'required|string|unique:users',
                //    'password' => 'required|string|min:8|confirmed',
                 'password' => 'required|string|min:8',
            ]);
        } catch (ValidationException $e) {
            Log::error('Validation Error', $e->errors());
            return response()->json(['errors' => $e->errors()], 422);
        }
        $result = $registerService->register($request->name, $request->phone, $request->email, $request->password);
        return response()->json($result);
    }



    public function register2Confirm(Request $request, RegisterServiceInterface $registerService)
    {

    }



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
