<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UserUpdateRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        // return true;
        return (auth()->guard('api')->user()->user_type->getPrecedence() == 1) || (auth()->guard('api')->user()->id == $this->id);
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'name' => 'sometimes|required|string|min:3|max:255',
            'user_type' => 'sometimes|required|string',
            'email' => 'sometimes|required|email|max:255|unique:users,email,' . $this->id,
            'password' => 'sometimes|confirmed|required|min:8|max:50',
        ];
    }
}
