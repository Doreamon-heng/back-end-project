<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Hash;
use App\Models\Otps;
use App\Models\User;

class OtpsController extends Controller
{
    // get all otps
    public function index()
    {
        try {
            $otps = Otps::all();

            if ($otps->isEmpty()) {
                return response()->json([
                    "error" => true,
                    "message" => "Otps not found!."
                ], 404);
            }

            return response()->json([
                "data" => $otps,
                "message" => "Otps retrieved successfully!."
            ]);
        } catch (\Throwable $e) {
            return $this->handleException($e, 'Unable to retrieve otps');
        }
    }

    // get single otp by id
    public function show($id)
    {
        try {
            $otp = Otps::find($id);

            if (!$otp) {
                return response()->json([
                    "error" => true,
                    "message" => "Otp not found!."
                ], 404);
            }

            return response()->json([
                "data" => $otp,
                "message" => "Otp retrieved successfully!."
            ]);
        } catch (\Throwable $e) {
            return $this->handleException($e, 'Unable to retrieve otp');
        }
    }

    // create a new otp
    public function store(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'email'      => 'required_without:phone|nullable|email',
                'phone'      => 'required_without:email|nullable|string|max:20',
                'code'       => 'required|string|max:10',
                'expires_at' => 'required|date',
                'is_used'    => 'boolean',
            ]);

            if ($validator->fails()) {
                return response()->json([
                    "error" => true,
                    "message" => $validator->errors()
                ], 422);
            }

            $otp = Otps::create($request->all());

            return response()->json([
                "data" => $otp,
                "message" => "Otp created successfully!."
            ], 201);
        } catch (\Throwable $e) {
            return $this->handleException($e, 'Unable to create otp');
        }
    }

    // update an existing otp
    public function update(Request $request, $id)
    {
        try {
            $otp = Otps::find($id);

            if (!$otp) {
                return response()->json([
                    "error" => true,
                    "message" => "Otp not found!."
                ], 404);
            }

            $validator = Validator::make($request->all(), [
                'email'      => 'sometimes|nullable|email',
                'phone'      => 'sometimes|nullable|string|max:20',
                'code'       => 'sometimes|required|string|max:10',
                'expires_at' => 'sometimes|required|date',
                'is_used'    => 'sometimes|boolean',
            ]);

            if ($validator->fails()) {
                return response()->json([
                    "error" => true,
                    "message" => $validator->errors()
                ], 422);
            }

            $otp->update($request->all());

            return response()->json([
                "data" => $otp,
                "message" => "Otp updated successfully!."
            ]);
        } catch (\Throwable $e) {
            return $this->handleException($e, 'Unable to update otp');
        }
    }

    // delete an otp
    public function destroy($id)
    {
        try {
            $otp = Otps::find($id);

            if (!$otp) {
                return response()->json([
                    "error" => true,
                    "message" => "Otp not found!."
                ], 404);
            }

            $otp->delete();

            return response()->json([
                "message" => "Otp deleted successfully!."
            ]);
        } catch (\Throwable $e) {
            return $this->handleException($e, 'Unable to delete otp');
        }
    }

    // verify an otp code against email or phone
    public function verify(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'email' => 'required_without:phone|nullable|email',
                'phone' => 'required_without:email|nullable|string|max:20',
                'code'  => 'required|string|max:10',
            ]);

            if ($validator->fails()) {
                return response()->json([
                    "error" => true,
                    "message" => $validator->errors()
                ], 422);
            }

            $query = Otps::where('code', $request->input('code'))
                ->where('is_used', false)
                ->where('expires_at', '>=', now());

            if ($request->filled('email')) {
                $query->where('email', $request->input('email'));
            } else {
                $query->where('phone', $request->input('phone'));
            }

            $otp = $query->first();

            if (!$otp) {
                return response()->json([
                    "error" => true,
                    "message" => "Invalid or expired OTP code."
                ], 422);
            }

            $otp->update(['is_used' => true]);

            return response()->json([
                "data" => $otp,
                "message" => "Otp verified successfully!."
            ]);
        } catch (\Throwable $e) {
            return $this->handleException($e, 'Unable to verify otp');
        }
    }

    // generate and send a random OTP code — used for forgot-password and change-password flows
    public function requestOtp(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'email' => 'required_without:phone|nullable|email',
                'phone' => 'required_without:email|nullable|string|max:20',
            ]);

            if ($validator->fails()) {
                return response()->json([
                    "error" => true,
                    "message" => $validator->errors()
                ], 422);
            }

            $field = $request->filled('email') ? 'email' : 'phone';
            $value = $request->input($field);

            // make sure the account actually exists before issuing a code
            $user = User::where($field, $value)->first();

            if (!$user) {
                return response()->json([
                    "error" => true,
                    "message" => "No account found with that " . $field . "."
                ], 404);
            }

            // invalidate any previous unused codes for this identifier
            Otps::where($field, $value)
                ->where('is_used', false)
                ->delete();

            // random 6-digit numeric code, e.g. 048213 (keeps leading zeros)
            $code = str_pad((string) random_int(0, 999999), 6, '0', STR_PAD_LEFT);

            $otp = Otps::create([
                $field       => $value,
                'code'       => $code,
                'expires_at' => now()->addMinutes(10),
                'is_used'    => false,
            ]);

            // TODO: send $code via Mail/SMS instead of returning it directly.
            // e.g. Mail::to($value)->send(new OtpCodeMail($code));
            // Not returning the raw code in a real production response — shown
            // here only so you can test the flow end-to-end.
            return response()->json([
                "data" => [
                    "otp_id"     => $otp->id,
                    "expires_at" => $otp->expires_at,
                    "code"       => $code, // remove this line once email/SMS sending is wired up
                ],
                "message" => "Otp code sent successfully!."
            ], 201);
        } catch (\Throwable $e) {
            return $this->handleException($e, 'Unable to send otp');
        }
    }

    // verify the OTP and set a new password in one step
    public function resetPassword(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'email'                 => 'required_without:phone|nullable|email',
                'phone'                 => 'required_without:email|nullable|string|max:20',
                'code'                  => 'required|string|max:10',
                'password'              => 'required|string|min:8|confirmed',
                // expects a matching 'password_confirmation' field
            ]);

            if ($validator->fails()) {
                return response()->json([
                    "error" => true,
                    "message" => $validator->errors()
                ], 422);
            }

            $field = $request->filled('email') ? 'email' : 'phone';
            $value = $request->input($field);

            $otp = Otps::where($field, $value)
                ->where('code', $request->input('code'))
                ->where('is_used', false)
                ->where('expires_at', '>=', now())
                ->first();

            if (!$otp) {
                return response()->json([
                    "error" => true,
                    "message" => "Invalid or expired OTP code."
                ], 422);
            }

            $user = User::where($field, $value)->first();

            if (!$user) {
                return response()->json([
                    "error" => true,
                    "message" => "No account found with that " . $field . "."
                ], 404);
            }

            $user->password = Hash::make($request->input('password'));
            $user->save();

            $otp->update(['is_used' => true]);

            return response()->json([
                "message" => "Password reset successfully!."
            ]);
        } catch (\Throwable $e) {
            return $this->handleException($e, 'Unable to reset password');
        }
    }
}