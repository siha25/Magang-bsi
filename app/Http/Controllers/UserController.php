<?php

namespace App\Http\Controllers;

use App\Models\Asrama;
use App\Models\User;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules\Password;

class UserController extends Controller
{
    /**
     * Menampilkan daftar akun pengguna sistem.
     */
    public function index(Request $request): View
    {
        $cari = $request->string('cari')->trim()->toString();
        $role = $request->string('role')->trim()->toString();
        $status = $request->string('status')->trim()->toString();

        $query = User::with('asrama');

        if ($cari !== '') {
            $query->where(function ($q) use ($cari) {
                $q->where('name', 'like', "%{$cari}%")
                    ->orWhere('email', 'like', "%{$cari}%");
            });
        }

        if ($role !== '') {
            $query->where('role', $role);
        }

        if ($status === 'aktif') {
            $query->where('status_aktif', true);
        } elseif ($status === 'nonaktif') {
            $query->where('status_aktif', false);
        }

        $users = $query->latest()->paginate(10)->withQueryString();

        $stats = [
            'total' => User::count(),
            'admin_pusat' => User::where('role', User::ROLE_ADMIN_PUSAT)->count(),
            'admin_cabang' => User::where('role', User::ROLE_ADMIN_CABANG)->count(),
            'pimpinan' => User::where('role', User::ROLE_PIMPINAN_PUSAT)->count(),
            'aktif' => User::where('status_aktif', true)->count(),
        ];

        return view('users.index', compact('users', 'stats', 'cari', 'role', 'status'));
    }

    /**
     * Menampilkan form pembuatan akun pengguna baru.
     */
    public function create(): View
    {
        $asramas = Asrama::where('status_aktif', true)->orderBy('nama_asrama')->get();

        return view('users.create', compact('asramas'));
    }

    /**
     * Menyimpan akun pengguna baru.
     */
    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users,email'],
            'role' => ['required', 'string', Rule::in([User::ROLE_ADMIN_PUSAT, User::ROLE_ADMIN_CABANG, User::ROLE_PIMPINAN_PUSAT])],
            'asrama_id' => [
                'nullable',
                Rule::requiredIf(fn () => $request->input('role') === User::ROLE_ADMIN_CABANG),
                'exists:asrama,id',
            ],
            'password' => ['required', 'string', 'min:6'],
            'status_aktif' => ['nullable', 'boolean'],
        ], [
            'name.required' => 'Nama pengguna wajib diisi.',
            'email.required' => 'Alamat email wajib diisi.',
            'email.email' => 'Format email tidak valid.',
            'email.unique' => 'Alamat email sudah digunakan oleh akun lain.',
            'role.required' => 'Peran / Role pengguna wajib dipilih.',
            'asrama_id.required' => 'Asrama penugasan wajib dipilih untuk Admin Cabang / Asrama.',
            'password.required' => 'Password wajib diisi.',
            'password.min' => 'Password minimal terdiri dari 6 karakter.',
        ]);

        $plainPassword = $validated['password'];

        $user = new User;
        $user->name = $validated['name'];
        $user->email = strtolower($validated['email']);
        $user->role = $validated['role'];
        $user->asrama_id = $validated['role'] === User::ROLE_ADMIN_CABANG ? $validated['asrama_id'] : null;
        $user->password = Hash::make($plainPassword);
        $user->encrypted_password = $plainPassword; // Terenkripsi aman via cast model untuk recovery password
        $user->status_aktif = $request->boolean('status_aktif', true);
        $user->email_verified_at = now();
        $user->save();

        return redirect()->route('users.index')
            ->with('success', "Akun pengguna '{$user->name}' berhasil ditambahkan ke sistem.");
    }

    /**
     * Menampilkan form edit akun pengguna.
     */
    public function edit(User $user): View
    {
        $asramas = Asrama::orderBy('nama_asrama')->get();

        return view('users.edit', compact('user', 'asramas'));
    }

    /**
     * Memperbarui profil atau data akun pengguna.
     */
    public function update(Request $request, User $user): RedirectResponse
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', Rule::unique('users')->ignore($user->id)],
            'role' => ['required', 'string', Rule::in([User::ROLE_ADMIN_PUSAT, User::ROLE_ADMIN_CABANG, User::ROLE_PIMPINAN_PUSAT])],
            'asrama_id' => [
                'nullable',
                Rule::requiredIf(fn () => $request->input('role') === User::ROLE_ADMIN_CABANG),
                'exists:asrama,id',
            ],
            'status_aktif' => ['nullable', 'boolean'],
        ], [
            'name.required' => 'Nama pengguna wajib diisi.',
            'email.required' => 'Alamat email wajib diisi.',
            'email.email' => 'Format email tidak valid.',
            'email.unique' => 'Alamat email sudah digunakan oleh akun lain.',
            'role.required' => 'Peran / Role pengguna wajib dipilih.',
            'asrama_id.required' => 'Asrama penugasan wajib dipilih untuk Admin Cabang / Asrama.',
        ]);

        // Cek proteksi akun utama
        if ($user->is_primary_admin && $validated['role'] !== User::ROLE_ADMIN_PUSAT) {
            return back()->with('error', 'Role akun Admin Utama tidak dapat diubah.');
        }

        $user->name = $validated['name'];
        $user->email = strtolower($validated['email']);

        if (! $user->is_primary_admin) {
            $user->role = $validated['role'];
            $user->status_aktif = $request->boolean('status_aktif', true);
        }

        $user->asrama_id = $validated['role'] === User::ROLE_ADMIN_CABANG ? $validated['asrama_id'] : null;
        $user->save();

        return redirect()->route('users.index')
            ->with('success', "Data akun pengguna '{$user->name}' berhasil diperbarui.");
    }

    /**
     * Memperbarui / Reset password pengguna oleh Admin Pusat.
     */
    public function resetPassword(Request $request, User $user): RedirectResponse
    {
        $validated = $request->validate([
            'password' => ['required', 'string', 'min:6'],
        ], [
            'password.required' => 'Password baru wajib diisi.',
            'password.min' => 'Password minimal terdiri dari 6 karakter.',
        ]);

        $newPassword = $validated['password'];
        $user->password = Hash::make($newPassword);
        $user->encrypted_password = $newPassword;
        $user->save();

        return back()->with('success', "Password untuk akun '{$user->name}' ({$user->email}) berhasil diperbarui.");
    }

    /**
     * Mengaktifkan / Menonaktifkan akun pengguna.
     */
    public function toggleStatus(User $user): RedirectResponse
    {
        if ($user->is_primary_admin || $user->id === Auth::id()) {
            return back()->with('error', 'Anda tidak dapat menonaktifkan akun sendiri atau Admin Utama.');
        }

        $user->status_aktif = ! $user->status_aktif;
        $user->save();

        $statusText = $user->status_aktif ? 'diaktifkan' : 'dinonaktifkan';

        return back()->with('success', "Akun '{$user->name}' berhasil {$statusText}.");
    }

    /**
     * Menghapus akun pengguna (selain Admin Utama & akun sendiri).
     */
    public function destroy(User $user): RedirectResponse
    {
        if ($user->is_primary_admin || $user->id === Auth::id()) {
            return back()->with('error', 'Akun ini dilindungi dan tidak dapat dihapus.');
        }

        $userName = $user->name;
        $user->delete();

        return redirect()->route('users.index')
            ->with('success', "Akun pengguna '{$userName}' berhasil dihapus dari sistem.");
    }
}
