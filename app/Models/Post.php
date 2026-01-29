<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Post extends Model
{
    protected $casts = [
        'id' => 'integer',
        'youtube_video_url' => 'string',
        'channel_id' => 'string',
        'video_id' => 'string',
        'channel_name' => 'string',
        'title' => 'string',
        'description' => 'string',
        'tags' => 'array',
        'published_at' => 'datetime',
    ];
    protected $fillable = [
        'youtube_video_url',
        'channel_id',
        'video_id',
        'channel_name',
        'title',
        'description',
        'tags',
        'published_at',
    ];
}
