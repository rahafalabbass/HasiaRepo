<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class ActivityLogMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
  public function handle($request, Closure $next)
{
    $response = $next($request);

    // Log activity
    $user = Auth::user();
    $activity = [
        'user_id' => $user ? $user->id : null,
        'username' => $user ? $user->name : 'Guest',
        'action' => $request->getMethod(),
        'url' => $request->fullUrl(),
        'timestamp' => now()->toDateTimeString()
    ];

    Log::info('Activity Log: ' . json_encode($activity));

    return $response;
}
}
