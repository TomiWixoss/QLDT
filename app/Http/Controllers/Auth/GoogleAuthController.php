<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\Customer;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Laravel\Socialite\Facades\Socialite;

class GoogleAuthController extends Controller
{
    /**
     * Redirect to Google OAuth consent screen
     */
    public function redirectToGoogle(): RedirectResponse
    {
        try {
            return Socialite::driver('google')->redirect();
        } catch (\Exception $e) {
            Log::error('Google OAuth redirect error', [
                'message' => $e->getMessage(),
            ]);

            return redirect()->route('login')
                ->with('error', 'Không thể kết nối với Google. Vui lòng kiểm tra cấu hình.');
        }
    }

    /**
     * Handle Google OAuth callback
     */
    public function handleGoogleCallback(): RedirectResponse
    {
        try {
            $googleUser = Socialite::driver('google')->user();
        } catch (\Exception $e) {
            Log::error('Google OAuth error', [
                'message' => $e->getMessage(),
            ]);

            return redirect()->route('login')
                ->with('error', 'Không thể đăng nhập với Google. Vui lòng thử lại.');
        }

        // Check if customer exists by google_id (returning user)
        $customer = Customer::where('google_id', $googleUser->getId())->first();

        if ($customer) {
            Auth::guard('customer')->login($customer);

            return redirect()->route('home')
                ->with('success', 'Đăng nhập thành công');
        }

        // Check if email already exists (registered via email/password)
        $existingCustomer = Customer::where('email', $googleUser->getEmail())->first();

        if ($existingCustomer) {
            // Link Google account to existing customer
            $existingCustomer->update(['google_id' => $googleUser->getId()]);
            Auth::guard('customer')->login($existingCustomer);

            return redirect()->route('home')
                ->with('success', 'Tài khoản đã được liên kết với Google');
        }

        // First-time registration
        $customer = Customer::create([
            'email' => $googleUser->getEmail(),
            'full_name' => $googleUser->getName(),
            'google_id' => $googleUser->getId(),
            'password' => null,
            'phone' => null,
            'points' => 0,
            'status' => 'active',
        ]);

        Auth::guard('customer')->login($customer);

        return redirect()->route('password.set')
            ->with('success', 'Đăng ký thành công! Vui lòng đặt mật khẩu để bảo mật tài khoản');
    }
}
