<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Symfony\Component\HttpFoundation\Response;

class SessionExpiry
{
    /**
     * Session lifetime in minutes (reads from config/session.php).
     */
    protected int $sessionLifetime;

    public function __construct()
    {
        $this->sessionLifetime = (int) config('session.lifetime', 120);
    }

    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next): Response
    {
        if (Auth::check()) {
            $lastActivity = $request->session()->get('last_activity_time');

            if ($lastActivity && $this->isSessionExpired($lastActivity)) {
                return $this->forceLogout($request);
            }

            $request->session()->put('last_activity_time', now()->timestamp);
        }

        return $next($request);
    }

    protected function isSessionExpired(int $lastActivity): bool
    {
        $inactiveFor = now()->timestamp - $lastActivity; // seconds
        $lifetimeInSeconds = $this->sessionLifetime * 60;

        return $inactiveFor > $lifetimeInSeconds;
    }

    protected function forceLogout(Request $request): Response
    {
        Auth::logout();
        Auth::guard('twill_users')->logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('login.choice')->with('session_expired', 'Your session has expired. Please log in again.');
    }
}
