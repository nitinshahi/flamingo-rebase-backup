<?php

namespace App\Http\Requests\API\Admin\Setting;

use Illuminate\Foundation\Http\FormRequest;
use App\Models\GlobalSetting;

class SettingEditRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return auth()->guard('api')->user()->user_type->getPrecedence() <3;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'setting_label' => 'sometimes|required|string|max:100',
            'setting_key' => 'sometimes|required|string|max:50|unique:global_settings,setting_key',
            'setting_group' => 'sometimes|required|string',
            'setting_status' => 'sometimes|required|boolean',
            'setting_description' => 'sometimes|nullable|string|max:1500',
            'value_type' => 'sometimes|required|string|in:boolean,string,text,image,integer,decimal,date,file,json',
        ];
    }
}
