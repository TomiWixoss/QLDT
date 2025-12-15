<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\AdminLoginRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Str;
use Illuminate\View\View;

class AdminLoginController extends Controller
{
    /**
     * Display admin login form
     */
    public function showLoginForm(): View
    {
        return view('admin.auth.login');
    }

    /**
     * Handle admin login request
     */
    public function login(AdminLoginRequest $request): RedirectResponse
    {
        // Check rate limiting
        $throttleKey = $this->throttleKey($request);

        if (RateLimiter::tooManyAttempts($throttleKey, 5)) {
            $seconds = RateLimiter::availableIn($throttleKey);
            return back()->withErrors([
                'email' => "Quá nhiều lần đăng nhập thất bại. Vui lòng thử lại sau {$seconds} giây.",
            ])->withInput($request->only('email'));
        }

        // Check if user exists and is active BEFORE attempting login
        $user = \App\Models\User::where('email', $request->email)->first();

        if ($user && $user->status === 'inactive') {
            return back()
                ->withInput($request->only('email'))
                ->withErrors(['email' => 'Tài khoản đã bị vô hiệu hóa. Vui lòng liên hệ quản trị viên.']);
        }

        // Attempt login with 'web' guard (default)
        if (Auth::attempt($request->only('email', 'password'), $request->boolean('remember'))) {
            RateLimiter::clear($throttleKey);
            $request->session()->regenerate();

            return redirect()->intended('/admin/dashboard');
        }

        // Failed login - increment rate limiter
        RateLimiter::hit($throttleKey, 60);

        return back()->withErrors([
            'email' => 'Email hoặc mật khẩu không đúng',
        ])->withInput($request->only('email'));
    }

    /**
     * Handle admin logout
     */
    public function logout(Request $request): RedirectResponse
    {
        Auth::logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/admin/login');
    }

    /**
     * Get the rate limiting throttle key
     */
    protected function throttleKey(Request $request): string
    {
        return Str::transliterate(Str::lower($request->input('email')) . '|' . $request->ip());
    }
}
