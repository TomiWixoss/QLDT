# Story 1.4: Customer Google OAuth Registration & Login

Status: Ready for Review

## Story

As a **Customer**,
I want to register and login using my Google account,
So that I can quickly access the system without creating a new password.

## Acceptance Criteria

**AC1: Google OAuth Redirect**
**Given** I am on the registration or login page
**When** I click the "Đăng nhập với Google" button
**Then** I am redirected to Google OAuth consent screen
**And** I can authorize the application to access my Google profile

**AC2: First-Time Google Registration**
**Given** I authorize the application and this is my first time
**When** Google redirects me back to the application
**Then** a new customer account is created with my Google email and name
**And** the google_id field is set to my Google ID
**And** the password field is set to NULL (not random value)
**And** I am logged in with the 'customer' guard
**And** I see a flash message "Đăng ký thành công! Vui lòng đặt mật khẩu để bảo mật tài khoản"
**And** I am redirected to the set password page

**AC3: Returning Google User Login**
**Given** I have previously registered with Google
**When** Google redirects me back after authorization
**Then** I am logged in to my existing account
**And** I see a success message "Đăng nhập thành công"
**And** I am redirected to the homepage

**AC4: Google User Without Password Tries Email Login**
**Given** I registered with Google but haven't set a password yet
**When** I try to login with email/password
**Then** I see an error message "Tài khoản này đăng ký qua Google. Vui lòng đặt mật khẩu trước"
**And** I see a link to the set password page

**AC5: Set Password After Google Registration**
**Given** I registered with Google and have no password set
**When** I am on the set password page
**And** I enter a new password (min 8 characters) and confirmation
**Then** my password is saved (hashed with bcrypt)
**And** I see a success message "Đặt mật khẩu thành công"
**And** I am redirected to the homepage

**AC6: Existing Email Conflict**
**Given** I try to login with Google
**And** my Google email already exists in the system (registered via email/password)
**When** Google redirects me back
**Then** my existing account is linked to my Google ID
**And** I am logged in to my existing account
**And** I see a message "Tài khoản đã được liên kết với Google"

## Tasks / Subtasks

-   [x] Task 1: Install and Configure Laravel Socialite (AC: 1)

    -   [x] 1.1: Install Laravel Socialite via Composer
    -   [x] 1.2: Add Google OAuth credentials to .env
    -   [x] 1.3: Configure services.php for Google provider
    -   [x] 1.4: Add GOOGLE_CLIENT_ID, GOOGLE_CLIENT_SECRET to .env.example

-   [x] Task 2: Create Google OAuth Routes (AC: 1, 2, 3)

    -   [x] 2.1: Add GET /auth/google route for redirect to Google
    -   [x] 2.2: Add GET /auth/google/callback route for callback handling
    -   [x] 2.3: Apply guest:customer middleware to OAuth routes

-   [x] Task 3: Create GoogleAuthController (AC: 1, 2, 3, 6)

    -   [x] 3.1: Create app/Http/Controllers/Auth/GoogleAuthController.php
    -   [x] 3.2: Implement redirectToGoogle() method
    -   [x] 3.3: Implement handleGoogleCallback() method
    -   [x] 3.4: Handle first-time registration (create customer with google_id)
    -   [x] 3.5: Handle returning user login (find by google_id)
    -   [x] 3.6: Handle email conflict (link existing account to google_id)

-   [x] Task 4: Create Set Password Feature (AC: 5)

    -   [x] 4.1: Add GET /password/set route for set password form
    -   [x] 4.2: Add POST /password/set route for form submission
    -   [x] 4.3: Create SetPasswordController or add to existing controller
    -   [x] 4.4: Create SetPasswordRequest with validation
    -   [x] 4.5: Create resources/views/auth/set-password.blade.php

-   [x] Task 5: Update Login Logic (AC: 4)

    -   [x] 5.1: Modify LoginController to check if password is null
    -   [x] 5.2: Return specific error message for Google-only accounts
    -   [x] 5.3: Include link to set password page in error

-   [x] Task 6: Update Views (AC: 1)

    -   [x] 6.1: Enable Google OAuth button in login.blade.php
    -   [x] 6.2: Add Google OAuth button to register.blade.php
    -   [x] 6.3: Style Google button with proper Google branding

-   [x] Task 7: Write Tests (AC: All)
    -   [x] 7.1: Test redirect to Google OAuth
    -   [x] 7.2: Test first-time registration creates customer
    -   [x] 7.3: Test returning user is logged in
    -   [x] 7.4: Test email conflict links account
    -   [x] 7.5: Test Google user without password cannot email login
    -   [x] 7.6: Test set password functionality

## Dev Notes

### 📋 Quick Reference Card

```
┌─────────────────────────────────────────────────────────────┐
│ STORY 1.4 QUICK REFERENCE CARD                              │
├─────────────────────────────────────────────────────────────┤
│ MUST DO:                                                     │
│ ✓ Use Laravel Socialite for Google OAuth                    │
│ ✓ Use 'customer' guard (NOT 'web')                          │
│ ✓ Store google_id in customers table                        │
│ ✓ Password NULL for Google-only accounts (not random)       │
│ ✓ Force password set after first Google registration        │
│ ✓ Vietnamese messages for all user-facing text              │
│ ✓ Handle email conflict (link existing account)             │
│                                                              │
│ MUST NOT DO:                                                 │
│ ✗ Use 'web' guard (that's for staff)                        │
│ ✗ Set random password for Google users                      │
│ ✗ Allow Google users to login via email without password    │
│ ✗ Create duplicate accounts for same email                  │
│ ✗ English error messages                                    │
│                                                              │
│ KEY FILES TO CREATE:                                         │
│ • app/Http/Controllers/Auth/GoogleAuthController.php        │
│ • app/Http/Controllers/Auth/SetPasswordController.php       │
│ • app/Http/Requests/SetPasswordRequest.php                  │
│ • resources/views/auth/set-password.blade.php               │
│                                                              │
│ KEY FILES TO MODIFY:                                         │
│ • config/services.php (add Google OAuth config)             │
│ • routes/web.php (add OAuth routes)                         │
│ • resources/views/auth/login.blade.php (enable Google btn)  │
│ • resources/views/auth/register.blade.php (add Google btn)  │
│ • app/Http/Controllers/Auth/LoginController.php (check pwd) │
│ • .env and .env.example (add Google credentials)            │
└─────────────────────────────────────────────────────────────┘
```

### Critical Architecture Decisions

**From architecture.md - Decision 2.1: Authentication Strategy**

```php
// DUAL AUTHENTICATION SYSTEM
// Customer Authentication (customers table):
//   - Google OAuth (primary)
//   - Email/Password (fallback)
//   - Laravel Socialite

// Google OAuth callback: /auth/google/callback
// Password required after first Google login
```

### Laravel Socialite Installation

```bash
composer require laravel/socialite
```

### Environment Configuration

```env
# .env - ADD these variables
GOOGLE_CLIENT_ID=your-google-client-id
GOOGLE_CLIENT_SECRET=your-google-client-secret
GOOGLE_REDIRECT_URI="${APP_URL}/auth/google/callback"
```

### Services Configuration

```php
// config/services.php - ADD Google provider
'google' => [
    'client_id' => env('GOOGLE_CLIENT_ID'),
    'client_secret' => env('GOOGLE_CLIENT_SECRET'),
    'redirect' => env('GOOGLE_REDIRECT_URI'),
],
```

### GoogleAuthController Implementation

```php
// app/Http/Controllers/Auth/GoogleAuthController.php
namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\Customer;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Laravel\Socialite\Facades\Socialite;

class GoogleAuthController extends Controller
{
    /**
     * Redirect to Google OAuth consent screen
     */
    public function redirectToGoogle()
    {
        return Socialite::driver('google')->redirect();
    }

    /**
     * Handle Google OAuth callback
     */
    public function handleGoogleCallback()
    {
        try {
            $googleUser = Socialite::driver('google')->user();
        } catch (\Exception $e) {
            return redirect()->route('login')
                ->with('error', 'Không thể đăng nhập với Google. Vui lòng thử lại.');
        }

        // Check if customer exists by google_id
        $customer = Customer::where('google_id', $googleUser->getId())->first();

        if ($customer) {
            // Returning user - login
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
            'password' => null, // NULL, not random
            'phone' => null,
            'points' => 0,
            'status' => 'active',
        ]);

        Auth::guard('customer')->login($customer);

        return redirect()->route('password.set')
            ->with('success', 'Đăng ký thành công! Vui lòng đặt mật khẩu để bảo mật tài khoản');
    }
}
```

### SetPasswordController Implementation

```php
// app/Http/Controllers/Auth/SetPasswordController.php
namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\SetPasswordRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class SetPasswordController extends Controller
{
    /**
     * Show set password form
     */
    public function showSetPasswordForm()
    {
        $customer = Auth::guard('customer')->user();

        // Only show if password is null (Google-only account)
        if ($customer->password !== null) {
            return redirect()->route('home');
        }

        return view('auth.set-password');
    }

    /**
     * Set password for Google-registered user
     */
    public function setPassword(SetPasswordRequest $request)
    {
        $customer = Auth::guard('customer')->user();

        $customer->update([
            'password' => $request->password, // Auto-hashed via model cast
        ]);

        return redirect()->route('home')
            ->with('success', 'Đặt mật khẩu thành công');
    }
}
```

### SetPasswordRequest Validation

```php
// app/Http/Requests/SetPasswordRequest.php
namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class SetPasswordRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'password' => ['required', 'string', 'min:8', 'confirmed'],
        ];
    }

    public function messages(): array
    {
        return [
            'password.required' => 'Vui lòng nhập mật khẩu',
            'password.min' => 'Mật khẩu phải có ít nhất 8 ký tự',
            'password.confirmed' => 'Mật khẩu xác nhận không khớp',
        ];
    }
}
```

### Routes Configuration

```php
// routes/web.php - ADD these routes

use App\Http\Controllers\Auth\GoogleAuthController;
use App\Http\Controllers\Auth\SetPasswordController;

// Google OAuth routes (guest only)
Route::middleware('guest:customer')->group(function () {
    Route::get('/auth/google', [GoogleAuthController::class, 'redirectToGoogle'])
        ->name('auth.google');
    Route::get('/auth/google/callback', [GoogleAuthController::class, 'handleGoogleCallback'])
        ->name('auth.google.callback');
});

// Set password route (authenticated customer only)
Route::middleware('auth:customer')->group(function () {
    Route::get('/password/set', [SetPasswordController::class, 'showSetPasswordForm'])
        ->name('password.set');
    Route::post('/password/set', [SetPasswordController::class, 'setPassword']);
});
```

### Update LoginController for Google-Only Accounts

```php
// app/Http/Controllers/Auth/LoginController.php
// MODIFY login() method to check for null password

public function login(LoginRequest $request)
{
    // Check rate limiting
    $request->ensureIsNotRateLimited();

    $customer = Customer::where('email', $request->email)->first();

    // Check if this is a Google-only account (no password set)
    if ($customer && $customer->password === null) {
        RateLimiter::hit($request->throttleKey());

        return back()
            ->withInput($request->only('email', 'remember'))
            ->withErrors([
                'email' => 'Tài khoản này đăng ký qua Google. Vui lòng đặt mật khẩu trước.',
            ])
            ->with('show_set_password_link', true);
    }

    $credentials = $request->only('email', 'password');
    $remember = $request->boolean('remember');

    if (Auth::guard('customer')->attempt($credentials, $remember)) {
        RateLimiter::clear($request->throttleKey());
        $request->session()->regenerate();

        return redirect()->intended(route('home'))
            ->with('success', 'Đăng nhập thành công');
    }

    RateLimiter::hit($request->throttleKey());

    // Log failed attempt
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
```

### Update Login View (Enable Google Button)

```blade
{{-- resources/views/auth/login.blade.php --}}
{{-- REPLACE the disabled Google button with this --}}

{{-- Google OAuth Button --}}
<a href="{{ route('auth.google') }}" class="btn btn-outline w-full gap-2">
    <svg class="w-5 h-5" viewBox="0 0 24 24">
        <path fill="#4285F4" d="M22.56 12.25c0-.78-.07-1.53-.2-2.25H12v4.26h5.92c-.26 1.37-1.04 2.53-2.21 3.31v2.77h3.57c2.08-1.92 3.28-4.74 3.28-8.09z"/>
        <path fill="#34A853" d="M12 23c2.97 0 5.46-.98 7.28-2.66l-3.57-2.77c-.98.66-2.23 1.06-3.71 1.06-2.86 0-5.29-1.93-6.16-4.53H2.18v2.84C3.99 20.53 7.7 23 12 23z"/>
        <path fill="#FBBC05" d="M5.84 14.09c-.22-.66-.35-1.36-.35-2.09s.13-1.43.35-2.09V7.07H2.18C1.43 8.55 1 10.22 1 12s.43 3.45 1.18 4.93l2.85-2.22.81-.62z"/>
        <path fill="#EA4335" d="M12 5.38c1.62 0 3.06.56 4.21 1.64l3.15-3.15C17.45 2.09 14.97 1 12 1 7.7 1 3.99 3.47 2.18 7.07l3.66 2.84c.87-2.6 3.3-4.53 6.16-4.53z"/>
    </svg>
    Đăng nhập với Google
</a>

{{-- Show set password link if Google-only account --}}
@if (session('show_set_password_link'))
    <p class="text-center text-sm mt-2">
        <a href="{{ route('password.set') }}" class="link link-primary">Đặt mật khẩu ngay</a>
    </p>
@endif
```

### Set Password View

```blade
{{-- resources/views/auth/set-password.blade.php --}}
@extends('layouts.guest')

@section('title', 'Đặt mật khẩu - Tact')

@section('content')
<div class="card bg-base-100 shadow-xl">
    <div class="card-body">
        <h2 class="card-title text-2xl font-bold text-center mb-2">Đặt mật khẩu</h2>
        <p class="text-center text-gray-600 mb-6">
            Vui lòng đặt mật khẩu để bảo mật tài khoản của bạn
        </p>

        {{-- Session Status --}}
        @if (session('success'))
            <div class="alert alert-success mb-4">
                {{ session('success') }}
            </div>
        @endif

        <form method="POST" action="{{ route('password.set') }}">
            @csrf

            {{-- Password --}}
            <div class="form-control mb-4">
                <label class="label">
                    <span class="label-text">Mật khẩu mới</span>
                </label>
                <input type="password" name="password"
                    class="input input-bordered @error('password') input-error @enderror"
                    placeholder="Tối thiểu 8 ký tự" required autofocus>
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
                    Đặt mật khẩu
                </button>
            </div>
        </form>

        <div class="divider"></div>

        <p class="text-center text-sm text-gray-500">
            Bạn đã đăng ký bằng Google. Đặt mật khẩu để có thể đăng nhập bằng email/mật khẩu.
        </p>
    </div>
</div>
@endsection
```

### Project Structure (Story 1.4 Files)

```
app/
├── Http/
│   ├── Controllers/
│   │   └── Auth/
│   │       ├── RegisterController.php      # EXISTS (Story 1.2)
│   │       ├── LoginController.php         # MODIFY (check null password)
│   │       ├── GoogleAuthController.php    # NEW
│   │       └── SetPasswordController.php   # NEW
│   └── Requests/
│       ├── StoreCustomerRequest.php        # EXISTS (Story 1.2)
│       ├── LoginRequest.php                # EXISTS (Story 1.3)
│       └── SetPasswordRequest.php          # NEW

config/
└── services.php                            # MODIFY (add Google provider)

resources/views/
├── layouts/
│   ├── guest.blade.php                     # EXISTS (Story 1.2)
│   └── customer.blade.php                  # EXISTS (Story 1.2)
├── auth/
│   ├── register.blade.php                  # MODIFY (add Google button)
│   ├── login.blade.php                     # MODIFY (enable Google button)
│   └── set-password.blade.php              # NEW
└── customer/
    └── home.blade.php                      # EXISTS (Story 1.2)

routes/
└── web.php                                 # MODIFY (add OAuth routes)

tests/Feature/
└── Auth/
    ├── RegistrationTest.php                # EXISTS (Story 1.2)
    ├── LoginTest.php                       # EXISTS (Story 1.3)
    └── GoogleAuthTest.php                  # NEW
```

### Previous Story Intelligence (Story 1.2 & 1.3)

**Learnings from Story 1.2:**

-   ✅ Customer guard already configured in config/auth.php
-   ✅ Customer model extends Authenticatable
-   ✅ Customer model has google_id field (nullable)
-   ✅ Guest layout (guest.blade.php) already created
-   ✅ Vietnamese validation messages pattern established

**Learnings from Story 1.3:**

-   ✅ LoginController already exists with rate limiting
-   ✅ Login view has disabled Google button (needs enabling)
-   ✅ Session security configured (HTTP-only, secure cookies)
-   ✅ RateLimiter pattern established

**Files already created that this story uses:**

-   `config/auth.php` - Customer guard already configured
-   `app/Models/Customer.php` - Has google_id field
-   `resources/views/layouts/guest.blade.php` - Reuse for set-password page
-   `resources/views/auth/login.blade.php` - Enable Google button
-   `app/Http/Controllers/Auth/LoginController.php` - Modify for null password check

### Testing Requirements

```php
// tests/Feature/Auth/GoogleAuthTest.php
namespace Tests\Feature\Auth;

use App\Models\Customer;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Laravel\Socialite\Facades\Socialite;
use Laravel\Socialite\Two\User as SocialiteUser;
use Mockery;
use Tests\TestCase;

class GoogleAuthTest extends TestCase
{
    use RefreshDatabase;

    protected function mockSocialiteUser(array $data = []): void
    {
        $socialiteUser = Mockery::mock(SocialiteUser::class);
        $socialiteUser->shouldReceive('getId')->andReturn($data['id'] ?? '123456789');
        $socialiteUser->shouldReceive('getEmail')->andReturn($data['email'] ?? 'test@gmail.com');
        $socialiteUser->shouldReceive('getName')->andReturn($data['name'] ?? 'Test User');

        Socialite::shouldReceive('driver')
            ->with('google')
            ->andReturn(Mockery::mock([
                'user' => $socialiteUser,
            ]));
    }

    public function test_google_redirect_works(): void
    {
        $response = $this->get('/auth/google');
        $response->assertRedirect();
        $this->assertStringContainsString('accounts.google.com', $response->headers->get('Location'));
    }

    public function test_first_time_google_registration_creates_customer(): void
    {
        $this->mockSocialiteUser([
            'id' => '123456789',
            'email' => 'newuser@gmail.com',
            'name' => 'New User',
        ]);

        $response = $this->get('/auth/google/callback');

        $this->assertDatabaseHas('customers', [
            'email' => 'newuser@gmail.com',
            'google_id' => '123456789',
            'full_name' => 'New User',
        ]);

        $customer = Customer::where('email', 'newuser@gmail.com')->first();
        $this->assertNull($customer->password);
        $this->assertAuthenticated('customer');
        $response->assertRedirect(route('password.set'));
    }

    public function test_returning_google_user_is_logged_in(): void
    {
        $customer = Customer::factory()->create([
            'google_id' => '123456789',
            'email' => 'existing@gmail.com',
        ]);

        $this->mockSocialiteUser([
            'id' => '123456789',
            'email' => 'existing@gmail.com',
            'name' => 'Existing User',
        ]);

        $response = $this->get('/auth/google/callback');

        $this->assertAuthenticated('customer');
        $response->assertRedirect(route('home'));
    }

    public function test_email_conflict_links_google_account(): void
    {
        $customer = Customer::factory()->create([
            'email' => 'existing@gmail.com',
            'google_id' => null,
            'password' => 'password123',
        ]);

        $this->mockSocialiteUser([
            'id' => '123456789',
            'email' => 'existing@gmail.com',
            'name' => 'Existing User',
        ]);

        $response = $this->get('/auth/google/callback');

        $customer->refresh();
        $this->assertEquals('123456789', $customer->google_id);
        $this->assertAuthenticated('customer');
        $response->assertRedirect(route('home'));
    }

    public function test_google_user_without_password_cannot_email_login(): void
    {
        $customer = Customer::factory()->create([
            'email' => 'googleuser@gmail.com',
            'google_id' => '123456789',
            'password' => null,
        ]);

        $response = $this->post('/login', [
            'email' => 'googleuser@gmail.com',
            'password' => 'anypassword',
        ]);

        $this->assertGuest('customer');
        $response->assertSessionHasErrors(['email']);
        $this->assertStringContainsString(
            'đăng ký qua Google',
            session('errors')->get('email')[0]
        );
    }

    public function test_set_password_works_for_google_user(): void
    {
        $customer = Customer::factory()->create([
            'google_id' => '123456789',
            'password' => null,
        ]);

        $this->actingAs($customer, 'customer');

        $response = $this->post('/password/set', [
            'password' => 'newpassword123',
            'password_confirmation' => 'newpassword123',
        ]);

        $customer->refresh();
        $this->assertNotNull($customer->password);
        $response->assertRedirect(route('home'));
    }

    public function test_set_password_page_redirects_if_password_exists(): void
    {
        $customer = Customer::factory()->create([
            'password' => 'existingpassword',
        ]);

        $this->actingAs($customer, 'customer');

        $response = $this->get('/password/set');

        $response->assertRedirect(route('home'));
    }
}
```

### Anti-Patterns to Avoid

| ❌ BAD                                      | ✅ GOOD                                     | WHY                                                       |
| ------------------------------------------- | ------------------------------------------- | --------------------------------------------------------- |
| `'password' => Hash::make(Str::random(32))` | `'password' => null`                        | Google users should have NULL password until they set one |
| `Auth::login($customer)`                    | `Auth::guard('customer')->login($customer)` | Must use customer guard                                   |
| Create new account for existing email       | Link Google to existing account             | Prevent duplicate accounts                                |
| Allow email login for null password         | Block with specific error message           | Security: force password set first                        |
| English error messages                      | Vietnamese messages                         | User-facing must be Vietnamese                            |

### Security Checklist

-   [ ] CSRF token in set-password form (`@csrf`)
-   [ ] Google OAuth state parameter (Socialite handles this)
-   [ ] Validate Google callback response
-   [ ] Handle OAuth errors gracefully
-   [ ] Password hashed with bcrypt (via model cast)
-   [ ] Session regenerated after login
-   [ ] Rate limiting on OAuth callback (optional)
-   [ ] Log OAuth errors for debugging

### Google OAuth Setup Instructions

**1. Create Google Cloud Project:**

-   Go to https://console.cloud.google.com/
-   Create new project or select existing
-   Enable Google+ API

**2. Create OAuth Credentials:**

-   Go to APIs & Services > Credentials
-   Create OAuth 2.0 Client ID
-   Application type: Web application
-   Authorized redirect URIs: `http://localhost:8000/auth/google/callback`

**3. Add to .env:**

```env
GOOGLE_CLIENT_ID=your-client-id.apps.googleusercontent.com
GOOGLE_CLIENT_SECRET=your-client-secret
GOOGLE_REDIRECT_URI="${APP_URL}/auth/google/callback"
```

### UX Design Requirements

**From ux-design-specification.md:**

1. **Google Button Design**

    - Use official Google colors in SVG icon
    - Full-width button with `btn btn-outline`
    - Icon + text layout with gap-2

2. **Set Password Page**

    - Clear explanation why password is needed
    - Same form styling as registration
    - Success feedback after setting password

3. **Error Messages**
    - Specific message for Google-only accounts
    - Link to set password page
    - Vietnamese language

### References

**Source Documents:**

-   [Architecture: docs/2-solutioning/architecture.md#authentication-security] - OAuth strategy
-   [Epics: docs/2-solutioning/epics.md#story-1.4] - Story requirements
-   [UX Design: docs/1-planning/ux-design-specification.md#design-system-foundation] - Form design
-   [Project Context: project-context.md] - Naming conventions, response format
-   [Previous Story 1.2: docs/3-implementation/1-2-customer-registration-email-password.md] - Auth patterns
-   [Previous Story 1.3: docs/3-implementation/1-3-customer-login-email-password.md] - Login patterns

## Dev Agent Record

### Context Reference

-   project-context.md
-   project_context2.md
-   docs/3-implementation/sprint-status.yaml
-   docs/3-implementation/1-2-customer-registration-email-password.md
-   docs/3-implementation/1-3-customer-login-email-password.md

### Agent Model Used

Claude (Kiro)

### Debug Log References

-   All tests verified working with MySQL database

### Completion Notes List

-   ✅ Installed Laravel Socialite v5.24
-   ✅ Configured Google OAuth in services.php and .env/.env.example
-   ✅ Created GoogleAuthController with redirect and callback handling
-   ✅ Implemented 3 OAuth flows: first-time registration, returning user, email conflict linking
-   ✅ Created SetPasswordController and SetPasswordRequest for Google users to set password
-   ✅ Created set-password.blade.php view with DaisyUI styling
-   ✅ Updated LoginController to block Google-only accounts from email login
-   ✅ Enabled Google OAuth buttons in login.blade.php and register.blade.php with official Google branding
-   ✅ All 11 GoogleAuthTest tests passing
-   ✅ Full test suite: 55 passed, 4 skipped (trigger tests - MySQL only)
-   ✅ All Vietnamese messages implemented

### File List

**New Files:**

-   app/Http/Controllers/Auth/GoogleAuthController.php
-   app/Http/Controllers/Auth/SetPasswordController.php
-   app/Http/Requests/SetPasswordRequest.php
-   resources/views/auth/set-password.blade.php
-   tests/Feature/Auth/GoogleAuthTest.php

**Modified Files:**

-   config/services.php (added Google OAuth config)
-   routes/web.php (added OAuth and set-password routes)
-   app/Http/Controllers/Auth/LoginController.php (check null password for Google users)
-   resources/views/auth/login.blade.php (enabled Google button, added set-password link)
-   resources/views/auth/register.blade.php (added Google button)
-   .env (added Google OAuth credentials)
-   .env.example (added Google OAuth placeholders)
