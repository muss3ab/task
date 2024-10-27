<?php

namespace App\Http\Controllers;


use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\ValidationException;


class AuthController extends Controller
{
    public function register(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'phone' => 'required|string|unique:users,phone',
            'password' => 'required|string|min:8',
        ]);

        $user = User::create([
            'name' => $request->name,
            'phone' => $request->phone,
            'password' => Hash::make($request->password),
            'verification_code' => str_pad(random_int(0, 999999), 6, '0', STR_PAD_LEFT),
        ]);

        // Log the verification code (in real app, you'd send via SMS)
        Log::info("Verification code for user {$user->phone}: {$user->verification_code}");

        $token = $user->createToken('auth_token')->plainTextToken;

        return response()->json([
            'user' => $user,
            'token' => $token,
            'message' => 'Please verify your phone number'
        ], 201);
    }

    public function login(Request $request)
    {
        $request->validate([
            'phone' => 'required|string',
            'password' => 'required|string',
        ]);

        $user = User::where('phone', $request->phone)->first();

        if (! $user || ! Hash::check($request->password, $user->password)) {
            throw ValidationException::withMessages([
                'phone' => ['The provided credentials are incorrect.'],
            ]);
        }

        if (! $user->phone_verified_at) {
            throw ValidationException::withMessages([
                'phone' => ['Please verify your phone number first.'],
            ]);
        }

        $token = $user->createToken('auth_token')->plainTextToken;

        return response()->json([
            'user' => $user,
            'token' => $token
        ]);
    }

    public function verify(Request $request)
    {
        $request->validate([
            'phone' => 'required|string',
            'verification_code' => 'required|string|size:6',
        ]);

        $user = User::where('phone', $request->phone)
            ->where('verification_code', $request->verification_code)
            ->first();

        if (! $user) {
            throw ValidationException::withMessages([
                'verification_code' => ['The verification code is invalid.'],
            ]);
        }

        $user->phone_verified_at = now();
        $user->verification_code = null;
        $user->save();

        return response()->json([
            'message' => 'Phone number verified successfully'
        ]);
    }
}
