<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Categories_image;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;

class Category_imageController extends Controller
{
    // Get all category images
    public function getAllCateImage()
    {
        try {
            $category_image = Categories_image::with('category')->get();

            if ($category_image->isEmpty()) {
                return response()->json([
                    "error" => "Image not found!."
                ], 404);
            }

            return response()->json([
                "data" => $category_image,
            ], 200);
        } catch (\Throwable $e) {
            return $this->handleException($e, 'Unable to retrieve category image');
        }
    }

    // Get details of a single category image
    public function detailsCateImage($id)
    {
        try {
            $category_image = Categories_image::with('category')->find($id);

            if (!$category_image) {
                return response()->json([
                    'error' => "category image not found!."
                ], 404);
            }

            return response()->json([
                "data" => $category_image,
            ], 200);
        } catch (\Throwable $e) {
            return $this->handleException($e, 'Unable to retrieve category image details');
        }
    }

    // Create category image
    public function createCateImage(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                "category_id" => "required|integer|exists:categories,id",
                "image_url" => "nullable|url",
                "file_name" => "nullable|string|max:255",
                "image" => "nullable|image|mimes:jpeg,png,jpg,gif|max:2048"
            ]);

            if ($validator->fails()) {
                return response()->json([
                    "status" => "error",
                    "errors" => $validator->errors()
                ], 422);
            }

            $path = null;
            $originalName = null;

            if ($request->hasFile('image')) {
                $file = $request->file('image');
                $path = $file->store('uploads', 'public');
                $originalName = $file->getClientOriginalName();
            }

            $cateImage = Categories_image::create([
                "category_id" => $request->category_id,
                "image_url" => $request->image_url ?? ($path ? asset('storage/' . $path) : null),
                "file_name" => $request->file_name ?? $originalName,
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Image uploaded successfully.',
                'data' => $cateImage,
                'url' => $path ? asset('storage/' . $path) : null,
                'path' => $path
            ], 201);
        } catch (\Throwable $e) {
            return $this->handleException($e, 'Unable to create category image');
        }
    }

    // Update category image
    public function updateCateImage(Request $request, $id = null)
    {
        try {
            $id = $id ?? $request->id;

            if (!$id) {
                return response()->json([
                    'error' => 'Category image ID is required for update.'
                ], 422);
            }

            // Find the category image by ID
            $cateImage = Categories_image::find($id);

            if (!$cateImage) {
                return response()->json([
                    'error' => "Category image not found."
                ], 404);
            }

            // Validate request
            $validator = Validator::make($request->all(), [
                "category_id" => "sometimes|required|integer|exists:categories,id",
                "image_url" => "nullable|url",
                "file_name" => "nullable|string|max:255",
                "image" => "nullable|image|mimes:jpeg,png,jpg,gif|max:2048"
            ]);

            if ($validator->fails()) {
                return response()->json([
                    "status" => "error",
                    "errors" => $validator->errors()
                ], 422);
            }

            // Handle file upload
            if ($request->hasFile('image')) {
                // Remove old file if it exists
                if ($cateImage->file_path && Storage::disk('public')->exists($cateImage->file_path)) {
                    Storage::disk('public')->delete($cateImage->file_path);
                }

                $file = $request->file('image');
                $path = $file->store('uploads', 'public'); // ✅ 'public' must be quoted

                $cateImage->file_path = $path;
                $cateImage->image_url = asset('storage/' . $path);
                $cateImage->file_name = $file->getClientOriginalName();
            }

            // Update other fields if provided
            if ($request->filled('category_id')) {
                $cateImage->category_id = $request->category_id;
            }
            if ($request->filled('image_url') && !$request->hasFile('image')) {
                $cateImage->image_url = $request->image_url;
            }
            if ($request->filled('file_name') && !$request->hasFile('image')) {
                $cateImage->file_name = $request->file_name;
            }

            $cateImage->save();

            return response()->json([
                'success' => true,
                'message' => 'Image updated successfully.',
                'data' => $cateImage,
            ], 200);

        } catch (\Throwable $e) {
            return response()->json([
                'error' => 'Unable to update category image',
                'details' => $e->getMessage()
            ], 500);
        }
    }



    // Delete category image
    public function destroyCateImage($id)
    {
        try {
            $cateImage = Categories_image::find($id);

            if (!$cateImage) {
                return response()->json([
                    'error' => "category image not found!."
                ], 404);
            }

            $cateImage->delete();

            return response()->json([
                'message' => 'category image deleted successfully'
            ], 200);
        } catch (\Throwable $e) {
            return $this->handleException($e, 'Unable to delete category image');
        }
    }
}