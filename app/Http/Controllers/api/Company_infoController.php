<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Company_infos;
use Illuminate\Support\Facades\Validator;

class Company_infoController extends Controller
{
    // Get all company info
    public function index()
    {
        try {
            $companyInfo = Company_infos::all();

            if ($companyInfo->isEmpty()) {
                return response()->json([
                    "error" => "Company info not found!."
                ], 404);
            }

            return response()->json([
                "data" => $companyInfo,
                "message" => "Company info retrieved successfully"
            ], 200);
        } catch (\Throwable $e) {
            return $this->handleException($e, 'Unable to retrieve company info');
        }
    }

    // Get details of a single company info
    public function show($id)
    {
        try {
            $companyInfo = Company_infos::find($id);

            if (!$companyInfo) {
                return response()->json([
                    'error' => "Company info not found!."
                ], 404);
            }

            return response()->json([
                "data" => $companyInfo,
                "message" => "Company info retrieved successfully"
            ], 200);
        } catch (\Throwable $e) {
            return $this->handleException($e, 'Unable to retrieve company info details');
        }
    }

    // Create company info
    public function store(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'logo' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
                'email' => 'nullable|email|max:255',
                'phone' => 'nullable|string|max:50',
                'name' => 'required|string|max:255',
                'address' => 'nullable|string|max:500',
                'facebook_link' => 'nullable|url',
                'youtube_link' => 'nullable|url',
                'tiktok_link' => 'nullable|url',
                'telegram_link' => 'nullable|url',
            ]);

            if ($validator->fails()) {
                return response()->json([
                    "status" => "error",
                    "errors" => $validator->errors()
                ], 422);
            }

            $path = null;
            if ($request->hasFile('logo')) {
                $path = $request->file('logo')->store('uploads/company', 'public');
            }

            $companyInfo = Company_infos::create([
                'logo' => $path ? asset('storage/' . $path) : null,
                'email' => $request->email,
                'phone' => $request->phone,
                'name' => $request->name,
                'address' => $request->address,
                'facebook_link' => $request->facebook_link,
                'youtube_link' => $request->youtube_link,
                'tiktok_link' => $request->tiktok_link,
                'telegram_link' => $request->telegram_link,
            ]);

            return response()->json([
                'success' => true,
                'data' => $companyInfo,
                'message' => 'Company info created successfully'
            ], 201);
        } catch (\Throwable $e) {
            return $this->handleException($e, 'Unable to create company info');
        }
    }

    // Update company info
    public function update(Request $request, $id)
    {
        try {
            $companyInfo = Company_infos::find($id);

            if (!$companyInfo) {
                return response()->json([
                    'error' => "Company info not found!."
                ], 404);
            }

            $validator = Validator::make($request->all(), [
                'logo' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
                'email' => 'nullable|email|max:255',
                'phone' => 'nullable|string|max:50',
                'name' => 'sometimes|required|string|max:255',
                'address' => 'nullable|string|max:500',
                'facebook_link' => 'nullable|url',
                'youtube_link' => 'nullable|url',
                'tiktok_link' => 'nullable|url',
                'telegram_link' => 'nullable|url',
            ]);

            if ($validator->fails()) {
                return response()->json([
                    "status" => "error",
                    "errors" => $validator->errors()
                ], 422);
            }

            if ($request->hasFile('logo')) {
                $path = $request->file('logo')->store('uploads/company', 'public');
                $companyInfo->logo = asset('storage/' . $path);
            }

            foreach (['email', 'phone', 'name', 'address', 'facebook_link', 'youtube_link', 'tiktok_link', 'telegram_link'] as $field) {
                if ($request->has($field)) {
                    $companyInfo->$field = $request->$field;
                }
            }

            $companyInfo->save();

            return response()->json([
                'success' => true,
                'data' => $companyInfo,
                'message' => 'Company info updated successfully'
            ], 200);
        } catch (\Throwable $e) {
            return $this->handleException($e, 'Unable to update company info');
        }
    }

    // Delete company info
    public function destroy($id)
    {
        try {
            $companyInfo = Company_infos::find($id);

            if (!$companyInfo) {
                return response()->json([
                    'error' => "Company info not found!."
                ], 404);
            }

            $companyInfo->delete();

            return response()->json([
                'message' => 'Company info deleted successfully'
            ], 200);
        } catch (\Throwable $e) {
            return $this->handleException($e, 'Unable to delete company info');
        }
    }
}