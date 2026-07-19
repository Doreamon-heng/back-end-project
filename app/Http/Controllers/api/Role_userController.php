<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Role_user;
use Illuminate\Support\Facades\Validator;

class Role_userController extends Controller
{
    //get all role_user
    public function index()
    {
        $role_user = Role_user::with('role', 'user')->get();
        return response()->json([
            'data' => $role_user
        ], 200);
    }

    //get role_user by id
    public function show($id)
    {
        $role_user = Role_user::with('role', 'user')->find($id);
        if (!$role_user) {
            return response()->json([
                'message' => 'Role_user not found'
            ], 404);
        }
        return response()->json([
            'data' => $role_user
        ], 200);
    }

    //create new role_user
    public function store(Request $r)
    {
        $validator = Validator::make($r->all(), [
            'role_id' => 'required|integer|exists:roles,id',
            'user_id' => 'required|integer|exists:users,id',
        ]);
        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()], 422);
        }
        $role_user = new Role_user();
        //find role and user by id
        $role_user->role_id = $r->role_id;
        $role_user->user_id = $r->user_id;
        dd($r->user_id);
        $role_user->save();
        return response()->json([
            'data' => array(
                'id' => $role_user->id,
                'role_id' => $role_user->role_id,
                'user_id' => $role_user->user_id,
            ),
            'message' => 'Role_user created successfully'
        ], 201);
    }

    //update role_user
    public function update(Request $r, $id)
    {
        $role_user = Role_user::find($id);
        if (!$role_user) {
            return response()->json([
                'message' => 'Role_user not found'
            ], 404);
        }
        return response()->json([
            'data' => [
                'role_id' => $r->role_id,
                'user_id' => $r->user_id,
                'updated_at' => now(),
            ],
            'message' => 'Role_user updated successfully'
        ], 200);
    }

    //delete role_user
    public function destroy($id)
    {
        $role_user = Role_user::find($id);
        if (!$role_user) {
            return response()->json([
                'message' => 'Role_user not found'
            ], 404);
        }
        $role_user->delete();
        return response()->json([
            'message' => 'Role_user deleted successfully'
        ], 200);
    }

}
