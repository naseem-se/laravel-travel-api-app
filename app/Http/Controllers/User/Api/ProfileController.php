<?php

namespace App\Http\Controllers\User\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\ChangePasswordRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Hash;

class ProfileController extends Controller
{
    public function updateProfile(Request $request)
    {
        try {
            // $validator = Validator::make($request->all(), [
            //     'email' => 'required|string|email|max:255|unique:users',
            //     'phone_number' => 'required|string|max:255|unique:users',
            // ]);
            $user = auth()->user();
            $filename = "";
            if ($request->hasFile('image')) {
                $path = public_path('storage\\' . $user->image);
                if (File::exists($path)) {
                    File::delete($path);
                }
                $filename = $request->image->store('profile', 'public');
            } else {
                $filename = $user->image;
            }
            $user->update([
                'address' => $request->address,
                'city' => $request->city,
                'zip_code' => $request->zip_code,
                'image' => $filename
            ]);
            return response()->json([
                'success' => true,
                'message' => 'Profile updated successfully',
                'data' => $user
            ]);
        } catch (\Throwable $th) {
            return response()->json([
                'success' => false,
                'message' => $th->getMessage()
            ]);
        }
    }

    public function changePassword(ChangePasswordRequest $request)
    {
        try {
            $validated = $request->validated();
            $user = auth()->user();
            if (!Hash::check($validated['old_password'], $user->password)) {
                return response()->json([
                    'success' => false,
                    'message' => 'Old password is incorrect'
                ]);
            }
            $user->update([
                'password' => bcrypt($validated['password'])
            ]);
            return response()->json([
                'success' => true,
                'message' => 'Password updated successfully'
            ]);
        } catch (\Throwable $th) {
            return response()->json([
                'success' => false,
                'message' => $th->getMessage()
            ]);
        }
    }
}
