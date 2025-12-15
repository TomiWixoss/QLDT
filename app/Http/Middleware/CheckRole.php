<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Symfony\Component\HttpFoundation\Response;

class CheckRole
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     * @param  string  ...$roles  Allowed roles (e.g., 'Admin', 'Manager')
     */
    public function handle(Request $request, Closure $next, string ...$roles): Response
    {
        $user = Auth::user();

        // Check if user is authenticated
        if (!$user) {
            return redirect()->route('admin.login');
        }

        // Check if user has any of the allowed roles
        if ($user->hasAnyRole($roles)) {
            return $next($request);
        }

        // Log unauthorized access attempt to security audit log
        Log::channel('security')->warning('Unauthorized access attempt', [
            'user_id' => $user->id,
            'user_email' => $user->email,
            'user_role' => $user->role?->name,
            'attempted_route' => $request->path(),
            'ip' => $request->ip(),
            'timestamp' => now()->toIso8601String(),
        ]);

        // Return 403 Forbidden with Vietnamese message
        abort(403, 'Bạn không có quyền truy cập trang này');
    }
}
