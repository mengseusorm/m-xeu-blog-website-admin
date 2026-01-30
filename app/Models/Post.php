<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Post extends Model
{
    protected $casts = [
        'id' => 'integer',
        'youtube_image_url' => 'string',
        'youtube_video_url' => 'string',
        'channel_id' => 'string',
        'video_id' => 'string',
        'channel_name' => 'string',
        'title' => 'string',
        'description' => 'string',
        'tags' => 'array',
        'published_at' => 'datetime',
        'categoryId' => 'integer',
    ];
    protected $fillable = [
        'youtube_image_url',
        'youtube_video_url',
        'channel_id',
        'video_id',
        'channel_name',
        'title',
        'description',
        'tags',
        'published_at',
        'categoryId'
    ];
}
