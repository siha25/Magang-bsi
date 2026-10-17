<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class StoreCabangRequest extends FormRequest
{
    /**
     * Tentukan apakah pengguna diizinkan untuk membuat permintaan ini.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Aturan validasi untuk penambahan cabang baru.
     *
     * @return array<string, ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'kode_cabang' => ['required', 'string', 'max:30', 'unique:cabang,kode_cabang'],
            'nama_cabang' => ['required', 'string', 'max:255'],
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
            'kode_cabang.required' => 'Kode cabang wajib diisi.',
            'kode_cabang.unique' => 'Kode cabang sudah digunakan oleh cabang lain.',
            'kode_cabang.max' => 'Kode cabang tidak boleh melebihi 30 karakter.',
            'nama_cabang.required' => 'Nama cabang wajib diisi.',
            'nama_cabang.max' => 'Nama cabang tidak boleh melebihi 255 karakter.',
            'nomor_telepon.max' => 'Nomor telepon tidak boleh melebihi 30 karakter.',
        ];
    }
}
