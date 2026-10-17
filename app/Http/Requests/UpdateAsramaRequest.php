<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateAsramaRequest extends FormRequest
{
    /**
     * Tentukan apakah pengguna diizinkan untuk membuat permintaan ini.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Aturan validasi untuk pembaruan data asrama.
     *
     * @return array<string, ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        $asramaId = $this->route('asrama')?->id;

        return [
            'kode_asrama' => ['required', 'string', 'max:30', Rule::unique('asrama', 'kode_asrama')->ignore($asramaId)],
            'nama_asrama' => ['required', 'string', 'max:255'],
            'wilayah' => ['nullable', 'string', 'max:100'],
            'alamat' => ['nullable', 'string', 'max:1000'],
            'penanggung_jawab' => ['nullable', 'string', 'max:150'],
            'nomor_telepon' => ['nullable', 'string', 'max:30'],
            'status_aktif' => ['nullable', 'boolean'],
        ];
    }

    /**
     * Pesan kustom validasi dalam bahasa Indonesia.
     *
     * @return array<string, string>
     */
    public function messages(): array
    {
        return [
            'kode_asrama.required' => 'Kode asrama wajib diisi.',
            'kode_asrama.unique' => 'Kode asrama sudah digunakan oleh asrama lain.',
            'kode_asrama.max' => 'Kode asrama tidak boleh melebihi 30 karakter.',
            'nama_asrama.required' => 'Nama asrama wajib diisi.',
            'nama_asrama.max' => 'Nama asrama tidak boleh melebihi 255 karakter.',
            'nomor_telepon.max' => 'Nomor telepon tidak boleh melebihi 30 karakter.',
        ];
    }
}
