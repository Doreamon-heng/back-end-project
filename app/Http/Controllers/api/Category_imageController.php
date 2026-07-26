<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Categories_image;
use Throwable;
use Validator;
class Category_imageController extends Controller
{
    //get all category image
    public function getAllCateImage()
    {
        try {
            $category_image = Categories_image::with('categories_id')->get()->all();
            if(!$category_image){
                return response()->json([
                    "error" => "Image not found!."
                ], 404);
               
            } 
            return response()->json([
                    "data" => $category_image,
                ], 200);
        }catch(\Throwable $e){
            return $this->handleException($e, 'Unable to retrieve category image');
        }
    }


    //get details category image
    public function detailsCateImage($id){
       try{
         $category_image = Categories_image::find($id);
         if(!$category_image){
            return response()->json([
                'error' => "category image not found!."
            ], 404);
         }
         return response()->json([
            "data" => $category_image,
         ], 200);
       }catch(\throwable $e){
        return $this->handleException($e, 'Unable to retrieve category image details');
       }
    }

    //create category image
    public function createCateImage(Request $r){
        $validator = Validator::make($r->all(), [
            "category_id" => "required|",
            "image_url" => "",
            "file_name" =>""
        ]);
    }
}
