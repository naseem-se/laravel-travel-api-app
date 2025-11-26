<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;

class ExperienceUpdateRequest extends FormRequest
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
            'title'       => 'sometimes|required|string|max:255',
            'category'    => 'sometimes|required|string|max:255|in:adventure,leisures,culture',
            'description' => 'sometimes|required|string',
            'address'     => 'sometimes|required|string',
            'price'       => 'sometimes|required|numeric|min:0',
            'duration'    => 'sometimes|required|string|max:255',
            'terms'       => 'nullable|string',
            'latitude'    => 'nullable|numeric',
            'longitude'   => 'nullable|numeric',
            'max_people'  => 'nullable|integer|min:1',

            'addons'         => 'nullable|array',
            'addons.*.title' => 'required_with:addons|string',
            'addons.*.price' => 'required_with:addons|numeric|min:0',

            'media'         => 'nullable|array',
            'media.*.type'  => 'required_with:media|in:image,video',
            'media.*.file'  => 'sometimes|file|mimes:jpeg,png,jpg,gif,svg,mp4,webm,avi,mpg,mkv|max:1000000',

            'time_slots'               => 'nullable|array',
            'time_slots.*.start_day'   => 'required_with:time_slots|date',
            'time_slots.*.end_day'     => 'required_with:time_slots|date',
            'time_slots.*.start_time'  => 'required_with:time_slots',
            'time_slots.*.end_time'    => 'required_with:time_slots',
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
