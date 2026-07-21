<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class SetLocale
{
    /**
     * Languages the site is available in.
     *
     * @var list<string>
     */
    public const SUPPORTED = ['en', 'fr', 'ru'];

    /**
     * Apply the visitor's chosen language, falling back to their browser
     * preference and finally the app default.
     */
    public function handle(Request $request, Closure $next): Response
    {
        $locale = $request->session()->get('locale');

        if (! in_array($locale, self::SUPPORTED, true)) {
            $locale = $request->getPreferredLanguage(self::SUPPORTED) ?? config('app.locale');
        }

        app()->setLocale($locale);

        return $next($request);
    }
}
