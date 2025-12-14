<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreCustomerRequest;
use App\Models\Customer;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;

class RegisterController extends Controller
{
    /**
     * Display the registration form.
     */
    public function showRegistrationForm(): View
    {
        return view('auth.register');
    }

    /**
     * Handle registration request.
     */
    public function store(StoreCustomerRequest $request): RedirectResponse
    {
        $customer = Customer::create([
            'email' => $request->email,
            'password' => $request->password,
            'full_name' => $request->full_name,
            'phone' => $request->phone,
            'points' => 0,
            'status' => 'active',
        ]);

        Auth::guard('customer')->login($customer);

        return redirect()->route('home')
            ->with('success', 'Đăng ký thành công');
    }
}
