<?php

namespace App\Http\Controllers\User\Api;

use App\Events\UserEmailVerification;
use App\Http\Controllers\Controller;
use App\Http\Requests\DocumentRequest;
use App\Http\Requests\EmailVerificationRequest;
use App\Http\Requests\LoginRequest;
use App\Http\Requests\RegisterRequest;
use App\Http\Requests\ResendOtpRequest;
use App\Http\Requests\ResetPasswordRequest;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use App\Events\EmailChangeRequested;
use Illuminate\Support\Facades\Validator;

class AuthController extends Controller
{
    public function register(RegisterRequest $request)
    {
        try {
            $validated = $request->validated();
            DB::beginTransaction();

            $code = rand(100000, 999999);

            $user = User::create([
                'full_name' => $validated['full_name'],
                'email' => $validated['email'],
                'phone_number' => $validated['phone_number'],
                'password' => bcrypt($validated['password']),
                'code' => $code,
            ]);


            // 🔥 Fire event after successful registration
            event(new UserEmailVerification($user));
            DB::commit();

            return response()->json([
                'success' => true,
                'message' => ['Registered successfully. Please check your email to verify your account.'],
            ]);
        } catch (\Throwable $th) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'message' => [$th->getMessage()],
            ]);
        }
    }

    public function verifyEmail(EmailVerificationRequest $request)
    {
        try {
            $validated = $request->validated();
            DB::beginTransaction();
            $user = User::where('code', $validated['code'])->first();
            if (!$user) {
                DB::rollBack();
                return response()->json([
                    'success' => false,
                    'message' => ['Invalid code.'],
                ]);
            }
            $user->update([
                'is_verified' => true,
                'code' => 0,
                'email_verified_at' => now(),
            ]);
            DB::commit();
            if ($request['forget_password']) {
                $token = $user->createToken('forget_password')->plainTextToken;
                return response()->json([
                    'success' => true,
                    'message' => ['Email verified successfully.'],
                    'token' => $token,
                ]);
            }
            return response()->json([
                'success' => true,
                'message' => ['Email verified successfully.'],
            ]);
        } catch (\Throwable $th) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'message' => [$th->getMessage()],
            ]);
        }
    }

    public function resendOtp(ResendOtpRequest $request)
    {
        try {
            $validated = $request->validated();
            DB::beginTransaction();
            $user = User::where('email', $validated['email'])->first();
            if (!$user) {
                DB::rollBack();
                return response()->json([
                    'success' => false,
                    'message' => ['User not found'],
                ]);
            }
            $code = rand(100000, 999999);
            $user->update([
                'code' => $code
            ]);
            event(new UserEmailVerification($user));
            DB::commit();
            return response()->json([
                'success' => true,
                'message' => ['Check your mail, Email sended successfully.'],
            ]);
        } catch (\Throwable $th) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'message' => [$th->getMessage()],
            ]);
        }
    }

    public function forgetPassword(ResendOtpRequest $request)
    {
        try {
            $validated = $request->validated();
            DB::beginTransaction();
            $user = User::where('email', $validated['email'])->first();
            if (!$user) {
                DB::rollBack();
                return response()->json([
                    'success' => false,
                    'message' => ['User not found'],
                ]);
            }
            $code = rand(100000, 999999);
            $user->update([
                'code' => $code
            ]);
            event(new UserEmailVerification($user));
            DB::commit();
            return response()->json([
                'success' => true,
                'message' => ['Check your mail, Email sended successfully.'],
            ]);
        } catch (\Throwable $th) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'message' => [$th->getMessage()],
            ]);
        }
    }

    public function resetPassword(ResetPasswordRequest $request)
    {
        try {
            $validated = $request->validated();
            DB::beginTransaction();
            $user = auth()->user();
            $user->update([
                'password' => bcrypt($validated['password'])
            ]);
            DB::commit();
            return response()->json([
                'success' => true,
                'message' => ['Password reset successfully.'],
            ]);
        } catch (\Throwable $th) {
            DB::rollback();
            return response()->json([
                'success' => false,
                'message' => [$th->getMessage()],
            ]);
        }
    }

    public function login(LoginRequest $request)
    {
        try {
            $validated = $request->validated();
            $user = User::where('email', $validated['email'])->first();
            if (!$user) {
                return response()->json([
                    'success' => false,
                    'message' => ['Email or password is incorrect'],
                ]);
            }
            if (!Hash::check($validated['password'], $user->password)) {
                return response()->json([
                    'success' => false,
                    'message' => ['Email or password is incorrect'],
                ]);
            }
            if (!$user->is_verified) {
                return response()->json([
                    'success' => false,
                    'message' => ['Email not verified'],
                ]);
            }

            // if ($user->role != $validated['role']) {
            //     return response()->json([
            //         'success' => false,
            //         'message' => ['Your account is not registered to this ' . $validated['role']]
            //     ]);
            // }
            $token = $user->createToken('auth_token')->plainTextToken;
            return response()->json([
                'success' => true,
                'message' => ['Login successfully.'],
                'token' => $token,
            ]);
        } catch (\Throwable $th) {
            return response()->json([
                'success' => false,
                'message' => [$th->getMessage()]
            ]);
        }
    }

    public function logout(Request $request)
    {
        try {
            $user = $request->user();

            if (!$user) {
                return response()->json([
                    'success' => false,
                    'message' => ['User not found.'],
                ]);
            }

            $user->tokens()->delete();
            return response()->json([
                'success' => true,
                'message' => ['Logged out successfully.'],
            ]);
        } catch (\Throwable $th) {
            return response()->json([
                'success' => false,
                'message' => [$th->getMessage()],
            ]);
        }
    }

    public function requestEmailChange(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required|email|unique:users,email',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => $validator->errors()->all(),
            ], 422);
        }

        $user = $request->user();

        if (!$user) {
            return response()->json([
                'success' => false,
                'message' => ['Unauthorized User'],
            ], 401);
        }

        try {
            DB::beginTransaction();

            $otp = random_int(100000, 999999);

            $user->email_change_otp = $otp;
            $user->new_email_temp = $request->email;
            $user->save();

            event(new EmailChangeRequested($request->email, $otp));

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => ['OTP sent to the new email address.'],
            ], 200);

        } catch (\Exception $e) {
            DB::rollBack();

            return response()->json([
                'success' => false,
                'message' => ['Something went wrong while processing the request.'],
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    public function confirmEmailChange(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'otp' => 'required|numeric',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => $validator->errors()->all(),
            ], 422);
        }

        $user = $request->user();

        if (!$user) {
            return response()->json([
                'success' => false,
                'message' => ['Unauthorized User'],
            ], 401);
        }

        try {
            DB::beginTransaction();

            if ($user->email_change_otp != $request->otp) {
                return response()->json([
                    'success' => false,
                    'message' => ['Invalid OTP'],
                ], 422);
            }

            $user->email = $user->new_email_temp;
            $user->email_verified_at = now();
            $user->email_change_otp = 0;
            $user->new_email_temp = null;
            $user->save();

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => ['Email changed successfully.'],
            ]);

        } catch (\Exception $e) {
            DB::rollBack();

            return response()->json([
                'success' => false,
                'message' => ['Something went wrong while confirming email change.'],
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    public function updateRole(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'role' => 'required|string|in:agency,traveler,local_guide',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => $validator->errors()->all(),
            ], 422);
        }

        $user = $request->user();

        if (!$user) {
            return response()->json([
                'success' => false,
                'message' => ['Unauthorized User'],
            ], 401);
        }

        try {
            DB::beginTransaction();

            $user->role = $request->role;
            $user->save();

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => ['Role updated successfully.'],
            ], 200);

        } catch (\Exception $e) {
            DB::rollBack();

            return response()->json([
                'success' => false,
                'message' => ['Something went wrong while updating role.'],
                'error' => $e->getMessage(),
            ], 500);
        }
    }

}
