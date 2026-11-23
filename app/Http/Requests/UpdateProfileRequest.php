<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateProfileRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()?->is_admin === true;
    }

    public function rules(): array
    {
        return [
            'name' => ['required', 'string', 'max:100'],
            'credentials' => ['nullable', 'string', 'max:100'],
            'headline' => ['required', 'string', 'max:160'],
            'intro' => ['required', 'string', 'max:600'],
            'bio' => ['required', 'string', 'max:4000'],
            'location' => ['required', 'string', 'max:150'],
            'email' => ['required', 'email', 'max:150'],
            'phone' => ['required', 'regex:/^\+?[0-9 ()-]{8,25}$/'],
            'website' => ['nullable', 'url:http,https', 'max:255'],
            'available' => ['required', 'boolean'],
            'photo' => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:4096'],
            'cv' => ['nullable', 'file', 'mimes:pdf', 'max:10240'],
        ];
    }
}
