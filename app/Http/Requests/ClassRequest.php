<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class ClassRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'name' => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string'],
            'schedule_day' => ['nullable', 'json', 'max:2000', function ($attribute, $value, $fail) {
                $data = json_decode($value, true);
                if (!is_array($data)) {
                    $fail('Format jadwal tidak valid.');
                    return;
                }

                $validDays = ['monday', 'tuesday', 'wednesday', 'thursday', 'friday', 'saturday', 'sunday'];

                foreach ($data as $day => $time) {
                    if (!in_array($day, $validDays)) {
                        $fail('Hari "' . $day . '" tidak valid.');
                        return;
                    }
                    if (!preg_match('/^\d{2}:\d{2}-\d{2}:\d{2}$/', $time)) {
                        $fail('Format waktu harus HH:MM-HH:MM (contoh: 08:00-10:00).');
                        return;
                    }
                }
            }],
        ];
    }

    public function messages(): array
    {
        return [
            'name.required' => 'Nama kelas wajib diisi.',
            'name.max' => 'Nama kelas maksimal 255 karakter.',
            'schedule_day.json' => 'Format jadwal tidak valid.',
        ];
    }
}