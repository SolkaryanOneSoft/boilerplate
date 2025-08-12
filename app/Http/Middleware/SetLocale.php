<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class SetLocale
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {
        // Get the locale from the request header
        $locale = $request->header('locale');

        // Check if the locale is one of the allowed values
        if (in_array($locale, ['ru', 'am', 'en'])) {
            app()->setLocale($locale);
        }else{
            app()->setLocale('en');
        }

        return $next($request);
    }
}
