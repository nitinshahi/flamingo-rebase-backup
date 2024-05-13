<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class GlobalSetting extends Model
{
    use HasFactory,SoftDeletes;
    protected $fillable = [
        'setting_label',
        'setting_key',
        'setting_group',
        'setting_status',
        'setting_description',
        'value_type', 
    ];
    protected $hidden = [
        'boolean_value',
        'string_value',
        'text_value',
        'image_value',
        'integer_value',
        'decimal_value',
        'date_value',
        'file_value',
        'json_value',
    ];
    protected $appends = [
        'setting_value'
    ];

    public function getSettingValueAttribute() {
        // $value_attribute = $this->attributes['value_type'].'_value';
        // return $this->attributes[$value_attribute];
        $value_type = $this->value_type;
        return $this->{$value_type.'_value'};
    }

}
