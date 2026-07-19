<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Facades\Validator;
use App\Models\User;
use App\Models\Role;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{

    //register user
    public function register(Request $r)
    {
        $validator = Validator::make($r->all(), [
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'phone_number' => 'required|string|max:15|unique:users',
            'password' => 'required|string|min:8',
            'role' => 'required|string|max:255',
        ]); 
        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()], 422);
        }

        $roleName = $r->input('role', 'user');
        $role = Role::firstOrCreate(['name' => $roleName]);

        $user = new User();
        $user->name = $r->name;
        $user->email = $r->email;
        $user->phone_number = $r->phone_number;
        $user->password = bcrypt($r->password);
        $user->role_id = $role->id;
        $user->save();

        $token = $user->createToken('auth-token')->plainTextToken;

        return response()->json([
            'data' => [
                'user' => [
                    'id' => $user->id,
                    'name' => $user->name,
                    'email' => $user->email,
                ],
                'token' => $token,
            ],
            'message' => 'User registered successfully'
        ], 201);

    }

    //login user
    public function login(Request $r)
    {
        $validator = Validator::make($r->all(), [
            'email' => 'required|string|email|max:255',
            'password' => 'required|string|min:8',
        ]);
        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()], 422);
        }

        $user = User::where('email', $r->email)->first();

        if (!$user || !Hash::check($r->password, $user->password)) {
            return response()->json([
                'error' => ['email' => ['The provided credentials are incorrect.']],
            ], 401);
        }

        $token = $user->createToken('auth-token')->plainTextToken;

        return response()->json([
            'data' => [
                'user' => [
                    'id' => $user->id,
                    'name' => $user->name,
                    'email' => $user->email,
                ],
                'token' => $token,
            ],
            'message' => 'User logged in successfully'
        ]);
    }

    //user logout
    public function logout(Request $r)
    {
        return response()->json([
            'message' => 'User logged out successfully'

        ], 200);
    }

    //user forgot password
    public function forgotPassword(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required|email|max:255',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'errors' => $validator->errors(),
            ], 422);
        }

        $status = Password::sendResetLink(
            $request->only('email')
        );

        if ($status === Password::RESET_LINK_SENT) {
            return response()->json([
                'message' => __($status),
            ], 200);
        }

        return response()->json([
            'message' => __($status),
        ], 400);
    }

    //recovery account by email
    public function recoveryAccount(Request $r)
    {
        $validator = Validator::make($r->all(), [
            'email' => 'required|email|max:255',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'errors' => $validator->errors(),
            ], 422);
        }

        // Here you can implement the logic to send a recovery email to the user
        // For example, you can use Laravel's built-in notification system

        return response()->json([
            'message' => 'Recovery email sent successfully',
        ], 200);
    }

    //recovery account by phone number
    public function recoveryAccountByPhone(Request $r)
    {
        $validator = Validator::make($r->all(), [
            'phone' => 'required|string|max:15',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'errors' => $validator->errors(),
            ], 422);
        }

        // Here you can implement the logic to send a recovery SMS to the user
        // For example, you can use a third-party service like Twilio

        return response()->json([
            'message' => 'Recovery SMS sent successfully',
        ], 200);
    }
}
