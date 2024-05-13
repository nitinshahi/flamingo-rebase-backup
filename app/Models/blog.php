<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class blog extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'title',
        'url_title',
        'abstract',
        'description',
        'status',
        'is_featured',
        'date',
        'meta_keywords',
        'meta_description',
        'avatar_image',
        'avatar_text',
        'avatar_caption',
        'banner_image',
        'banner_caption',
        'banner_text',
    ];

    public function authors(){
        return $this->belongsToMany(Author::class);
    }

    public function blogcategories(){
        return $this->belongsToMany(BlogCategory::class);
    }
}
