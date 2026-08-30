<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ApplicationRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    /**
     * @return array<string, mixed>
     */
    public function rules(): array
    {
        return [
            'school_id' => 'required|exists:schools,id',
            'student_name' => 'required|string|max:255',
            'student_nik' => 'nullable|string|max:20',
            'student_gender' => 'nullable|in:Laki-laki,Perempuan',
            'student_birth_place' => 'nullable|string|max:100',
            'student_birth_date' => 'nullable|date',
            'student_address' => 'nullable|string|max:500',
            'previous_school' => 'nullable|string|max:255',
            'target_jenjang' => 'nullable|string|max:50',
            'parent_name' => 'required|string|max:255',
            'parent_email' => 'nullable|email|max:255',
            'parent_phone' => 'nullable|string|max:20',
            'parent_whatsapp' => 'nullable|string|max:20',
            'notes' => 'nullable|string|max:1000',
        ];
    }

    /**
     * @return array<string, string>
     */
    public function messages(): array
    {
        return [
            'school_id.required' => 'Sekolah wajib dipilih.',
            'student_name.required' => 'Nama siswa wajib diisi.',
            'parent_name.required' => 'Nama orang tua wajib diisi.',
        ];
    }
}
