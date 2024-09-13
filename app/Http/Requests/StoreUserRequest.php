<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;
class StoreUserRequest extends FormRequest
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
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'password' => ['required', 'string', 'min:8'],
        ];
    }

    /**
     * Get the validation messages that apply to the request.
     *
     * @return array<string, string>
     */

    public function messages(): array
    {
        return [
            'name.required' => 'Name is required',
            'name.string' => 'Name must be a string',
            'name.max' => 'Name must not be greater than 255 characters',
            'email.required' => 'Email is required',
            'email.string' => 'Email must be a string',
            'email.email' => 'Email is invalid',
            'email.max' => 'Email must not be greater than 255 characters',
            'email.unique' => 'Email is already taken',
            'password.required' => 'Password is required',
            'password.string' => 'Password must be a string',
            'password.min' => 'Password must not be less than 8 characters',
        ];
    }

    // failedValidation
    protected function failedValidation(Validator $validator)
    {

         $response=[
            'meta' => [
                'success' => false,
                'code' => 400,
                'message' => 'failed'
            ],
            'data' => $validator->errors()->setFormat(':message')->all()
        ];

        throw new HttpResponseException(response()->json($response, 400));
    }

}