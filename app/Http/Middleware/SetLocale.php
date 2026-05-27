<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class SetLocale
{
    /** @param Closure(Request): Response $next */
    public function handle(Request $request, Closure $next): Response
    {
        $lang = $request->query('lang');
        if (is_string($lang) && in_array($lang, ['en', 'ur'], true)) {
            app()->setLocale($lang);
            session(['locale' => $lang]);
        } elseif (session()->has('locale')) {
            $locale = session('locale');
            if (is_string($locale)) {
                app()->setLocale($locale);
            }
        }

        return $next($request);
    }
}
