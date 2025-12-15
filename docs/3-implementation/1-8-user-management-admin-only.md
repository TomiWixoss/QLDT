# Story 1.8: User Management (Admin Only)

Status: Done

---

## 🚨 CRITICAL DECISIONS - READ FIRST!

```
╔══════════════════════════════════════════════════════════════════════════════╗
║  🎯 QUICK START - CRITICAL CONTEXT FOR DEV AGENT                             ║
╠══════════════════════════════════════════════════════════════════════════════╣
║                                                                              ║
║  DATABASE SCHEMA (from migration):                                           ║
║  • username - VARCHAR(50) UNIQUE NOT NULL ← REQUIRED FIELD!                  ║
║  • email    - VARCHAR(100) UNIQUE NULLABLE                                   ║
║                                                                              ║
║  AUTHENTICATION (from Story 1.6):                                            ║
║  • AdminLoginController uses EMAIL for login (not username)                  ║
║  • User model has both 'username' and 'email' in $fillable                   ║
║                                                                              ║
║  ⚠️ DECISION: User Management MUST include BOTH fields:                      ║
║  • username - Required for database constraint                               ║
║  • email    - Required for login (consistent with Story 1.6)                 ║
║                                                                              ║
║  ✅ ALREADY DONE (Story 1.7 - DO NOT RECREATE):                              ║
║  • CheckRole middleware registered in bootstrap/app.php                      ║
║  • Gate 'manage-users' defined in AppServiceProvider                         ║
║  • UserController placeholder exists                                         ║
║  • Route /admin/users exists with role:Admin middleware                      ║
║                                                                              ║
╚══════════════════════════════════════════════════════════════════════════════╝
```

---

## Story

As an **Admin**,
I want to create, update, and deactivate staff accounts,
So that I can manage who has access to the system and their roles.

## Acceptance Criteria

**AC1: View User List**
**Given** I am logged in as an Admin
**When** I navigate to /admin/users
**Then** I see a list of all staff users with their username, name, email, role, and status
**And** I see a "Tạo người dùng mới" button

**AC2: Create New User**
**Given** I click "Tạo người dùng mới"
**When** I fill in username, email, full name, password, password confirmation, and select a role
**Then** a new user account is created in the users table
**And** the password is hashed with bcrypt
**And** I see a success message "Tạo người dùng thành công"

**AC3: Update User Information**
**Given** I want to update a user's information
**When** I click "Sửa" on a user row and update their name, email, or role
**Then** the user's information is updated
**And** I see a success message "Cập nhật người dùng thành công"

**AC4: Deactivate User**
**Given** I want to deactivate a user
**When** I click "Vô hiệu hóa" on a user row
**Then** the user's status is set to 'inactive'
**And** the user can no longer login
**And** I see a success message "Đã vô hiệu hóa người dùng"

**AC5: Reactivate User**
**Given** I want to reactivate an inactive user
**When** I click "Kích hoạt" on an inactive user row
**Then** the user's status is set to 'active'
**And** I see a success message "Đã kích hoạt người dùng"

**AC6: Cannot Deactivate Self**
**Given** I try to deactivate my own account
**When** I click "Vô hiệu hóa" on my own user row
**Then** I see an error message "Không thể vô hiệu hóa tài khoản của chính mình"
**And** my account remains active

**AC7: Non-Admin Access Denied**
**Given** I am logged in as a Manager, Sales, or Warehouse staff
**When** I try to access /admin/users
**Then** I see a 403 Forbidden error
**And** I cannot manage users

---

## Tasks / Subtasks

-   [x] Task 1: Update UserController with Full CRUD (AC: 1-6)

    -   [x] 1.1: REPLACE placeholder `index()` with full implementation (paginated list with role)
    -   [x] 1.2: Add `create()` method to show create form with roles dropdown
    -   [x] 1.3: Add `store()` method with StoreUserRequest validation
    -   [x] 1.4: Add `edit()` method to show edit form
    -   [x] 1.5: Add `update()` method with UpdateUserRequest validation
    -   [x] 1.6: Add `destroy()` method to toggle status (NOT hard delete)
    -   [x] 1.7: Add self-deactivation prevention check in destroy()

-   [x] Task 2: Create Form Request Validation (AC: 2, 3)

    -   [x] 2.1: Create `StoreUserRequest` with username unique, email unique, password min 8 confirmed
    -   [x] 2.2: Create `UpdateUserRequest` with username/email unique (except current)
    -   [x] 2.3: Add Vietnamese validation messages

-   [x] Task 3: Update Routes (AC: All)

    -   [x] 3.1: REPLACE single /users route with resource routes
    -   [x] 3.2: Keep role:Admin middleware (already exists from Story 1.7)

-   [x] Task 4: Update User List View (AC: 1)

    -   [x] 4.1: REPLACE placeholder view with DaisyUI data table
    -   [x] 4.2: Display columns: Username, Name, Email, Role, Status, Actions
    -   [x] 4.3: Add "Tạo người dùng mới" button
    -   [x] 4.4: Add conditional action buttons: Sửa, Vô hiệu hóa/Kích hoạt
    -   [x] 4.5: Add pagination links
    -   [x] 4.6: Disable deactivate button for current user

-   [x] Task 5: Create User Form Views (AC: 2, 3)

    -   [x] 5.1: Create `create.blade.php` with all fields including password_confirmation
    -   [x] 5.2: Create `edit.blade.php` with optional password change
    -   [x] 5.3: Add form fields: username, email, full_name, password, password_confirmation, role_id, phone
    -   [x] 5.4: Add role dropdown with 4 options from database

-   [x] Task 6: Update AdminLoginController for Status Check (AC: 4)

    -   [x] 6.1: Add status check BEFORE Auth::attempt()
    -   [x] 6.2: Return Vietnamese error message for inactive users

-   [x] Task 7: Write Tests (AC: All)
    -   [x] 7.1: Test Admin can view user list with pagination
    -   [x] 7.2: Test Admin can create new user with all fields
    -   [x] 7.3: Test Admin can update user
    -   [x] 7.4: Test Admin can deactivate user
    -   [x] 7.5: Test Admin can reactivate user
    -   [x] 7.6: Test Admin cannot deactivate self
    -   [x] 7.7: Test deactivated user cannot login
    -   [x] 7.8: Test non-Admin cannot access user management
    -   [x] 7.9: Test validation errors (username/email unique, password confirmation)

---

## Dev Notes

### 📋 Quick Reference Card

```
┌─────────────────────────────────────────────────────────────┐
│ STORY 1.8 QUICK REFERENCE CARD                              │
├─────────────────────────────────────────────────────────────┤
│ MUST DO:                                                     │
│ ✓ Include BOTH username AND email fields (database requires)│
│ ✓ REPLACE placeholder controller/view (not extend)          │
│ ✓ Use Form Request validation with password_confirmation    │
│ ✓ Vietnamese messages for all responses                     │
│ ✓ Prevent self-deactivation (disable button + backend check)│
│ ✓ Toggle status (not hard delete)                           │
│ ✓ Check user status on login (update AdminLoginController)  │
│ ✓ Add pagination links to list view                         │
│                                                              │
│ ALREADY DONE (Story 1.7 - DO NOT RECREATE):                 │
│ ✓ CheckRole middleware in bootstrap/app.php                 │
│ ✓ Gate 'manage-users' in AppServiceProvider                 │
│ ✓ Route /admin/users with role:Admin middleware             │
│ ✓ UserController placeholder (REPLACE content)              │
│ ✓ users/index.blade.php placeholder (REPLACE content)       │
│                                                              │
│ FILES TO CREATE:                                             │
│ • app/Http/Requests/Admin/StoreUserRequest.php              │
│ • app/Http/Requests/Admin/UpdateUserRequest.php             │
│ • resources/views/admin/users/create.blade.php              │
│ • resources/views/admin/users/edit.blade.php                │
│ • tests/Feature/Admin/UserManagementTest.php                │
│                                                              │
│ FILES TO UPDATE (REPLACE CONTENT):                           │
│ • app/Http/Controllers/Admin/UserController.php             │
│ • resources/views/admin/users/index.blade.php               │
│ • routes/web.php (replace single route with resource)       │
│ • app/Http/Controllers/Auth/AdminLoginController.php        │
└─────────────────────────────────────────────────────────────┘
```

### 🔴 DATABASE SCHEMA - CRITICAL!

```sql
-- ACTUAL users table (from database/migrations/2024_01_01_000002_create_users_table.php)
Schema::create('users', function (Blueprint $table) {
    $table->id();
    $table->foreignId('role_id')->constrained('roles');
    $table->string('username', 50)->unique();        -- ⚠️ REQUIRED! NOT NULL
    $table->string('password', 255);
    $table->string('full_name', 100);
    $table->string('email', 100)->unique()->nullable(); -- Optional but used for login
    $table->string('phone', 15)->nullable();
    $table->string('avatar', 255)->nullable();
    $table->enum('status', ['active', 'inactive'])->default('active');
    $table->rememberToken();
    $table->timestamps();
});

-- roles table (4 roles seeded)
-- id: 1=Admin, 2=Manager, 3=Sales, 4=Warehouse
```

### 🔵 Previous Story Context (Story 1.6)

**AdminLoginController uses EMAIL for authentication:**

```php
// app/Http/Controllers/Auth/AdminLoginController.php (line 37)
if (Auth::attempt($request->only('email', 'password'), $request->boolean('remember'))) {
```

**User model $fillable includes BOTH fields:**

```php
// app/Models/User.php
protected $fillable = [
    'role_id',
    'username',  // ← Required by database
    'password',
    'full_name',
    'email',     // ← Used for login
    'phone',
    'avatar',
    'status',
];
```

---

### UserController Implementation (REPLACE PLACEHOLDER)

```php
// app/Http/Controllers/Admin/UserController.php
// ⚠️ REPLACE entire file content - don't extend placeholder!

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\StoreUserRequest;
use App\Http\Requests\Admin\UpdateUserRequest;
use App\Models\Role;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Hash;
use Illuminate\View\View;

class UserController extends Controller
{
    public function index(): View
    {
        $users = User::with('role')
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        return view('admin.users.index', compact('users'));
    }

    public function create(): View
    {
        $roles = Role::all();
        return view('admin.users.create', compact('roles'));
    }

    public function store(StoreUserRequest $request): RedirectResponse
    {
        User::create([
            'username' => $request->username,  // ← REQUIRED!
            'email' => $request->email,
            'full_name' => $request->full_name,
            'password' => Hash::make($request->password),
            'role_id' => $request->role_id,
            'phone' => $request->phone,
            'status' => 'active',
        ]);

        return redirect()
            ->route('admin.users.index')
            ->with('success', 'Tạo người dùng thành công');
    }

    public function edit(User $user): View
    {
        $roles = Role::all();
        return view('admin.users.edit', compact('user', 'roles'));
    }

    public function update(UpdateUserRequest $request, User $user): RedirectResponse
    {
        $data = [
            'username' => $request->username,
            'email' => $request->email,
            'full_name' => $request->full_name,
            'role_id' => $request->role_id,
            'phone' => $request->phone,
        ];

        if ($request->filled('password')) {
            $data['password'] = Hash::make($request->password);
        }

        $user->update($data);

        return redirect()
            ->route('admin.users.index')
            ->with('success', 'Cập nhật người dùng thành công');
    }

    public function destroy(User $user): RedirectResponse
    {
        // Prevent self-deactivation
        if ($user->id === auth()->id()) {
            return redirect()
                ->route('admin.users.index')
                ->with('error', 'Không thể vô hiệu hóa tài khoản của chính mình');
        }

        // Toggle status (not hard delete)
        $newStatus = $user->status === 'active' ? 'inactive' : 'active';
        $user->update(['status' => $newStatus]);

        $message = $newStatus === 'active'
            ? 'Đã kích hoạt người dùng'
            : 'Đã vô hiệu hóa người dùng';

        return redirect()
            ->route('admin.users.index')
            ->with('success', $message);
    }
}
```

### Form Request Validation (CREATE NEW FILES)

```php
// app/Http/Requests/Admin/StoreUserRequest.php - CREATE THIS FILE
namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;

class StoreUserRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true; // Authorization handled by middleware
    }

    public function rules(): array
    {
        return [
            'username' => ['required', 'string', 'max:50', 'unique:users,username'],
            'email' => ['required', 'email', 'max:100', 'unique:users,email'],
            'full_name' => ['required', 'string', 'max:100'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
            'role_id' => ['required', 'exists:roles,id'],
            'phone' => ['nullable', 'string', 'max:15'],
        ];
    }

    public function messages(): array
    {
        return [
            'username.required' => 'Vui lòng nhập tên đăng nhập',
            'username.unique' => 'Tên đăng nhập đã được sử dụng',
            'username.max' => 'Tên đăng nhập không được quá 50 ký tự',
            'email.required' => 'Vui lòng nhập email',
            'email.email' => 'Email không hợp lệ',
            'email.unique' => 'Email đã được sử dụng',
            'full_name.required' => 'Vui lòng nhập họ tên',
            'password.required' => 'Vui lòng nhập mật khẩu',
            'password.min' => 'Mật khẩu phải có ít nhất 8 ký tự',
            'password.confirmed' => 'Mật khẩu xác nhận không khớp',
            'role_id.required' => 'Vui lòng chọn vai trò',
            'role_id.exists' => 'Vai trò không hợp lệ',
        ];
    }
}
```

```php
// app/Http/Requests/Admin/UpdateUserRequest.php - CREATE THIS FILE
namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateUserRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'username' => [
                'required', 'string', 'max:50',
                Rule::unique('users', 'username')->ignore($this->user->id)
            ],
            'email' => [
                'required', 'email', 'max:100',
                Rule::unique('users', 'email')->ignore($this->user->id)
            ],
            'full_name' => ['required', 'string', 'max:100'],
            'password' => ['nullable', 'string', 'min:8', 'confirmed'],
            'role_id' => ['required', 'exists:roles,id'],
            'phone' => ['nullable', 'string', 'max:15'],
        ];
    }

    public function messages(): array
    {
        return [
            'username.required' => 'Vui lòng nhập tên đăng nhập',
            'username.unique' => 'Tên đăng nhập đã được sử dụng',
            'email.required' => 'Vui lòng nhập email',
            'email.email' => 'Email không hợp lệ',
            'email.unique' => 'Email đã được sử dụng',
            'full_name.required' => 'Vui lòng nhập họ tên',
            'password.min' => 'Mật khẩu phải có ít nhất 8 ký tự',
            'password.confirmed' => 'Mật khẩu xác nhận không khớp',
            'role_id.required' => 'Vui lòng chọn vai trò',
            'role_id.exists' => 'Vai trò không hợp lệ',
        ];
    }
}
```

### Routes Configuration (REPLACE SINGLE ROUTE)

```php
// routes/web.php - FIND AND REPLACE the users route section

// BEFORE (Story 1.7 - single route):
Route::middleware('role:Admin')->group(function () {
    Route::get('/users', [UserController::class, 'index'])->name('admin.users.index');
});

// AFTER (Story 1.8 - resource routes):
Route::middleware('role:Admin')->group(function () {
    Route::resource('users', UserController::class)->names([
        'index' => 'admin.users.index',
        'create' => 'admin.users.create',
        'store' => 'admin.users.store',
        'edit' => 'admin.users.edit',
        'update' => 'admin.users.update',
        'destroy' => 'admin.users.destroy',
    ]);
});
```

### Update AdminLoginController (ADD STATUS CHECK)

```php
// app/Http/Controllers/Auth/AdminLoginController.php
// ⚠️ UPDATE the login() method - add status check BEFORE Auth::attempt()

public function login(AdminLoginRequest $request): RedirectResponse
{
    $throttleKey = $this->throttleKey($request);

    if (RateLimiter::tooManyAttempts($throttleKey, 5)) {
        $seconds = RateLimiter::availableIn($throttleKey);
        return back()->withErrors([
            'email' => "Quá nhiều lần đăng nhập thất bại. Vui lòng thử lại sau {$seconds} giây.",
        ])->withInput($request->only('email'));
    }

    // ⚠️ ADD THIS: Check if user exists and is active BEFORE attempting login
    $user = \App\Models\User::where('email', $request->email)->first();

    if ($user && $user->status === 'inactive') {
        return back()
            ->withInput($request->only('email'))
            ->withErrors(['email' => 'Tài khoản đã bị vô hiệu hóa. Vui lòng liên hệ quản trị viên.']);
    }

    if (Auth::attempt($request->only('email', 'password'), $request->boolean('remember'))) {
        RateLimiter::clear($throttleKey);
        $request->session()->regenerate();
        return redirect()->intended('/admin/dashboard');
    }

    RateLimiter::hit($throttleKey, 60);

    return back()->withErrors([
        'email' => 'Email hoặc mật khẩu không đúng',
    ])->withInput($request->only('email'));
}
```

### User List View (REPLACE PLACEHOLDER)

```blade
{{-- resources/views/admin/users/index.blade.php --}}
{{-- ⚠️ REPLACE entire placeholder content with this --}}
@extends('layouts.admin')

@section('title', 'Quản lý người dùng - Tact Admin')

@section('content')
<div class="space-y-6">
    {{-- Page Header --}}
    <div class="flex flex-col md:flex-row md:items-center md:justify-between">
        <div>
            <h1 class="text-2xl font-bold">Quản lý người dùng</h1>
            <p class="text-gray-500 mt-1">Danh sách nhân viên trong hệ thống</p>
        </div>
        <div class="mt-4 md:mt-0">
            <a href="{{ route('admin.users.create') }}" class="btn btn-primary">
                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"/>
                </svg>
                Tạo người dùng mới
            </a>
        </div>
    </div>

    {{-- Flash Messages --}}
    @if(session('success'))
        <div class="alert alert-success">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
            </svg>
            <span>{{ session('success') }}</span>
        </div>
    @endif

    @if(session('error'))
        <div class="alert alert-error">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z"/>
            </svg>
            <span>{{ session('error') }}</span>
        </div>
    @endif

    {{-- Users Table --}}
    <div class="card bg-base-100 shadow">
        <div class="card-body p-0">
            <div class="overflow-x-auto">
                <table class="table">
                    <thead>
                        <tr>
                            <th>Username</th>
                            <th>Họ tên</th>
                            <th>Email</th>
                            <th>Vai trò</th>
                            <th>Trạng thái</th>
                            <th>Thao tác</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($users as $user)
                        <tr>
                            <td class="font-mono text-sm">{{ $user->username }}</td>
                            <td>{{ $user->full_name }}</td>
                            <td>{{ $user->email }}</td>
                            <td>
                                <span class="badge badge-outline">{{ $user->role->name ?? 'N/A' }}</span>
                            </td>
                            <td>
                                @if($user->status === 'active')
                                    <span class="badge badge-success">Hoạt động</span>
                                @else
                                    <span class="badge badge-error">Vô hiệu</span>
                                @endif
                            </td>
                            <td>
                                <div class="flex gap-2">
                                    <a href="{{ route('admin.users.edit', $user) }}" class="btn btn-sm btn-ghost">
                                        Sửa
                                    </a>

                                    @if($user->id !== auth()->id())
                                        <form method="POST" action="{{ route('admin.users.destroy', $user) }}"
                                              onsubmit="return confirm('{{ $user->status === 'active' ? 'Vô hiệu hóa' : 'Kích hoạt' }} người dùng này?')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-sm {{ $user->status === 'active' ? 'btn-error' : 'btn-success' }} btn-outline">
                                                {{ $user->status === 'active' ? 'Vô hiệu hóa' : 'Kích hoạt' }}
                                            </button>
                                        </form>
                                    @else
                                        <button class="btn btn-sm btn-ghost opacity-50" disabled title="Không thể vô hiệu hóa tài khoản của chính mình">
                                            Vô hiệu hóa
                                        </button>
                                    @endif
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="6" class="text-center py-8 text-gray-500">
                                Chưa có người dùng nào
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            {{-- Pagination --}}
            @if($users->hasPages())
            <div class="p-4 border-t">
                {{ $users->links() }}
            </div>
            @endif
        </div>
    </div>
</div>
@endsection
```

### Create User Form View (CREATE NEW FILE)

```blade
{{-- resources/views/admin/users/create.blade.php - CREATE THIS FILE --}}
@extends('layouts.admin')

@section('title', 'Tạo người dùng mới - Tact Admin')

@section('content')
<div class="space-y-6">
    {{-- Page Header --}}
    <div class="flex items-center gap-4">
        <a href="{{ route('admin.users.index') }}" class="btn btn-ghost btn-sm">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
            </svg>
        </a>
        <h1 class="text-2xl font-bold">Tạo người dùng mới</h1>
    </div>

    {{-- Form Card --}}
    <div class="card bg-base-100 shadow max-w-2xl">
        <div class="card-body">
            <form method="POST" action="{{ route('admin.users.store') }}">
                @csrf

                {{-- Username --}}
                <div class="form-control mb-4">
                    <label class="label"><span class="label-text font-medium">Tên đăng nhập *</span></label>
                    <input type="text" name="username" value="{{ old('username') }}"
                        class="input input-bordered @error('username') input-error @enderror"
                        placeholder="admin01" required>
                    @error('username')
                        <label class="label"><span class="label-text-alt text-error">{{ $message }}</span></label>
                    @enderror
                </div>

                {{-- Email --}}
                <div class="form-control mb-4">
                    <label class="label"><span class="label-text font-medium">Email *</span></label>
                    <input type="email" name="email" value="{{ old('email') }}"
                        class="input input-bordered @error('email') input-error @enderror"
                        placeholder="admin@tact.vn" required>
                    @error('email')
                        <label class="label"><span class="label-text-alt text-error">{{ $message }}</span></label>
                    @enderror
                </div>

                {{-- Full Name --}}
                <div class="form-control mb-4">
                    <label class="label"><span class="label-text font-medium">Họ tên *</span></label>
                    <input type="text" name="full_name" value="{{ old('full_name') }}"
                        class="input input-bordered @error('full_name') input-error @enderror"
                        placeholder="Nguyễn Văn A" required>
                    @error('full_name')
                        <label class="label"><span class="label-text-alt text-error">{{ $message }}</span></label>
                    @enderror
                </div>

                {{-- Phone --}}
                <div class="form-control mb-4">
                    <label class="label"><span class="label-text font-medium">Số điện thoại</span></label>
                    <input type="text" name="phone" value="{{ old('phone') }}"
                        class="input input-bordered @error('phone') input-error @enderror"
                        placeholder="0901234567">
                    @error('phone')
                        <label class="label"><span class="label-text-alt text-error">{{ $message }}</span></label>
                    @enderror
                </div>

                {{-- Role --}}
                <div class="form-control mb-4">
                    <label class="label"><span class="label-text font-medium">Vai trò *</span></label>
                    <select name="role_id" class="select select-bordered @error('role_id') select-error @enderror" required>
                        <option value="">-- Chọn vai trò --</option>
                        @foreach($roles as $role)
                            <option value="{{ $role->id }}" {{ old('role_id') == $role->id ? 'selected' : '' }}>
                                {{ $role->name }}
                            </option>
                        @endforeach
                    </select>
                    @error('role_id')
                        <label class="label"><span class="label-text-alt text-error">{{ $message }}</span></label>
                    @enderror
                </div>

                {{-- Password --}}
                <div class="form-control mb-4">
                    <label class="label"><span class="label-text font-medium">Mật khẩu *</span></label>
                    <input type="password" name="password"
                        class="input input-bordered @error('password') input-error @enderror"
                        placeholder="Tối thiểu 8 ký tự" required>
                    @error('password')
                        <label class="label"><span class="label-text-alt text-error">{{ $message }}</span></label>
                    @enderror
                </div>

                {{-- Password Confirmation --}}
                <div class="form-control mb-6">
                    <label class="label"><span class="label-text font-medium">Xác nhận mật khẩu *</span></label>
                    <input type="password" name="password_confirmation"
                        class="input input-bordered"
                        placeholder="Nhập lại mật khẩu" required>
                </div>

                {{-- Submit --}}
                <div class="form-control">
                    <button type="submit" class="btn btn-primary">Tạo người dùng</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
```

### Edit User Form View (CREATE NEW FILE)

```blade
{{-- resources/views/admin/users/edit.blade.php - CREATE THIS FILE --}}
@extends('layouts.admin')

@section('title', 'Sửa người dùng - Tact Admin')

@section('content')
<div class="space-y-6">
    {{-- Page Header --}}
    <div class="flex items-center gap-4">
        <a href="{{ route('admin.users.index') }}" class="btn btn-ghost btn-sm">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
            </svg>
        </a>
        <h1 class="text-2xl font-bold">Sửa người dùng: {{ $user->full_name }}</h1>
    </div>

    {{-- Form Card --}}
    <div class="card bg-base-100 shadow max-w-2xl">
        <div class="card-body">
            <form method="POST" action="{{ route('admin.users.update', $user) }}">
                @csrf
                @method('PUT')

                {{-- Username --}}
                <div class="form-control mb-4">
                    <label class="label"><span class="label-text font-medium">Tên đăng nhập *</span></label>
                    <input type="text" name="username" value="{{ old('username', $user->username) }}"
                        class="input input-bordered @error('username') input-error @enderror" required>
                    @error('username')
                        <label class="label"><span class="label-text-alt text-error">{{ $message }}</span></label>
                    @enderror
                </div>

                {{-- Email --}}
                <div class="form-control mb-4">
                    <label class="label"><span class="label-text font-medium">Email *</span></label>
                    <input type="email" name="email" value="{{ old('email', $user->email) }}"
                        class="input input-bordered @error('email') input-error @enderror" required>
                    @error('email')
                        <label class="label"><span class="label-text-alt text-error">{{ $message }}</span></label>
                    @enderror
                </div>

                {{-- Full Name --}}
                <div class="form-control mb-4">
                    <label class="label"><span class="label-text font-medium">Họ tên *</span></label>
                    <input type="text" name="full_name" value="{{ old('full_name', $user->full_name) }}"
                        class="input input-bordered @error('full_name') input-error @enderror" required>
                    @error('full_name')
                        <label class="label"><span class="label-text-alt text-error">{{ $message }}</span></label>
                    @enderror
                </div>

                {{-- Phone --}}
                <div class="form-control mb-4">
                    <label class="label"><span class="label-text font-medium">Số điện thoại</span></label>
                    <input type="text" name="phone" value="{{ old('phone', $user->phone) }}"
                        class="input input-bordered @error('phone') input-error @enderror">
                    @error('phone')
                        <label class="label"><span class="label-text-alt text-error">{{ $message }}</span></label>
                    @enderror
                </div>

                {{-- Role --}}
                <div class="form-control mb-4">
                    <label class="label"><span class="label-text font-medium">Vai trò *</span></label>
                    <select name="role_id" class="select select-bordered @error('role_id') select-error @enderror" required>
                        @foreach($roles as $role)
                            <option value="{{ $role->id }}" {{ old('role_id', $user->role_id) == $role->id ? 'selected' : '' }}>
                                {{ $role->name }}
                            </option>
                        @endforeach
                    </select>
                    @error('role_id')
                        <label class="label"><span class="label-text-alt text-error">{{ $message }}</span></label>
                    @enderror
                </div>

                {{-- Password (Optional) --}}
                <div class="divider">Đổi mật khẩu (tùy chọn)</div>

                <div class="form-control mb-4">
                    <label class="label"><span class="label-text font-medium">Mật khẩu mới</span></label>
                    <input type="password" name="password"
                        class="input input-bordered @error('password') input-error @enderror"
                        placeholder="Để trống nếu không đổi">
                    @error('password')
                        <label class="label"><span class="label-text-alt text-error">{{ $message }}</span></label>
                    @enderror
                </div>

                <div class="form-control mb-6">
                    <label class="label"><span class="label-text font-medium">Xác nhận mật khẩu mới</span></label>
                    <input type="password" name="password_confirmation"
                        class="input input-bordered"
                        placeholder="Nhập lại mật khẩu mới">
                </div>

                {{-- Submit --}}
                <div class="form-control">
                    <button type="submit" class="btn btn-primary">Cập nhật</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
```

### Test Implementation

```php
// tests/Feature/Admin/UserManagementTest.php - CREATE THIS FILE
namespace Tests\Feature\Admin;

use App\Models\Role;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class UserManagementTest extends TestCase
{
    use RefreshDatabase;

    protected User $admin;
    protected User $manager;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed(\Database\Seeders\RoleSeeder::class);

        $adminRole = Role::where('name', 'Admin')->first();
        $managerRole = Role::where('name', 'Manager')->first();

        $this->admin = User::factory()->create([
            'role_id' => $adminRole->id,
            'username' => 'admin_test',
            'email' => 'admin@test.com',
            'status' => 'active',
        ]);

        $this->manager = User::factory()->create([
            'role_id' => $managerRole->id,
            'username' => 'manager_test',
            'email' => 'manager@test.com',
            'status' => 'active',
        ]);
    }

    public function test_admin_can_view_user_list(): void
    {
        $response = $this->actingAs($this->admin)->get(route('admin.users.index'));
        $response->assertStatus(200);
        $response->assertSee('Quản lý người dùng');
    }

    public function test_admin_can_create_user(): void
    {
        $response = $this->actingAs($this->admin)->post(route('admin.users.store'), [
            'username' => 'newuser',
            'email' => 'newuser@test.com',
            'full_name' => 'New User',
            'password' => 'password123',
            'password_confirmation' => 'password123',
            'role_id' => Role::where('name', 'Sales')->first()->id,
        ]);

        $response->assertRedirect(route('admin.users.index'));
        $this->assertDatabaseHas('users', ['username' => 'newuser', 'email' => 'newuser@test.com']);
    }

    public function test_admin_can_update_user(): void
    {
        $user = User::factory()->create(['username' => 'olduser', 'role_id' => $this->admin->role_id]);

        $response = $this->actingAs($this->admin)->put(route('admin.users.update', $user), [
            'username' => 'updateduser',
            'email' => $user->email,
            'full_name' => 'Updated Name',
            'role_id' => $user->role_id,
        ]);

        $response->assertRedirect(route('admin.users.index'));
        $this->assertDatabaseHas('users', ['username' => 'updateduser', 'full_name' => 'Updated Name']);
    }

    public function test_admin_can_deactivate_user(): void
    {
        $user = User::factory()->create(['status' => 'active', 'role_id' => $this->admin->role_id]);

        $response = $this->actingAs($this->admin)->delete(route('admin.users.destroy', $user));

        $response->assertRedirect(route('admin.users.index'));
        $this->assertDatabaseHas('users', ['id' => $user->id, 'status' => 'inactive']);
    }

    public function test_admin_can_reactivate_user(): void
    {
        $user = User::factory()->create(['status' => 'inactive', 'role_id' => $this->admin->role_id]);

        $response = $this->actingAs($this->admin)->delete(route('admin.users.destroy', $user));

        $response->assertRedirect(route('admin.users.index'));
        $this->assertDatabaseHas('users', ['id' => $user->id, 'status' => 'active']);
    }

    public function test_admin_cannot_deactivate_self(): void
    {
        $response = $this->actingAs($this->admin)->delete(route('admin.users.destroy', $this->admin));

        $response->assertRedirect(route('admin.users.index'));
        $response->assertSessionHas('error');
        $this->assertDatabaseHas('users', ['id' => $this->admin->id, 'status' => 'active']);
    }

    public function test_deactivated_user_cannot_login(): void
    {
        $user = User::factory()->create([
            'status' => 'inactive',
            'email' => 'inactive@test.com',
            'password' => bcrypt('password'),
            'role_id' => $this->admin->role_id,
        ]);

        $response = $this->post(route('admin.login.submit'), [
            'email' => 'inactive@test.com',
            'password' => 'password',
        ]);

        $response->assertSessionHasErrors('email');
        $this->assertGuest();
    }

    public function test_non_admin_cannot_access_user_management(): void
    {
        $response = $this->actingAs($this->manager)->get(route('admin.users.index'));
        $response->assertStatus(403);
    }

    public function test_validation_errors_display_correctly(): void
    {
        $response = $this->actingAs($this->admin)->post(route('admin.users.store'), [
            'username' => '', // Required
            'email' => 'invalid-email',
            'password' => 'short',
            'password_confirmation' => 'different',
        ]);

        $response->assertSessionHasErrors(['username', 'email', 'password', 'full_name', 'role_id']);
    }
}
```

---

## References

-   [Source: database/migrations/2024_01_01_000002_create_users_table.php - Actual schema]
-   [Source: app/Http/Controllers/Auth/AdminLoginController.php - Email authentication]
-   [Source: app/Models/User.php - Model with username and email fields]
-   [Source: docs/3-implementation/1-6-staff-authentication-role-setup.md - Previous story context]
-   [Source: docs/3-implementation/1-7-role-based-access-control-rbac.md - RBAC implementation]
-   [Source: docs/2-solutioning/architecture.md#Authentication & Security - Lines 485-544]
-   [Source: docs/2-solutioning/epics.md#Story 1.8 - Lines 1132-1181]

---

## Dev Agent Record

### Context Reference

-   project-context.md ✅
-   project_context2.md ✅
-   docs/3-implementation/1-6-staff-authentication-role-setup.md ✅
-   docs/3-implementation/1-7-role-based-access-control-rbac.md ✅
-   database/migrations/2024_01_01_000002_create_users_table.php ✅
-   app/Http/Controllers/Auth/AdminLoginController.php ✅
-   app/Models/User.php ✅

### File List

**Files to CREATE:**

-   app/Http/Requests/Admin/StoreUserRequest.php
-   app/Http/Requests/Admin/UpdateUserRequest.php
-   resources/views/admin/users/create.blade.php
-   resources/views/admin/users/edit.blade.php
-   tests/Feature/Admin/UserManagementTest.php

**Files to UPDATE (REPLACE CONTENT):**

-   app/Http/Controllers/Admin/UserController.php
-   resources/views/admin/users/index.blade.php
-   routes/web.php (replace single route with resource)
-   app/Http/Controllers/Auth/AdminLoginController.php (add status check)

### Completion Notes List

-   ✅ Implemented full CRUD for User Management (Admin only)
-   ✅ UserController with index, create, store, edit, update, destroy methods
-   ✅ StoreUserRequest and UpdateUserRequest with Vietnamese validation messages
-   ✅ Resource routes replacing single route, maintaining role:Admin middleware
-   ✅ DaisyUI-styled views: index (data table with pagination), create, edit forms
-   ✅ Status toggle (active/inactive) instead of hard delete
-   ✅ Self-deactivation prevention (backend check + disabled button)
-   ✅ AdminLoginController updated to block inactive users before Auth::attempt()
-   ✅ 21 comprehensive tests covering all ACs (all passing)
-   ✅ Full test suite: 125 tests, 358 assertions, all passing

### Files Created

-   app/Http/Controllers/Admin/UserController.php (replaced)
-   app/Http/Requests/Admin/StoreUserRequest.php (new)
-   app/Http/Requests/Admin/UpdateUserRequest.php (new)
-   resources/views/admin/users/index.blade.php (replaced)
-   resources/views/admin/users/create.blade.php (new)
-   resources/views/admin/users/edit.blade.php (new)
-   tests/Feature/Admin/UserManagementTest.php (new)

### Files Modified

-   routes/web.php (resource routes for users)
-   app/Http/Controllers/Auth/AdminLoginController.php (status check)
-   docs/3-implementation/sprint-status.yaml (status update)

### Agent Model Used

Claude (Anthropic)
