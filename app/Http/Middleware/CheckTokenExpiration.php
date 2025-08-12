<?php
namespace App\Http\Middleware;

use App\Exceptions\CustomErrorException;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class CheckTokenExpiration
{
    public function handle(Request $request, Closure $next)
    {
        if ($request->user() && $request->user()->token()) {
            if ($request->user()->token()->expires_at && $request->user()->token()->expires_at->isPast()) {
                throw new CustomErrorException('unauthenticated', 'auth', Response::HTTP_UNAUTHORIZED);
            }
        }

        return $next($request);
    }
}
