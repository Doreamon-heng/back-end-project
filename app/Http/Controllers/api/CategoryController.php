<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Models\Categories_image;
use App\Models\Categories;
use Illuminate\Validation\Rule;

class CategoryController extends Controller
{
    // Get all categories with their image
    public function index(Request $r)
    {
        try {
            $categories = Categories::with('categories_image')->get();

            return response()->json([
                'data' => $categories,
                'message' => 'Categories retrieved successfully'
            ]);
        } catch (\Throwable $e) {
            return $this->handleException($e, 'Unable to retrieve categories');
        }
    }

    // Get single category with its image
    public function show($id)
    {
        try {
            $category = Categories::with('categories_image')->find($id);

            if (!$category) {
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

    // Create category
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

    // Update category
    public function update(Request $request, $id)
    {
        try {
            $category = Categories::find($id);

            if (!$category) {
                return response()->json(['error' => 'Categories not found'], 404);
            }

            $validator = Validator::make($request->all(), [
                'name' => [
                    'sometimes',
                    'required',
                    'string',
                    'max:255',
                    Rule::unique('categories')->ignore($category->id),
                ],
                'description' => 'nullable|string',
            ]);

            if ($validator->fails()) {
                return response()->json(['error' => $validator->errors()], 400);
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

    // Delete category
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