<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;

class StoreBookingRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(){ return auth()->check(); }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules()
    {
        return [
            'experience_id' => 'required|exists:experiences,id',
            'start_at' => 'nullable|date|after:now',
            'guests' => 'required|integer|min:1',
            'addons.*.title' => 'required_with:addons|string',
            'addons.*.price' => 'required_with:addons|numeric|min:0',
            'payment_provider' => 'required|in:stripe,paypal',
            'currency' => 'nullable|string|size:3',
            'total_amount' => 'required|numeric|min:0',
        ];
    }

    public function failedValidation(Validator $validator)
    {
        throw new HttpResponseException(
            response()->json([
                'message' => $validator->errors()->all(),
                'success' => false,
            ], 422)
        );
    }

}
