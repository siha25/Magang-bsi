<?php

// BAGIAN: PENGURUS CABANG - Pengaturan Akun, Sistem, & Bahasa (i18n)

namespace App\Cabang\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;

class PengaturanController extends Controller
{
    /**
     * Menampilkan halaman pengaturan portal cabang.
     */
    public function index(Request $request): View
    {
        $user = $request->user();

        $currentLocale = session('locale', $user?->locale ?? config('app.locale', 'id'));
        $currentTheme = session('theme', $user?->theme ?? 'light');
        $currentTimezone = session('timezone', $user?->timezone ?? 'Asia/Jakarta');
        $currentDateFormat = session('date_format', $user?->date_format ?? 'd/m/Y');

        $timezones = [
            'Asia/Jakarta' => __('Waktu Indonesia Barat (WIB)'),
            'Asia/Makassar' => __('Waktu Indonesia Tengah (WITA)'),
            'Asia/Jayapura' => __('Waktu Indonesia Timur (WIT)'),
        ];

        $dateFormats = [
            'd/m/Y' => date('d/m/Y').' (DD/MM/YYYY)',
            'd F Y' => date('d F Y').' (DD Bulan YYYY)',
            'Y-m-d' => date('Y-m-d').' (YYYY-MM-DD)',
        ];

        return view('cabang.views.pengaturan.index', compact(
            'currentLocale',
            'currentTheme',
            'currentTimezone',
            'currentDateFormat',
            'timezones',
            'dateFormats'
        ));
    }

    /**
     * Menyimpan preferensi pengaturan pengguna & cabang.
     */
    public function update(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'locale' => ['required', 'in:id,en'],
            'theme' => ['required', 'in:light,dark,system'],
            'timezone' => ['required', 'in:Asia/Jakarta,Asia/Makassar,Asia/Jayapura'],
            'date_format' => ['required', 'in:d/m/Y,d F Y,Y-m-d'],
        ]);

        $user = $request->user();
        if ($user) {
            $user->update($validated);
        }

        session([
            'locale' => $validated['locale'],
            'theme' => $validated['theme'],
            'timezone' => $validated['timezone'],
            'date_format' => $validated['date_format'],
        ]);

        cookie()->queue('app_locale', $validated['locale'], 60 * 24 * 365);
        cookie()->queue('app_theme', $validated['theme'], 60 * 24 * 365);

        App::setLocale($validated['locale']);

        return redirect()->route('cabang.pengaturan.index')
            ->with('success', __('Pengaturan berhasil disimpan.'));
    }

    /**
     * Tombol cepat / shortcut pengalihan bahasa.
     */
    public function switchLocale(Request $request): RedirectResponse
    {
        $locale = $request->input('locale');
        if (! in_array($locale, ['id', 'en'], true)) {
            $locale = 'id';
        }

        $user = $request->user();
        if ($user) {
            $user->update(['locale' => $locale]);
        }

        session(['locale' => $locale]);
        cookie()->queue('app_locale', $locale, 60 * 24 * 365);
        App::setLocale($locale);

        return redirect()->back()->with('success', __('Bahasa berhasil diubah.'));
    }
}
