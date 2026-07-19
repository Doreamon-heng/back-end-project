<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Role;
use Illuminate\Support\Facades\Validator;

class RoleController extends Controller
{
    public function index(){
        $roles = Role::paginate(10);
        if($roles->isEmpty()){
            return response()->json([
                'message' => 'No roles found'
            ], 404);
        }
        return response()->json([
            'data' => $roles,
            'message' => 'Roles retrieved successfully'
        ], 200);

    }

    //get role by id
    public function show($id){
        $role = Role::find($id);
        //check if role exist
        if (!$role) {
            return response()->json([
                'message' => 'Role not found'
            ], 404);
        }
        //else return role
        return response()->json([
            'data' => $role,
            'message' => 'Role retrieved successfully'
        ], 200);
    }

    //create new role
    public function store(Request $r){
        
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
            'data' => array(
                'id' => $role->id,
                'name' => $role->name,
            ),
            'message' => 'Role created successfully'
        ], 201);
    }

    //update role
    public function update(Request $r, $id){
        $role = Role::find($id);
        //check if role exist
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
    }

    //delete role
    public function destroy($id){
        $role = Role::find($id);
        //check if role exist
        if (!$role) {
            return response()->json([
                'message' => 'Role not found'
            ], 404);    
        }
    }
}
