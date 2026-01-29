<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\PaginateRequest;
use App\Http\Requests\PostRequest;
use App\Http\Requests\YoutubeVideoRequest;
use App\Http\Resources\PostResource;
use App\Http\Resources\YoutubeVideoResource;
use App\Models\Post;
use App\Services\PostService;
use App\Services\YoutubeService;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class PostController extends Controller
{

    public YoutubeService $youtubeService;
    public PostService $postService;

    public function __construct(YoutubeService $youtubeService, PostService $postService)
    {
        $this->youtubeService = $youtubeService;
        $this->postService = $postService;
    }

    public function ytdetails(YoutubeVideoRequest $request): \Illuminate\Http\Response | YoutubeVideoResource | \Illuminate\Contracts\Foundation\Application | \Illuminate\Contracts\Routing\ResponseFactory
    {
        try {
            return new YoutubeVideoResource($this->youtubeService->youtubeVideoDetail($request));
        } catch (Exception $exception) {
            return response(['status' => false, 'message' => $exception->getMessage()], 422);
        }
    }

    public function index(PaginateRequest $request): \Illuminate\Http\Response | \Illuminate\Http\Resources\Json\AnonymousResourceCollection | \Illuminate\Contracts\Foundation\Application | \Illuminate\Contracts\Routing\ResponseFactory
    {
        try {
            return PostResource::collection($this->postService->list($request));
        } catch (Exception $e) {
            Log::error('Error in fetching posts: '.$e->getMessage());
            return response(['status' => false, 'message' => 'Failed to fetch posts'],  500);
        }
    }
    public function createPost(PostRequest $request ) : \Illuminate\Http\Response|PostResource|\Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\Routing\ResponseFactory
    {
        try {
            return new PostResource($this->postService->store($request));
        } catch (Exception $e) {
            Log::error('Error in createPost: '.$e->getMessage());
            return response(['status' => false, 'message' => 'Failed to create post'],  500);
        }
    }
    public function destroy($post): \Illuminate\Http\Response|\Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\Routing\ResponseFactory
    {
        try {
            $this->postService->destroy($post);
            return response('', 202);
        } catch (Exception $exception) {
            return response(['status' => false, 'message' => $exception->getMessage()], 422);
        }
    }

}
