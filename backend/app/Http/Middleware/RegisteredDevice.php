<?php

namespace App\Http\Middleware;

use App\Models\AccessGate;
use Closure;
use Illuminate\Http\Request;

class RegisteredDevice
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
        $gate = AccessGate::where('host', $request->ip())->first();

        if (!$gate) {
            return response(['message' => 'Unregistered device', 'ip' => $request->ip()], 401);
        }

        return $next($request);
    }
}
