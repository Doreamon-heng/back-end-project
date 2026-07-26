<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Models\Categories;
use Illuminate\Validation\Rule;

class CategoryController extends Controller
{
    public function index()
    {
        try {
            $category = Categories::with('categories_image')->all();
            return response()->json([
                'data' => $category,
                'message' => 'Categories retrieved successfully'
            ]);
        } catch (\Throwable $e) {
            return $this->handleException($e, 'Unable to retrieve categories');
        }
    }

    public function show($id)
    {
        try {
            $category = Categories::find($id);
            if (!$category ) {
                return response()->json(['error' => 'Categories not found'], 404);
            }
            return response()->json([
                'data' => $category,
                'message' => 'Categories details retrieved successfully'
            ]);
        } catch (\Throwable $e) {
            return $this->handleException($e, 'Unable to retrieve category details');
        }
    }

    //create category

    public function store(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'name' => [
                    'required',
                    'string',
                    'max:255',
                    Rule::unique('categories')->ignore($request->id),
                ],
                'description' => 'nullable|string',
            ]);

            if ($validator->fails()) {
                return response()->json(['error' => $validator->errors()], 400);
            }

            $category = Categories::create([
                'name' => $request->name,
                
                'description' => $request->description,
            ]);

            return response()->json([
                'data' => $category,
                'message' => 'Categories created successfully'
            ], 201);
        } catch (\Throwable $e) {
            return $this->handleException($e, 'Unable to create category');
        }
    }

    public function update(Request $request, $id)
    {
        try {
            $category = Categories::find($id);
           
            if (!$category) {
                return response()->json(['error' => 'Categories not found'], 404);
            }

            if ($request->has('name')) {
                $category->name = $request->name;
            }
            if ($request->has('description')) {
                $category->description = $request->description;
            }

            $category->save();
            

            return response()->json([
                'data' => $category,
                'message' => 'Categories updated successfully'
            ], 200);
        } catch (\Throwable $e) {
            return $this->handleException($e, 'Unable to update category');
        }
    }

    public function destroy($id)
    {
        try {
            $category = Categories::find($id);
            if (!$category) {
                return response()->json(['error' => 'Categories not found'], 404);
            }

            $category->delete();

            return response()->json([
                'message' => 'Categories deleted successfully'
            ], 200);
        } catch (\Throwable $e) {
            return $this->handleException($e, 'Unable to delete category');
        }
    }
}
