<?php

// BAGIAN: SHARED - Middleware Multi-Bahasa (i18n Localization)

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use Symfony\Component\HttpFoundation\Response;

class SetLocale
{
    /**
     * Bahasa yang didukung oleh sistem.
     *
     * @var array<int, string>
     */
    public const SUPPORTED_LOCALES = ['id', 'en'];

    /**
     * Menetapkan bahasa aplikasi dari session, preferensi user, atau cookie.
     */
    public function handle(Request $request, Closure $next): Response
    {
        $locale = session('locale');

        if (! $locale && $request->user()) {
            $locale = $request->user()->locale;
        }

        if (! $locale) {
            $locale = $request->cookie('app_locale');
        }

        if (! in_array($locale, self::SUPPORTED_LOCALES, true)) {
            $locale = config('app.locale', 'id');
        }

        App::setLocale($locale);

        return $next($request);
    }
}

