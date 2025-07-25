<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Article extends Model
{
    protected $fillable = [
        'title',
        'summary',
        'content',
        'url',
        'image_url',
        'source',
        'author',
        'category',
        'published_at',
        'api_origin',
    ];
}
