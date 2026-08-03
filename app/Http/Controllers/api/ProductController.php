<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use App\Models\Products;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class ProductController extends Controller
{
    //get all products
    public function index()
    {
        try {
            $products = Products::with('category', 'products_image')->paginate(10);
            return response()->json($products);
        } catch (\Throwable $e) {
            return $this->handleException($e, 'Unable to retrieve products');
        }
    }

    //get details products
    public function show($id)
    {
        try {
            $product = Products::with('category')->find($id);
            if (!$product) {
                return response()->json([
                    'message' => 'Product not found'
                ], 404);
            }
            return response()->json($product);
        } catch (\Throwable $e) {
            return $this->handleException($e, 'Unable to retrieve product details');
        }
    }

    //create new products
    public function create(Request $request)
    {
        return $this->store($request);
    }

    public function store(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'name' => 'required|string|max:255',
                'details' => 'nullable|string|max:255',
                'price' => 'required|numeric',
                'category_id' => 'required|integer|exists:categories,id',
            ]);

            if ($validator->fails()) {
                return response()->json(['error' => $validator->errors()], 422);
            }

            $product = new Products();
            $product->name = $request->name;
            $product->details = $request->details;
            $product->price = $request->price;
            $product->category_id = $request->category_id;
            $product->save();

            return response()->json([
                'data' => [
                    'id' => $product->id,
                    'name' => $product->name,
                    'details' => $product->details,
                    'price' => $product->price,
                    'category_id' => $product->category_id,
                ],
                'message' => 'Product created successfully'
            ], 201);
        } catch (\Throwable $e) {
            return $this->handleException($e, 'Unable to create product');
        }
    }

    public function update(Request $request, $id)
    {
        try {
            $product = Products::find($id);
            if (!$product) {
                return response()->json(['message' => 'Product not found'], 404);
            }

            if ($request->has('name')) {
                $product->name = $request->name;
            }
            if ($request->has('details')) {
                $product->details = $request->details;
            }
            if ($request->has('price')) {
                $product->price = $request->price;
            }
            if ($request->has('category_id')) {
                $product->category_id = $request->category_id;
            }

            $product->save();

            return response()->json([
                'data' => $product,
                'message' => 'Product updated successfully'
            ], 200);
        } catch (\Throwable $e) {
            return $this->handleException($e, 'Unable to update product');
        }
    }

    public function destroy($id)
    {
        try {
            $product = Products::find($id);
            if (!$product) {
                return response()->json(['message' => 'Product not found'], 404);
            }

            $product->delete();

            return response()->json([
                'message' => 'Product deleted successfully'
            ], 200);
        } catch (\Throwable $e) {
            return $this->handleException($e, 'Unable to delete product');
        }
    }
}
