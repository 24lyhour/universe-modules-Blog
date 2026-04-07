<?php

namespace Modules\Blog\Http\Requests\Dashboard\V1\BannerRequest;

use Illuminate\Foundation\Http\FormRequest;

class StoreBannerRequest extends FormRequest
{
    public function authorize(): bool
    {
        return auth()->check();
    }

    public function rules(): array
    {
        return [
            'name' => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string', 'max:200'],
            'image_url' => ['required', 'array', 'min:1'],
            'image_url.*' => ['required', 'string', 'url'],
            'is_active' => ['boolean'],
            'start_at' => ['nullable', 'date'],
            'end_at' => ['nullable', 'date', 'after_or_equal:start_at'],
        ];
    }
}
