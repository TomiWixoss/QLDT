<?php

namespace App\Http\Middleware;

use Illuminate\Auth\Middleware\Authenticate as Middleware;
use Illuminate\Http\Request;

class Authenticate extends Middleware
{
    /**
     * Get the path the user should be redirected to when they are not authenticated.
     */
    protected function redirectTo(Request $request): ?string
    {
        if ($request->expectsJson()) {
            return null;
        }

        // Redirect admin routes to admin login
        if ($request->is('admin/*') || $request->is('admin')) {
            session()->flash('message', 'Vui lòng đăng nhập để tiếp tục');
            return route('admin.login');
        }

        // Redirect customer routes to customer login
        return route('login');
    }
}
