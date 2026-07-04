<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class AnnouncementRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'title' => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string'],
            'expired_at' => ['nullable', 'date', 'after:today'],
        ];
    }

    public function messages(): array
    {
        return [
            'title.required' => 'Judul pengumuman wajib diisi.',
            'title.max' => 'Judul maksimal 255 karakter.',
            'expired_at.date' => 'Format tanggal kadaluarsa tidak valid.',
            'expired_at.after' => 'Tanggal kadaluarsa harus setelah hari ini.',
        ];
    }
}