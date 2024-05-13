<?php

namespace App\Http\Requests\API\Admin\Author;

use Illuminate\Foundation\Http\FormRequest;

class AuthorStoreRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
    return auth()->guard('api')->user()->user_type->getPrecedence() < 4;    
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'salutation' => 'required|string|max:5|in:Mr.,Miss.,Mrs.,Ms.',
            'fullname' => 'required|string|min:3|max:255',
            'url_title' => 'required|string|min:3|max:255',
            'slug' => 'required|string|min:3|max:255|unique:authors,slug',
            'email' => 'required|email|max:255|unique:authors,email',
            'status' => 'required|boolean',
            'description' => 'sometimes|nullable|string|max:1500',
            'meta_description' => 'sometimes|nullable|string|max:500',
            'meta_keywords' => 'sometimes|nullable|string|max:500',
            'image' => 'sometimes|image|mimes:jpeg,jpg,bmp,png,svg|max:5120',
            'image_caption' => 'sometimes|nullable|string|max:100|',
            'image_alt_caption' => 'sometimes|nullable|string|max:100',
        ];
    }
}
