<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use App\Models\subscriptions;

class CheckSubscriptionRejected
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
        $subscription = subscriptions::findOrFail($request->id);

        if ($subscription->state_cancelled == 1) {
            return response()->json([
                'status' => 'error',
                'message' => 'لا يمكن إعادة قبول الطلب المرفوض.'
            ], 403);
        }

        return $next($request);
    }
}
