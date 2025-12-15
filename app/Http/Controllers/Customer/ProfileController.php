<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use App\Http\Requests\ChangePasswordRequest;
use App\Http\Requests\UpdateProfileRequest;
use Illuminate\Support\Facades\Auth;

class ProfileController extends Controller
{
    /**
     * Display customer profile page
     */
    public function show()
    {
        $customer = Auth::guard('customer')->user();

        return view('customer.profile', [
            'customer' => $customer,
        ]);
    }

    /**
     * Update customer profile information
     */
    public function update(UpdateProfileRequest $request)
    {
        $customer = Auth::guard('customer')->user();

        $customer->update($request->validated());

        return back()->with('success', 'Cập nhật thông tin thành công');
    }

    /**
     * Update customer password
     */
    public function updatePassword(ChangePasswordRequest $request)
    {
        $customer = Auth::guard('customer')->user();

        // Current password validation is handled by ChangePasswordRequest::withValidator()
        $customer->update([
            'password' => $request->password, // Auto-hashed via model cast
        ]);

        return back()->with('password_success', 'Đổi mật khẩu thành công');
    }
}
