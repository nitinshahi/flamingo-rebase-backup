<?php

namespace App\Http\Requests\API\Actions;

use Illuminate\Foundation\Http\FormRequest;

class BulkRestore extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return auth()->guard('api')->user()->user_type->getPrecedence() < 3;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'bulk' => 'required|array',
            'bulk.*' => 'required|integer|distinct',
        ];
    }
}
