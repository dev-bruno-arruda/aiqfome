<?php

namespace App\Services;

use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;
use Illuminate\Support\Facades\Auth;

class AuthService 
{
    public function login(string $email, string $password): array
    {
        $user = User::where('email', $email)->first();

        if (!$user || !Hash::check($password, $user->password)) {
            throw ValidationException::withMessages([
                'email' => ['The provided credentials are incorrect.'],
            ]);
        }
        $token = $user->createToken(env('APP_NAME'))->plainTextToken;
        $role = $user->role;

        return ['token' => $token, 'role' => $role];
    }
    public function logout($request): void
    {
        $request->user()->currentAccessToken()->delete();
    }

    public function getUser($request): User
    {
        return $request->user();
    }

    public function updatePassword(User $user, string $currentPassword, string $newPassword): void
    {
        if ($user->id !== Auth::id() && Auth::user()->role !== 'admin') {
            throw ValidationException::withMessages([
                'current_password' => ['You can only update your own password.'],
            ]);
        }

        if ($user->id === Auth::id() && !Hash::check($currentPassword, $user->password)) {
            throw ValidationException::withMessages([
                'current_password' => ['Error updating password.'],
            ]);
        }
                
        $user->password = Hash::make($newPassword);
        $user->save();
    }
    
    
}
