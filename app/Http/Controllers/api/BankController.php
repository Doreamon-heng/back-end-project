<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Bank;
use Validator;

class BankController extends Controller
{
    // Get all banks
    public function getBank()
    {
        try {
            $bank = Bank::all();

            if ($bank->isEmpty()) {
                return response()->json([
                    "error" => true,
                    "message" => "Bank not found!."
                ], 404);
            }

            return response()->json([
                "data" => $bank,
                "message" => "Bank get successfully!."
            ]);
        } catch (\Throwable $e) {
            return $this->handleException($e, 'Unable to retrieve Bank');
        }
    }

    // Get details of a single bank
    public function detailsBank($id)
    {
        try {
            $bank = Bank::findOrFail($id);

            if (!$bank) {
                return response()->json([
                    "error" => true,
                    "message" => "Bank not found!."
                ], 404);
            }

            return response()->json([
                "data" => $bank,
                "message" => "Bank retrieved successfully!."
            ]);
        } catch (\Throwable $e) {
            return $this->handleException($e, 'Unable to retrieve Bank details');
        }
    }

    // Create a new bank
    public function createBank(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'name' => 'required|string|max:255',
                'qr_code' => 'nullable|string'
            ]);

            if ($validator->fails()) {
                return response()->json([
                    "error" => true,
                    "message" => $validator->errors()->first(),
                    "errors" => $validator->errors()
                ], 422);
            }

            $bank = Bank::create($validator->validated());

            return response()->json([
                "data" => $bank,
                "message" => "Bank created successfully!."
            ], 201);
        } catch (\Throwable $e) {
            return $this->handleException($e, 'Unable to create Bank');
        }
    }

    // Update an existing bank
    public function update(Request $request, $id)
    {
        try {
            $bank = Bank::find($id);

            if (!$bank) {
                return response()->json([
                    "error" => true,
                    "message" => "Bank not found!."
                ], 404);
            }

            $validator = Validator::make($request->all(), [
                'name' => 'sometimes|required|string|max:255',
                // add other fields here as needed
            ]);

            if ($validator->fails()) {
                return response()->json([
                    "error" => true,
                    "message" => $validator->errors()->first(),
                    "errors" => $validator->errors()
                ], 422);
            }

            $bank->update($validator->validated());

            return response()->json([
                "data" => $bank,
                "message" => "Bank updated successfully!."
            ]);
        } catch (\Throwable $e) {
            return $this->handleException($e, 'Unable to update Bank');
        }
    }

    // Delete a bank
    public function destroy($id)
    {
        try {
            $bank = Bank::find($id);

            if (!$bank) {
                return response()->json([
                    "error" => true,
                    "message" => "Bank not found!."
                ], 404);
            }

            $bank->delete();

            return response()->json([
                "error" => false,
                "message" => "Bank deleted successfully!."
            ]);
        } catch (\Throwable $e) {
            return $this->handleException($e, 'Unable to delete Bank');
        }
    }
}