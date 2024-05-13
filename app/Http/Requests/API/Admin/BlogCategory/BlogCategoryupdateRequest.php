<?php

namespace App\Http\Requests\API\Admin\BlogCategory;

use Illuminate\Foundation\Http\FormRequest;

class BlogCategoryupdateRequest extends FormRequest
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
            'title' => 'sometimes|required|string|min:3|max:100',
            'url_title' => 'sometimes|required|string|min:3|max:100|unique:blog_categories,url_title',
            'status' => 'sometimes|required|boolean',
            'description' => 'sometimes|required|string|max:2000',
            'meta_title' => 'sometimes|required|string|min:3|max:100',
            'meta_keywords' => 'sometimes|required|string|',
            'meta_description' => 'sometimes|required|string|',
            'image' => 'sometimes|image|mimes:jpeg,jpg,bmp,png,svg|max:5120',
            'image_caption' => 'sometimes|required|string|min:3|max:100',
            'image_alt_caption' => 'sometimes|required|string|min:3|max:100',
            'banner' => 'sometimes|image|mimes:jpeg,jpg,bmp,png,svg|max:5120',
            'banner_caption' => 'sometimes|required|string|min:3|max:100',
            'banner_alt_caption' => 'sometimes|required|string|min:3|max:100',
        ];
    }
}
