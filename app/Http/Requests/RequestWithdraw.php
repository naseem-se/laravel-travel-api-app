<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;

class RequestWithdraw extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
   public function authorize(){ return auth()->check() && in_array(auth()->user()->role,['local_guide','agency']); }
    public function rules(){ return ['amount' => 'required|numeric|min:1']; }

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
