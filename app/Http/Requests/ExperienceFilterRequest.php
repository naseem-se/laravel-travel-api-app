<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;

class ExperienceFilterRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'search'     => 'nullable|string|max:255',
            'min_price'  => 'nullable|numeric|min:0',
            'max_price'  => 'nullable|numeric|min:0',
            'category'   => 'nullable|string|in:all,adventure,leisures,culture',
            'duration'   => 'nullable|string|max:50',
            'rating'     => 'nullable|numeric|min:1|max:5',
            'page'       => 'nullable|integer|min:1',
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
