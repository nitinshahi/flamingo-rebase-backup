<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Author extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'salutation',
        'fullname',
        'url_title',
        'slug',
        'email',
        'status',
        'description',
        'meta_description',
        'meta_keywords',
        'image',
        'image_caption',
        'image_alt_caption',
    ];

    public function blog(){
        return $this->belongsToMany(Blog::class);
    }
}
