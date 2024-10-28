<?php
namespace App\Services;

use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\ValidationException;
use App\Helpers\ErrorLogger;

class UserService
{
    public function register($data)
    {
        try {
            $users = User::create([
                'email' => $data['email'],
                'username' => $data['username'],
                'password' => Hash::make($data['password']),
                'role' => $data['role'],
            ]);
            
            return $users;
        } catch (\Exception $e) {
            // Catat error menggunakan ErrorLogger
            ErrorLogger::logError("Registration error: " . $e->getMessage());
            return null;
        }
    }

    public function login($data)
    {
        try {
            // Cek apakah login menggunakan email atau username
            $user = User::where('email', $data['email'] ?? null)
                        ->orWhere('username', $data['username'] ?? null)
                        ->first();
    
            if (!$user || !Hash::check($data['password'], $user->password)) {
                throw ValidationException::withMessages([
                    'credentials' => ['The provided credentials are incorrect.'],
                ]);
            }
    
            // Buat token dan signature
            // $token = bin2hex(random_bytes(30)); // Contoh cara membuat token
            // $signature = hash_hmac('sha256', $user->id . $user->email, config('app.key'));
    
            // return [
            //     'token' => $token,
            //     'signature' => $signature,
            // ];
        } catch (\Exception $e) {
            ErrorLogger::logError("Login error: " . $e->getMessage());
            throw $e;
        }
    }    

}
