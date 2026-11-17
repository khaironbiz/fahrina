<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreAssessmentRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $rules = [
            'name' => ['required', 'string', 'max:100'],
            'email' => ['required', 'email:rfc,dns,filter', 'max:150'],
            'age' => ['required', 'integer', 'min:18', 'max:100'],
            'gender' => ['required', 'in:L,P'],
            'department' => ['required', 'string', 'max:50'],
            'work_tenure' => ['required', 'string', 'max:50'],
            'screen_time' => ['required', 'string', 'max:50'],
        ];

        // 9 NBM questions (b1 to b9)
        for ($i = 1; $i <= 9; $i++) {
            $rules["b{$i}"] = ['required', 'integer', 'between:1,4'];
        }

        // 4 ROSA questions (c1 to c4)
        for ($j = 1; $j <= 4; $j++) {
            $rules["c{$j}"] = ['required', 'integer', 'between:1,3'];
        }

        return $rules;
    }

    public function messages(): array
    {
        return [
            'name.required' => 'Nama atau inisial pegawai wajib diisi.',
            'email.required' => 'Alamat email wajib diisi untuk pengiriman laporan hasil.',
            'email.email' => 'Format alamat email tidak valid.',
            'age.required' => 'Usia wajib diisi.',
            'age.min' => 'Usia minimal adalah 18 tahun.',
            'age.max' => 'Usia maksimal adalah 100 tahun.',
            'gender.required' => 'Jenis kelamin wajib dipilih.',
            'department.required' => 'Unit / divisi kerja wajib dipilih.',
            'work_tenure.required' => 'Masa kerja wajib dipilih.',
            'screen_time.required' => 'Durasi kerja di depan layar per hari wajib dipilih.',
            'b*.required' => 'Seluruh pertanyaan skrining keluhan otot (NBM) wajib dijawab.',
            'c*.required' => 'Seluruh pertanyaan postur & fasilitas kerja (ROSA) wajib dijawab.',
        ];
    }
}
