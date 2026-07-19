<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;
use App\Models\User_image;
use Illuminate\Support\Facades\Storage;
use Illuminate\Http\Request;

class User_imageController extends Controller
{
    //get all user images 
    public function getAllUserImages(){
       $user_images = User_image::with('user')->get();
        //check if user images exist
        if (!$user_images) {
            return response()->json([
                'message' => 'No user images found'
            ], 404);
        }
        //else return user images
        return response()->json([
            'data' => $user_images,
            'message' => 'User images retrieved successfully'
        ], 200);
    }

    //get user image by id
    public function getUserImageById($id){
        //check if user image exist
        //check query builder to get user image with user details
        $user_image = User_image::with([
            'user' => function($query){
                $query->select('id', 'name', 'email')->get();
            }
        ])->find($id);
        //check if user image exist
        if (!$user_image) {
            return response()->json([
                'message' => 'User image not found'
            ], 404);
        }
        //else return user image
        return response()->json([
            'data' => $user_image,
            'message' => 'User image retrieved successfully'
        ], 200);
    }
}
