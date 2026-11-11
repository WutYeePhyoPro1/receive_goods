<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class EnsureUserIsActive
{

    public function handle(Request $request, Closure $next)
    {
        $user = $request->user();

        if (!$user || (int) $user->status === 1) {
            return $next($request);
        }

        if ($request->hasSession()) {
            Auth::guard('web')->logout();
            $request->session()->invalidate();
            $request->session()->regenerateToken();
        }

        if ($request->expectsJson() || $request->is('api/*')) {
            return response()->json([
                'success' => false,
                'message' => 'Your account is inactive.',
            ], 403);
        }

        return redirect('/login')->withErrors([
            'employee_code' => 'Your account is inactive.',
        ]);
    }
}
