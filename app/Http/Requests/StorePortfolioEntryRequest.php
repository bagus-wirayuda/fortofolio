<?php

namespace App\Http\Requests;

use App\Models\PortfolioEntry;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StorePortfolioEntryRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()?->is_admin === true;
    }

    public function rules(): array
    {
        return [
            'kind' => ['required', Rule::in(array_keys(PortfolioEntry::KINDS))],
            'title' => ['required', 'string', 'max:150'],
            'subtitle' => ['nullable', 'string', 'max:180'],
            'period' => ['nullable', 'string', 'max:80'],
            'category' => ['nullable', 'string', 'max:60'],
            'description' => ['nullable', 'string', 'max:4000'],
            'url' => ['nullable', 'url:http,https', 'max:2048'],
            'image' => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:4096'],
            'remove_image' => ['sometimes', 'boolean'],
            'sort_order' => ['required', 'integer', 'min:0', 'max:10000'],
            'is_published' => ['required', 'boolean'],
        ];
    }
}
