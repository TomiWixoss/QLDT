# Story 1.2: Customer Registration with Email/Password

Status: Done

## Story

As a **Customer**,
I want to register an account using my email and password,
So that I can shop online and track my orders.

## Acceptance Criteria

**AC1: Successful Registration**
**Given** I am on the registration page
**When** I enter valid email, password (min 8 characters), password confirmation, full name, and phone number
**Then** my account is created in the customers table
**And** my password is hashed using bcrypt (min 10 rounds)
**And** I receive a success message "Đăng ký thành công"
**And** I am automatically logged in with the 'customer' guard
**And** I am redirected to the homepage

**AC2: Duplicate Email Validation**
**Given** I enter an email that already exists
**When** I submit the registration form
**Then** I see an error message "Email đã được sử dụng"
**And** the form retains my input (except password)

**AC3: Password Length Validation**
**Given** I enter a password shorter than 8 characters
**When** I submit the registration form
**Then** I see an error message "Mật khẩu phải có ít nhất 8 ký tự"

**AC4: Password Confirmation Validation**
**Given** I enter mismatched passwords
**When** I submit the registration form
**Then** I see an error message "Mật khẩu xác nhận không khớp"

**AC5: Phone Number Validation**
**Given** I enter an invalid phone number format
**When** I submit the registration form
**Then** I see an error message "Số điện thoại không hợp lệ"

## Tasks / Subtasks

-   [x] Task 1: Setup Customer Authentication Guard (AC: All)

    -   [x] 1.1: Configure 'customer' guard in config/auth.php
    -   [x] 1.2: Configure 'customers' provider pointing to Customer model
    -   [x] 1.3: Update Customer model to implement Authenticatable

-   [x] Task 2: Create Registration Routes (AC: 1)

    -   [x] 2.1: Add GET /register route for registration form
    -   [x] 2.2: Add POST /register route for form submission
    -   [x] 2.3: Group routes under guest middleware

-   [x] Task 3: Create RegisterController (AC: 1, 2, 3, 4, 5)

    -   [x] 3.1: Create app/Http/Controllers/Auth/RegisterController.php
    -   [x] 3.2: Implement showRegistrationForm() method
    -   [x] 3.3: Implement store() method with validation and creation

-   [x] Task 4: Create StoreCustomerRequest (AC: 2, 3, 4, 5)

    -   [x] 4.1: Create app/Http/Requests/StoreCustomerRequest.php
    -   [x] 4.2: Define validation rules (email unique, password min 8, phone regex)
    -   [x] 4.3: Define Vietnamese error messages

-   [x] Task 5: Create Guest Layout (AC: 1)

    -   [x] 5.1: Create resources/views/layouts/guest.blade.php
    -   [x] 5.2: Include Tailwind CSS + DaisyUI styling
    -   [x] 5.3: Add Inter Variable Font

-   [x] Task 6: Create Registration View (AC: 1, 2, 3, 4, 5)

    -   [x] 6.1: Create resources/views/auth/register.blade.php
    -   [x] 6.2: Build form with DaisyUI components
    -   [x] 6.3: Add client-side validation feedback
    -   [x] 6.4: Add error display for validation messages
    -   [x] 6.5: Add link to login page

-   [x] Task 7: Create Customer Layout (AC: 1)

    -   [x] 7.1: Create resources/views/layouts/customer.blade.php
    -   [x] 7.2: Add header with navigation
    -   [x] 7.3: Add footer
    -   [x] 7.4: Add mobile bottom navigation

-   [x] Task 8: Create Homepage (AC: 1)

    -   [x] 8.1: Create resources/views/customer/home.blade.php
    -   [x] 8.2: Add welcome message for logged-in user
    -   [x] 8.3: Add placeholder for featured products

-   [x] Task 9: Write Tests (AC: All)
    -   [x] 9.1: Test successful registration creates customer
    -   [x] 9.2: Test duplicate email shows error
    -   [x] 9.3: Test short password shows error
    -   [x] 9.4: Test mismatched passwords shows error
    -   [x] 9.5: Test invalid phone shows error
    -   [x] 9.6: Test user is logged in after registration

## Dev Notes

### 📋 Quick Reference Card

```
┌─────────────────────────────────────────────────────────────┐
│ STORY 1.2 QUICK REFERENCE CARD                              │
├─────────────────────────────────────────────────────────────┤
│ MUST DO:                                                     │
│ ✓ Customer guard separate from web (staff) guard            │
│ ✓ bcrypt password hashing (Laravel default)                 │
│ ✓ Vietnamese error messages                                 │
│ ✓ Form retains input on error (except password)             │
│ ✓ Auto-login after registration                             │
│                                                              │
│ MUST NOT DO:                                                 │
│ ✗ Use 'web' guard for customers (that's for staff)          │
│ ✗ Store plain text passwords                                │
│ ✗ English error messages                                    │
│ ✗ Redirect to admin dashboard                               │
│                                                              │
│ KEY FILES TO CREATE:                                         │
│ • config/auth.php (modify)                                  │
│ • app/Http/Controllers/Auth/RegisterController.php          │
│ • app/Http/Requests/StoreCustomerRequest.php                │
│ • resources/views/layouts/guest.blade.php                   │
│ • resources/views/layouts/customer.blade.php                │
│ • resources/views/auth/register.blade.php                   │
│ • resources/views/customer/home.blade.php                   │
│ • routes/web.php (modify)                                   │
└─────────────────────────────────────────────────────────────┘
```

### Critical Architecture Decisions

**From architecture.md - Decision 2.1: Authentication Strategy**

```php
// DUAL AUTHENTICATION SYSTEM
// Staff (users table) → 'web' guard → /admin/login
// Customer (customers table) → 'customer' guard → /login, /register

// config/auth.php
'guards' => [
    'web' => [
        'driver' => 'session',
        'provider' => 'users',
    ],
    'customer' => [
        'driver' => 'session',
        'provider' => 'customers',
    ],
],

'providers' => [
    'users' => [
        'driver' => 'eloquent',
        'model' => App\Models\User::class,
    ],
    'customers' => [
        'driver' => 'eloquent',
        'model' => App\Models\Customer::class,
    ],
],
```

### Customer Model Requirements

**CRITICAL: Customer model must implement Authenticatable**

```php
// app/Models/Customer.php
namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class Customer extends Authenticatable
{
    use Notifiable;

    protected $fillable = [
        'email',
        'password',
        'full_name',
        'phone',
        'address',
        'google_id',
        'points',
        'status',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected function casts(): array
    {
        return [
            'password' => 'hashed',
            'points' => 'integer',
        ];
    }
}
```

### Validation Rules (Vietnamese Messages)

```php
// app/Http/Requests/StoreCustomerRequest.php
public function rules(): array
{
    return [
        'email' => ['required', 'email', 'unique:customers,email'],
        'password' => ['required', 'string', 'min:8', 'confirmed'],
        'full_name' => ['required', 'string', 'max:100'],
        'phone' => ['required', 'regex:/^(0[3|5|7|8|9])+([0-9]{8})$/'],
    ];
}

public function messages(): array
{
    return [
        'email.required' => 'Vui lòng nhập email',
        'email.email' => 'Email không hợp lệ',
        'email.unique' => 'Email đã được sử dụng',
        'password.required' => 'Vui lòng nhập mật khẩu',
        'password.min' => 'Mật khẩu phải có ít nhất 8 ký tự',
        'password.confirmed' => 'Mật khẩu xác nhận không khớp',
        'full_name.required' => 'Vui lòng nhập họ tên',
        'full_name.max' => 'Họ tên không được quá 100 ký tự',
        'phone.required' => 'Vui lòng nhập số điện thoại',
        'phone.regex' => 'Số điện thoại không hợp lệ',
    ];
}
```

### Controller Implementation Pattern

```php
// app/Http/Controllers/Auth/RegisterController.php
namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreCustomerRequest;
use App\Models\Customer;
use Illuminate\Support\Facades\Auth;

class RegisterController extends Controller
{
    public function showRegistrationForm()
    {
        return view('auth.register');
    }

    public function store(StoreCustomerRequest $request)
    {
        $customer = Customer::create([
            'email' => $request->email,
            'password' => $request->password, // Auto-hashed via cast
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
```

### Routes Configuration

```php
// routes/web.php
use App\Http\Controllers\Auth\RegisterController;

// Guest routes (not logged in)
Route::middleware('guest:customer')->group(function () {
    Route::get('/register', [RegisterController::class, 'showRegistrationForm'])
        ->name('register');
    Route::post('/register', [RegisterController::class, 'store']);
});

// Customer authenticated routes
Route::middleware('auth:customer')->group(function () {
    Route::get('/', function () {
        return view('customer.home');
    })->name('home');
});

// Public homepage (for guests)
Route::get('/', function () {
    return view('customer.home');
})->name('home');
```

### UX Design Requirements

**From ux-design-specification.md:**

1. **Form Design (DaisyUI)**

    - Use `input input-bordered` for text fields
    - Use `btn btn-primary` for submit button
    - Use `alert alert-error` for error messages
    - Mobile-first: Full-width inputs on mobile

2. **Color Palette**

    - Primary: `#3b82f6` (tact-blue)
    - Error: `#ef4444`
    - Success: `#10b981`

3. **Typography**

    - Font: Inter Variable
    - Form labels: `text-sm font-medium text-gray-700`
    - Input text: `text-base`

4. **Touch Targets**
    - Minimum 44px height for buttons and inputs
    - Adequate spacing between form elements

### Guest Layout Template

```blade
{{-- resources/views/layouts/guest.blade.php --}}
<!DOCTYPE html>
<html lang="vi" data-theme="tact">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Tact - Cửa hàng điện thoại')</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="min-h-screen bg-base-200 flex items-center justify-center">
    <div class="w-full max-w-md p-6">
        @yield('content')
    </div>
</body>
</html>
```

### Registration Form Template

```blade
{{-- resources/views/auth/register.blade.php --}}
@extends('layouts.guest')

@section('title', 'Đăng ký - Tact')

@section('content')
<div class="card bg-base-100 shadow-xl">
    <div class="card-body">
        <h2 class="card-title text-2xl font-bold text-center mb-6">Đăng ký tài khoản</h2>

        <form method="POST" action="{{ route('register') }}">
            @csrf

            {{-- Full Name --}}
            <div class="form-control mb-4">
                <label class="label">
                    <span class="label-text">Họ và tên</span>
                </label>
                <input type="text" name="full_name" value="{{ old('full_name') }}"
                    class="input input-bordered @error('full_name') input-error @enderror"
                    placeholder="Nguyễn Văn A" required>
                @error('full_name')
                    <label class="label">
                        <span class="label-text-alt text-error">{{ $message }}</span>
                    </label>
                @enderror
            </div>

            {{-- Email --}}
            <div class="form-control mb-4">
                <label class="label">
                    <span class="label-text">Email</span>
                </label>
                <input type="email" name="email" value="{{ old('email') }}"
                    class="input input-bordered @error('email') input-error @enderror"
                    placeholder="email@example.com" required>
                @error('email')
                    <label class="label">
                        <span class="label-text-alt text-error">{{ $message }}</span>
                    </label>
                @enderror
            </div>

            {{-- Phone --}}
            <div class="form-control mb-4">
                <label class="label">
                    <span class="label-text">Số điện thoại</span>
                </label>
                <input type="tel" name="phone" value="{{ old('phone') }}"
                    class="input input-bordered @error('phone') input-error @enderror"
                    placeholder="0901234567" required>
                @error('phone')
                    <label class="label">
                        <span class="label-text-alt text-error">{{ $message }}</span>
                    </label>
                @enderror
            </div>

            {{-- Password --}}
            <div class="form-control mb-4">
                <label class="label">
                    <span class="label-text">Mật khẩu</span>
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

            {{-- Password Confirmation --}}
            <div class="form-control mb-6">
                <label class="label">
                    <span class="label-text">Xác nhận mật khẩu</span>
                </label>
                <input type="password" name="password_confirmation"
                    class="input input-bordered"
                    placeholder="Nhập lại mật khẩu" required>
            </div>

            {{-- Submit Button --}}
            <div class="form-control">
                <button type="submit" class="btn btn-primary">
                    Đăng ký
                </button>
            </div>
        </form>

        <div class="divider">hoặc</div>

        <p class="text-center text-sm">
            Đã có tài khoản?
            <a href="{{ route('login') }}" class="link link-primary">Đăng nhập</a>
        </p>
    </div>
</div>
@endsection
```

### Project Structure (Story 1.2 Files)

```
app/
├── Http/
│   ├── Controllers/
│   │   └── Auth/
│   │       └── RegisterController.php    # NEW
│   └── Requests/
│       └── StoreCustomerRequest.php      # NEW
├── Models/
│   └── Customer.php                      # MODIFY (add Authenticatable)

config/
└── auth.php                              # MODIFY (add customer guard)

resources/views/
├── layouts/
│   ├── guest.blade.php                   # NEW
│   └── customer.blade.php                # NEW
├── auth/
│   └── register.blade.php                # NEW
└── customer/
    └── home.blade.php                    # NEW

routes/
└── web.php                               # MODIFY (add registration routes)

tests/Feature/
└── Auth/
    └── RegistrationTest.php              # NEW
```

### Previous Story Intelligence (Story 1.1)

**Learnings from Story 1.1:**

-   ✅ Customer model already exists with correct fields
-   ✅ Database schema has customers table with email, password, full_name, phone, points, status
-   ✅ phpunit.xml configured for MySQL testing
-   ✅ Factories exist: CustomerFactory.php

**Files already created that this story uses:**

-   `app/Models/Customer.php` - Needs modification to extend Authenticatable
-   `database/factories/CustomerFactory.php` - Can use for testing

**Patterns established:**

-   Vietnamese messages for all user-facing text
-   Form Request classes for validation
-   Feature tests for all acceptance criteria

### Testing Requirements

```php
// tests/Feature/Auth/RegistrationTest.php
namespace Tests\Feature\Auth;

use App\Models\Customer;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class RegistrationTest extends TestCase
{
    use RefreshDatabase;

    public function test_registration_screen_can_be_rendered(): void
    {
        $response = $this->get('/register');
        $response->assertStatus(200);
    }

    public function test_new_customers_can_register(): void
    {
        $response = $this->post('/register', [
            'full_name' => 'Test User',
            'email' => 'test@example.com',
            'phone' => '0901234567',
            'password' => 'password123',
            'password_confirmation' => 'password123',
        ]);

        $this->assertAuthenticated('customer');
        $response->assertRedirect(route('home'));
    }

    public function test_duplicate_email_shows_error(): void
    {
        Customer::factory()->create(['email' => 'test@example.com']);

        $response = $this->post('/register', [
            'full_name' => 'Test User',
            'email' => 'test@example.com',
            'phone' => '0901234567',
            'password' => 'password123',
            'password_confirmation' => 'password123',
        ]);

        $response->assertSessionHasErrors(['email']);
    }

    public function test_short_password_shows_error(): void
    {
        $response = $this->post('/register', [
            'full_name' => 'Test User',
            'email' => 'test@example.com',
            'phone' => '0901234567',
            'password' => 'short',
            'password_confirmation' => 'short',
        ]);

        $response->assertSessionHasErrors(['password']);
    }

    public function test_mismatched_passwords_shows_error(): void
    {
        $response = $this->post('/register', [
            'full_name' => 'Test User',
            'email' => 'test@example.com',
            'phone' => '0901234567',
            'password' => 'password123',
            'password_confirmation' => 'different123',
        ]);

        $response->assertSessionHasErrors(['password']);
    }

    public function test_invalid_phone_shows_error(): void
    {
        $response = $this->post('/register', [
            'full_name' => 'Test User',
            'email' => 'test@example.com',
            'phone' => '123456',
            'password' => 'password123',
            'password_confirmation' => 'password123',
        ]);

        $response->assertSessionHasErrors(['phone']);
    }
}
```

### Anti-Patterns to Avoid

| ❌ BAD                                | ✅ GOOD                                     | WHY                            |
| ------------------------------------- | ------------------------------------------- | ------------------------------ |
| `Auth::login($customer)`              | `Auth::guard('customer')->login($customer)` | Must use customer guard        |
| `Hash::make($password)` in controller | Let model cast handle it                    | Password cast auto-hashes      |
| English error messages                | Vietnamese messages                         | User-facing must be Vietnamese |
| `$request->all()`                     | `$request->validated()`                     | Only use validated data        |
| Redirect to `/admin`                  | Redirect to `/` (home)                      | Customers go to customer area  |

### Security Checklist

-   [ ] CSRF token in form (`@csrf`)
-   [ ] Password hashed with bcrypt (via model cast)
-   [ ] Email validated as unique
-   [ ] Phone validated with regex
-   [ ] No sensitive data in session flash
-   [ ] Password not retained in form on error

### References

**Source Documents:**

-   [Architecture: docs/2-solutioning/architecture.md#authentication-security] - Auth strategy
-   [Epics: docs/2-solutioning/epics.md#story-1.2] - Story requirements
-   [UX Design: docs/1-planning/ux-design-specification.md#design-system-foundation] - Form design
-   [Project Context: project-context.md] - Naming conventions, response format
-   [Previous Story: docs/3-implementation/1-1-project-setup-database-schema.md] - Patterns established

## Review Follow-ups (AI)

-   [ ] [AI-Review][MEDIUM] Login link in register.blade.php uses `#` - Update to `route('login')` when Story 1.3 is implemented [resources/views/auth/register.blade.php:95]
-   [ ] [AI-Review][MEDIUM] Logout form action in customer.blade.php uses `#` - Add logout route and controller when Story 1.3 is implemented [resources/views/layouts/customer.blade.php:42]

## Dev Agent Record

### Context Reference

<!-- Path(s) to story context XML will be added here by context workflow -->

### Agent Model Used

Claude Sonnet 4

### Debug Log References

### Completion Notes List

-   ✅ Configured dual authentication system: 'web' guard for staff, 'customer' guard for customers
-   ✅ Customer model already extended Authenticatable (from Story 1.1)
-   ✅ Created RegisterController with showRegistrationForm() and store() methods
-   ✅ Created StoreCustomerRequest with Vietnamese validation messages
-   ✅ Created guest.blade.php layout with Inter font and DaisyUI
-   ✅ Created customer.blade.php layout with header, footer, mobile bottom nav
-   ✅ Created register.blade.php form with all required fields and error display
-   ✅ Created home.blade.php with welcome message and trust signals
-   ✅ All 8 registration tests pass (36 total tests pass)
-   ✅ Password hashing via model cast (bcrypt)
-   ✅ Auto-login after registration with customer guard
-   ✅ Form retains input on error (except password)

### Code Review Fixes (2025-12-14)

-   ✅ Fixed phone regex in StoreCustomerRequest: `/^0[35789][0-9]{8}$/` (simpler, correct)
-   ✅ Added `remember_token` to Customer model hidden fields
-   ✅ Fixed CustomerFactory phone format to match Vietnamese phone regex
-   ⏳ Login/logout routes deferred to Story 1.3 (added as action items)

### File List

**New Files:**

-   app/Http/Controllers/Auth/RegisterController.php
-   app/Http/Requests/StoreCustomerRequest.php
-   resources/views/layouts/guest.blade.php
-   resources/views/layouts/customer.blade.php
-   resources/views/auth/register.blade.php
-   resources/views/customer/home.blade.php
-   tests/Feature/Auth/RegistrationTest.php

**Modified Files:**

-   config/auth.php (added customer guard and customers provider)
-   routes/web.php (added registration routes)
-   app/Models/Customer.php (added remember_token to hidden)
-   app/Http/Requests/StoreCustomerRequest.php (fixed phone regex)
-   database/factories/CustomerFactory.php (fixed phone format)
