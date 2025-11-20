<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;

class ChangePasswordRequest extends FormRequest
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
    public function rules(): array
    {
        return [
            'old_password' => 'required',
            'password' => 'required|confirmed|min:8',
        ];
    }

    public function failedValidation(Validator $validator)
    {
        throw new HttpResponseException(
            response()->json(
                [
                    'message' => $validator->errors()->all(),
                    'success' => false
                ],
                422
            )
        );
    }
}
