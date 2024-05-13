<?php

namespace App\Http\Resources\Admin;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class SettingResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            // 'setting_label' => $this->setting_label,
            // 'setting_key' => $this->setting_key,
            // 'setting_group' => $this->setting_group,
            // 'setting_status' => $this->setting_status,
            // 'setting_description' => $this->setting_description,
            // 'value_type' => $this->value_type,
            $request->setting_label
        ];
    }
}
