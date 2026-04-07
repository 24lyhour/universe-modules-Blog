<?php

namespace Modules\Blog\Http\Requests\Dashboard\V1\SpecialOfferRequest;

use Illuminate\Foundation\Http\FormRequest;

class UpdateSpecialOfferRequest extends FormRequest
{
    public function authorize(): bool
    {
        return auth()->check();
    }

    public function rules(): array
    {
        return [
            'title' => ['sometimes', 'required', 'string', 'max:255'],
            'subtitle' => ['nullable', 'string', 'max:255'],
            'icon' => ['nullable', 'string', 'max:100'],
            'gradient_start' => ['nullable', 'string', 'max:7'],
            'gradient_end' => ['nullable', 'string', 'max:7'],
            'button_text' => ['nullable', 'string', 'max:50'],
            'deeplink' => ['nullable', 'string', 'max:500'],
            'is_active' => ['boolean'],
            'sort_order' => ['nullable', 'integer', 'min:0'],
            'start_at' => ['nullable', 'date'],
            'end_at' => ['nullable', 'date', 'after_or_equal:start_at'],
        ];
    }
}
