<?php

namespace App\Http\Controllers;

use App\Services\UserService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;

class UsersController extends Controller
{
    protected $userService;

    public function __construct(UserService $userService)
    {
        $this->userService = $userService;
    }

    // Fungsi untuk registrasi pengguna
    public function register(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required|string|email|max:255',
            'username' => 'required|string|max:255|unique:users',
            'password' => 'required|string|min:6',
            'role' => 'required|in:admin,user' // Pastikan role sesuai ENUM
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        $user = $this->userService->register($request->all());

        if (!$user) {
            return response()->json(['error' => 'Registrasi gagal'], 500);
        }

        return response()->json(['message' => 'Pengguna berhasil didaftarkan', 'user' => $user], 201);
    }

    // Fungsi untuk login pengguna
    public function login(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required_without:username|email',
            'username' => 'required_without:email|string',
            'password' => 'required'
        ]);
    
        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }
    
        try {
            // Mengambil token dan signature dari UserService
            $userService = $this->userService->login($request->all());
            
            // Kembalikan response dengan token dan signature
            return response()->json([
                'token' => $userService['token'],
                'signature' => $userService['signature']
            ], 200);
        } catch (ValidationException $e) {
            return response()->json(['error' => 'Invalid credentials'], 401);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Login failed'], 500);
        }
    }
    
}
