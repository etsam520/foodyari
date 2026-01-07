<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;

class MultiGuardBroadcastAuth
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */

        public function handle($request, Closure $next)
    {

        $guards = ['admin', 'user', 'vendor'];
        // dd(auth('admin')->user());
        foreach ($guards as $guard) {
            if (Auth::guard($guard)->check()) {
                Auth::shouldUse($guard);
                Log::info('Authenticated via guard: ' . $guard); // ✅ add this
                return $next($request);
            }
        }

        Log::warning('Broadcast auth failed: no guard matched');
        throw new AccessDeniedHttpException();
    }
}
