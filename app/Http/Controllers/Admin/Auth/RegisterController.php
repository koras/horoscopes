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


class RegisterController extends Controller
{
    // создавать работу на много лучше, чем искать работу

    public function stepOne(Request $request, RegisterServiceInterface $registerService)
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


        $result = $registerService->StepOne($request->name, $request->phone, $request->email, $request->password);
        return response()->json($result);
    }



    public function stepTwo(Request $request, RegisterServiceInterface $registerService)
    {
        $result = $registerService->register($request->token, $request->code);
        return response()->json($result);
    }

}
