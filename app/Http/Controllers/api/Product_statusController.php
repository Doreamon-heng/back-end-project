<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Product_status;
use Illuminate\Support\Facades\Validator;

class Product_statusController extends Controller
{
    // ✅ Get all product statuses
    public function getAllStatus()
    {
        try {
            $product_statuses = Product_status::with('product')->get();

            if ($product_statuses->isEmpty()) {
                return response()->json([
                    'success' => false,
                    'message' => 'No product statuses found.',
                    'data'    => []
                ], 404);
            }

            return response()->json([
                'success' => true,
                'message' => 'Product statuses retrieved successfully.',
                'data'    => $product_statuses
            ], 200);

        } catch (\Throwable $e) {
            return response()->json([
                'success' => false,
                'message' => 'Unable to fetch product statuses.',
                'error'   => $e->getMessage()
            ], 500);
        }
    }

    // ✅ Get single product status by ID
    public function getStatus($id)
    {
        try {
            $status = Product_status::with('product')->find($id);

            if (!$status) {
                return response()->json([
                    'success' => false,
                    'message' => 'Product status not found.'
                ], 404);
            }

            return response()->json([
                'success' => true,
                'message' => 'Product status retrieved successfully.',
                'data'    => $status
            ], 200);

        } catch (\Throwable $e) {
            return response()->json([
                'success' => false,
                'message' => 'Unable to fetch product status.',
                'error'   => $e->getMessage()
            ], 500);
        }
    }

    // ✅ Create new product status
    public function createStatus(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'status_name' => 'required|string|max:255',
            'product_id'  => 'required|exists:products,id'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors'  => $validator->errors()
            ], 422);
        }

        try {
            $status = Product_status::create($request->all());

            return response()->json([
                'success' => true,
                'message' => 'Product status created successfully.',
                'data'    => $status
            ], 201);

        } catch (\Throwable $e) {
            return response()->json([
                'success' => false,
                'message' => 'Unable to create product status.',
                'error'   => $e->getMessage()
            ], 500);
        }
    }

    // ✅ Update product status
    public function updateStatus(Request $request, $id)
    {
        try {
            $status = Product_status::find($id);

            if (!$status) {
                return response()->json([
                    'success' => false,
                    'message' => 'Product status not found.'
                ], 404);
            }

            $status->update($request->all());

            return response()->json([
                'success' => true,
                'message' => 'Product status updated successfully.',
                'data'    => $status
            ], 200);

        } catch (\Throwable $e) {
            return response()->json([
                'success' => false,
                'message' => 'Unable to update product status.',
                'error'   => $e->getMessage()
            ], 500);
        }
    }

    // ✅ Delete product status
    public function deleteStatus($id)
    {
        try {
            $status = Product_status::find($id);

            if (!$status) {
                return response()->json([
                    'success' => false,
                    'message' => 'Product status not found.'
                ], 404);
            }

            $status->delete();

            return response()->json([
                'success' => true,
                'message' => 'Product status deleted successfully.'
            ], 200);

        } catch (\Throwable $e) {
            return response()->json([
                'success' => false,
                'message' => 'Unable to delete product status.',
                'error'   => $e->getMessage()
            ], 500);
        }
    }
}
