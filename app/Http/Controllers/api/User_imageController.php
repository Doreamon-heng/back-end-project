<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use App\Models\User_image;

class User_imageController extends Controller
{
    //get all user images
    public function getAllUserImages()
    {
        try {
            $user_images = User_image::with('user')->get();
            if ($user_images->isEmpty()) {
                return response()->json([
                    'message' => 'No user images found'
                ], 404);
            }

            return response()->json([
                'data' => $user_images,
                'message' => 'User images retrieved successfully'
            ], 200);
        } catch (\Throwable $e) {
            return $this->handleException($e, 'Unable to retrieve user images');
        }
    }

    //get user image by id
    public function getUserImageById($id)
    {
        try {
            $user_image = User_image::with([
                'user' => function ($query) {
                    $query->select('id', 'name', 'email');
                }
            ])->find($id);

            if (!$user_image) {
                return response()->json([
                    'message' => 'User image not found'
                ], 404);
            }

            return response()->json([
                'data' => $user_image,
                'message' => 'User image retrieved successfully'
            ], 200);
        } catch (\Throwable $e) {
            return $this->handleException($e, 'Unable to retrieve user image');
        }
    }

    
}
