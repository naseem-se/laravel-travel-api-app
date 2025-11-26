<?php

namespace App\Http\Controllers\User\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Experience;
use App\Http\Requests\ExperienceStoreRequest;
use App\Http\Requests\ExperienceUpdateRequest;
use Illuminate\Support\Facades\DB;
use Exception;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Storage;

class ExperienceController extends Controller
{
    public function index(Request $request)
    {
        $user = $request->user();

        try{
            return response()->json([
                        'status' => true,
                        'experiences' => Experience::with(['addons', 'media', 'timeSlots'])->where('user_id', $user->id)->get()
                    ], 200);
        } catch (Exception $e) {
            return response()->json([
                'status' => false,
                'message' => 'Failed to retrieve experiences',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function store(ExperienceStoreRequest $request)
    {

        DB::beginTransaction();
        $user = $request->user();
        try {
            // Create Experience
            $experience = Experience::create([
                'user_id' => $user->id,
                'title'   => $request->title,
                'description' => $request->description,
                'address' => $request->address,
                'price' => $request->price,
                'category' => $request->category,
                'duration' => $request->duration,
                'terms' => $request->terms,
                'latitude' => $request->latitude,
                'longitude' => $request->longitude,
                'max_people' => $request->max_people,
            ]);

            // Save Addons
            if ($request->addons) {
                foreach ($request->addons as $addon) {
                    $experience->addons()->create([
                        'experience_id' => $experience->id,
                        'title'  => $addon['title'],
                        'price' => $addon['price']
                    ]);
                }
            }

            // Save Media
            if ($request->media) {
                foreach ($request->media as $media) {
                    $path = $media['file']->store('experience_media', 'public');
                    // $path = $media['file'];

                    $experience->media()->create([
                        'experience_id' => $experience->id,
                        'type'      => $media['type'],
                        'file_path' => $path
                    ]);
                }
            }

            // Save Time Slots
            if ($request->time_slots) {
                foreach ($request->time_slots as $slot) {
                    $experience->timeSlots()->create([
                        'experience_id' => $experience->id,
                        'start_day'       => $slot['start_day'],
                        'end_day'       => $slot['end_day'],
                        'start_time' => $slot['start_time'],
                        'end_time'   => $slot['end_time'],
                        'max_people' => $slot['max_people']
                    ]);
                }
            }

            DB::commit();

            return response()->json([
                'status' => true,
                'message' => 'Experience created successfully',
                'data' => $experience->load(['addons', 'media', 'timeSlots'])
            ], 201);

        } catch (Exception $e) {
            DB::rollBack();

            return response()->json([
                'status' => false,
                'message' => 'Failed to create experience',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function show(Request $request, $id)
    {
        try {
            $user = $request->user();
            $experience = Experience::with(['addons', 'media', 'timeSlots'])->where('user_id', $user->id)->findOrFail($id);

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

    public function update(ExperienceUpdateRequest $request, $id)
    {
        DB::beginTransaction();
        $user = $request->user();

        try {
            $experience = Experience::where('user_id', $user->id)->findOrFail($id);

            if(!$experience){
                return response()->json([
                    'status' => false,
                    'message' => 'Experience not found',
                    'error' => 'Experience not found'
                ], 404);
            }

            $experience->update([
                'title' => $request->input('title', $experience->title),
                'category' => $request->input('category', $experience->category),
                'description' => $request->input('description', $experience->description),
                'address' => $request->input('address', $experience->address),
                'price' => $request->input('price', $experience->price),
                'duration' => $request->input('duration', $experience->duration),
                'terms' => $request->input('terms', $experience->terms),
                'latitude' => $request->input('latitude', $experience->latitude),
                'longitude' => $request->input('longitude', $experience->longitude),
                'max_people' => $request->input('max_people', $experience->max_people),
            ]);

            // Update related models: addons, media and time slots.
            // For simplicity, delete existing related records and recreate from the request.
            if ($request->has('addons')) {
                $experience->addons()->delete();
                foreach ($request->addons as $addon) {
                    $experience->addons()->create([
                        'experience_id' => $experience->id,
                        'title' => $addon['title'],
                        'price' => $addon['price'],
                    ]);
                }
            }

            // Update media only if media comes in request
            if ($request->filled('media')) {

                // 1. Delete old media files
                foreach ($experience->media as $oldMedia) {
                    if (Storage::disk('public')->exists($oldMedia->file_path)) {
                        Storage::disk('public')->delete($oldMedia->file_path);
                    }
                }

                // 2. Delete old database records
                $experience->media()->delete();

                // 3. Save new media files
                foreach ($request->media as $media) {

                    $path = $media['file']->store('experience_media', 'public');

                    $experience->media()->create([
                        'type' => $media['type'],
                        'file_path' => $path,
                    ]);
                }
            }

            if ($request->has('time_slots')) {
                $experience->timeSlots()->delete();
                foreach ($request->time_slots as $slot) {
                    $experience->timeSlots()->create([
                        'experience_id' => $experience->id,
                        'start_day' => $slot['start_day'],
                        'end_day' => $slot['end_day'],
                        'start_time' => $slot['start_time'],
                        'end_time' => $slot['end_time'],
                    ]);
                }
            }


            DB::commit();

            return response()->json([
                'status' => true,
                'message' => 'Experience updated successfully',
                'data' => $experience->load(['addons', 'media', 'timeSlots'])
            ], 200);

        } catch (Exception $e) {
            DB::rollBack();

            return response()->json([
                'status' => false,
                'message' => 'Failed to update experience',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function destroy(Request $request, $id)
    {

        DB::beginTransaction();

        try {
            $user = $request->user();
            $experience = Experience::where('user_id', $user->id)->findOrFail($id);
            $experience->delete();

            DB::commit();

            return response()->json([
                'status' => true,
                'message' => 'Experience deleted successfully'
            ], 200);

        } catch (Exception $e) {
            DB::rollBack();

            return response()->json([
                'status' => false,
                'message' => 'Failed to delete experience',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function changeStatus(Request $request, $id)
    {

        $validator = Validator::make(['id' => $id, 'status' => $request->status], [
            'id' => 'required|integer|exists:experiences,id',
            'status' => 'required|in:active,draft,paused'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => false,
                'message' => $validator->errors()->all(),
            ], 422);
        }

        try {
            $user = $request->user();
            $experience = Experience::where('user_id', $user->id)->findOrFail($id);

            // Toggle status
            $experience->status = $request->status;
            $experience->save();

            return response()->json([
                'status' => true,
                'message' => 'Experience status changed successfully',
                'data' => ['status' => $experience->status]
            ], 200);

        } catch (Exception $e) {
            return response()->json([
                'status' => false,
                'message' => 'Failed to change experience status',
                'error' => $e->getMessage()
            ], 500);
        }
    }


}
