<?php

namespace App\Http\Middleware;

use App\CentralLogics\Helpers;
use App\Models\Customer;
use App\Models\UserPassKey;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class ValidatePassKey
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */

    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next): Response
    {
        $passKey = $request->header('Pass-Key');

        if (!$passKey) {
            return Helpers::ApiResponse(false, 'Missing Pass-Key header', null, 401);
        }

        $passKeyRecord = UserPassKey::where('key', $passKey)
        ->whereDate('expire_at', '>=', now()->toDateString())
        ->first();

        if (!$passKeyRecord ) {
            return Helpers::ApiResponse(false, 'Invalid or expired Pass-Key', null, 401);
        }

        $user = Customer::find($passKeyRecord->user_id);

        // ✅ Allow selected routes even if user not found

        if (!$user) {
            if ($request->is([
                'api/fetch-saved-location',
                'api/save-current-address',
                'api/user',
            ])) {
                return $next($request);
            }

            return Helpers::ApiResponse(false, 'User not found', null, 401);
        }

        // ✅ Authenticate customer
        Auth::guard('customer')->setUser($user);

        // ✅ Attach user to request (optional)
        $request->merge([
            'auth_user' => $user
        ]);

        return $next($request);
    }

}
