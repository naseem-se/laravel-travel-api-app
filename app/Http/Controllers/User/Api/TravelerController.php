<?php

namespace App\Http\Controllers\User\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Experience;
use Exception;
use App\Models\User;
use App\Http\Requests\ExperienceFilterRequest;
use Illuminate\Support\Facades\DB;
use App\Http\Requests\ExperienceRatingRequest;
use App\Models\ExperienceRating;
use App\Models\UserRating;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;


class TravelerController extends Controller
{
    //
    public function getExperiences(Request $request)
    {
        try {
            return response()->json([
                'status' => true,
                'experiences' => Experience::where('status', 'active')->get()
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

    public function getGuides()
    {
        try {
            $guides = User::where('role', 'local_guide')->get();

            return response()->json([
                'status' => true,
                'data' => $guides
            ], 200);

        } catch (Exception $e) {
            return response()->json([
                'status' => false,
                'message' => 'Failed to retrieve guides',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function getGuideDetails($id)
    {
        try {

            $guide = User::with(['experiences', 'experiences.media', 'experiences.addons', 'experiences.timeSlots'])->where('role', 'local_guide')->findOrFail($id);

            return response()->json([
                'status' => true,
                'data' => $guide
            ], 200);

        } catch (Exception $e) {
            return response()->json([
                'status' => false,
                'message' => 'Guide not found',
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
                    'data' => $experiences
                ], 200);
            });

        } catch (Exception $e) {

            return response()->json([
                'success' => false,
                'message' => 'Something went wrong!',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function getFilteredGuides(Request $request)
    {
        $validated = Validator::make($request->all(), [
            'search' => 'nullable|string|max:100',
            'min_price' => 'nullable|numeric|min:0',
            'max_price' => 'nullable|numeric|min:0',
            'languages' => 'nullable|array',
            'languages.*' => 'string|max:30',
            'status' => 'nullable|in:all,licensed,unlicensed',
            'duration' => 'nullable|in:1,2,3,4,5,6,7,8,9,10',
            'rating' => 'nullable|numeric|min:1|max:5'
        ]);

        if ($validated->fails()) {
            return response()->json([
                'success' => false,
                'message' => $validated->errors()->all()
            ], 422);
        }

        try {
            return DB::transaction(function () use ($request) {

                $query = User::with('user_details')
                    ->where('role', 'local_guide')
                    ->whereHas('user_details');

                // 🔍 Search (full_name + details.description)
                if ($request->filled('search')) {
                    $search = $request->search;

                    $query->where(function ($q) use ($search) {
                        $q->where('full_name', 'LIKE', "%{$search}%")
                            ->orWhereHas('user_details', function ($d) use ($search) {
                                $d->where('description', 'LIKE', "%{$search}%");
                            });
                    });
                }

                // 🗣 Languages Filter
                if (is_array($request->input('languages'))) {
                    $languages = $request->input('languages');

                    $query->whereHas('user_details', function ($q) use ($languages) {
                        foreach ($languages as $lang) {
                            $q->whereJsonContains('languages', $lang);
                        }
                    });
                }

                // 📜 License Status Filter
                if ($request->status === 'licensed') {
                    $query->whereHas(
                        'user_details',
                        fn($q) =>
                        $q->where('license_status', 'verified')
                    );
                } elseif ($request->status === 'unlicensed') {
                    $query->whereHas(
                        'user_details',
                        fn($q) =>
                        $q->whereNull('license_status')
                            ->orWhere('license_status', '!=', 'verified')
                    );
                }

                // ⭐ Rating Filter
                if ($request->filled('rating')) {
                    $query->where('rating', '>=', $request->rating);
                }

                // 📄 Pagination
                $guides = $query->latest()->paginate(10);

                return response()->json([
                    'success' => true,
                    'message' => 'Guides fetched successfully.',
                    'data' => $guides
                ], 200);
            });

        } catch (Exception $e) {

            return response()->json([
                'success' => false,
                'message' => 'Something went wrong!',
                'error' => $e->getMessage()
            ], 500);
        }

    }
    public function getFilteredAgencies(Request $request)
    {
        $validated = Validator::make($request->all(), [
            'search' => 'nullable|string|max:100',
            'min_price' => 'nullable|numeric|min:0',
            'max_price' => 'nullable|numeric|min:0',
            'languages' => 'nullable|array',
            'languages.*' => 'string|max:30',
            'status' => 'nullable|in:all,licensed,unlicensed',
            'duration' => 'nullable|in:1,2,3,4,5,6,7,8,9,10',
            'rating' => 'nullable|numeric|min:1|max:5'
        ]);

        if ($validated->fails()) {
            return response()->json([
                'success' => false,
                'message' => $validated->errors()->all()
            ], 422);
        }

        try {
            return DB::transaction(function () use ($request) {

                $query = User::with('user_details')
                    ->where('role', 'agency')
                    ->whereHas('user_details');

                // 🔍 Search (full_name + details.description)
                if ($request->filled('search')) {
                    $search = $request->search;

                    $query->where(function ($q) use ($search) {
                        $q->where('full_name', 'LIKE', "%{$search}%")
                            ->orWhereHas('user_details', function ($d) use ($search) {
                                $d->where('description', 'LIKE', "%{$search}%");
                            });
                    });
                }

                // 🗣 Languages Filter
                if (is_array($request->input('languages'))) {
                    $languages = $request->input('languages');

                    $query->whereHas('user_details', function ($q) use ($languages) {
                        foreach ($languages as $lang) {
                            $q->whereJsonContains('languages', $lang);
                        }
                    });
                }

                // 📜 License Status Filter
                if ($request->status === 'licensed') {
                    $query->whereHas(
                        'user_details',
                        fn($q) =>
                        $q->where('license_status', 'verified')
                    );
                } elseif ($request->status === 'unlicensed') {
                    $query->whereHas(
                        'user_details',
                        fn($q) =>
                        $q->whereNull('license_status')
                            ->orWhere('license_status', '!=', 'verified')
                    );
                }

                // ⭐ Rating Filter
                if ($request->filled('rating')) {
                    $query->where('rating', '>=', $request->rating);
                }

                // 📄 Pagination
                $guides = $query->latest()->paginate(10);

                return response()->json([
                    'success' => true,
                    'message' => 'Agencies fetched successfully.',
                    'data' => $guides
                ], 200);
            });

        } catch (Exception $e) {

            return response()->json([
                'success' => false,
                'message' => 'Something went wrong!',
                'error' => $e->getMessage()
            ], 500);
        }

    }

    public function addRating(ExperienceRatingRequest $request)
    {
        try {

            return DB::transaction(function () use ($request) {

                $experience = Experience::findOrFail($request->experience_id);

                // Prevent multiple ratings from same user
                $existing = ExperienceRating::where('experience_id', $experience->id)
                    ->where('user_id', $request->id())
                    ->first();

                if ($existing) {
                    return response()->json([
                        'success' => false,
                        'message' => 'You have already rated this experience.'
                    ], 409);
                }

                // Save rating
                ExperienceRating::create([
                    'experience_id' => $experience->id,
                    'user_id' => $request->id(),
                    'rating' => $request->rating,
                    'comment' => $request->comment,
                ]);

                // Recalculate average rating
                $avgRating = ExperienceRating::where('experience_id', $experience->id)->avg('rating');

                $experience->update([
                    'rating' => number_format($avgRating, 2) // store avg rating
                ]);

                return response()->json([
                    'success' => true,
                    'message' => 'Rating added successfully.',
                    'data' => [
                        'average_rating' => $experience->rating
                    ]
                ], 201);
            });

        } catch (Exception $e) {

            return response()->json([
                'success' => false,
                'message' => 'Something went wrong!',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function addUserRating(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'user_id' => 'required|exists:users,id',
            'rating' => 'required|integer|min:1|max:5',
            'review' => 'nullable|string|max:1000',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => $validator->errors()->all()
            ], 422);
        }

        try {
            DB::beginTransaction();

            $rating = UserRating::updateOrCreate(
                [
                    'user_id' => $request->user_id,
                    'traveler_id' => $request->id() // logged-in traveler
                ],
                [
                    'rating' => $request->rating,
                    'review' => $request->review
                ]
            );

            $user = User::findOrFail($request->user_id);
            $averageRating = UserRating::where('user_id', $user->id)->avg('rating');
            $user->rating = number_format($averageRating, 2);
            $user->save();

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Rating submitted successfully',
                'data' => $rating
            ]);

        } catch (Exception $e) {
            DB::rollBack();
            return response()->json(['
                success' => false,
                'message' => 'Failed to submit rating',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function getUserRatings($userId)
    {
        $ratings = UserRating::with('traveler:id,name')->where('user_id', $userId)->get();
        $average = $ratings->avg('rating');

        return response()->json([
            'success' => true,
            'average_rating' => $average,
            'ratings' => $ratings
        ]);
    }


}
