<?php

namespace App\Http\Requests\API\Admin;

use Illuminate\Foundation\Http\FormRequest;

class UserStoreRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return auth()->guard('api')->user()->user_type->getPrecedence() == 1;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'name' => 'required|string|min:3|max:255',
            'user_type' => 'required|string',
            'email' => 'required|email|max:255|unique:users,email',
            'password' => 'required|string|confirmed|min:8|max:50',
        ];
    }
}
