<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Tymon\JWTAuth\Facades\JWTAuth;

class AuthController extends Controller
{
    public function register(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'username' => 'required|string|max:255|unique:users',  
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:6|confirmed',
            'role' => 'nullable|string',
            'avatar' => 'nullable|string',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        try {
            $user = User::create([
                'username' => $request->username,  
                'email' => $request->email,
                'password' => $request->password, // Model sẽ tự hash
                'role' => $request->role ?? 'USER',     
                'avatar' => $request->avatar ?? null,    
            ]);

            // Tạo JWT token
            $token = JWTAuth::fromUser($user);

            return response()->json([
                'access_token' => $token,
                'token_type' => 'Bearer',
                'user' => $user,
            ], 201);
            
        } catch (\Exception $e) {
            return response()->json([
                'error' => 'Registration failed',
                'message' => $e->getMessage()
            ], 500);
        }
    }

    public function login(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required|string|email',
            'password' => 'required|string',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $credentials = $request->only('email', 'password');

        if (!$token = JWTAuth::attempt($credentials)) {
            return response()->json(['message' => 'Invalid credentials'], 401);
        }

        $user = JWTAuth::user();

        return response()->json([
            'access_token' => $token,
            'token_type' => 'Bearer',
            'user' => $user,
        ]);
    }

    public function logout(Request $request)
{
    try {
        // Lấy token từ request
        $token = $request->header('Authorization');
        
        if ($token) {
            // Bỏ "Bearer " prefix nếu có
            $token = str_replace('Bearer ', '', $token);
            
            // Set token và invalidate
            JWTAuth::setToken($token)->invalidate();
        }
        
        return response()->json([
            'message' => 'Logged out successfully',
            'success' => true
        ], 200);
        
    } catch (\Tymon\JWTAuth\Exceptions\TokenInvalidException $e) {
        // Token đã invalid rồi, vẫn coi như logout thành công
        return response()->json([
            'message' => 'Logged out successfully',
            'success' => true
        ], 200);
        
    } catch (\Tymon\JWTAuth\Exceptions\TokenExpiredException $e) {
        // Token đã hết hạn, vẫn coi như logout thành công
        return response()->json([
            'message' => 'Logged out successfully', 
            'success' => true
        ], 200);
        
    } catch (\Tymon\JWTAuth\Exceptions\JWTException $e) {
        // Không có token hoặc token không hợp lệ
        return response()->json([
            'message' => 'Logged out successfully',
            'success' => true
        ], 200);
        
    } catch (\Exception $e) {
        // Lỗi khác, vẫn trả về thành công vì logout nên luôn thành công
        return response()->json([
            'message' => 'Logged out successfully',
            'success' => true
        ], 200);
    }
}

    public function me()
    {
        try {
            $user = JWTAuth::parseToken()->authenticate();
            return response()->json($user);
        } catch (\Exception $e) {
            return response()->json(['message' => 'Invalid token'], 401);
        }
    }

    public function resetPassword(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required|string|email',
            'password' => 'required|string|min:6|confirmed',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $user = User::where('email', $request->email)->first();
        if (!$user) {
            return response()->json(['message' => 'User not found'], 404);
        }
        $user->password = Hash::make($request->password);
        $user->save();
        return response()->json(['message' => 'Password reset successfully']);
    }
}