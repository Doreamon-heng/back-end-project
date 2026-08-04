<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Warranty;
use Illuminate\Support\Facades\Validator;

class WarrantyController extends Controller
{
    //get all warranty
    public function index()
    {
        try {
            $warranties = Warranty::with('product')->get();
            if ($warranties->isEmpty()) {
                return response()->json([
                    'message' => 'No warranties found'
                ], 404);
            }
            return response()->json([
                'data' => $warranties,
                'message' => 'Warranties retrieved successfully'
            ], 200);
        } catch (\Throwable $e) {
            return $this->handleException($e, 'Unable to retrieve warranties');
        }
    }
    //get warranty by id
    public function show($id)
    {
        try {
            $warranty = Warranty::with('product')->find($id);
            if (!$warranty) {
                return response()->json([
                    'message' => 'Warranty not found'
                ], 404);
            }
            return response()->json([
                'data' => $warranty,
                'message' => 'Warranty retrieved successfully'
            ], 200);
        } catch (\Throwable $e) {
            return $this->handleException($e, 'Unable to retrieve warranty details');
        }
    }

    //create new warranty
    public function store(Request $r)
    {
        try {
            $validator = Validator::make($r->all(), [
                'product_id' => 'required|integer|exists:products,id',
                'warranty_period' => 'required|string|max:255',
            ]);

            if ($validator->fails()) {
                return response()->json(['error' => $validator->errors()], 422);
            }

            $warranty = Warranty::create([
                'product_id' => $r->product_id,
                'warranty_period' => $r->warranty_period,
            ]);

            return response()->json([
                'message' => 'Warranty created successfully',
                'data' => $warranty,
            ], 201);

        } catch (\Throwable $e) {
            return $this->handleException($e, 'Unable to create warranty');
        }
    }

    //update warranty
    public function update(Request $r, $id)
    {
        try {
            $warranty = Warranty::find($id);
            if (!$warranty) {
                return response()->json([
                    'message' => 'Warranty not found'
                ], 404);
            }

            $validator = Validator::make($r->all(), [
                'product_id' => 'nullable|integer|exists:products,id',
                'warranty_period' => 'nullable|string|max:255',
            ]);

            if ($validator->fails()) {
                return response()->json(['error' => $validator->errors()], 422);
            }

            $warranty->update($r->only(['product_id', 'warranty_period']));

            return response()->json([
                'message' => 'Warranty updated successfully',
                'data' => $warranty,
            ], 200);

        } catch (\Throwable $e) {
            return $this->handleException($e, 'Unable to update warranty');
        }
    }

    //delete warranty
    public function destroy($id)
    {
        try {
            $warranty = Warranty::find($id);
            if (!$warranty) {
                return response()->json([
                    'message' => 'Warranty not found'
                ], 404);
            }
        } catch (\Throwable $e) {
            return $this->handleException($e, 'Unable to delete warranty');
        }
    }
}
