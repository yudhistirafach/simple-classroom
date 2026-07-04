<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class TaskRequest extends FormRequest
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
            'deadline_at' => ['required', 'date', 'after:now'],
        ];
    }

    public function messages(): array
    {
        return [
            'title.required' => 'Judul tugas wajib diisi.',
            'title.max' => 'Judul maksimal 255 karakter.',
            'deadline_at.required' => 'Deadline wajib diisi.',
            'deadline_at.date' => 'Format deadline tidak valid.',
            'deadline_at.after' => 'Deadline harus setelah waktu sekarang.',
        ];
    }
}