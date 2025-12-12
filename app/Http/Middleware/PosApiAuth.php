<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class PosApiAuth
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
        $secret = config('app.pos_secret', env('POS_SECRET_KEY'));

        if (!$secret) {
            // If no secret is configured, deny everything to be safe, or allow for dev. 
            // Better to fail safe.
            return response()->json(['error' => 'Server configuration error: POS Secret not missing'], 500);
        }

        $token = $request->header('X-POS-SECRET');

        if ($token !== $secret) {
            return response()->json(['error' => 'Unauthorized: Invalid POS Secret'], 401);
        }

        return $next($request);
    }
}
