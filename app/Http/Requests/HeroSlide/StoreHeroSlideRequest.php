<?php

declare(strict_types=1);

namespace App\Http\Requests\HeroSlide;

use Illuminate\Foundation\Http\FormRequest;

class StoreHeroSlideRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'title' => ['required', 'string', 'max:100'],
            'subtitle' => ['nullable', 'string', 'max:200'],
            'image' => ['required', 'image', 'mimes:jpg,jpeg,png,webp,svg', 'max:5120'],
            'illustration_type' => ['nullable', 'string', 'in:emoji,image'],
            'illustration_image' => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp,svg', 'max:2048'],
            'link_url' => ['nullable', 'url', 'max:255'],
            'link_text' => ['nullable', 'string', 'max:50'],
            'order' => ['nullable', 'integer', 'min:0'],
            'is_active' => ['nullable', 'boolean'],
        ];
    }

    public function messages(): array
    {
        return [
            'title.required' => 'Judul slide wajib diisi.',
            'title.max' => 'Judul maksimal 100 karakter.',
            'image.required' => 'Gambar slide wajib diunggah.',
            'image.image' => 'File harus berupa gambar.',
            'image.mimes' => 'Format gambar harus jpg, jpeg, png, webp, atau svg.',
            'image.max' => 'Ukuran gambar maksimal 5 MB.',
            'link_url.url' => 'URL tautan tidak valid.',
        ];
    }
}
