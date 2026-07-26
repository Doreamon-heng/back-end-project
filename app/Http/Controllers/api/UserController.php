<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Validator;

class UserController extends Controller
{
    //get all  users
    public function getAllUsers()
    {
        try {
            $users = User::with('role')->paginate(12);

            if ($users->isEmpty()) {
                return response()->json([
                    'message' => 'No users found'
                ], 404);
            }

            return response()->json([
                'data' => $users,
                'message' => 'Users retrieved successfully'
            ], 200);
        } catch (\Throwable $e) {
            return $this->handleException($e, 'Unable to retrieve users');
        }
    }

    //get user by id
    public function getUserById($id)
    {
        try {
            $user = User::find($id);
            if (!$user) {
                return response()->json([
                    'message' => 'User not found'
                ], 404);
            }

            return response()->json([
                'data' => $user,
                'message' => 'User retrieved successfully'
            ], 200);
        } catch (\Throwable $e) {
            return $this->handleException($e, 'Unable to retrieve user details');
        }
    }

    //Create new user
    public function createUser(Request $r)
    {
        try {
            $validator = Validator::make($r->all(), [
                'name' => 'required|string|max:255',
                'email' => 'required|string|email|max:255|unique:users',
                'phone_number' => 'required|string|max:15|unique:users',
                'password' => 'required|string|min:8',
            ]);
            if ($validator->fails()) {
                return response()->json(['error' => $validator->errors()], 422);
            }
            return response()->json([
                'data' => [
                    'name' => $r->name,
                    'email' => $r->email,
                    'phone_number' => $r->phone_number,
                    'password' => bcrypt($r->password),
                    'created_at' => now(),
                ],
                'message' => 'User created successfully'
            ], 201);
        } catch (\Throwable $e) {
            return $this->handleException($e, 'Unable to create user');
        }
    }

    //update user by id
    public function updateUser(Request $r, $id)
    {
        try {
            $user = User::find($id);
            if (!$user) {
                return response()->json([
                    'message' => 'User not found'
                ], 404);
            }

            if ($r->has('name')) {
                $user->name = $r->name;
            }
            if ($r->has('email')) {
                $user->email = $r->email;
            }
            $user->save();

            return response()->json([
                'data' => $user,
                'message' => 'User updated successfully'
            ], 200);
        } catch (\Throwable $e) {
            return $this->handleException($e, 'Unable to update user');
        }
    }

    //delete user by id
    public function deleteUser($id)
    {
        try {
            $user = User::find($id);

            if (!$user) {
                return response()->json([
                    'success' => false,
                    'message' => 'User not found'
                ], 404);
            }

            $user->delete();

            return response()->json([
                'success' => true,
                'message' => 'User deleted successfully'
            ], 200);
        } catch (\Throwable $e) {
            return $this->handleException($e, 'Unable to delete user');
        }
    }
}

