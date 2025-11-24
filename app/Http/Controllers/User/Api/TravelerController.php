<?php

namespace App\Http\Controllers\User\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Experience;
use Exception;
use App\Models\User;
use App\Http\Requests\ExperienceFilterRequest;
use Illuminate\Support\Facades\DB;

class TravelerController extends Controller
{
    //
    public function getExperiences(Request $request)
    {
        try {
            return response()->json([
                'status' => true,
                'experiences' => Experience::with(['addons', 'media', 'timeSlots'])->get()
            ], 200);
        } catch (Exception $e) {
            return response()->json([
                'status' => false,
                'message' => 'Failed to retrieve experiences',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function getExperienceDetails($id)
    {
        try {

            $experience = Experience::with(['addons', 'media', 'timeSlots'])->findOrFail($id);

            return response()->json([
                'status' => true,
                'data' => $experience
            ], 200);

        } catch (Exception $e) {
            return response()->json([
                'status' => false,
                'message' => 'Experience not found',
                'error' => $e->getMessage()
            ], 404);
        }
    }


    public function getAgencies()
    {
        try {
            $agencies = User::where('role', 'agency')->get();

            return response()->json([
                'status' => true,
                'data' => $agencies
            ], 200);

        } catch (Exception $e) {
            return response()->json([
                'status' => false,
                'message' => 'Failed to retrieve agencies',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function getAgencyDetails($id)
    {
        try {

            $agency = User::with(['experiences', 'experiences.media', 'experiences.addons', 'experiences.timeSlots'])->where('role', 'agency')->findOrFail($id);

            return response()->json([
                'status' => true,
                'data' => $agency
            ], 200);

        } catch (Exception $e) {
            return response()->json([
                'status' => false,
                'message' => 'Agency not found',
                'error' => $e->getMessage()
            ], 404);
        }
    }

    public function getFilteredExperiences(ExperienceFilterRequest $request)
    {
        try {
            return DB::transaction(function () use ($request) {

                $query = Experience::query();

                // 🔍 Search
                if ($request->search) {
                    $query->where('title', 'LIKE', "%{$request->search}%")
                          ->orWhere('description', 'LIKE', "%{$request->search}%");
                }

                // 💲 Price Range
                if ($request->min_price !== null && $request->max_price !== null) {
                    $query->whereBetween('price', [
                        $request->min_price,
                        $request->max_price
                    ]);
                }

                // 🏷 Category Filter
                if ($request->category && $request->category !== "all") {
                    $query->where('category', $request->category);
                }

                // ⏳ Duration Filter
                if ($request->duration) {
                    $query->where('duration', $request->duration);
                }

                // ⭐ Rating Filter
                if ($request->rating) {
                    $query->where('rating', '>=', $request->rating);
                }

                // Pagination
                $experiences = $query->orderBy('id', 'desc')->paginate(10);

                return response()->json([
                    'success' => true,
                    'message' => 'Experiences fetched successfully.',
                    'data'    => $experiences
                ], 200);
            });

        } catch (Exception $e) {

            return response()->json([
                'success' => false,
                'message' => 'Something went wrong!',
                'error'   => $e->getMessage()
            ], 500);
        }
    }


}
