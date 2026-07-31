<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Slide_show;
use Illuminate\Support\Facades\Validator;

class Slide_showController extends Controller
{
    // Get all slides
    public function index()
    {
        try {
            $slides = Slide_show::all();

            if ($slides->isEmpty()) {
                return response()->json([
                    "error" => "Slide show not found!."
                ], 404);
            }

            return response()->json([
                "data" => $slides,
                "message" => "Slide show retrieved successfully"
            ], 200);
        } catch (\Throwable $e) {
            return $this->handleException($e, 'Unable to retrieve slide show');
        }
    }

    // Get details of a single slide
    public function show($id)
    {
        try {
            $slide = Slide_show::find($id);

            if (!$slide) {
                return response()->json([
                    'error' => "Slide show not found!."
                ], 404);
            }

            return response()->json([
                "data" => $slide,
                "message" => "Slide show retrieved successfully"
            ], 200);
        } catch (\Throwable $e) {
            return $this->handleException($e, 'Unable to retrieve slide show details');
        }
    }

    // Create slide
    public function store(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'title' => 'required|string|max:255',
                'sub_title' => 'nullable|string|max:255',
                'desc' => 'nullable|string',
                'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            ]);

            if ($validator->fails()) {
                return response()->json([
                    "status" => "error",
                    "errors" => $validator->errors()
                ], 422);
            }

            $path = null;
            if ($request->hasFile('image')) {
                $path = $request->file('image')->store('uploads/slide_show', 'public');
            }

            $slide = Slide_show::create([
                'title' => $request->title,
                'sub_title' => $request->sub_title,
                'desc' => $request->desc,
                'image' => $path, // store raw path only, accessor handles the URL
            ]);

            return response()->json([
                'success' => true,
                'data' => $slide,
                'message' => 'Slide show created successfully'
            ], 201);
        } catch (\Throwable $e) {
            return $this->handleException($e, 'Unable to create slide show');
        }
    }

    // Update slide
    public function update(Request $request, $id)
    {
        try {
            $slide = Slide_show::find($id);

            if (!$slide) {
                return response()->json([
                    'error' => "Slide show not found!."
                ], 404);
            }

            $validator = Validator::make($request->all(), [
                'title' => 'sometimes|required|string|max:255',
                'sub_title' => 'nullable|string|max:255',
                'desc' => 'nullable|string',
                'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            ]);

            if ($validator->fails()) {
                return response()->json([
                    "status" => "error",
                    "errors" => $validator->errors()
                ], 422);
            }

            if ($request->hasFile('image')) {
                // Delete old file if one exists
                $oldPath = $slide->getRawOriginal('image');
                if ($oldPath && \Storage::disk('public')->exists($oldPath)) {
                    \Storage::disk('public')->delete($oldPath);
                }

                $path = $request->file('image')->store('uploads/slide_show', 'public');
                $slide->setAttribute('image', $path); // raw path only
            }

            foreach (['title', 'sub_title', 'desc'] as $field) {
                if ($request->has($field)) {
                    $slide->$field = $request->$field;
                }
            }

            $slide->save();

            return response()->json([
                'success' => true,
                'data' => $slide,
                'message' => 'Slide show updated successfully'
            ], 200);
        } catch (\Throwable $e) {
            return $this->handleException($e, 'Unable to update slide show');
        }
    }

    // Delete slide
    public function destroy($id)
    {
        try {
            $slide = Slide_show::find($id);

            if (!$slide) {
                return response()->json([
                    'error' => "Slide show not found!."
                ], 404);
            }

            // Delete associated image file
            $oldPath = $slide->getRawOriginal('image');
            if ($oldPath && \Storage::disk('public')->exists($oldPath)) {
                \Storage::disk('public')->delete($oldPath);
            }

            $slide->delete();

            return response()->json([
                'message' => 'Slide show deleted successfully'
            ], 200);
        } catch (\Throwable $e) {
            return $this->handleException($e, 'Unable to delete slide show');
        }
    }
}