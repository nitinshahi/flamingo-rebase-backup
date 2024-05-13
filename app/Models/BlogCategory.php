<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class BlogCategory extends Model
{
    use HasFactory, SoftDeletes;
    protected $fillable = [
        'title',
        'url_title',
        'status',
        'description',
        'meta_title',
        'meta_keywords',
        'meta_description',
        'image',
        'image_caption',
        'image_alt_caption',
        'banner',
        'banner_caption',
        'banner_alt_caption',
    ];

    public function blog(){
        return $this->belongsToMany(Blog::class);
    }
}
