<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\SlideShowImages;
use Illuminate\Support\Facades\Storage;
use Validator;

class SlideShowImageController extends Controller
{
    //get all slide show images
    public function index()
    {
        try {
            $slideShowImages = SlideShowImages::all();

            if ($slideShowImages->isEmpty()) {
                return response()->json([
                    "error" => true,
                    "message" => "Slide show images not found!."
                ], 404);
            }

            return response()->json([
                "data" => $slideShowImages,
                "message" => "Slide show images retrieved successfully!."
            ]);
        } catch (\Throwable $e) {
            return $this->handleException($e, 'Unable to retrieve slide show images');
        }
    }


    //get single slide show image
    public function show($id)
    {
        try {
            $slideShowImage = SlideShowImages::findOrFail($id);

            if (!$slideShowImage) {
                return response()->json([
                    "error" => true,
                    "message" => "Slide show image not found!."
                ], 404);
            }

            return response()->json([
                "data" => $slideShowImage,
                "message" => "Slide show image retrieved successfully!."
            ]);
        } catch (\Throwable $e) {
            return $this->handleException($e, 'Unable to retrieve slide show image details');
        }
    }

    //create slide show image
    public function store(Request $request)
    {
        try {
            // Normalize: FormData often sends null/undefined as literal strings.
            // Treat "", "null", "undefined" (and whitespace-only) as truly empty.
            $imageUrlValue = $request->input('image_url');
            if ($request->has('image_url') && trim((string) $imageUrlValue) === '') {
                $request->merge(['image_url' => null]);
            } elseif (in_array(trim((string) $imageUrlValue), ['null', 'undefined'], true)) {
                $request->merge(['image_url' => null]);
            }

            $validator = Validator::make($request->all(), [
                'slide_show_id' => 'required|integer|exists:slide_shows,id',
                'image' => 'nullable|file|image|mimes:jpeg,png,jpg,gif,svg,webp|max:2048',
                'image_url' => 'nullable|url',
                'file_name' => 'nullable|string|max:255',

            ]);

            if ($validator->fails()) {
                return response()->json([
                    "error" => true,
                    "message" => $validator->errors()
                ], 422);
            }

            if (!$request->hasFile('image') && !$request->filled('image_url')) {
                return response()->json([
                    "error" => true,
                    "message" => "Either an 'image' file or an 'image_url' must be provided."
                ], 422);
            }

            $data = $request->only(['slide_show_id', 'file_name']);

            if ($request->hasFile('image')) {
                $file = $request->file('image');
                $path = $file->store('slide_show_images', 'public'); // storage/app/public/slide_show_images
                $data['image_url'] = Storage::url($path);            // /storage/slide_show_images/xxxx.jpg
                $data['file_name'] = $data['file_name'] ?? $file->getClientOriginalName();
            } else {
                $data['image_url'] = $request->input('image_url');
            }

            $slideShowImage = SlideShowImages::create($data);

            return response()->json([
                "data" => $slideShowImage,
                "message" => "Slide show image created successfully!."
            ], 201);
        } catch (\Throwable $e) {
            return $this->handleException($e, 'Unable to create slide show image');
        }
    }

    //update slide show image
    public function update(Request $request, $id)
    {
        try {
            $slideShowImage = SlideShowImages::findOrFail($id);
            $slideShowImage->update($request->all());
            return response()->json([
                "data" => $slideShowImage,
                "message" => "Slide show image updated successfully!."
            ]);
        } catch (\Throwable $e) {
            return $this->handleException($e, 'Slide show image not found');
        }
    }

    //delete slide show image
    public function destroy($id)
    {
        try {
            $slideShowImage = SlideShowImages::findOrFail($id);
            $slideShowImage->delete();
            return response()->json([
                "message" => "Slide show image deleted successfully!."
            ]);
        } catch (\Throwable $e) {
            return $this->handleException($e, 'Slide show image not found');
        }
    }
}
