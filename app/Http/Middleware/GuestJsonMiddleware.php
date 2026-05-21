<?php
namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class GuestJsonMiddleware {
    public function handle(Request $request, Closure $next) {
        if (!auth()->check()) {
            if ($request->expectsJson() || $request->ajax()) {
                return response()->json(['success' => false, 'message' => 'Unauthenticated.', 'login_required' => true], 401);
            }
            return response()->json(['login_required' => true], 401);
        }
        return $next($request);
    }
}
