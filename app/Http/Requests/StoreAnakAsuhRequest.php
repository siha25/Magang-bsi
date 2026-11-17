<?php

namespace App\Http\Requests;

use App\Models\AnakAsuh;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class StoreAnakAsuhRequest extends FormRequest
{
    /**
     * Tentukan apakah pengguna diizinkan untuk membuat permintaan ini.
     */
    public function authorize(): bool
    {
        return $this->user()?->can('create', AnakAsuh::class) ?? false;
    }

    /**
     * Siapkan data sebelum divalidasi.
     * Field nomor_induk dibuat otomatis oleh server saat penyimpanan.
     * Jika field kosong tetapi asrama dan tanggal masuk telah diisi, sediakan nilai sementara.
     */
    protected function prepareForValidation(): void
    {
        if (blank($this->input('nomor_induk')) && $this->filled('asrama_id') && $this->filled('tanggal_masuk')) {
            $this->merge([
                'nomor_induk' => 'AUTO-GENERATED',
            ]);
        }
    }

    /**
     * Aturan validasi untuk penambahan data anak asuh baru.
     *
     * @return array<string, ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'asrama_id' => ['required', 'integer', 'exists:asrama,id'],
            'nomor_induk' => ['required', 'string', 'max:50'],
            'nama_lengkap' => ['required', 'string', 'max:255'],
            'jenis_kelamin' => ['required', 'in:L,P'],
            'tempat_lahir' => ['nullable', 'string', 'max:100'],
            'tanggal_lahir' => ['nullable', 'date'],
            'jenjang_pendidikan' => ['required', 'in:SD,SMP,SMA'],
            'kelas' => ['nullable', 'string', 'max:50'],
            'tanggal_masuk' => ['required', 'date'],
            'status_aktif' => ['nullable', 'boolean'],
            'catatan' => ['nullable', 'string', 'max:2000'],
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
            'asrama_id.required' => 'Pilihan asrama wajib dipilih.',
            'asrama_id.exists' => 'Asrama yang dipilih tidak valid atau belum terdaftar.',
            'nomor_induk.required' => 'Nomor induk anak asuh wajib diisi.',
            'nomor_induk.max' => 'Nomor induk tidak boleh melebihi 50 karakter.',
            'nama_lengkap.required' => 'Nama lengkap anak asuh wajib diisi.',
            'nama_lengkap.max' => 'Nama lengkap tidak boleh melebihi 255 karakter.',
            'jenis_kelamin.required' => 'Jenis kelamin wajib dipilih.',
            'jenis_kelamin.in' => 'Pilihan jenis kelamin harus L (Laki-laki) atau P (Perempuan).',
            'jenjang_pendidikan.required' => 'Jenjang pendidikan wajib dipilih.',
            'jenjang_pendidikan.in' => 'Pilihan jenjang pendidikan harus SD, SMP, atau SMA.',
            'tanggal_lahir.date' => 'Format tanggal lahir tidak valid.',
            'tanggal_masuk.required' => 'Tanggal masuk asrama wajib diisi untuk penomoran induk otomatis.',
            'tanggal_masuk.date' => 'Format tanggal masuk asrama tidak valid.',
            'catatan.max' => 'Catatan tidak boleh melebihi 2000 karakter.',
        ];
    }
}
