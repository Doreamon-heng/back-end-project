<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Categories;
use App\Models\Categories_image;
use Validator;

class CategoryController extends Controller
{
    public function index()
    {
        $categories = Categories::with('Categories')->get();
        return response()->json($categories);
    }

    //create category
    public function create(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
        ]);

        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()], 400);
        }

        $category = Categories::create([
            'name' => $request->name,
            'description' => $request->description,
        ]);

        return response()->json($category, 201);
    }

    //create image for category
    public function createImage(Request $request, $categoryId)
    {
        $validator = Validator::make($request->all(), [
            'image_url' => 'required|string|max:255',
            'file_name' => 'required|string|max:255',
        ]);

        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()], 400);
        }

        $categoryImage = Categories_image::create([
            'category_id' => $categoryId,
            'image_url' => $request->image_url,
            'file_name' => $request->file_name,
        ]);

        return response()->json($categoryImage, 201);
    }

    //get details of category
    public function getDetails($id)
    {
        $category = Categories::with('Categories')->find($id);
        if (!$category) {
            return response()->json(['error' => 'Category not found'], 404);
        }
        return response()->json($category);
    }



}
