<?php

namespace App\Http\Middleware;

use App\Models\Language;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Auth;

class VendorLocal
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
   public function handle(Request $request, Closure $next)
{
    $locale = null;

    if (Auth::guard('vendor')->check()) {
        $locale = Auth::guard('vendor')->user()->lang_code;
    }

    if (empty($locale)) {
        // Fetch default language from the database
        $code = Language::query()->where('is_default', '=', 1)->value('code');

        // Ensure it's a valid locale format
        $locale = trim($code ?? 'en'); 
    } else {
        $locale = trim($locale); // Ensure no extra spaces
    }

    // Validate against Laravel's available locales (modify as needed)
    $validLocales = ['en', 'fr', 'es']; // Add supported locales
    if (!in_array($locale, $validLocales)) {
        $locale = 'en'; // Default to 'en' if invalid
    }

    App::setLocale($locale); // Set a valid locale

    return $next($request);
}

}
