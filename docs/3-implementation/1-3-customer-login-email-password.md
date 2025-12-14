# Story 1.3: Customer Login with Email/Password

Status: Done

## Story

As a **Customer**,
I want to login using my email and password,
So that I can access my account and make purchases.

## Acceptance Criteria

**AC1: Successful Login**
**Given** I have a registered account
**When** I enter correct email and password on the login page
**Then** I am logged in with the 'customer' guard
**And** I see a success message "Đăng nhập thành công"
**And** I am redirected to the homepage
**And** my session is created with HTTP-only and secure cookies

**AC2: Invalid Credentials**
**Given** I enter incorrect email or password
**When** I submit the login form
**Then** I see an error message "Email hoặc mật khẩu không đúng"
**And** I remain on the login page
**And** the failed attempt is logged for security monitoring

**AC3: Login Throttling**
**Given** I attempt to login 5 times with wrong credentials within 1 minute
**When** I try the 6th attempt
**Then** I see an error message "Quá nhiều lần đăng nhập sai. Vui lòng thử lại sau 1 phút"
**And** my IP is temporarily throttled

**AC4: Logout**
**Given** I am logged in
**When** I click the logout button
**Then** my session is destroyed
**And** I am redirected to the homepage
**And** I see a message "Đã đăng xuất thành công"

**AC5: Remember Me (Optional)**
**Given** I check the "Ghi nhớ đăng nhập" checkbox
**When** I login successfully
**Then** my session persists for 30 days (via remember token)

## Tasks / Subtasks

-   [x] Task 1: Create Login Routes (AC: 1, 2, 3)

    -   [x] 1.1: Add GET /login route for login form
    -   [x] 1.2: Add POST /login route for form submission
    -   [x] 1.3: Add POST /logout route for logout
    -   [x] 1.4: Apply guest:customer middleware to login routes
    -   [x] 1.5: Apply auth:customer middleware to logout route

-   [x] Task 2: Create LoginController (AC: 1, 2, 3, 4)

    -   [x] 2.1: Create app/Http/Controllers/Auth/LoginController.php
    -   [x] 2.2: Implement showLoginForm() method
    -   [x] 2.3: Implement login() method with authentication
    -   [x] 2.4: Implement logout() method
    -   [x] 2.5: Add rate limiting using RateLimiter facade

-   [x] Task 3: Create LoginRequest (AC: 2, 3)

    -   [x] 3.1: Create app/Http/Requests/LoginRequest.php
    -   [x] 3.2: Define validation rules (email required, password required)
    -   [x] 3.3: Define Vietnamese error messages
    -   [x] 3.4: Implement rate limiting logic in request

-   [x] Task 4: Create Login View (AC: 1, 2, 3, 5)

    -   [x] 4.1: Create resources/views/auth/login.blade.php
    -   [x] 4.2: Build form with DaisyUI components
    -   [x] 4.3: Add error display for validation messages
    -   [x] 4.4: Add "Remember me" checkbox
    -   [x] 4.5: Add link to registration page
    -   [x] 4.6: Add link to forgot password (placeholder for future - Google OAuth button disabled)

-   [x] Task 5: Update Layouts (AC: 4)

    -   [x] 5.1: Update customer.blade.php logout form action
    -   [x] 5.2: Add login/register links for guests in header
    -   [x] 5.3: Show user name when logged in

-   [x] Task 6: Configure Session Security (AC: 1)

    -   [x] 6.1: Verify session config in config/session.php
    -   [x] 6.2: Ensure HTTP-only cookies (http_only => true by default)
    -   [x] 6.3: Ensure secure cookies in production (SESSION_SECURE_COOKIE env)

-   [x] Task 7: Write Tests (AC: All)
    -   [x] 7.1: Test login screen renders
    -   [x] 7.2: Test successful login with valid credentials
    -   [x] 7.3: Test failed login with invalid credentials
    -   [x] 7.4: Test login throttling after 5 failed attempts
    -   [x] 7.5: Test logout destroys session
    -   [x] 7.6: Test remember me functionality

## Dev Notes

### 📋 Quick Reference Card

```
┌─────────────────────────────────────────────────────────────┐
│ STORY 1.3 QUICK REFERENCE CARD                              │
├─────────────────────────────────────────────────────────────┤
│ MUST DO:                                                     │
│ ✓ Use 'customer' guard (NOT 'web')                          │
│ ✓ Rate limiting: 5 attempts per minute per IP               │
│ ✓ Vietnamese error messages                                 │
│ ✓ Log failed login attempts                                 │
│ ✓ HTTP-only and secure session cookies                      │
│ ✓ CSRF protection on all forms                              │
│                                                              │
│ MUST NOT DO:                                                 │
│ ✗ Use 'web' guard (that's for staff)                        │
│ ✗ Expose technical error details to users                   │
│ ✗ Allow unlimited login attempts                            │
│ ✗ English error messages                                    │
│ ✗ Redirect to admin dashboard                               │
│                                                              │
│ KEY FILES TO CREATE:                                         │
│ • app/Http/Controllers/Auth/LoginController.php             │
│ • app/Http/Requests/LoginRequest.php                        │
│ • resources/views/auth/login.blade.php                      │
│ • tests/Feature/Auth/LoginTest.php                          │
│                                                              │
│ KEY FILES TO MODIFY:                                         │
│ • routes/web.php (add login/logout routes)                  │
│ • resources/views/layouts/customer.blade.php (logout form)  │
│ • resources/views/auth/register.blade.php (login link)      │
└─────────────────────────────────────────────────────────────┘
```

### Critical Architecture Decisions

**From architecture.md - Decision 2.1: Authentication Strategy**

```php
// DUAL AUTHENTICATION SYSTEM (Already configured in Story 1.2)
// Staff (users table) → 'web' guard → /admin/login
// Customer (customers table) → 'customer' guard → /login

// config/auth.php (already configured)
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
```

**From architecture.md - Decision 2.3: Security Measures**

```php
// Rate limiting: 5 attempts per minute
// Login throttling implementation
RateLimiter::for('login', function (Request $request) {
    return Limit::perMinute(5)->by($request->ip());
});
```

### LoginController Implementation Pattern

```php
// app/Http/Controllers/Auth/LoginController.php
namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\LoginRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class LoginController extends Controller
{
    public function showLoginForm()
    {
        return view('auth.login');
    }

    public function login(LoginRequest $request)
    {
        $credentials = $request->only('email', 'password');
        $remember = $request->boolean('remember');

        if (Auth::guard('customer')->attempt($credentials, $remember)) {
            $request->session()->regenerate();

            return redirect()->intended(route('home'))
                ->with('success', 'Đăng nhập thành công');
        }

        // Log failed attempt for security monitoring
        Log::warning('Failed login attempt', [
            'email' => $request->email,
            'ip' => $request->ip(),
            'user_agent' => $request->userAgent(),
        ]);

        return back()
            ->withInput($request->only('email', 'remember'))
            ->withErrors([
                'email' => 'Email hoặc mật khẩu không đúng',
            ]);
    }

    public function logout(Request $request)
    {
        Auth::guard('customer')->logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('home')
            ->with('success', 'Đã đăng xuất thành công');
    }
}
```

### LoginRequest with Rate Limiting

```php
// app/Http/Requests/LoginRequest.php
namespace App\Http\Requests;

use Illuminate\Auth\Events\Lockout;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;

class LoginRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'email' => ['required', 'string', 'email'],
            'password' => ['required', 'string'],
        ];
    }

    public function messages(): array
    {
        return [
            'email.required' => 'Vui lòng nhập email',
            'email.email' => 'Email không hợp lệ',
            'password.required' => 'Vui lòng nhập mật khẩu',
        ];
    }

    /**
     * Ensure the login request is not rate limited.
     */
    public function ensureIsNotRateLimited(): void
    {
        if (! RateLimiter::tooManyAttempts($this->throttleKey(), 5)) {
            return;
        }

        event(new Lockout($this));

        $seconds = RateLimiter::availableIn($this->throttleKey());

        throw ValidationException::withMessages([
            'email' => 'Quá nhiều lần đăng nhập sai. Vui lòng thử lại sau ' . ceil($seconds / 60) . ' phút',
        ]);
    }

    /**
     * Get the rate limiting throttle key for the request.
     */
    public function throttleKey(): string
    {
        return Str::transliterate(Str::lower($this->string('email')).'|'.$this->ip());
    }
}
```

### Routes Configuration

```php
// routes/web.php - ADD these routes

use App\Http\Controllers\Auth\LoginController;

// Guest routes (not logged in as customer)
Route::middleware('guest:customer')->group(function () {
    // Registration routes (already exist from Story 1.2)
    Route::get('/register', [RegisterController::class, 'showRegistrationForm'])
        ->name('register');
    Route::post('/register', [RegisterController::class, 'store']);

    // Login routes (NEW)
    Route::get('/login', [LoginController::class, 'showLoginForm'])
        ->name('login');
    Route::post('/login', [LoginController::class, 'login']);
});

// Customer authenticated routes
Route::middleware('auth:customer')->group(function () {
    Route::post('/logout', [LoginController::class, 'logout'])
        ->name('logout');
});
```

### Login View Template

```blade
{{-- resources/views/auth/login.blade.php --}}
@extends('layouts.guest')

@section('title', 'Đăng nhập - Tact')

@section('content')
<div class="card bg-base-100 shadow-xl">
    <div class="card-body">
        <h2 class="card-title text-2xl font-bold text-center mb-6">Đăng nhập</h2>

        {{-- Session Status --}}
        @if (session('success'))
            <div class="alert alert-success mb-4">
                {{ session('success') }}
            </div>
        @endif

        <form method="POST" action="{{ route('login') }}">
            @csrf

            {{-- Email --}}
            <div class="form-control mb-4">
                <label class="label">
                    <span class="label-text">Email</span>
                </label>
                <input type="email" name="email" value="{{ old('email') }}"
                    class="input input-bordered @error('email') input-error @enderror"
                    placeholder="email@example.com" required autofocus>
                @error('email')
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
                    placeholder="Nhập mật khẩu" required>
                @error('password')
                    <label class="label">
                        <span class="label-text-alt text-error">{{ $message }}</span>
                    </label>
                @enderror
            </div>

            {{-- Remember Me --}}
            <div class="form-control mb-6">
                <label class="label cursor-pointer justify-start gap-2">
                    <input type="checkbox" name="remember" class="checkbox checkbox-primary checkbox-sm"
                        {{ old('remember') ? 'checked' : '' }}>
                    <span class="label-text">Ghi nhớ đăng nhập</span>
                </label>
            </div>

            {{-- Submit Button --}}
            <div class="form-control">
                <button type="submit" class="btn btn-primary">
                    Đăng nhập
                </button>
            </div>
        </form>

        <div class="divider">hoặc</div>

        {{-- Google OAuth Button (Placeholder for Story 1.4) --}}
        <button class="btn btn-outline w-full" disabled>
            <svg class="w-5 h-5 mr-2" viewBox="0 0 24 24">
                <path fill="currentColor" d="M22.56 12.25c0-.78-.07-1.53-.2-2.25H12v4.26h5.92c-.26 1.37-1.04 2.53-2.21 3.31v2.77h3.57c2.08-1.92 3.28-4.74 3.28-8.09z"/>
                <path fill="currentColor" d="M12 23c2.97 0 5.46-.98 7.28-2.66l-3.57-2.77c-.98.66-2.23 1.06-3.71 1.06-2.86 0-5.29-1.93-6.16-4.53H2.18v2.84C3.99 20.53 7.7 23 12 23z"/>
                <path fill="currentColor" d="M5.84 14.09c-.22-.66-.35-1.36-.35-2.09s.13-1.43.35-2.09V7.07H2.18C1.43 8.55 1 10.22 1 12s.43 3.45 1.18 4.93l2.85-2.22.81-.62z"/>
                <path fill="currentColor" d="M12 5.38c1.62 0 3.06.56 4.21 1.64l3.15-3.15C17.45 2.09 14.97 1 12 1 7.7 1 3.99 3.47 2.18 7.07l3.66 2.84c.87-2.6 3.3-4.53 6.16-4.53z"/>
            </svg>
            Đăng nhập với Google (Sắp có)
        </button>

        <p class="text-center text-sm mt-4">
            Chưa có tài khoản?
            <a href="{{ route('register') }}" class="link link-primary">Đăng ký ngay</a>
        </p>
    </div>
</div>
@endsection
```

### Update Customer Layout (Logout Form)

```blade
{{-- resources/views/layouts/customer.blade.php --}}
{{-- Update the logout form in header --}}

@auth('customer')
    <div class="dropdown dropdown-end">
        <div tabindex="0" role="button" class="btn btn-ghost">
            {{ Auth::guard('customer')->user()->full_name }}
            <svg class="w-4 h-4 ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
            </svg>
        </div>
        <ul tabindex="0" class="dropdown-content menu p-2 shadow bg-base-100 rounded-box w-52">
            <li><a href="#">Tài khoản của tôi</a></li>
            <li><a href="#">Đơn hàng</a></li>
            <li>
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit" class="w-full text-left">Đăng xuất</button>
                </form>
            </li>
        </ul>
    </div>
@else
    <a href="{{ route('login') }}" class="btn btn-ghost">Đăng nhập</a>
    <a href="{{ route('register') }}" class="btn btn-primary">Đăng ký</a>
@endauth
```

### Session Configuration Verification

```php
// config/session.php - VERIFY these settings
return [
    'driver' => env('SESSION_DRIVER', 'database'), // or 'file'
    'lifetime' => env('SESSION_LIFETIME', 120),
    'expire_on_close' => false,
    'encrypt' => false,
    'cookie' => env('SESSION_COOKIE', 'tact_session'),
    'path' => '/',
    'domain' => env('SESSION_DOMAIN'),
    'secure' => env('SESSION_SECURE_COOKIE', false), // true in production
    'http_only' => true, // CRITICAL: Must be true
    'same_site' => 'lax',
];
```

### Project Structure (Story 1.3 Files)

```
app/
├── Http/
│   ├── Controllers/
│   │   └── Auth/
│   │       ├── RegisterController.php    # EXISTS (Story 1.2)
│   │       └── LoginController.php       # NEW
│   └── Requests/
│       ├── StoreCustomerRequest.php      # EXISTS (Story 1.2)
│       └── LoginRequest.php              # NEW

resources/views/
├── layouts/
│   ├── guest.blade.php                   # EXISTS (Story 1.2)
│   └── customer.blade.php                # MODIFY (logout form)
├── auth/
│   ├── register.blade.php                # MODIFY (login link)
│   └── login.blade.php                   # NEW
└── customer/
    └── home.blade.php                    # EXISTS (Story 1.2)

routes/
└── web.php                               # MODIFY (add login/logout routes)

tests/Feature/
└── Auth/
    ├── RegistrationTest.php              # EXISTS (Story 1.2)
    └── LoginTest.php                     # NEW
```

### Previous Story Intelligence (Story 1.2)

**Learnings from Story 1.2:**

-   ✅ Customer guard already configured in config/auth.php
-   ✅ Customer model extends Authenticatable with remember_token
-   ✅ Guest layout (guest.blade.php) already created
-   ✅ Customer layout (customer.blade.php) already created
-   ✅ Vietnamese validation messages pattern established
-   ✅ DaisyUI form components pattern established

**Files already created that this story uses:**

-   `config/auth.php` - Customer guard already configured
-   `app/Models/Customer.php` - Already extends Authenticatable
-   `resources/views/layouts/guest.blade.php` - Reuse for login page
-   `resources/views/layouts/customer.blade.php` - Update logout form

**Review Follow-ups from Story 1.2 (NOW RESOLVED):**

-   ✅ Login link in register.blade.php uses `#` → Update to `route('login')`
-   ✅ Logout form action in customer.blade.php uses `#` → Add logout route

### Testing Requirements

```php
// tests/Feature/Auth/LoginTest.php
namespace Tests\Feature\Auth;

use App\Models\Customer;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class LoginTest extends TestCase
{
    use RefreshDatabase;

    public function test_login_screen_can_be_rendered(): void
    {
        $response = $this->get('/login');
        $response->assertStatus(200);
    }

    public function test_customers_can_login_with_valid_credentials(): void
    {
        $customer = Customer::factory()->create([
            'password' => 'password123',
        ]);

        $response = $this->post('/login', [
            'email' => $customer->email,
            'password' => 'password123',
        ]);

        $this->assertAuthenticated('customer');
        $response->assertRedirect(route('home'));
    }

    public function test_customers_cannot_login_with_invalid_password(): void
    {
        $customer = Customer::factory()->create([
            'password' => 'password123',
        ]);

        $response = $this->post('/login', [
            'email' => $customer->email,
            'password' => 'wrong-password',
        ]);

        $this->assertGuest('customer');
        $response->assertSessionHasErrors(['email']);
    }

    public function test_customers_cannot_login_with_nonexistent_email(): void
    {
        $response = $this->post('/login', [
            'email' => 'nonexistent@example.com',
            'password' => 'password123',
        ]);

        $this->assertGuest('customer');
        $response->assertSessionHasErrors(['email']);
    }

    public function test_login_is_throttled_after_5_failed_attempts(): void
    {
        $customer = Customer::factory()->create();

        // Make 5 failed attempts
        for ($i = 0; $i < 5; $i++) {
            $this->post('/login', [
                'email' => $customer->email,
                'password' => 'wrong-password',
            ]);
        }

        // 6th attempt should be throttled
        $response = $this->post('/login', [
            'email' => $customer->email,
            'password' => 'wrong-password',
        ]);

        $response->assertSessionHasErrors(['email']);
        $this->assertStringContainsString(
            'Quá nhiều lần đăng nhập sai',
            session('errors')->get('email')[0]
        );
    }

    public function test_customers_can_logout(): void
    {
        $customer = Customer::factory()->create();

        $this->actingAs($customer, 'customer');

        $response = $this->post('/logout');

        $this->assertGuest('customer');
        $response->assertRedirect(route('home'));
    }

    public function test_remember_me_creates_remember_token(): void
    {
        $customer = Customer::factory()->create([
            'password' => 'password123',
        ]);

        $response = $this->post('/login', [
            'email' => $customer->email,
            'password' => 'password123',
            'remember' => true,
        ]);

        $this->assertAuthenticated('customer');
        $customer->refresh();
        $this->assertNotNull($customer->remember_token);
    }
}
```

### Anti-Patterns to Avoid

| ❌ BAD                                               | ✅ GOOD                                                                    | WHY                                   |
| ---------------------------------------------------- | -------------------------------------------------------------------------- | ------------------------------------- |
| `Auth::attempt($credentials)`                        | `Auth::guard('customer')->attempt($credentials)`                           | Must use customer guard               |
| No rate limiting                                     | Use RateLimiter facade                                                     | Prevent brute force attacks           |
| `return back()->withErrors(['Invalid credentials'])` | `return back()->withErrors(['email' => 'Email hoặc mật khẩu không đúng'])` | Vietnamese messages                   |
| `$request->session()->flush()`                       | `$request->session()->invalidate()`                                        | Proper session cleanup                |
| Expose "Email not found" vs "Wrong password"         | Generic "Email hoặc mật khẩu không đúng"                                   | Security: don't reveal which is wrong |

### Security Checklist

-   [ ] CSRF token in login form (`@csrf`)
-   [ ] Rate limiting: 5 attempts per minute
-   [ ] Failed login attempts logged
-   [ ] Session regenerated on login
-   [ ] Session invalidated on logout
-   [ ] CSRF token regenerated on logout
-   [ ] HTTP-only session cookies
-   [ ] Generic error message (don't reveal if email exists)
-   [ ] Remember token properly handled

### UX Design Requirements

**From ux-design-specification.md:**

1. **Form Design (DaisyUI)**

    - Use `input input-bordered` for text fields
    - Use `btn btn-primary` for submit button
    - Use `alert alert-error` for error messages
    - Use `checkbox checkbox-primary` for remember me

2. **Loading States**

    - Add loading indicator on form submit (optional enhancement)

3. **Touch Targets**
    - Minimum 44px height for buttons and inputs
    - Checkbox with adequate click area

### References

**Source Documents:**

-   [Architecture: docs/2-solutioning/architecture.md#authentication-security] - Auth strategy, rate limiting
-   [Epics: docs/2-solutioning/epics.md#story-1.3] - Story requirements
-   [UX Design: docs/1-planning/ux-design-specification.md#design-system-foundation] - Form design
-   [Project Context: project-context.md] - Naming conventions, response format
-   [Previous Story: docs/3-implementation/1-2-customer-registration-email-password.md] - Patterns established

## Dev Agent Record

### Context Reference

-   project-context.md
-   project_context2.md
-   docs/3-implementation/sprint-status.yaml

### Agent Model Used

Claude (Anthropic)

### Debug Log References

-   Discovered missing `remember_token` column in customers table
-   Created migration to add remember_token column

### Completion Notes List

-   ✅ Implemented customer login with email/password using 'customer' guard
-   ✅ Rate limiting: 5 attempts per minute per IP+email combination
-   ✅ Vietnamese error messages for all validation and authentication errors
-   ✅ Failed login attempts logged for security monitoring
-   ✅ Session regeneration on login, invalidation on logout
-   ✅ Remember me functionality with remember_token cookie
-   ✅ HTTP-only session cookies configured by default
-   ✅ All 11 login tests passing
-   ✅ Full test suite (47 tests) passing with no regressions
-   ✅ Updated customer layout with proper logout form and login link for guests
-   ✅ Updated register page with link to login page

### File List

**New Files:**

-   app/Http/Controllers/Auth/LoginController.php
-   app/Http/Requests/LoginRequest.php
-   resources/views/auth/login.blade.php
-   tests/Feature/Auth/LoginTest.php
-   database/migrations/2025_12_14_200000_add_remember_token_to_customers_table.php

**Modified Files:**

-   routes/web.php (added login/logout routes)
-   resources/views/layouts/customer.blade.php (logout form action, login link)
-   resources/views/auth/register.blade.php (login link)
-   docs/3-implementation/sprint-status.yaml (status update)
-   .env.example (added SESSION_SECURE_COOKIE documentation)

### Change Log

-   2025-12-14: Story 1.3 implemented - Customer Login with Email/Password
-   2025-12-14: Code Review completed - Fixed PHPUnit deprecation warnings, added CSRF test, documented session security settings
