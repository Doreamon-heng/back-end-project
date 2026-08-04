<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Stock;
use Illuminate\Support\Facades\Validator;

class StockController extends Controller
{
    //get all stock
    public function index()
    {
        try {
            $stocks = Stock::with('product')->get();
            if ($stocks->isEmpty()) {
                return response()->json([
                    'message' => 'No stocks found'
                ], 404);
            }
            return response()->json([
                'data' => $stocks,
                'message' => 'Stocks retrieved successfully'
            ], 200);
        } catch (\Throwable $e) {
            return $this->handleException($e, 'Unable to retrieve stocks');
        }
    }

    //get stock by id
    public function show($id)
    {
        try {
            $stock = Stock::with('product')->find($id);
            if (!$stock) {
                return response()->json([
                    'message' => 'Stock not found'
                ], 404);
            }
            return response()->json([
                'data' => $stock,
                'message' => 'Stock retrieved successfully'
            ], 200);
        } catch (\Throwable $e) {
            return $this->handleException($e, 'Unable to retrieve stock details');
        }


    }
    //create new stock
    public function store(Request $r)
    {
        try {
            $validator = Validator::make($r->all(), [
                'product_id' => 'required|integer|exists:products,id',
                'max_quantity' => 'nullable|integer',
                'min_quantity' => 'nullable|integer',
            ]);
            if ($validator->fails()) {
                return response()->json(['error' => $validator->errors()], 422);
            }
            $stock = new Stock();
            $stock->product_id = $r->product_id;
   
            $stock->save();
            return response()->json([
                'data' => [
                    'id' => $stock->id,
                    'product_id' => $stock->product_id,
  
                ],
                'message' => 'Stock created successfully'
            ], 201);
        } catch (\Throwable $e) {
            return $this->handleException($e, 'Unable to create stock');
        }
    }
}
