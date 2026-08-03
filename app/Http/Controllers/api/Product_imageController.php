<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Products_image;

class Product_imageController extends Controller
{
    // ✅ Get all product images
    public function index()
    {
        $images = Products_image::with('product')->get();

        return response()->json([
            'success' => true,
            'data' => $images
        ]);
    }

    // ✅ Get single product image
    public function detailsProductImage($id)
    {
        $image = Products_image::with('products')->find($id);

        if (!$image) {
            return response()->json(['success' => false, 'message' => 'Product image not found'], 404);
        }

        return response()->json(['success' => true, 'data' => $image]);
    }

    // ✅ Create product image
    public function createProductImage(Request $request)
    {
        $image = Products_image::create($request->all());

        return response()->json([
            'success' => true,
            'message' => 'Product image created successfully',
            'data' => $image
        ], 201);
    }

    // ✅ Update product image
    public function update(Request $request, $id)
    {
        $image = Products_image::find($id);

        if (!$image) {
            return response()->json(['success' => false, 'message' => 'Product image not found'], 404);
        }

        $image->update($request->all());

        return response()->json(['success' => true, 'message' => 'Product image updated', 'data' => $image]);
    }

    // ✅ Delete product image
    public function destroy($id)
    {
        $image = Products_image::find($id);

        if (!$image) {
            return response()->json(['success' => false, 'message' => 'Product image not found'], 404);
        }

        $image->delete();

        return response()->json(['success' => true, 'message' => 'Product image deleted']);
    }

    // ✅ Enable product image
    public function enable($id)
    {
        $image = Products_image::find($id);

        if (!$image) {
            return response()->json(['success' => false, 'message' => 'Product image not found'], 404);
        }

        $image->is_enabled = true;
        $image->save();

        return response()->json(['success' => true, 'message' => 'Product image enabled', 'data' => $image]);
    }

    // ✅ Disable product image
    public function disable($id)
    {
        $image = Products_image::find($id);

        if (!$image) {
            return response()->json(['success' => false, 'message' => 'Product image not found'], 404);
        }

        $image->is_enabled = false;
        $image->save();

        return response()->json(['success' => true, 'message' => 'Product image disabled', 'data' => $image]);
    }
}
