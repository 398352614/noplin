<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class LanguageMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param Request $request
     * @param Closure $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next): mixed
    {
        if (app('request')->header('language') == 'en') {
            app()->setLocale('en');
        } else {
            app()->setLocale('cn');
        }
        return $next($request);
    }
}
