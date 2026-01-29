<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class PostResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'youtube_video_url' => $this->youtube_video_url,
            'channel_id' => $this->channel_id,
            'video_id' => $this->video_id,
            'channel_name' => $this->channel_name,
            'title' => $this->title,
            'description' => $this->description,
            'tags' => $this->tags,
            'published_at' => $this->published_at,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }
}
