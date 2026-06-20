<?php

namespace App\Http\Controllers\Domains\Category\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Categories;
use Illuminate\Http\Request;
use App\Models\Categories_image;
use Validator;

class CategoryController extends Controller
{
    //here for only category 
    //get all categories
    public function getAllCategories()
    {
        $categories = Categories::per_page(10)->get();
        return response()->json($categories);
    }

    //get details of a category
    public function Details($id)
    {
        $category = Categories::find($id);
        if (!$category) {
            return response()->json([
                'message' => 'Category not found',
                'status' => 404
            ], 404);
        }
        return response()->json($category);
    }

    //create a new category
    public function create(Request $r)
    {
        //validate the request
        $validator = Validator::make($r->all(), [
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
        ]);

        //check if validation fails
        if ($validator->fails()) {
            return response()->json([
                'message' => 'Validation failed',
                'status' => 422,
                'errors' => $validator->errors()
            ], 422);
        }

        //create and store the category
        $category = Categories::create($r->all());
        return response()->json($category, 201);
    }

    //update a category
    public function update(request $r, $id)
    {
        //validate the request
        $validator = Validator::make($r->all(), [
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
        ]);

        //check if validation fails
        if ($validator->fails()) {
            return response()->json([
                'message' => 'Validation failed',
                'status' => 422,
                'errors' => $validator->errors()
            ], 422);
        }

        //find the category
        $category = Categories::find($id);
        if (!$category) {
            return response()->json([
                'message' => 'Category not found',
                'status' => 404
            ], 404);
        }

        //update the category
        $category->update($r->all());
        return response()->json($category);
    }

    //delete a category
    public function delete($id)
    {
        //find the category
        $category = Categories::find($id);
        if (!$category) {
            return response()->json([
                'message' => 'Category not found',
                'status' => 404
            ], 404);
        }
        $category->delete();
        return response()->json([
            'message' => 'Category deleted successfully',
            'status' => 200
        ], 200);
    }


    //here for category images
    //get all images of a category
    public function getAllImages($id)
    {
        
        //find the category
        $category = Categories::find($id);
        if (!$category) {
            return response()->json([
                'message' => 'Category not found',
                'status' => 404
            ], 404);
        }

        //get all images of the category
        $images = Categories_image::where('categories_id', $id)->get();
        return response()->json($images);
    }

    //get image by id
    public function detailsImage($id){
        $image = Categories_image::find($id);
        if(!$image){
            return response()->json([
                'message' => 'Image not found!',
                'status' => 404,
            ], 404);
        }
        return response()->json($image);
    }

    //update image category
    public function updateImage(Request $r, $id){

    //Validate data
        $validator = Validator::make($r->all(), [
            "image_url" => "required|string",
            "file_name" => "required"
        ]);
        if($validator->fails()){
            return response()->json([
                
            ]);
        }
    }



}


