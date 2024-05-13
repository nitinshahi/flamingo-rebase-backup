<?php

namespace App\Http\Requests\API\Admin\Setting;

use Illuminate\Foundation\Http\FormRequest;

class SettingStoreRequest extends FormRequest
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
            'setting_label' => 'required|string|max:100',
            'setting_key' => 'required|string|max:50|unique:global_settings,setting_key',
            'setting_group' => 'required|string',
            'setting_status' => 'required|boolean',
            'setting_description' => 'sometimes|string|max:1500',
            'value_type' => 'required|string|max:15',
        ];
    }

}
