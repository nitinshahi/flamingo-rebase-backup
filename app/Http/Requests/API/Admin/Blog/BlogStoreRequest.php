<?php

namespace App\Http\Requests\API\Admin\Blog;

use Illuminate\Foundation\Http\FormRequest;

class BlogStoreRequest extends FormRequest
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
            'title' => 'required|string|min:2|max:100',
            'url_title' => 'required|string|min:2|max:100|unique:blogs,url_title',
            'abstract' => 'sometimes|string|min:2|max:300',
            'description' => 'required|string|min:2|max:1500',
            'status' => 'required|boolean',
            'is_featured' => 'required|boolean',
            'date' => 'required|date',
            'meta_keywords' => 'sometimes|string|min:2|max:100',
            'meta_description' => 'sometimes|string|min:2|max:100',
            'avatar_image' => 'sometimes|image|mimes:jpeg,jpg,bmp,png,svg|max:5120',
            'avatar_text' => 'sometimes|string|min:2|max:100',
            'avatar_caption' => 'sometimes|string|min:2|max:100',
            'banner_image' => 'sometimes|image|mimes:jpeg,jpg,bmp,png,svg|max:5120',
            'banner_caption' => 'sometimes|string|min:2|max:100',
            'banner_text' => 'sometimes|string|min:2|max:100',
        ];
    }
}
