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
use App\Models\UserDetail;
use Illuminate\Support\Facades\Storage;

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
            $user = $request->user();
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

    public function storeUserDetails(Request $request)
    {
        $user = $request->user();
        $userType = $user->role; 

        if($user->user_details){
            return response()->json([
                'success' => false,
                'message' => ['User details already exists. You can update it from your profile.'],
            ], 400);
        }

        try {
            // ------------------------------
            // Dynamic Validation Rules
            // ------------------------------
            $rules = [
                'languages' => 'required|string|max:255',
                'currency' => 'required|string|max:255',

                'id_upload' => 'nullable|file|mimes:png,jpg,jpeg,pdf|max:10240',
                'business_certificate' => 'nullable|file|mimes:png,jpg,jpeg,pdf|max:10240',
                'license' => 'nullable|file|mimes:png,jpg,jpeg,pdf|max:10240',

                'description' => 'nullable|string',
                'cultural_experience' => 'nullable|string',

                'upload_photos.*' => 'nullable|file|mimes:png,jpg,jpeg,pdf|max:10240'
            ];

            if ($userType === 'agency' || $userType === 'local_guide') {
                $rules['id_upload'] = 'required|file|mimes:png,jpg,jpeg,pdf|max:10240';
                $rules['business_certificate'] = 'required|file|mimes:png,jpg,jpeg,pdf|max:10240';
                $rules['description'] = 'required|string';
                $rules['cultural_experience'] = 'required|string';
                $rules['upload_photos'] = 'required|array|min:1';
                $rules['upload_photos.*'] = 'required|file|mimes:png,jpg,jpeg,pdf|max:10240';
            }

            $validated = Validator::make($request->all(), $rules);

            if ($validated->fails()) {
                return response()->json([
                    'success' => false,
                    'errors' =>  $validated->errors()->all()
                ], 422);
            }

            DB::beginTransaction();

            // Upload single files
            $idUpload = $request->file('id_upload')?->store('uploads/id', 'public');
            $businessCertificate = $request->file('business_certificate')?->store('uploads/business', 'public');
            $license = $request->file('license')?->store('uploads/license', 'public');

            // Multiple upload photos
            $photos = [];
            if ($request->hasFile('upload_photos')) {
                foreach ($request->file('upload_photos') as $file) {
                    $photos[] = $file->store('uploads/photos', 'public');
                }
            }

            $languages = $request->input('languages');

            $details = UserDetail::create([
                'user_id' => $user->id,
                'languages' => $languages,
                'currency' => $request->currency,
                'id_upload' => $userType != 'traveler' ? $idUpload : null,
                'business_certificate' => $userType != 'traveler' ? $businessCertificate : null,
                'license' => $userType != 'traveler' ? $license : null,
                'description' => $userType != 'traveler' ? $request->description : null,
                'cultural_experience' => $userType != 'traveler' ? $request->cultural_experience : null,
                'upload_photos' =>  $userType != 'traveler' ? $photos : null,

            ]);

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'User details updated successfully.',
                'data' => $details
            ]);

        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'message' => 'Something went wrong.',
                'error' => $e->getMessage()
            ], 500);
        }
    }


    public function updateUserDetails(Request $request)
    {
        $user = $request->user();
        $userType = $user->role;

        try {
            $rules = [
                'languages' => 'required|string|max:255',
                'currency' => 'required|string|max:255',

                'id_upload' => 'nullable|file|mimes:png,jpg,jpeg,pdf|max:10240',
                'business_certificate' => 'nullable|file|mimes:png,jpg,jpeg,pdf|max:10240',
                'license' => 'nullable|file|mimes:png,jpg,jpeg,pdf|max:10240',

                'description' => 'nullable|string',
                'cultural_experience' => 'nullable|string',

                'upload_photos.*' => 'nullable|file|mimes:png,jpg,jpeg,pdf|max:10240'
            ];

            if ($userType === 'agency' || $userType === 'local_guide') {
                $rules['id_upload'] = 'required|file|mimes:png,jpg,jpeg,pdf|max:10240';
                $rules['business_certificate'] = 'required|file|mimes:png,jpg,jpeg,pdf|max:10240';
                $rules['description'] = 'required|string';
                $rules['cultural_experience'] = 'required|string';
                $rules['upload_photos'] = 'required|array|min:1';
                $rules['upload_photos.*'] = 'required|file|mimes:png,jpg,jpeg,pdf|max:10240';
            }

            $validated = Validator::make($request->all(), $rules);
            if ($validated->fails()) {
                return response()->json([
                    'success' => false,
                    'errors' => $validated->errors()
                ], 422);
            }

            DB::beginTransaction();

            $details = UserDetail::where('user_id', $user->id)->first();

            if (!$details) {
                return response()->json([
                    'success' => false,
                    'message' => 'User details not found.'
                ], 404);
            }

            // Upload updated files
            if ($request->hasFile('id_upload')) {
                if($details->id_upload){
                    Storage::disk('public')->delete($details->id_upload);
                }
                $details->id_upload = $request->file('id_upload')->store('uploads/id', 'public');
                $details->id_upload_status = 'pending';
            }

            if ($request->hasFile('business_certificate')) {
                if($details->business_certificate){
                    Storage::disk('public')->delete($details->business_certificate);
                }
                $details->business_certificate = $request->file('business_certificate')->store('uploads/business', 'public');
                $details->business_certificate_status = 'pending';
            }

            if ($request->hasFile('license')) {
                if($details->license){
                    Storage::disk('public')->delete($details->license);
                }
                $details->license = $request->file('license')->store('uploads/license', 'public');
                $details->license_status = 'pending';
            }

            if ($request->hasFile('upload_photos')) {
                if($details->upload_photos){
                    foreach ($details->upload_photos as $photo) {
                        Storage::disk('public')->delete($photo);
                    }
                }
                $photos = [];
                foreach ($request->file('upload_photos') as $file) {
                    $photos[] = $file->store('uploads/photos', 'public');
                }
                $details->upload_photos = $photos;
            }

            $languages = $request->input('languages');

            // Update normal fields
            $details->languages = $languages;
            $details->currency = $request->currency;
            $details->description = $userType != 'traveler' ? $request->description : null;
            $details->cultural_experience = $userType != 'traveler' ? $request->cultural_experience : null;
            $details->overall_status = $userType != 'traveler' ? 'pending' : $details->overall_status;

            $details->save();

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'User details updated successfully.',
                'data' => $details
            ]);

        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'message' => 'Failed to update user details.',
                'error' => $e->getMessage()
            ], 500);
        }
    }



}
