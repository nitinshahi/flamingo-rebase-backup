<?php

namespace App\Http\Requests\API\Admin\Setting;

use App\Models\GlobalSetting;
use Illuminate\Foundation\Http\FormRequest;

class SettingUpdateRequest extends FormRequest
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
            'setting_key' => 'required|required',
            'setting_status' => 'sometimes|required|boolean',
            'setting_description' => 'sometimes|nullable|string|max:1500',
            'vaule_type' => 'sometimes|required|string|max:15',
            'setting_value' =>'required|'.$this->setSettingValue(),
        ];
    }

    public function setSettingValue (){
        $setting = GlobalSetting::where('setting_key', $this->setting_key)->first();
            // 'user_value_type' => $setting->value_type,
            if(!$setting){
                return;
            }
        $user_value_type = $setting->value_type;
        if($user_value_type == 'text'){
            $user_value_type = 'string';
        }
        if($user_value_type == 'image'){
            $user_value_type = 'image|mimes:jpeg,jpg,bmp,png,svg|max:5120';
        }elseif ($user_value_type == 'file') {
            $user_value_type = 'file|mimes:pdf,txt,docx,xls|max:10240';
        }
        return $user_value_type;
          
    }
}
