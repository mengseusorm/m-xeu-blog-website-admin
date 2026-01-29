<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class YoutubeService
{
    /**
     * Create a new class instance.
     */
    public function __construct()
    {
        
    }

    public function youtubeVideoDetail($request)
    { 
        if($request->youtube_url){
            $video_id = $this->formatURL($request->youtube_url);
            $youtube = Http::get('https://www.googleapis.com/youtube/v3/videos?part=snippet&id='.$video_id.'&key='.env('YOUTUBE_API_KEY')); 
        } 
        return [
            'youtube_details' => $youtube->json()
        ];
    } 
    public function formatURL($param){
        $videoId = str_replace('https://www.youtube.com/watch?v=', '', $param);
        return $videoId;
    }


}
