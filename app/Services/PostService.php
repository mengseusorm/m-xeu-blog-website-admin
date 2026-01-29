<?php

namespace App\Services;

use App\Http\Requests\PaginateRequest;
use App\Http\Requests\PostRequest;
use App\Models\Post;
use Exception;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class PostService
{
    public $post;

    /**
     * Create a new class instance.
     */
    public function __construct()
    {
        //
    }

    public function list(PaginateRequest $request)
    {
        try {
            $requests = $request->all();
            // return Post::all()->where(function ($query) use ($requests) {});
            return Post::all();

        } catch (Exception $exception) {
            Log::info($exception->getMessage());
            DB::rollBack();
            throw new Exception($exception->getMessage(), 422);
        }
    }

    public function store(PostRequest $request)
    {
        try {
            DB::transaction(function () use ($request) {
                $this->post = Post::create($request->validated());
            });
            return $this->post;
        } catch (Exception $exception) {
            Log::info($exception->getMessage());
            DB::rollBack();
            throw new Exception($exception->getMessage(), 422);
        }
    }

    public function update(PostRequest $request, $id)
    {
        try {
            DB::transaction(function () use ($request, $id) {
                $this->post = Post::findOrFail($id);
                $this->post->update($request->validated());
            });
            return $this->post;
        } catch (Exception $exception) {
            Log::info($exception->getMessage());
            DB::rollBack();
            throw new Exception($exception->getMessage(), 422);
        }
    }
    
    public function destroy($id)
    {
        try {
            DB::transaction(function () use ($id) {
                $this->post = Post::findOrFail($id);
                $this->post->delete();
            });
            return $this->post;
        } catch (Exception $exception) {
            Log::info($exception->getMessage());
            DB::rollBack();
            throw new Exception($exception->getMessage(), 422);
        }
    }
}
