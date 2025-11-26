<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;

class ExperienceStoreRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return auth()->check();
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules()
    {
        return [
            'title'       => 'required|string|max:255',
            'category'    => 'required|string|max:255|in:adventure,leisures,culture',
            'description' => 'required|string',
            'address'     => 'required|string',
            'price'       => 'required|numeric|min:0',
            'duration'    => 'required|string|max:255',
            'terms'       => 'nullable|string',
            'latitude'    => 'nullable|numeric',
            'longitude'   => 'nullable|numeric',
            'max_people'   => 'nullable|integer|min:1',

            // Addons
            'addons'                 => 'nullable|array',
            'addons.*.title'          => 'required|string',
            'addons.*.price'         => 'required|numeric|min:0',

            // Media
            'media'                  => 'required|array',
            'media.*.type'           => 'required|in:image,video',
            // 'media.*.file'           => 'required|string',
            'media.*.file'           => 'required|file|mimes:jpeg,png,jpg,gif,svg,mp4,webm,avi,mpg,mkv|max:1000000',

            // Timeslots
            'time_slots'                => 'required|array',
            'time_slots.*.start_day'   => 'required|date',
            'time_slots.*.end_day'     => 'required|date',
            'time_slots.*.start_time'   => 'required',
            'time_slots.*.end_time'     => 'required',
        ];
    }

    protected function failedValidation(Validator $validator)
    {
        throw new HttpResponseException(
            response()->json([
                'message' => $validator->errors()->all(),
                'success' => false,
            ], 422)
        );
    }
}
