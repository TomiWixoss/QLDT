<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\SetPasswordRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class SetPasswordController extends Controller
{
    /**
     * Show set password form
     */
    public function showSetPasswordForm(): View|RedirectResponse
    {
        $customer = Auth::guard('customer')->user();

        // Only show if password is null (Google-only account)
        if ($customer->hasPassword()) {
            return redirect()->route('home');
        }

        return view('auth.set-password');
    }

    /**
     * Set password for Google-registered user
     */
    public function setPassword(SetPasswordRequest $request): RedirectResponse
    {
        $customer = Auth::guard('customer')->user();

        $customer->update([
            'password' => $request->password,
        ]);

        return redirect()->route('home')
            ->with('success', 'Đặt mật khẩu thành công');
    }
}
