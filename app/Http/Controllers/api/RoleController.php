<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Role;
use Illuminate\Support\Facades\Validator;

class RoleController extends Controller
{
    public function index()
    {
        try {
            $roles = Role::paginate(10);
            if ($roles->isEmpty()) {
                return response()->json([
                    'message' => 'No roles found'
                ], 404);
            }
            return response()->json([
                'data' => $roles,
                'message' => 'Roles retrieved successfully'
            ], 200);
        } catch (\Throwable $e) {
            return $this->handleException($e, 'Unable to retrieve roles');
        }
    }

    //get role by id
    public function show($id)
    {
        try {
            $role = Role::find($id);
            if (!$role) {
                return response()->json([
                    'message' => 'Role not found'
                ], 404);
            }
            return response()->json([
                'data' => $role,
                'message' => 'Role retrieved successfully'
            ], 200);
        } catch (\Throwable $e) {
            return $this->handleException($e, 'Unable to retrieve role details');
        }
    }

    //create new role
    public function store(Request $r)
    {
        try {
            $validator = Validator::make($r->all(), [
                'name' => 'required|string|max:255|unique:roles',
            ]);
            if ($validator->fails()) {
                return response()->json(['error' => $validator->errors()], 422);
            }
            $role = new Role();
            $role->name = $r->name;
            $role->save();
            return response()->json([
                'data' => [
                    'id' => $role->id,
                    'name' => $role->name,
                ],
                'message' => 'Role created successfully'
            ], 201);
        } catch (\Throwable $e) {
            return $this->handleException($e, 'Unable to create role');
        }
    }

    //update role
    public function update(Request $r, $id)
    {
        try {
            $role = Role::find($id);
            if (!$role) {
                return response()->json([
                    'message' => 'Role not found'
                ], 404);
            }
            $role->name = $r->name;
            $role->save();
            return response()->json([
                'data' => $role,
                'message' => 'Role updated successfully'
            ], 200);
        } catch (\Throwable $e) {
            return $this->handleException($e, 'Unable to update role');
        }
    }

    //delete role
    public function destroy($id)
    {
        try {
            $role = Role::find($id);
            if (!$role) {
                return response()->json([
                    'message' => 'Role not found'
                ], 404);
            }

            $role->delete();

            return response()->json([
                'message' => 'Role deleted successfully'
            ], 200);
        } catch (\Throwable $e) {
            return $this->handleException($e, 'Unable to delete role');
        }
    }
}
