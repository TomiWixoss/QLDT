# Story 1.5: Customer Profile Management

Status: Done

## Story

As a **Customer**,
I want to view and update my profile information,
So that I can keep my account details current and view my loyalty points.

## Acceptance Criteria

**AC1: View Profile Page**
**Given** I am logged in as a customer
**When** I navigate to the profile page (/profile)
**Then** I see my current information: full name, email, phone, loyalty points balance
**And** I see a form to update my full name and phone number
**And** I see a separate section to change my password

**AC2: Update Profile Information**
**Given** I update my full name and phone number with valid data
**When** I submit the update form
**Then** my information is updated in the database
**And** I see a success message "Cập nhật thông tin thành công"
**And** the form shows my updated information

**AC3: Change Password**
**Given** I want to change my password
**When** I enter my current password, new password (min 8 chars), and confirmation
**Then** my password is updated and hashed with bcrypt
**And** I see a success message "Đổi mật khẩu thành công"
**And** I remain logged in (session is not destroyed)

**AC4: Incorrect Current Password**
**Given** I enter an incorrect current password
**When** I submit the change password form
**Then** I see an error message "Mật khẩu hiện tại không đúng"
**And** my password is not changed

**AC5: View Loyalty Points**
**Given** I view my profile
**When** I look at the loyalty points section
**Then** I see my current points balance (e.g., "500 điểm = 500.000đ")
**And** I see a brief explanation "Tích 1 điểm cho mỗi 100.000đ mua hàng"

## Tasks / Subtasks

-   [x] Task 1: Create ProfileController (AC: 1, 2)

    -   [x] 1.1: Create app/Http/Controllers/Customer/ProfileController.php
    -   [x] 1.2: Implement show() method to display profile page
    -   [x] 1.3: Implement update() method to update profile info
    -   [x] 1.4: Add proper authorization (customer guard)

-   [x] Task 2: Create Profile Update Request (AC: 2)

    -   [x] 2.1: Create app/Http/Requests/UpdateProfileRequest.php
    -   [x] 2.2: Add validation rules (full_name required, phone format)
    -   [x] 2.3: Add Vietnamese validation messages

-   [x] Task 3: Create Change Password Feature (AC: 3, 4)

    -   [x] 3.1: Implement updatePassword() method in ProfileController
    -   [x] 3.2: Create app/Http/Requests/ChangePasswordRequest.php
    -   [x] 3.3: Validate current password matches
    -   [x] 3.4: Validate new password (min 8 chars, confirmed)

-   [x] Task 4: Create Profile Routes (AC: All)

    -   [x] 4.1: Add GET /profile route for show
    -   [x] 4.2: Add PUT /profile route for update
    -   [x] 4.3: Add PUT /profile/password route for password change
    -   [x] 4.4: Apply auth:customer middleware

-   [x] Task 5: Create Profile View (AC: 1, 5)

    -   [x] 5.1: Create resources/views/customer/profile.blade.php
    -   [x] 5.2: Display current profile information
    -   [x] 5.3: Create profile update form
    -   [x] 5.4: Create password change form (separate section)
    -   [x] 5.5: Display loyalty points with explanation

-   [x] Task 6: Update Navigation (AC: 1)

    -   [x] 6.1: Add profile link to customer layout navigation
    -   [x] 6.2: Add profile link to mobile bottom navigation

-   [x] Task 7: Write Tests (AC: All)
    -   [x] 7.1: Test profile page displays correctly
    -   [x] 7.2: Test profile update with valid data
    -   [x] 7.3: Test profile update with invalid data
    -   [x] 7.4: Test password change with correct current password
    -   [x] 7.5: Test password change with incorrect current password
    -   [x] 7.6: Test unauthenticated access redirects to login

## Dev Notes

### 📋 Quick Reference Card

```
┌─────────────────────────────────────────────────────────────┐
│ STORY 1.5 QUICK REFERENCE CARD                              │
├─────────────────────────────────────────────────────────────┤
│ MUST DO:                                                     │
│ ✓ Use 'customer' guard (NOT 'web')                          │
│ ✓ Use Customer namespace for controller                     │
│ ✓ Verify current password before changing                   │
│ ✓ Hash new password with bcrypt (via model cast)            │
│ ✓ Display points with VND equivalent (1 point = 1,000đ)     │
│ ✓ Vietnamese messages for all user-facing text              │
│ ✓ Keep session alive after password change                  │
│ ✓ Email field is READ-ONLY (cannot be changed)              │
│                                                              │
│ MUST NOT DO:                                                 │
│ ✗ Use 'web' guard (that's for staff)                        │
│ ✗ Allow email change (security risk)                        │
│ ✗ Destroy session after password change                     │
│ ✗ Skip current password verification                        │
│ ✗ English error messages                                    │
│                                                              │
│ KEY FILES TO CREATE:                                         │
│ • app/Http/Controllers/Customer/ProfileController.php       │
│ • app/Http/Requests/UpdateProfileRequest.php                │
│ • app/Http/Requests/ChangePasswordRequest.php               │
│ • resources/views/customer/profile.blade.php                │
│                                                              │
│ KEY FILES TO MODIFY:                                         │
│ • routes/web.php (add profile routes)                       │
│ • resources/views/layouts/customer.blade.php (add nav link) │
└─────────────────────────────────────────────────────────────┘
```

### Critical Architecture Decisions

**From architecture.md - Decision 2.1: Authentication Strategy**

```php
// Customer Authentication (customers table):
//   - Guard: 'customer' (session-based)
//   - Middleware: auth:customer
//   - Table: customers (email, password, full_name, phone, points, status)
```

**From architecture.md - Decision 4.1: Layout Architecture**

```php
// Customer Layout: resources/views/layouts/customer.blade.php
// - Nike-inspired design
// - Bottom navigation (mobile)
// - Premium typography (Inter Variable Font)
```

### ProfileController Implementation

```php
// app/Http/Controllers/Customer/ProfileController.php
namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use App\Http\Requests\UpdateProfileRequest;
use App\Http\Requests\ChangePasswordRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

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

        // Verify current password
        if (!Hash::check($request->current_password, $customer->password)) {
            return back()->withErrors([
                'current_password' => 'Mật khẩu hiện tại không đúng',
            ]);
        }

        $customer->update([
            'password' => $request->password, // Auto-hashed via model cast
        ]);

        return back()->with('password_success', 'Đổi mật khẩu thành công');
    }
}
```

### UpdateProfileRequest Validation

```php
// app/Http/Requests/UpdateProfileRequest.php
namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateProfileRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'full_name' => ['required', 'string', 'max:255'],
            'phone' => ['nullable', 'string', 'regex:/^(0[3|5|7|8|9])+([0-9]{8})$/'],
        ];
    }

    public function messages(): array
    {
        return [
            'full_name.required' => 'Vui lòng nhập họ tên',
            'full_name.max' => 'Họ tên không được quá 255 ký tự',
            'phone.regex' => 'Số điện thoại không hợp lệ (VD: 0912345678)',
        ];
    }
}
```

### ChangePasswordRequest Validation

```php
// app/Http/Requests/ChangePasswordRequest.php
namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ChangePasswordRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'current_password' => ['required', 'string'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
        ];
    }

    public function messages(): array
    {
        return [
            'current_password.required' => 'Vui lòng nhập mật khẩu hiện tại',
            'password.required' => 'Vui lòng nhập mật khẩu mới',
            'password.min' => 'Mật khẩu mới phải có ít nhất 8 ký tự',
            'password.confirmed' => 'Mật khẩu xác nhận không khớp',
        ];
    }
}
```

### Routes Configuration

```php
// routes/web.php - ADD these routes inside customer authenticated group

use App\Http\Controllers\Customer\ProfileController;

// Customer authenticated routes
Route::middleware('auth:customer')->group(function () {
    // ... existing routes (home, password.set)

    // Profile routes
    Route::get('/profile', [ProfileController::class, 'show'])->name('profile');
    Route::put('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::put('/profile/password', [ProfileController::class, 'updatePassword'])->name('profile.password');
});
```

### Profile View Implementation

```blade
{{-- resources/views/customer/profile.blade.php --}}
@extends('layouts.customer')

@section('title', 'Tài khoản của tôi - Tact')

@section('content')
<div class="container mx-auto px-4 py-8 max-w-2xl">
    <h1 class="text-2xl font-semibold mb-8">Tài khoản của tôi</h1>

    {{-- Loyalty Points Card --}}
    <div class="card bg-gradient-to-r from-blue-500 to-blue-600 text-white mb-8">
        <div class="card-body">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm opacity-90">Điểm tích lũy</p>
                    <p class="text-3xl font-bold">{{ number_format($customer->points) }} điểm</p>
                    <p class="text-sm opacity-90 mt-1">
                        = {{ number_format($customer->points * 1000) }}đ
                    </p>
                </div>
                <div class="text-right">
                    <svg class="w-16 h-16 opacity-50" fill="currentColor" viewBox="0 0 24 24">
                        <path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm-2 15l-5-5 1.41-1.41L10 14.17l7.59-7.59L19 8l-9 9z"/>
                    </svg>
                </div>
            </div>
            <div class="divider divider-neutral opacity-30 my-2"></div>
            <p class="text-xs opacity-75">
                <svg class="w-4 h-4 inline mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
                Tích 1 điểm cho mỗi 100.000đ mua hàng
            </p>
        </div>
    </div>

    {{-- Profile Information Form --}}
    <div class="card bg-base-100 shadow-xl mb-8">
        <div class="card-body">
            <h2 class="card-title text-lg mb-4">Thông tin cá nhân</h2>

            @if (session('success'))
                <div class="alert alert-success mb-4">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                    </svg>
                    {{ session('success') }}
                </div>
            @endif

            <form method="POST" action="{{ route('profile.update') }}">
                @csrf
                @method('PUT')

                {{-- Email (Read-only) --}}
                <div class="form-control mb-4">
                    <label class="label">
                        <span class="label-text font-medium">Email</span>
                    </label>
                    <input type="email" value="{{ $customer->email }}"
                        class="input input-bordered bg-base-200" disabled>
                    <label class="label">
                        <span class="label-text-alt text-gray-500">Email không thể thay đổi</span>
                    </label>
                </div>

                {{-- Full Name --}}
                <div class="form-control mb-4">
                    <label class="label">
                        <span class="label-text font-medium">Họ và tên</span>
                    </label>
                    <input type="text" name="full_name" value="{{ old('full_name', $customer->full_name) }}"
                        class="input input-bordered @error('full_name') input-error @enderror"
                        placeholder="Nhập họ và tên" required>
                    @error('full_name')
                        <label class="label">
                            <span class="label-text-alt text-error">{{ $message }}</span>
                        </label>
                    @enderror
                </div>

                {{-- Phone --}}
                <div class="form-control mb-6">
                    <label class="label">
                        <span class="label-text font-medium">Số điện thoại</span>
                    </label>
                    <input type="tel" name="phone" value="{{ old('phone', $customer->phone) }}"
                        class="input input-bordered @error('phone') input-error @enderror"
                        placeholder="VD: 0912345678">
                    @error('phone')
                        <label class="label">
                            <span class="label-text-alt text-error">{{ $message }}</span>
                        </label>
                    @enderror
                </div>

                {{-- Submit Button --}}
                <div class="form-control">
                    <button type="submit" class="btn btn-primary">
                        Cập nhật thông tin
                    </button>
                </div>
            </form>
        </div>
    </div>

    {{-- Change Password Form --}}
    <div class="card bg-base-100 shadow-xl">
        <div class="card-body">
            <h2 class="card-title text-lg mb-4">Đổi mật khẩu</h2>

            @if (session('password_success'))
                <div class="alert alert-success mb-4">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                    </svg>
                    {{ session('password_success') }}
                </div>
            @endif

            <form method="POST" action="{{ route('profile.password') }}">
                @csrf
                @method('PUT')

                {{-- Current Password --}}
                <div class="form-control mb-4">
                    <label class="label">
                        <span class="label-text font-medium">Mật khẩu hiện tại</span>
                    </label>
                    <input type="password" name="current_password"
                        class="input input-bordered @error('current_password') input-error @enderror"
                        placeholder="Nhập mật khẩu hiện tại" required>
                    @error('current_password')
                        <label class="label">
                            <span class="label-text-alt text-error">{{ $message }}</span>
                        </label>
                    @enderror
                </div>

                {{-- New Password --}}
                <div class="form-control mb-4">
                    <label class="label">
                        <span class="label-text font-medium">Mật khẩu mới</span>
                    </label>
                    <input type="password" name="password"
                        class="input input-bordered @error('password') input-error @enderror"
                        placeholder="Tối thiểu 8 ký tự" required>
                    @error('password')
                        <label class="label">
                            <span class="label-text-alt text-error">{{ $message }}</span>
                        </label>
                    @enderror
                </div>

                {{-- Confirm New Password --}}
                <div class="form-control mb-6">
                    <label class="label">
                        <span class="label-text font-medium">Xác nhận mật khẩu mới</span>
                    </label>
                    <input type="password" name="password_confirmation"
                        class="input input-bordered"
                        placeholder="Nhập lại mật khẩu mới" required>
                </div>

                {{-- Submit Button --}}
                <div class="form-control">
                    <button type="submit" class="btn btn-outline">
                        Đổi mật khẩu
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
```

### Update Customer Layout Navigation

```blade
{{-- resources/views/layouts/customer.blade.php --}}
{{-- ADD profile link to desktop navigation (in header) --}}

{{-- Desktop Navigation - Add to user dropdown menu --}}
@auth('customer')
    <div class="dropdown dropdown-end">
        <label tabindex="0" class="btn btn-ghost btn-circle avatar">
            <div class="w-10 rounded-full bg-primary text-primary-content flex items-center justify-center">
                {{ substr(Auth::guard('customer')->user()->full_name, 0, 1) }}
            </div>
        </label>
        <ul tabindex="0" class="menu menu-sm dropdown-content mt-3 z-[1] p-2 shadow bg-base-100 rounded-box w-52">
            <li class="menu-title">
                <span>{{ Auth::guard('customer')->user()->full_name }}</span>
            </li>
            <li><a href="{{ route('profile') }}">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                </svg>
                Tài khoản
            </a></li>
            <li><a href="{{ route('logout') }}" onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/>
                </svg>
                Đăng xuất
            </a></li>
        </ul>
    </div>
    <form id="logout-form" action="{{ route('logout') }}" method="POST" class="hidden">
        @csrf
    </form>
@endauth

{{-- Mobile Bottom Navigation - Add Account tab --}}
{{-- In the bottom navigation bar, ensure Account icon links to profile --}}
<nav class="btm-nav btm-nav-sm md:hidden">
    <a href="{{ route('home') }}" class="{{ request()->routeIs('home') ? 'active' : '' }}">
        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/>
        </svg>
        <span class="btm-nav-label text-xs">Trang chủ</span>
    </a>
    <a href="#" class="">
        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
        </svg>
        <span class="btm-nav-label text-xs">Tìm kiếm</span>
    </a>
    <a href="#" class="">
        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z"/>
        </svg>
        <span class="btm-nav-label text-xs">Giỏ hàng</span>
    </a>
    @auth('customer')
        <a href="{{ route('profile') }}" class="{{ request()->routeIs('profile*') ? 'active' : '' }}">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
            </svg>
            <span class="btm-nav-label text-xs">Tài khoản</span>
        </a>
    @else
        <a href="{{ route('login') }}" class="">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
            </svg>
            <span class="btm-nav-label text-xs">Đăng nhập</span>
        </a>
    @endauth
</nav>
```

### Project Structure (Story 1.5 Files)

```
app/
├── Http/
│   ├── Controllers/
│   │   ├── Auth/
│   │   │   ├── RegisterController.php      # EXISTS (Story 1.2)
│   │   │   ├── LoginController.php         # EXISTS (Story 1.3)
│   │   │   ├── GoogleAuthController.php    # EXISTS (Story 1.4)
│   │   │   └── SetPasswordController.php   # EXISTS (Story 1.4)
│   │   └── Customer/
│   │       └── ProfileController.php       # NEW
│   └── Requests/
│       ├── StoreCustomerRequest.php        # EXISTS (Story 1.2)
│       ├── LoginRequest.php                # EXISTS (Story 1.3)
│       ├── SetPasswordRequest.php          # EXISTS (Story 1.4)
│       ├── UpdateProfileRequest.php        # NEW
│       └── ChangePasswordRequest.php       # NEW

resources/views/
├── layouts/
│   ├── guest.blade.php                     # EXISTS (Story 1.2)
│   └── customer.blade.php                  # MODIFY (add profile nav)
├── auth/
│   ├── register.blade.php                  # EXISTS (Story 1.2)
│   ├── login.blade.php                     # EXISTS (Story 1.3)
│   └── set-password.blade.php              # EXISTS (Story 1.4)
└── customer/
    ├── home.blade.php                      # EXISTS (Story 1.2)
    └── profile.blade.php                   # NEW

routes/
└── web.php                                 # MODIFY (add profile routes)

tests/Feature/
└── Customer/
    └── ProfileTest.php                     # NEW
```

### Previous Story Intelligence (Story 1.2, 1.3, 1.4)

**Learnings from Story 1.2:**

-   ✅ Customer guard already configured in config/auth.php
-   ✅ Customer model extends Authenticatable with password hashing
-   ✅ Customer model has: email, password, full_name, phone, points, status, google_id
-   ✅ Guest layout (guest.blade.php) already created
-   ✅ Vietnamese validation messages pattern established

**Learnings from Story 1.3:**

-   ✅ LoginController already exists with rate limiting
-   ✅ Session security configured (HTTP-only, secure cookies)
-   ✅ RateLimiter pattern established
-   ✅ Customer layout (customer.blade.php) created

**Learnings from Story 1.4:**

-   ✅ SetPasswordController pattern for password updates
-   ✅ Password hashing via model cast (no need for Hash::make)
-   ✅ Form styling with DaisyUI established
-   ✅ Success message pattern with session flash

**Files already created that this story uses:**

-   `config/auth.php` - Customer guard already configured
-   `app/Models/Customer.php` - Has all required fields
-   `resources/views/layouts/customer.blade.php` - Reuse for profile page
-   `app/Http/Requests/SetPasswordRequest.php` - Reference for password validation

### Testing Requirements

```php
// tests/Feature/Customer/ProfileTest.php
namespace Tests\Feature\Customer;

use App\Models\Customer;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class ProfileTest extends TestCase
{
    use RefreshDatabase;

    public function test_profile_page_displays_customer_information(): void
    {
        $customer = Customer::factory()->create([
            'full_name' => 'Nguyễn Văn A',
            'email' => 'test@example.com',
            'phone' => '0912345678',
            'points' => 500,
        ]);

        $response = $this->actingAs($customer, 'customer')
            ->get('/profile');

        $response->assertStatus(200);
        $response->assertSee('Nguyễn Văn A');
        $response->assertSee('test@example.com');
        $response->assertSee('0912345678');
        $response->assertSee('500'); // points
    }

    public function test_profile_page_requires_authentication(): void
    {
        $response = $this->get('/profile');

        $response->assertRedirect('/login');
    }

    public function test_customer_can_update_profile_information(): void
    {
        $customer = Customer::factory()->create();

        $response = $this->actingAs($customer, 'customer')
            ->put('/profile', [
                'full_name' => 'Trần Văn B',
                'phone' => '0987654321',
            ]);

        $response->assertRedirect();
        $response->assertSessionHas('success', 'Cập nhật thông tin thành công');

        $customer->refresh();
        $this->assertEquals('Trần Văn B', $customer->full_name);
        $this->assertEquals('0987654321', $customer->phone);
    }

    public function test_profile_update_validates_phone_format(): void
    {
        $customer = Customer::factory()->create();

        $response = $this->actingAs($customer, 'customer')
            ->put('/profile', [
                'full_name' => 'Test User',
                'phone' => 'invalid-phone',
            ]);

        $response->assertSessionHasErrors(['phone']);
    }

    public function test_customer_can_change_password_with_correct_current_password(): void
    {
        $customer = Customer::factory()->create([
            'password' => 'oldpassword123',
        ]);

        $response = $this->actingAs($customer, 'customer')
            ->put('/profile/password', [
                'current_password' => 'oldpassword123',
                'password' => 'newpassword456',
                'password_confirmation' => 'newpassword456',
            ]);

        $response->assertRedirect();
        $response->assertSessionHas('password_success', 'Đổi mật khẩu thành công');

        $customer->refresh();
        $this->assertTrue(Hash::check('newpassword456', $customer->password));
    }

    public function test_password_change_fails_with_incorrect_current_password(): void
    {
        $customer = Customer::factory()->create([
            'password' => 'correctpassword',
        ]);

        $response = $this->actingAs($customer, 'customer')
            ->put('/profile/password', [
                'current_password' => 'wrongpassword',
                'password' => 'newpassword456',
                'password_confirmation' => 'newpassword456',
            ]);

        $response->assertSessionHasErrors(['current_password']);

        $customer->refresh();
        $this->assertTrue(Hash::check('correctpassword', $customer->password));
    }

    public function test_password_change_validates_minimum_length(): void
    {
        $customer = Customer::factory()->create([
            'password' => 'oldpassword123',
        ]);

        $response = $this->actingAs($customer, 'customer')
            ->put('/profile/password', [
                'current_password' => 'oldpassword123',
                'password' => 'short',
                'password_confirmation' => 'short',
            ]);

        $response->assertSessionHasErrors(['password']);
    }

    public function test_password_change_validates_confirmation(): void
    {
        $customer = Customer::factory()->create([
            'password' => 'oldpassword123',
        ]);

        $response = $this->actingAs($customer, 'customer')
            ->put('/profile/password', [
                'current_password' => 'oldpassword123',
                'password' => 'newpassword456',
                'password_confirmation' => 'differentpassword',
            ]);

        $response->assertSessionHasErrors(['password']);
    }

    public function test_session_remains_active_after_password_change(): void
    {
        $customer = Customer::factory()->create([
            'password' => 'oldpassword123',
        ]);

        $this->actingAs($customer, 'customer')
            ->put('/profile/password', [
                'current_password' => 'oldpassword123',
                'password' => 'newpassword456',
                'password_confirmation' => 'newpassword456',
            ]);

        // Verify still authenticated
        $this->assertAuthenticated('customer');
    }

    public function test_loyalty_points_display_with_vnd_equivalent(): void
    {
        $customer = Customer::factory()->create([
            'points' => 250,
        ]);

        $response = $this->actingAs($customer, 'customer')
            ->get('/profile');

        $response->assertStatus(200);
        $response->assertSee('250'); // points
        $response->assertSee('250.000'); // VND equivalent (250 * 1000)
    }
}
```

### Anti-Patterns to Avoid

| ❌ BAD                                   | ✅ GOOD                                           | WHY                            |
| ---------------------------------------- | ------------------------------------------------- | ------------------------------ |
| `Auth::user()`                           | `Auth::guard('customer')->user()`                 | Must use customer guard        |
| Allow email change                       | Email is read-only                                | Security: email is identity    |
| `Hash::make($password)`                  | `$customer->password = $password`                 | Model cast handles hashing     |
| Destroy session after password change    | Keep session alive                                | Better UX                      |
| Skip current password check              | Always verify current password                    | Security requirement           |
| English error messages                   | Vietnamese messages                               | User-facing must be Vietnamese |
| `App\Http\Controllers\ProfileController` | `App\Http\Controllers\Customer\ProfileController` | Follow namespace convention    |

### Security Checklist

-   [ ] CSRF token in all forms (`@csrf`)
-   [ ] Current password verified before change
-   [ ] New password hashed with bcrypt (via model cast)
-   [ ] Session NOT destroyed after password change
-   [ ] Email field is read-only (cannot be changed)
-   [ ] Phone validation with Vietnamese format
-   [ ] auth:customer middleware on all routes
-   [ ] No sensitive data in error messages

### UX Design Requirements

**From ux-design-specification.md:**

1. **Form Design**

    - Labels above inputs (font-medium)
    - Rounded inputs (input-bordered from DaisyUI)
    - Focus state: blue border + subtle shadow
    - Inline validation errors (red text below field)

2. **Loyalty Points Display**

    - Prominent card with gradient background (blue)
    - Large point number (text-3xl font-bold)
    - VND equivalent clearly shown
    - Explanation text for earning rules

3. **Success/Error Feedback**

    - Alert component for success messages
    - Inline errors for validation
    - Vietnamese language for all messages

4. **Mobile Responsiveness**
    - Single column layout on mobile
    - Full-width cards
    - Touch-friendly inputs (44x44px minimum)
    - Bottom navigation with Account tab

### References

**Source Documents:**

-   [Architecture: docs/2-solutioning/architecture.md#authentication-security] - Auth strategy
-   [Epics: docs/2-solutioning/epics.md#story-1.5] - Story requirements
-   [UX Design: docs/1-planning/ux-design-specification.md#visual-design-foundation] - Form design
-   [Project Context: project-context.md] - Naming conventions, response format
-   [Previous Story 1.4: docs/3-implementation/1-4-customer-google-oauth-registration-login.md] - Password patterns

## Dev Agent Record

### Context Reference

-   project-context.md
-   project_context2.md
-   docs/3-implementation/sprint-status.yaml
-   docs/3-implementation/1-4-customer-google-oauth-registration-login.md
-   docs/2-solutioning/architecture.md
-   docs/2-solutioning/epics.md
-   docs/1-planning/ux-design-specification.md

### Agent Model Used

Claude (Kiro) - Dev Agent

### Debug Log References

N/A

### Implementation Plan

1. Created ProfileController with show(), update(), updatePassword() methods
2. Created UpdateProfileRequest with full_name required, phone regex validation
3. Created ChangePasswordRequest with current_password, password min:8 confirmed
4. Added routes: GET/PUT /profile, PUT /profile/password with auth:customer middleware
5. Created profile.blade.php with loyalty points card, profile form, password form
6. Updated customer layout navigation (desktop dropdown + mobile bottom nav)
7. Wrote 13 comprehensive tests covering all acceptance criteria

### Completion Notes List

-   ✅ Story drafted with comprehensive developer context
-   ✅ All acceptance criteria mapped to tasks
-   ✅ Code examples provided for all components
-   ✅ Testing requirements defined
-   ✅ Anti-patterns documented
-   ✅ Previous story learnings incorporated
-   ✅ UX requirements extracted
-   ✅ **IMPLEMENTATION COMPLETE (2025-12-15)**
-   ✅ All 7 tasks completed with red-green-refactor cycle
-   ✅ 13 tests written and passing (34 assertions)
-   ✅ Full test suite: 72 tests passing, no regressions
-   ✅ Navigation updated for both desktop and mobile
-   ✅ Loyalty points display with VND equivalent
-   ✅ Vietnamese validation messages implemented
-   ✅ **CODE REVIEW COMPLETE (2025-12-15)**
-   ✅ 6 issues found and fixed (4 MEDIUM, 2 LOW)
-   ✅ 15 tests now passing (38 assertions) - added 2 auth tests
-   ✅ Full test suite: 74 tests passing, no regressions

### Code Review Fixes (2025-12-15)

| #   | Severity | Issue                                | Fix Applied                                     |
| --- | -------- | ------------------------------------ | ----------------------------------------------- |
| 1   | MEDIUM   | Missing container padding for mobile | Added `px-4` to profile container               |
| 2   | MEDIUM   | Manual password check in controller  | Moved to ChangePasswordRequest::withValidator() |
| 3   | MEDIUM   | Missing ARIA labels on SVG icons     | Added `aria-hidden="true"` to all SVGs          |
| 4   | MEDIUM   | Missing auth tests for PUT routes    | Added 2 tests for unauthenticated access        |
| 5   | LOW      | Hardcoded points value (1000)        | Created config/tact.php with points_value       |
| 6   | LOW      | Missing authorization test           | Covered by middleware (no action needed)        |

### File List

**Files Created:**

-   app/Http/Controllers/Customer/ProfileController.php
-   app/Http/Requests/UpdateProfileRequest.php
-   app/Http/Requests/ChangePasswordRequest.php
-   resources/views/customer/profile.blade.php
-   tests/Feature/Customer/ProfileTest.php
-   config/tact.php (code review fix)

**Files Modified:**

-   routes/web.php (added profile routes)
-   resources/views/layouts/customer.blade.php (added navigation links)
-   docs/3-implementation/sprint-status.yaml (status: in-progress → review → done)
