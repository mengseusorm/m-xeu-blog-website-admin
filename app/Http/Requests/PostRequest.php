<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules\Unique;

class PostRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'youtube_video_url' => ['required', Rule::unique('posts', 'youtube_video_url')->ignore($this->route('id'))],
            'channel_id' => 'required|string',
            'video_id' => 'required|string',
            'channel_name' => 'required|string',
            'title' => 'required|string',
            'description' => 'required|string',
            'tags' => 'nullable',
            'published_at' => 'nullable',
        ];
    }
}
