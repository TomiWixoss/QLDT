# 🎯 ROADMAP FINAL - TACT O2O SYSTEM

**Dự án:** Website Quản lý Cửa hàng Điện thoại (O2O Model)  
**Timeline:** 8 tuần (7 ngày/tuần) + 2 tuần báo cáo  
**Strategy:** Follow workflow thầy + Thêm features đầy đủ

---

## 📊 OVERVIEW

**Yêu cầu thầy (Must Have):**

-   ✅ 12 CRUD modules
-   ✅ Auth (thủ công + Google OAuth)
-   ✅ Orders management
-   ✅ Reports & Analytics

**Features bổ sung (Should Have):**

-   ✅ Customer shopping flow (web orders)
-   ✅ POS interface (bán tại quầy)
-   ✅ Stock management với triggers
-   ✅ Promotions & Loyalty points

**Result:** O2O System hoàn chỉnh!

---

## 🗓️ TUẦN 1: FOUNDATION (Show thầy: Database + Templates)

### **Yêu cầu thầy:**

-   Chọn đề tài
-   Xây dựng CSDL
-   Thiết kế giao diện
-   Research Auth options

### **Implementation Plan:**

**Ngày 1-2: Project Setup**

```bash
# Install Laravel 12
composer create-project laravel/laravel tact
cd tact

# Install packages
composer require laravel/breeze laravel/socialite intervention/image
npm install -D tailwindcss daisyui alpinejs

# Setup .env
DB_DATABASE=db_quanlydienthoai
GOOGLE_CLIENT_ID=your-client-id
GOOGLE_CLIENT_SECRET=your-secret
```

**Ngày 3-4: Database (12 bảng)**

**Migrations order (dependency-based):**

1. `create_roles_table` (4 roles: Admin, Manager, Sales, Warehouse)
2. `create_users_table` (Nhân viên)
3. `create_customers_table` (Khách hàng - có google_id)
4. `create_categories_table`
5. `create_brands_table`
6. `create_suppliers_table`
7. `create_products_table`
8. `create_product_specs_table`
9. `create_stock_movements_table`
10. `create_promotions_table`
11. `create_orders_table`
12. `create_order_items_table`

**Models với Relationships:**

```php
// User.php
public function role() { return $this->belongsTo(Role::class); }

// Product.php
public function category() { return $this->belongsTo(Category::class); }
public function brand() { return $this->belongsTo(Brand::class); }
public function specs() { return $this->hasOne(ProductSpec::class); }

// Order.php
public function customer() { return $this->belongsTo(Customer::class); }
public function items() { return $this->hasMany(OrderItem::class); }
```

**Seeders:**

-   RoleSeeder (4 roles)
-   UserSeeder (1 admin)
-   CategorySeeder (5 categories)
-   BrandSeeder (10 brands)
-   ProductFactory (50 products với specs)
-   CustomerFactory (100 customers)

**Ngày 5-6: UI Templates**

**Admin Layout (DaisyUI):**

```blade
{{-- layouts/admin.blade.php --}}
<div class="drawer lg:drawer-open">
  <input id="drawer" type="checkbox" class="drawer-toggle" />

  <!-- Sidebar -->
  <div class="drawer-side">
    <ul class="menu p-4 w-80 bg-base-200">
      <li><a href="/admin/dashboard">📊 Dashboard</a></li>
      <li><a href="/admin/users">👥 Users</a></li>
      <li><a href="/admin/customers">🛍️ Customers</a></li>
      <li><a href="/admin/products">📦 Products</a></li>
      <li><a href="/admin/orders">🛒 Orders</a></li>
      <li><a href="/admin/stock-movements">🏪 Stock</a></li>
      <li><a href="/admin/promotions">💰 Promotions</a></li>
      <li><a href="/admin/reports">📊 Reports</a></li>
    </ul>
  </div>

  <!-- Content -->
  <div class="drawer-content">
    <!-- Header -->
    <div class="navbar bg-base-300">
      <div class="flex-1">
        <label for="drawer" class="btn btn-square btn-ghost lg:hidden">☰</label>
        <span class="text-xl font-bold">Tact Admin</span>
      </div>
      <div class="flex-none">
        <div class="dropdown dropdown-end">
          <label tabindex="0" class="btn btn-ghost btn-circle avatar">
            <div class="w-10 rounded-full">
              <img src="{{ auth()->user()->avatar ?? '/default-avatar.png' }}" />
            </div>
          </label>
          <ul class="menu dropdown-content mt-3 p-2 shadow bg-base-100 rounded-box w-52">
            <li><a>{{ auth()->user()->full_name }}</a></li>
            <li><a>{{ auth()->user()->role->name }}</a></li>
            <li>
              <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit">Đăng xuất</button>
              </form>
            </li>
          </ul>
        </div>
      </div>
    </div>

    <!-- Main Content -->
    <main class="p-6">
      @yield('content')
    </main>
  </div>
</div>
```

**Shop Layout (DaisyUI):**

```blade
{{-- layouts/shop.blade.php --}}
<div class="min-h-screen flex flex-col">
  <!-- Navbar -->
  <div class="navbar bg-primary text-primary-content">
    <div class="flex-1">
      <a href="/" class="btn btn-ghost text-xl">🏪 Tact Store</a>
    </div>
    <div class="flex-none gap-2">
      <div class="form-control">
        <input type="text" placeholder="Tìm sản phẩm..." class="input input-bordered w-64" />
      </div>
      <a href="/cart" class="btn btn-ghost btn-circle">
        🛒 <span class="badge badge-sm">3</span>
      </a>
      @auth('customer')
        <div class="dropdown dropdown-end">
          <label tabindex="0" class="btn btn-ghost btn-circle avatar">
            <div class="w-10 rounded-full">
              <img src="{{ auth('customer')->user()->avatar }}" />
            </div>
          </label>
          <ul class="menu dropdown-content mt-3 p-2 shadow bg-base-100 rounded-box w-52">
            <li><a href="/account">Tài khoản</a></li>
            <li><a href="/orders">Đơn hàng</a></li>
            <li>
              <form method="POST" action="{{ route('customer.logout') }}">
                @csrf
                <button type="submit">Đăng xuất</button>
              </form>
            </li>
          </ul>
        </div>
      @else
        <a href="/login" class="btn btn-ghost">Đăng nhập</a>
      @endauth
    </div>
  </div>

  <!-- Content -->
  <main class="flex-1 container mx-auto p-6">
    @yield('content')
  </main>

  <!-- Footer -->
  <footer class="footer footer-center p-10 bg-base-200 text-base-content">
    <div>
      <p>© 2024 Tact Store - Website Quản lý Cửa hàng Điện thoại</p>
    </div>
  </footer>
</div>
```

**Ngày 7: Test & Demo Prep**

-   Test migrations: `php artisan migrate:fresh --seed`
-   Test relationships: `php artisan tinker`
-   Chụp screenshots: ERD, Templates
-   Chuẩn bị giải thích cho thầy

### **Deliverables Tuần 1:**

✅ Database 12 bảng + ERD  
✅ Models với relationships  
✅ Seeders + 150+ records  
✅ Admin layout (DaisyUI)  
✅ Shop layout (DaisyUI)

---

## 🔐 TUẦN 2: AUTHENTICATION (Show thầy: Auth hoàn chỉnh)

### **Yêu cầu thầy:**

-   Liệt kê chức năng
-   Đăng ký (thủ công + Google)
-   Đăng nhập (thủ công + Google)

### **Implementation Plan:**

**Ngày 1-2: Laravel Breeze + Customization**

```bash
php artisan breeze:install blade
npm install && npm run build
```

**Customize Breeze views với DaisyUI:**

-   `resources/views/auth/register.blade.php`
-   `resources/views/auth/login.blade.php`

**Register Form (với upload avatar):**

```php
// RegisterController.php
public function store(Request $request) {
    $validated = $request->validate([
        'username' => 'required|unique:users',
        'password' => 'required|min:6|confirmed',
        'full_name' => 'required',
        'email' => 'required|email|unique:users',
        'phone' => 'nullable',
        'avatar' => 'nullable|image|max:2048',
        'role_id' => 'required|exists:roles,id',
    ]);

    if ($request->hasFile('avatar')) {
        $validated['avatar'] = $request->file('avatar')->store('avatars', 'public');
    }

    $validated['password'] = bcrypt($validated['password']);

    User::create($validated);

    return redirect()->route('login')->with('success', 'Đăng ký thành công!');
}
```

**Ngày 3-4: Google OAuth**

**Setup Google API:**

1. Google Cloud Console → Create Project
2. Enable Google+ API
3. Create OAuth 2.0 credentials
4. Add redirect URI: `http://localhost:8000/auth/google/callback`

**Config socialite:**

```php
// config/services.php
'google' => [
    'client_id' => env('GOOGLE_CLIENT_ID'),
    'client_secret' => env('GOOGLE_CLIENT_SECRET'),
    'redirect' => env('GOOGLE_REDIRECT_URI'),
],
```

**GoogleController:**

```php
public function redirect() {
    return Socialite::driver('google')->redirect();
}

public function callback() {
    $googleUser = Socialite::driver('google')->user();

    $customer = Customer::where('email', $googleUser->email)->first();

    if (!$customer) {
        // New Google user
        $customer = Customer::create([
            'email' => $googleUser->email,
            'full_name' => $googleUser->name,
            'avatar' => $googleUser->avatar,
            'google_id' => $googleUser->id,
            'password' => null, // Will set later
        ]);

        // Redirect to set password
        session(['new_google_user' => $customer->id]);
        return redirect()->route('set-password');
    }

    // Existing user - login
    auth()->guard('customer')->login($customer);
    return redirect()->route('home');
}
```

**Set Password Page:**

```blade
{{-- auth/set-password.blade.php --}}
<form method="POST" action="{{ route('set-password.store') }}">
    @csrf
    <h2>Thiết lập mật khẩu</h2>
    <p>Bạn đã đăng nhập bằng Google. Vui lòng thiết lập mật khẩu để có thể đăng nhập bằng email sau này.</p>

    <input type="password" name="password" placeholder="Mật khẩu mới" required />
    <input type="password" name="password_confirmation" placeholder="Nhập lại mật khẩu" required />

    <button type="submit" class="btn btn-primary">Xác nhận</button>
</form>
```

**Ngày 5-6: Role-Based Access Control**

**Middleware:**

```php
// app/Http/Middleware/CheckRole.php
public function handle($request, Closure $next, ...$roles) {
    if (!auth()->check()) {
        return redirect()->route('login');
    }

    $userRole = auth()->user()->role->name;

    if (!in_array($userRole, $roles)) {
        abort(403, 'Bạn không có quyền truy cập');
    }

    return $next($request);
}
```

**Routes:**

```php
// Admin routes
Route::middleware(['auth', 'role:admin,manager'])->prefix('admin')->group(function() {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('admin.dashboard');
    Route::resource('users', UserController::class);
    Route::resource('products', ProductController::class);
});

// Sales routes
Route::middleware(['auth', 'role:admin,manager,sales'])->group(function() {
    Route::get('/pos', [POSController::class, 'index'])->name('pos');
    Route::resource('orders', OrderController::class);
});

// Warehouse routes
Route::middleware(['auth', 'role:admin,manager,warehouse'])->group(function() {
    Route::resource('stock-movements', StockMovementController::class);
});
```

**Gates:**

```php
// AuthServiceProvider.php
Gate::define('manage-products', fn($user) =>
    in_array($user->role->name, ['admin', 'manager'])
);

Gate::define('view-reports', fn($user) =>
    in_array($user->role->name, ['admin', 'manager'])
);
```

**Ngày 7: Test & Polish**

-   Test đăng ký thủ công
-   Test Google OAuth
-   Test set password flow
-   Test role-based access
-   UI polish với DaisyUI
-   Toast notifications (SweetAlert2)

### **Deliverables Tuần 2:**

✅ Đăng ký hoạt động (có upload avatar)  
✅ Google OAuth hoạt động  
✅ Set password cho Google users  
✅ Đăng nhập hoạt động  
✅ Role-based access hoạt động  
✅ Middleware + Gates

---

## 📝 TUẦN 3: USERS CRUD COMPLETE (Show thầy: CRUD đầy đủ)

### **Yêu cầu thầy:**

-   Chức năng thoát
-   Template quản trị
-   Chức năng thêm dữ liệu
-   **Làm xong quản lý người dùng** (List, Create, Edit, Delete, Lock, Search)

### **Implementation Plan:**

**Ngày 1: Logout + Admin Template Polish**

**Logout:**

```php
public function logout(Request $request) {
    auth()->logout();
    $request->session()->invalidate();
    $request->session()->regenerateToken();
    return redirect()->route('login');
}
```

**Admin Template Enhancements:**

-   Active menu states
-   Breadcrumbs
-   Flash messages display
-   Loading states

**Ngày 2-3: Users CRUD - List & Search**

**Controller:**

```php
public function index(Request $request) {
    $query = User::with('role');

    // Search
    if ($search = $request->search) {
        $query->where(function($q) use ($search) {
            $q->where('username', 'like', "%{$search}%")
              ->orWhere('full_name', 'like', "%{$search}%")
              ->orWhere('email', 'like', "%{$search}%");
        });
    }

    // Filter by role
    if ($request->role_id) {
        $query->where('role_id', $request->role_id);
    }

    // Filter by status
    if ($request->status) {
        $query->where('status', $request->status);
    }

    $users = $query->paginate(10);
    $roles = Role::all();

    return view('admin.users.index', compact('users', 'roles'));
}
```

**View với DaisyUI:**

```blade
<div class="card bg-base-100 shadow-xl">
  <div class="card-body">
    <div class="flex justify-between items-center mb-4">
      <h2 class="card-title">Quản lý Users</h2>
      <a href="{{ route('admin.users.create') }}" class="btn btn-primary">
        ➕ Thêm User
      </a>
    </div>

    <!-- Search & Filter -->
    <form method="GET" class="flex gap-2 mb-4">
      <input type="text" name="search" placeholder="Tìm kiếm..."
             value="{{ request('search') }}" class="input input-bordered flex-1" />

      <select name="role_id" class="select select-bordered">
        <option value="">Tất cả quyền</option>
        @foreach($roles as $role)
          <option value="{{ $role->id }}" {{ request('role_id') == $role->id ? 'selected' : '' }}>
            {{ $role->name }}
          </option>
        @endforeach
      </select>

      <select name="status" class="select select-bordered">
        <option value="">Tất cả trạng thái</option>
        <option value="active" {{ request('status') == 'active' ? 'selected' : '' }}>Active</option>
        <option value="inactive" {{ request('status') == 'inactive' ? 'selected' : '' }}>Inactive</option>
      </select>

      <button type="submit" class="btn btn-primary">Lọc</button>
      <a href="{{ route('admin.users.index') }}" class="btn btn-ghost">Reset</a>
    </form>

    <!-- Table -->
    <div class="overflow-x-auto">
      <table class="table table-zebra">
        <thead>
          <tr>
            <th>ID</th>
            <th>Avatar</th>
            <th>Username</th>
            <th>Tên đầy đủ</th>
            <th>Email</th>
            <th>Quyền</th>
            <th>Trạng thái</th>
            <th>Hành động</th>
          </tr>
        </thead>
        <tbody>
          @foreach($users as $user)
          <tr>
            <td>{{ $user->id }}</td>
            <td>
              <div class="avatar">
                <div class="w-12 rounded-full">
                  <img src="{{ $user->avatar ? asset('storage/'.$user->avatar) : '/default-avatar.png' }}" />
                </div>
              </div>
            </td>
            <td>{{ $user->username }}</td>
            <td>{{ $user->full_name }}</td>
            <td>{{ $user->email }}</td>
            <td><span class="badge badge-primary">{{ $user->role->name }}</span></td>
            <td>
              <span class="badge {{ $user->status == 'active' ? 'badge-success' : 'badge-error' }}">
                {{ $user->status }}
              </span>
            </td>
            <td>
              <div class="flex gap-2">
                <a href="{{ route('admin.users.show', $user) }}" class="btn btn-sm btn-info">👁️</a>
                <a href="{{ route('admin.users.edit', $user) }}" class="btn btn-sm btn-warning">✏️</a>

                @if($user->status == 'active')
                  <form method="POST" action="{{ route('admin.users.lock', $user) }}">
                    @csrf @method('PATCH')
                    <button type="submit" class="btn btn-sm btn-error">🔒</button>
                  </form>
                @else
                  <form method="POST" action="{{ route('admin.users.unlock', $user) }}">
                    @csrf @method('PATCH')
                    <button type="submit" class="btn btn-sm btn-success">🔓</button>
                  </form>
                @endif

                <form method="POST" action="{{ route('admin.users.destroy', $user) }}"
                      onsubmit="return confirm('Xác nhận xóa?')">
                  @csrf @method('DELETE')
                  <button type="submit" class="btn btn-sm btn-error">🗑️</button>
                </form>
              </div>
            </td>
          </tr>
          @endforeach
        </tbody>
      </table>
    </div>

    <!-- Pagination -->
    <div class="mt-4">
      {{ $users->links() }}
    </div>
  </div>
</div>
```

**Ngày 4-5: Users CRUD - Create & Edit**

**Create Form:**

```blade
<form method="POST" action="{{ route('admin.users.store') }}" enctype="multipart/form-data">
  @csrf

  <div class="form-control">
    <label class="label"><span class="label-text">Username *</span></label>
    <input type="text" name="username" class="input input-bordered" required />
    @error('username')<span class="text-error">{{ $message }}</span>@enderror
  </div>

  <div class="form-control">
    <label class="label"><span class="label-text">Password *</span></label>
    <input type="password" name="password" class="input input-bordered" required />
  </div>

  <div class="form-control">
    <label class="label"><span class="label-text">Confirm Password *</span></label>
    <input type="password" name="password_confirmation" class="input input-bordered" required />
  </div>

  <div class="form-control">
    <label class="label"><span class="label-text">Tên đầy đủ *</span></label>
    <input type="text" name="full_name" class="input input-bordered" required />
  </div>

  <div class="form-control">
    <label class="label"><span class="label-text">Email *</span></label>
    <input type="email" name="email" class="input input-bordered" required />
  </div>

  <div class="form-control">
    <label class="label"><span class="label-text">Số điện thoại</span></label>
    <input type="text" name="phone" class="input input-bordered" />
  </div>

  <div class="form-control">
    <label class="label"><span class="label-text">Avatar</span></label>
    <input type="file" name="avatar" class="file-input file-input-bordered" accept="image/*" />
    <div id="preview" class="mt-2"></div>
  </div>

  <div class="form-control">
    <label class="label"><span class="label-text">Quyền *</span></label>
    <select name="role_id" class="select select-bordered" required>
      <option value="">Chọn quyền</option>
      @foreach($roles as $role)
        <option value="{{ $role->id }}">{{ $role->name }}</option>
      @endforeach
    </select>
  </div>

  <div class="form-control">
    <label class="label"><span class="label-text">Trạng thái *</span></label>
    <select name="status" class="select select-bordered" required>
      <option value="active">Active</option>
      <option value="inactive">Inactive</option>
    </select>
  </div>

  <div class="flex gap-2 mt-4">
    <button type="submit" class="btn btn-primary">Lưu</button>
    <a href="{{ route('admin.users.index') }}" class="btn btn-ghost">Hủy</a>
  </div>
</form>

<script>
// Image preview
document.querySelector('input[name="avatar"]').addEventListener('change', function(e) {
    const file = e.target.files[0];
    if (file) {
        const reader = new FileReader();
        reader.onload = function(e) {
            document.getElementById('preview').innerHTML =
                `<img src="${e.target.result}" class="w-32 h-32 rounded-full" />`;
        };
        reader.readAsDataURL(file);
    }
});
</script>
```

**Ngày 6: Users CRUD - Delete & Lock**

**Lock/Unlock:**

```php
public function lock(User $user) {
    $user->update(['status' => 'inactive']);
    return back()->with('success', 'Đã khóa tài khoản!');
}

public function unlock(User $user) {
    $user->update(['status' => 'active']);
    return back()->with('success', 'Đã mở khóa tài khoản!');
}
```

**Delete:**

```php
public function destroy(User $user) {
    // Delete avatar
    if ($user->avatar) {
        Storage::disk('public')->delete($user->avatar);
    }

    $user->delete();

    return redirect()->route('admin.users.index')->with('success', 'Xóa thành công!');
}
```

**Ngày 7: Polish & Test**

-   Toast notifications (SweetAlert2)
-   Confirm dialogs
-   Loading states
-   Validation messages tiếng Việt
-   Test all CRUD operations
-   Screenshots

### **Deliverables Tuần 3:**

✅ Logout hoạt động  
✅ Admin template hoàn chỉnh  
✅ Users List với search/filter/pagination  
✅ Users Create với upload avatar  
✅ Users Edit  
✅ Users Delete  
✅ Lock/Unlock accounts  
✅ Toast notifications

---

## ✏️ TUẦN 4: CRUD PATTERN & DETAIL PAGES (Show thầy: Detail + Pattern)

### **Yêu cầu thầy:**

-   Thiết kế và hiển thị kết quả (Detail pages)
-   Sửa và xóa dữ liệu

### **Implementation Plan:**

**Ngày 1-2: Detail Pages & Pattern Refinement**

**User Detail Page:**

```blade
<div class="card bg-base-100 shadow-xl">
  <div class="card-body">
    <h2 class="card-title">Chi tiết User #{{ $user->id }}</h2>

    <div class="grid grid-cols-2 gap-4">
      <div>
        <div class="avatar">
          <div class="w-32 rounded-full">
            <img src="{{ $user->avatar ? asset('storage/'.$user->avatar) : '/default-avatar.png' }}" />
          </div>
        </div>
      </div>

      <div>
        <p><strong>Username:</strong> {{ $user->username }}</p>
        <p><strong>Tên đầy đủ:</strong> {{ $user->full_name }}</p>
        <p><strong>Email:</strong> {{ $user->email }}</p>
        <p><strong>Số điện thoại:</strong> {{ $user->phone }}</p>
        <p><strong>Quyền:</strong> <span class="badge badge-primary">{{ $user->role->name }}</span></p>
        <p><strong>Trạng thái:</strong>
          <span class="badge {{ $user->status == 'active' ? 'badge-success' : 'badge-error' }}">
            {{ $user->status }}
          </span>
        </p>
        <p><strong>Ngày tạo:</strong> {{ $user->created_at->format('d/m/Y H:i') }}</p>
      </div>
    </div>

    <div class="card-actions justify-end mt-4">
      <a href="{{ route('admin.users.edit', $user) }}" class="btn btn-warning">Sửa</a>
      <a href="{{ route('admin.users.index') }}" class="btn btn-ghost">Quay lại</a>
    </div>
  </div>
</div>
```

**Ngày 3-4: CRUD Pattern Template**

**Tạo Base CRUD Components:**

```blade
{{-- components/crud-table.blade.php --}}
<div class="overflow-x-auto">
  <table class="table table-zebra">
    <thead>
      <tr>
        {{ $headers }}
      </tr>
    </thead>
    <tbody>
      {{ $rows }}
    </tbody>
  </table>
</div>

{{-- components/crud-actions.blade.php --}}
<div class="flex gap-2">
  @if($showView ?? true)
    <a href="{{ $viewUrl }}" class="btn btn-sm btn-info">👁️</a>
  @endif

  @if($showEdit ?? true)
    <a href="{{ $editUrl }}" class="btn btn-sm btn-warning">✏️</a>
  @endif

  @if($showDelete ?? true)
    <form method="POST" action="{{ $deleteUrl }}" onsubmit="return confirm('Xác nhận xóa?')">
      @csrf @method('DELETE')
      <button type="submit" class="btn btn-sm btn-error">🗑️</button>
    </form>
  @endif
</div>
```

**Ngày 5-6: Apply Pattern to Simple CRUDs**

**Categories CRUD (Simple):**

```php
// CategoryController.php - Copy từ UserController, adjust fields
public function index() {
    $categories = Category::paginate(10);
    return view('admin.categories.index', compact('categories'));
}

public function store(Request $request) {
    $validated = $request->validate([
        'name' => 'required|unique:categories',
        'description' => 'nullable',
    ]);

    Category::create($validated);
    return redirect()->route('admin.categories.index')->with('success', 'Thêm thành công!');
}
```

**Brands CRUD (với upload logo):**

```php
public function store(Request $request) {
    $validated = $request->validate([
        'name' => 'required|unique:brands',
        'origin' => 'nullable',
        'logo' => 'nullable|image|max:2048',
    ]);

    if ($request->hasFile('logo')) {
        $validated['logo'] = $request->file('logo')->store('brands', 'public');
    }

    Brand::create($validated);
    return redirect()->route('admin.brands.index')->with('success', 'Thêm thành công!');
}
```

**Suppliers CRUD:**

```php
public function store(Request $request) {
    $validated = $request->validate([
        'name' => 'required|unique:suppliers',
        'tax_code' => 'nullable',
        'phone' => 'nullable',
        'email' => 'nullable|email',
        'address' => 'nullable',
    ]);

    Supplier::create($validated);
    return redirect()->route('admin.suppliers.index')->with('success', 'Thêm thành công!');
}
```

**Ngày 7: Test & Polish**

-   Test all simple CRUDs
-   Consistent UI across modules
-   Screenshots

### **Deliverables Tuần 4:**

✅ User detail page  
✅ CRUD pattern template  
✅ Categories CRUD  
✅ Brands CRUD (với logo)  
✅ Suppliers CRUD  
✅ Consistent UI

---

## 📦 TUẦN 5: PRODUCTS & CUSTOMERS (Show thầy: Complex CRUD)

### **Yêu cầu thầy:**

-   Làm chức năng tương tự cho các trang khác

### **Implementation Plan:**

**Ngày 1-2: Customers CRUD**

**Copy pattern từ Users, adjust:**

-   Không có username (dùng email)
-   Có google_id, facebook_id
-   Có points, address, city
-   Password nullable (nếu đăng ký Google)

```php
public function store(Request $request) {
    $validated = $request->validate([
        'email' => 'required|email|unique:customers',
        'password' => 'nullable|min:6',
        'full_name' => 'required',
        'phone' => 'nullable',
        'avatar' => 'nullable|image',
        'address' => 'nullable',
        'city' => 'nullable',
    ]);

    if ($request->filled('password')) {
        $validated['password'] = bcrypt($validated['password']);
    }

    if ($request->hasFile('avatar')) {
        $validated['avatar'] = $request->file('avatar')->store('customers', 'public');
    }

    Customer::create($validated);
    return redirect()->route('admin.customers.index')->with('success', 'Thêm thành công!');
}
```

**Ngày 3-6: Products CRUD (Complex)**

**Install Image Intervention:**

```bash
composer require intervention/image
```

**Product Form (3 tabs):**

```blade
<div class="tabs tabs-boxed">
  <a class="tab tab-active" data-tab="info">Thông tin chung</a>
  <a class="tab" data-tab="image">Hình ảnh</a>
  <a class="tab" data-tab="specs">Thông số kỹ thuật</a>
</div>

<!-- Tab 1: Info -->
<div id="tab-info" class="tab-content">
  <div class="form-control">
    <label class="label"><span class="label-text">SKU *</span></label>
    <input type="text" name="sku" class="input input-bordered" required />
  </div>

  <div class="form-control">
    <label class="label"><span class="label-text">Tên sản phẩm *</span></label>
    <input type="text" name="name" class="input input-bordered" required />
  </div>

  <div class="grid grid-cols-2 gap-4">
    <div class="form-control">
      <label class="label"><span class="label-text">Danh mục *</span></label>
      <select name="category_id" class="select select-bordered" required>
        @foreach($categories as $category)
          <option value="{{ $category->id }}">{{ $category->name }}</option>
        @endforeach
      </select>
    </div>

    <div class="form-control">
      <label class="label"><span class="label-text">Thương hiệu *</span></label>
      <select name="brand_id" class="select select-bordered" required>
        @foreach($brands as $brand)
          <option value="{{ $brand->id }}">{{ $brand->name }}</option>
        @endforeach
      </select>
    </div>
  </div>

  <div class="grid grid-cols-3 gap-4">
    <div class="form-control">
      <label class="label"><span class="label-text">Giá bán *</span></label>
      <input type="number" name="price" class="input input-bordered" required />
    </div>

    <div class="form-control">
      <label class="label"><span class="label-text">Giá vốn</span></label>
      <input type="number" name="cost" class="input input-bordered" />
    </div>

    <div class="form-control">
      <label class="label"><span class="label-text">Bảo hành (tháng)</span></label>
      <input type="number" name="warranty_months" class="input input-bordered" value="12" />
    </div>
  </div>
</div>

<!-- Tab 2: Image -->
<div id="tab-image" class="tab-content hidden">
  <div class="form-control">
    <label class="label"><span class="label-text">Hình ảnh sản phẩm</span></label>
    <input type="file" name="image" class="file-input file-input-bordered" accept="image/*" />
    <div id="image-preview" class="mt-4"></div>
  </div>
</div>

<!-- Tab 3: Specs -->
<div id="tab-specs" class="tab-content hidden">
  <div class="grid grid-cols-2 gap-4">
    <div class="form-control">
      <label class="label"><span class="label-text">Màn hình</span></label>
      <input type="text" name="screen" class="input input-bordered" placeholder="6.7 inch OLED" />
    </div>

    <div class="form-control">
      <label class="label"><span class="label-text">Hệ điều hành</span></label>
      <input type="text" name="os" class="input input-bordered" placeholder="iOS 17" />
    </div>

    <div class="form-control">
      <label class="label"><span class="label-text">CPU</span></label>
      <input type="text" name="cpu" class="input input-bordered" placeholder="Apple A17 Pro" />
    </div>

    <div class="form-control">
      <label class="label"><span class="label-text">RAM</span></label>
      <input type="text" name="ram" class="input input-bordered" placeholder="8GB" />
    </div>

    <div class="form-control">
      <label class="label"><span class="label-text">ROM</span></label>
      <input type="text" name="rom" class="input input-bordered" placeholder="256GB" />
    </div>

    <div class="form-control">
      <label class="label"><span class="label-text">Camera</span></label>
      <input type="text" name="camera" class="input input-bordered" placeholder="48MP + 12MP + 12MP" />
    </div>

    <div class="form-control">
      <label class="label"><span class="label-text">Pin</span></label>
      <input type="text" name="battery" class="input input-bordered" placeholder="4422 mAh" />
    </div>

    <div class="form-control">
      <label class="label"><span class="label-text">SIM</span></label>
      <input type="text" name="sim" class="input input-bordered" placeholder="2 Nano SIM" />
    </div>
  </div>
</div>
```

**ProductController:**

```php
public function store(Request $request) {
    $validated = $request->validate([
        'category_id' => 'required|exists:categories,id',
        'brand_id' => 'required|exists:brands,id',
        'sku' => 'required|unique:products',
        'name' => 'required',
        'price' => 'required|numeric',
        'cost' => 'nullable|numeric',
        'warranty_months' => 'required|integer',
        'image' => 'nullable|image|max:2048',
        // Specs
        'screen' => 'nullable',
        'os' => 'nullable',
        'cpu' => 'nullable',
        'ram' => 'nullable',
        'rom' => 'nullable',
        'camera' => 'nullable',
        'battery' => 'nullable',
        'sim' => 'nullable',
    ]);

    // Upload & resize image
    if ($request->hasFile('image')) {
        $image = $request->file('image');
        $filename = time() . '_' . $image->getClientOriginalName();

        // Resize to 800x800
        $img = Image::make($image)->resize(800, 800, function($constraint) {
            $constraint->aspectRatio();
            $constraint->upsize();
        });

        // Save
        $path = storage_path('app/public/products/' . $filename);
        $img->save($path);

        // Thumbnail 200x200
        $thumb = Image::make($image)->fit(200, 200);
        $thumbPath = storage_path('app/public/products/thumbs/' . $filename);
        $thumb->save($thumbPath);

        $validated['image'] = 'products/' . $filename;
    }

    // Create product
    $product = Product::create($validated);

    // Create specs
    $product->specs()->create([
        'screen' => $request->screen,
        'os' => $request->os,
        'cpu' => $request->cpu,
        'ram' => $request->ram,
        'rom' => $request->rom,
        'camera' => $request->camera,
        'battery' => $request->battery,
        'sim' => $request->sim,
    ]);

    return redirect()->route('admin.products.index')->with('success', 'Thêm sản phẩm thành công!');
}
```

**Ngày 7: Test & Polish**

-   Test Customers CRUD
-   Test Products CRUD (upload ảnh, specs)
-   Image preview
-   Tab switching
-   Screenshots

### **Deliverables Tuần 5:**

✅ Customers CRUD  
✅ Products CRUD với 3 tabs  
✅ Upload ảnh + resize (800x800 + thumbnail 200x200)  
✅ Product specs  
✅ SKU management  
✅ 9/12 modules hoàn thành

---

## 🛒 TUẦN 6: ORDERS & O2O (Show thầy: Orders + POS + Stock)

### **Yêu cầu thầy:**

-   Thực hiện trang quản trị

### **Implementation Plan:**

**Ngày 1-2: Stock Movements & Promotions**

**Stock Movements CRUD:**

```php
public function store(Request $request) {
    $validated = $request->validate([
        'product_id' => 'required|exists:products,id',
        'supplier_id' => 'required_if:type,in|exists:suppliers,id',
        'type' => 'required|in:in,out',
        'quantity' => 'required|integer|min:1',
        'note' => 'nullable',
    ]);

    DB::transaction(function() use ($validated) {
        // Create movement
        StockMovement::create([
            ...$validated,
            'user_id' => auth()->id(),
        ]);

        // Update product quantity
        $product = Product::find($validated['product_id']);
        if ($validated['type'] === 'in') {
            $product->increment('quantity', $validated['quantity']);
        } else {
            if ($product->quantity < $validated['quantity']) {
                throw new \Exception('Số lượng tồn kho không đủ!');
            }
            $product->decrement('quantity', $validated['quantity']);
        }
    });

    return redirect()->route('admin.stock-movements.index')->with('success', 'Thành công!');
}
```

**Promotions CRUD:**

```php
public function store(Request $request) {
    $validated = $request->validate([
        'code' => 'required|unique:promotions',
        'name' => 'required',
        'type' => 'required|in:fixed,percent',
        'value' => 'required|numeric',
        'min_order' => 'nullable|numeric',
        'max_discount' => 'nullable|numeric',
        'start_date' => 'required|date',
        'end_date' => 'required|date|after:start_date',
        'usage_limit' => 'nullable|integer',
    ]);

    Promotion::create($validated);
    return redirect()->route('admin.promotions.index')->with('success', 'Thêm khuyến mãi thành công!');
}
```

**Ngày 3-4: Orders Management (Admin)**

**Orders List:**

```php
public function index(Request $request) {
    $query = Order::with(['customer', 'user']);

    // Filters
    if ($request->order_status) {
        $query->where('order_status', $request->order_status);
    }

    if ($request->source) {
        $query->where('source', $request->source);
    }

    if ($request->date_from) {
        $query->whereDate('created_at', '>=', $request->date_from);
    }

    if ($request->date_to) {
        $query->whereDate('created_at', '<=', $request->date_to);
    }

    $orders = $query->latest()->paginate(20);

    return view('admin.orders.index', compact('orders'));
}
```

**Order Detail & Actions:**

```php
public function show(Order $order) {
    $order->load(['customer', 'user', 'items.product']);
    return view('admin.orders.show', compact('order'));
}

// Duyệt đơn
public function confirm(Order $order) {
    if ($order->order_status !== 'pending') {
        return back()->with('error', 'Chỉ có thể duyệt đơn pending!');
    }

    $order->update(['order_status' => 'confirmed']);
    return back()->with('success', 'Đã duyệt đơn hàng!');
}

// Giao hàng
public function ship(Request $request, Order $order) {
    $validated = $request->validate([
        'tracking_code' => 'required',
        'shipping_carrier' => 'required',
    ]);

    $order->update([
        'order_status' => 'shipping',
        ...$validated,
    ]);

    return back()->with('success', 'Đã chuyển sang giao hàng!');
}

// Hoàn thành
public function complete(Order $order) {
    DB::transaction(function() use ($order) {
        $order->update([
            'order_status' => 'completed',
            'payment_status' => 'paid',
        ]);

        // Add loyalty points
        if ($order->customer) {
            $points = floor($order->total_money / 100000);
            $order->customer->increment('points', $points);
        }
    });

    return back()->with('success', 'Đơn hàng đã hoàn thành!');
}
```

**Ngày 5-6: Customer Shopping Flow (Bonus)**

**Shop Pages:**

```php
// HomeController.php
public function index() {
    $featuredProducts = Product::active()
        ->orderBy('created_at', 'desc')
        ->take(8)
        ->get();

    $categories = Category::all();
    $brands = Brand::all();

    return view('shop.home', compact('featuredProducts', 'categories', 'brands'));
}

// ProductController.php (Shop)
public function index(Request $request) {
    $query = Product::active()->with(['category', 'brand']);

    // Filter by category
    if ($request->category_id) {
        $query->where('category_id', $request->category_id);
    }

    // Filter by brand
    if ($request->brand_id) {
        $query->where('brand_id', $request->brand_id);
    }

    // Filter by price
    if ($request->price_min) {
        $query->where('price', '>=', $request->price_min);
    }
    if ($request->price_max) {
        $query->where('price', '<=', $request->price_max);
    }

    // Sort
    if ($request->sort == 'price_asc') {
        $query->orderBy('price', 'asc');
    } elseif ($request->sort == 'price_desc') {
        $query->orderBy('price', 'desc');
    } else {
        $query->latest();
    }

    $products = $query->paginate(12);
    $categories = Category::all();
    $brands = Brand::all();

    return view('shop.products.index', compact('products', 'categories', 'brands'));
}

public function show(Product $product) {
    $product->load(['category', 'brand', 'specs']);
    $relatedProducts = Product::active()
        ->where('category_id', $product->category_id)
        ->where('id', '!=', $product->id)
        ->take(4)
        ->get();

    return view('shop.products.show', compact('product', 'relatedProducts'));
}
```

**Cart System (Session-based):**

```php
// CartController.php
public function add(Request $request) {
    $product = Product::findOrFail($request->product_id);
    $quantity = $request->quantity ?? 1;

    // Check stock
    if ($product->quantity < $quantity) {
        return back()->with('error', 'Sản phẩm không đủ số lượng!');
    }

    $cart = session()->get('cart', []);

    if (isset($cart[$product->id])) {
        $cart[$product->id]['quantity'] += $quantity;
    } else {
        $cart[$product->id] = [
            'name' => $product->name,
            'price' => $product->price,
            'quantity' => $quantity,
            'image' => $product->image,
        ];
    }

    session()->put('cart', $cart);

    return back()->with('success', 'Đã thêm vào giỏ hàng!');
}

public function update(Request $request) {
    $cart = session()->get('cart', []);

    if (isset($cart[$request->product_id])) {
        $cart[$request->product_id]['quantity'] = $request->quantity;
        session()->put('cart', $cart);
    }

    return back()->with('success', 'Đã cập nhật giỏ hàng!');
}

public function remove($productId) {
    $cart = session()->get('cart', []);

    if (isset($cart[$productId])) {
        unset($cart[$productId]);
        session()->put('cart', $cart);
    }

    return back()->with('success', 'Đã xóa khỏi giỏ hàng!');
}
```

**Checkout & Order Creation:**

```php
public function checkout() {
    $cart = session()->get('cart', []);

    if (empty($cart)) {
        return redirect()->route('shop.home')->with('error', 'Giỏ hàng trống!');
    }

    $subtotal = collect($cart)->sum(fn($item) => $item['price'] * $item['quantity']);

    return view('shop.checkout', compact('cart', 'subtotal'));
}

public function placeOrder(Request $request) {
    $validated = $request->validate([
        'shipping_name' => 'required',
        'shipping_phone' => 'required',
        'shipping_address' => 'required',
        'payment_method' => 'required|in:cash,card,transfer,cod',
        'promotion_code' => 'nullable',
    ]);

    $cart = session()->get('cart', []);

    if (empty($cart)) {
        return redirect()->route('shop.home')->with('error', 'Giỏ hàng trống!');
    }

    DB::transaction(function() use ($validated, $cart) {
        // Calculate totals
        $subtotal = collect($cart)->sum(fn($item) => $item['price'] * $item['quantity']);
        $discount = 0;

        // Apply promotion
        if ($validated['promotion_code']) {
            $promo = Promotion::where('code', $validated['promotion_code'])
                ->where('status', 1)
                ->where('start_date', '<=', now())
                ->where('end_date', '>=', now())
                ->first();

            if ($promo && $subtotal >= $promo->min_order) {
                if ($promo->type === 'fixed') {
                    $discount = $promo->value;
                } else {
                    $discount = $subtotal * ($promo->value / 100);
                }

                if ($promo->max_discount && $discount > $promo->max_discount) {
                    $discount = $promo->max_discount;
                }
            }
        }

        $total = $subtotal - $discount;

        // Create order
        $order = Order::create([
            'order_code' => 'ORD' . time(),
            'source' => 'web',
            'customer_id' => auth('customer')->id(),
            'subtotal' => $subtotal,
            'discount' => $discount,
            'total_money' => $total,
            'payment_method' => $validated['payment_method'],
            'payment_status' => 'unpaid',
            'order_status' => 'pending',
            'shipping_name' => $validated['shipping_name'],
            'shipping_phone' => $validated['shipping_phone'],
            'shipping_address' => $validated['shipping_address'],
        ]);

        // Create order items
        foreach ($cart as $productId => $item) {
            $order->items()->create([
                'product_id' => $productId,
                'quantity' => $item['quantity'],
                'price' => $item['price'],
            ]);
        }

        // Clear cart
        session()->forget('cart');
    });

    return redirect()->route('shop.order-success')->with('success', 'Đặt hàng thành công!');
}
```

**Ngày 7: POS Interface (Bonus)**

**POS Layout (Alpine.js):**

```blade
<div x-data="posApp()" class="grid grid-cols-3 gap-4 h-screen p-4">
  <!-- Left: Product Search -->
  <div class="col-span-1 card bg-base-100 shadow-xl">
    <div class="card-body">
      <h2 class="card-title">Tìm sản phẩm</h2>

      <input type="text"
             x-model="searchQuery"
             @input.debounce="searchProducts()"
             placeholder="Quét SKU hoặc tìm tên..."
             class="input input-bordered" />

      <div class="overflow-y-auto h-96">
        <template x-for="product in searchResults" :key="product.id">
          <div @click="addToCart(product)"
               class="card bg-base-200 mb-2 cursor-pointer hover:bg-base-300">
            <div class="card-body p-3">
              <div class="flex gap-2">
                <img :src="product.image" class="w-16 h-16 object-cover rounded" />
                <div>
                  <p class="font-bold" x-text="product.name"></p>
                  <p class="text-sm" x-text="formatPrice(product.price)"></p>
                  <p class="text-xs">Tồn: <span x-text="product.quantity"></span></p>
                </div>
              </div>
            </div>
          </div>
        </template>
      </div>
    </div>
  </div>

  <!-- Middle: Cart -->
  <div class="col-span-1 card bg-base-100 shadow-xl">
    <div class="card-body">
      <h2 class="card-title">Giỏ hàng</h2>

      <div class="overflow-y-auto h-96">
        <template x-for="(item, index) in cart" :key="index">
          <div class="card bg-base-200 mb-2">
            <div class="card-body p-3">
              <div class="flex justify-between items-center">
                <div>
                  <p class="font-bold" x-text="item.name"></p>
                  <p class="text-sm" x-text="formatPrice(item.price)"></p>
                </div>
                <div class="flex items-center gap-2">
                  <button @click="item.quantity--" class="btn btn-sm">-</button>
                  <span x-text="item.quantity"></span>
                  <button @click="item.quantity++" class="btn btn-sm">+</button>
                  <button @click="removeFromCart(index)" class="btn btn-sm btn-error">🗑️</button>
                </div>
              </div>
              <p class="text-right font-bold" x-text="formatPrice(item.price * item.quantity)"></p>
            </div>
          </div>
        </template>
      </div>

      <div class="divider"></div>

      <div class="text-2xl font-bold text-right">
        Tổng: <span x-text="formatPrice(total)"></span>
      </div>
    </div>
  </div>

  <!-- Right: Customer & Payment -->
  <div class="col-span-1 card bg-base-100 shadow-xl">
    <div class="card-body">
      <h2 class="card-title">Khách hàng</h2>

      <input type="text"
             x-model="customerPhone"
             @input.debounce="searchCustomer()"
             placeholder="Số điện thoại..."
             class="input input-bordered" />

      <div x-show="customer">
        <p><strong>Tên:</strong> <span x-text="customer?.full_name"></span></p>
        <p><strong>Điểm:</strong> <span x-text="customer?.points"></span></p>
      </div>

      <div x-show="!customer && customerPhone.length >= 10">
        <button @click="createQuickCustomer()" class="btn btn-primary btn-sm">
          Tạo khách hàng mới
        </button>
      </div>

      <div class="divider"></div>

      <h3 class="font-bold">Thanh toán</h3>

      <select x-model="paymentMethod" class="select select-bordered">
        <option value="cash">Tiền mặt</option>
        <option value="card">Thẻ</option>
        <option value="transfer">Chuyển khoản</option>
      </select>

      <button @click="completeOrder()"
              :disabled="cart.length === 0"
              class="btn btn-success btn-lg">
        Hoàn tất (F2)
      </button>

      <button @click="clearCart()" class="btn btn-error">
        Xóa giỏ hàng
      </button>
    </div>
  </div>
</div>

<script>
function posApp() {
    return {
        searchQuery: '',
        searchResults: [],
        cart: [],
        customerPhone: '',
        customer: null,
        paymentMethod: 'cash',

        get total() {
            return this.cart.reduce((sum, item) => sum + (item.price * item.quantity), 0);
        },

        async searchProducts() {
            if (this.searchQuery.length < 2) return;

            const response = await fetch(`/api/pos/products/search?q=${this.searchQuery}`);
            this.searchResults = await response.json();
        },

        addToCart(product) {
            const existing = this.cart.find(item => item.id === product.id);

            if (existing) {
                existing.quantity++;
            } else {
                this.cart.push({
                    id: product.id,
                    name: product.name,
                    price: product.price,
                    quantity: 1,
                });
            }
        },

        removeFromCart(index) {
            this.cart.splice(index, 1);
        },

        async searchCustomer() {
            if (this.customerPhone.length < 10) return;

            const response = await fetch(`/api/pos/customers/search?phone=${this.customerPhone}`);
            const data = await response.json();
            this.customer = data.customer;
        },

        async createQuickCustomer() {
            const name = prompt('Tên khách hàng:');
            if (!name) return;

            const response = await fetch('/api/pos/customers', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                },
                body: JSON.stringify({
                    full_name: name,
                    phone: this.customerPhone,
                }),
            });

            this.customer = await response.json();
        },

        async completeOrder() {
            if (this.cart.length === 0) return;

            const response = await fetch('/api/pos/orders', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                },
                body: JSON.stringify({
                    customer_id: this.customer?.id,
                    items: this.cart,
                    payment_method: this.paymentMethod,
                }),
            });

            if (response.ok) {
                alert('Đơn hàng thành công!');
                this.clearCart();
            }
        },

        clearCart() {
            this.cart = [];
            this.customer = null;
            this.customerPhone = '';
        },

        formatPrice(price) {
            return new Intl.NumberFormat('vi-VN', { style: 'currency', currency: 'VND' }).format(price);
        },
    };
}

// Keyboard shortcuts
document.addEventListener('keydown', (e) => {
    if (e.key === 'F2') {
        e.preventDefault();
        // Trigger complete order
    }
});
</script>
```

### **Deliverables Tuần 6:**

✅ Stock movements CRUD  
✅ Promotions CRUD  
✅ Orders management (admin)  
✅ Order status flow  
✅ Customer shopping flow (home, list, detail, cart, checkout)  
✅ POS interface (Alpine.js + AJAX)  
✅ 12/12 modules hoàn thành  
✅ O2O functionality complete!

---

## 📊 TUẦN 7: REPORTS & ANALYTICS (Show thầy: Dashboard + Reports)

### **Yêu cầu thầy:**

-   Thực hiện thống kê dữ liệu

### **Implementation Plan:**

**Ngày 1-2: Dashboard**

```php
// DashboardController.php
public function index() {
    // Cards
    $todayRevenue = Order::whereDate('created_at', today())
        ->where('order_status', 'completed')
        ->sum('total_money');

    $pendingOrders = Order::where('order_status', 'pending')->count();

    $lowStockProducts = Product::where('quantity', '<', 5)
        ->where('status', 'active')
        ->count();

    $newCustomers = Customer::whereDate('created_at', today')->count();

    // Monthly revenue (last 6 months)
    $monthlyRevenue = Order::where('order_status', 'completed')
        ->selectRaw('MONTH(created_at) as month, YEAR(created_at) as year, SUM(total_money) as revenue')
        ->whereYear('created_at', date('Y'))
        ->groupBy('month', 'year')
        ->orderBy('month')
        ->get();

    // Pending orders list
    $pendingOrdersList = Order::where('order_status', 'pending')
        ->with('customer')
        ->latest()
        ->take(10)
        ->get();

    // Top products
    $topProducts = DB::table('order_items')
        ->join('products', 'order_items.product_id', '=', 'products.id')
        ->join('orders', 'order_items.order_id', '=', 'orders.id')
        ->where('orders.order_status', 'completed')
        ->select('products.name', DB::raw('SUM(order_items.quantity) as total_sold'))
        ->groupBy('products.id', 'products.name')
        ->orderByDesc('total_sold')
        ->limit(5)
        ->get();

    return view('admin.dashboard', compact(
        'todayRevenue',
        'pendingOrders',
        'lowStockProducts',
        'newCustomers',
        'monthlyRevenue',
        'pendingOrdersList',
        'topProducts'
    ));
}
```

**Dashboard View:**

```blade
<div class="grid grid-cols-4 gap-4 mb-6">
  <!-- Card 1 -->
  <div class="stats shadow">
    <div class="stat">
      <div class="stat-figure text-primary">💰</div>
      <div class="stat-title">Doanh thu hôm nay</div>
      <div class="stat-value text-primary">{{ number_format($todayRevenue) }}đ</div>
    </div>
  </div>

  <!-- Card 2 -->
  <div class="stats shadow">
    <div class="stat">
      <div class="stat-figure text-secondary">🛒</div>
      <div class="stat-title">Đơn hàng mới</div>
      <div class="stat-value text-secondary">{{ $pendingOrders }}</div>
    </div>
  </div>

  <!-- Card 3 -->
  <div class="stats shadow">
    <div class="stat">
      <div class="stat-figure text-warning">⚠️</div>
      <div class="stat-title">Sản phẩm sắp hết</div>
      <div class="stat-value text-warning">{{ $lowStockProducts }}</div>
    </div>
  </div>

  <!-- Card 4 -->
  <div class="stats shadow">
    <div class="stat">
      <div class="stat-figure text-success">👥</div>
      <div class="stat-title">Khách hàng mới</div>
      <div class="stat-value text-success">{{ $newCustomers }}</div>
    </div>
  </div>
</div>

<!-- Charts -->
<div class="grid grid-cols-2 gap-4 mb-6">
  <!-- Revenue Chart -->
  <div class="card bg-base-100 shadow-xl">
    <div class="card-body">
      <h2 class="card-title">Doanh thu theo tháng</h2>
      <canvas id="revenueChart"></canvas>
    </div>
  </div>

  <!-- Top Products Chart -->
  <div class="card bg-base-100 shadow-xl">
    <div class="card-body">
      <h2 class="card-title">Top 5 sản phẩm bán chạy</h2>
      <canvas id="topProductsChart"></canvas>
    </div>
  </div>
</div>

<!-- Pending Orders -->
<div class="card bg-base-100 shadow-xl">
  <div class="card-body">
    <h2 class="card-title">Đơn hàng cần xử lý</h2>
    <div class="overflow-x-auto">
      <table class="table table-zebra">
        <thead>
          <tr>
            <th>Mã đơn</th>
            <th>Khách hàng</th>
            <th>Tổng tiền</th>
            <th>Thời gian</th>
            <th>Hành động</th>
          </tr>
        </thead>
        <tbody>
          @foreach($pendingOrdersList as $order)
          <tr>
            <td>{{ $order->order_code }}</td>
            <td>{{ $order->customer->full_name }}</td>
            <td>{{ number_format($order->total_money) }}đ</td>
            <td>{{ $order->created_at->diffForHumans() }}</td>
            <td>
              <a href="{{ route('admin.orders.show', $order) }}" class="btn btn-sm btn-primary">
                Xem
              </a>
            </td>
          </tr>
          @endforeach
        </tbody>
      </table>
    </div>
  </div>
</div>

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
// Revenue Chart
const revenueCtx = document.getElementById('revenueChart');
new Chart(revenueCtx, {
    type: 'bar',
    data: {
        labels: {!! json_encode($monthlyRevenue->pluck('month')) !!},
        datasets: [{
            label: 'Doanh thu (VNĐ)',
            data: {!! json_encode($monthlyRevenue->pluck('revenue')) !!},
            backgroundColor: 'rgba(54, 162, 235, 0.5)',
            borderColor: 'rgba(54, 162, 235, 1)',
            borderWidth: 1
        }]
    },
    options: {
        responsive: true,
        scales: {
            y: {
                beginAtZero: true,
                ticks: {
                    callback: function(value) {
                        return new Intl.NumberFormat('vi-VN').format(value) + 'đ';
                    }
                }
            }
        }
    }
});

// Top Products Chart
const topProductsCtx = document.getElementById('topProductsChart');
new Chart(topProductsCtx, {
    type: 'doughnut',
    data: {
        labels: {!! json_encode($topProducts->pluck('name')) !!},
        datasets: [{
            data: {!! json_encode($topProducts->pluck('total_sold')) !!},
            backgroundColor: [
                'rgba(255, 99, 132, 0.5)',
                'rgba(54, 162, 235, 0.5)',
                'rgba(255, 206, 86, 0.5)',
                'rgba(75, 192, 192, 0.5)',
                'rgba(153, 102, 255, 0.5)',
            ],
        }]
    },
    options: {
        responsive: true,
    }
});
</script>
@endpush
```

**Ngày 3-4: Revenue Report**

```php
public function revenue(Request $request) {
    $query = Order::where('order_status', 'completed');

    // Date filters
    $dateFrom = $request->date_from ?? now()->startOfMonth()->format('Y-m-d');
    $dateTo = $request->date_to ?? now()->format('Y-m-d');

    $query->whereDate('created_at', '>=', $dateFrom)
          ->whereDate('created_at', '<=', $dateTo);

    // Totals
    $totalRevenue = $query->sum('total_money');
    $totalOrders = $query->count();
    $avgOrderValue = $totalOrders > 0 ? $totalRevenue / $totalOrders : 0;

    // Daily breakdown
    $dailyRevenue = $query->selectRaw('DATE(created_at) as date, SUM(total_money) as revenue, COUNT(*) as orders')
        ->groupBy('date')
        ->orderBy('date')
        ->get();

    // By source
    $bySource = Order::where('order_status', 'completed')
        ->whereDate('created_at', '>=', $dateFrom)
        ->whereDate('created_at', '<=', $dateTo)
        ->selectRaw('source, SUM(total_money) as revenue, COUNT(*) as orders')
        ->groupBy('source')
        ->get();

    return view('admin.reports.revenue', compact(
        'totalRevenue',
        'totalOrders',
        'avgOrderValue',
        'dailyRevenue',
        'bySource',
        'dateFrom',
        'dateTo'
    ));
}
```

**Ngày 5: Products Report**

```php
public function products() {
    // Top selling
    $topProducts = DB::table('order_items')
        ->join('products', 'order_items.product_id', '=', 'products.id')
        ->join('orders', 'order_items.order_id', '=', 'orders.id')
        ->where('orders.order_status', 'completed')
        ->select(
            'products.id',
            'products.name',
            'products.price',
            DB::raw('SUM(order_items.quantity) as total_sold'),
            DB::raw('SUM(order_items.quantity * order_items.price) as total_revenue')
        )
        ->groupBy('products.id', 'products.name', 'products.price')
        ->orderByDesc('total_sold')
        ->limit(20)
        ->get();

    // Low stock
    $lowStockProducts = Product::where('quantity', '<', 5)
        ->where('status', 'active')
        ->with(['category', 'brand'])
        ->get();

    // Out of stock
    $outOfStockProducts = Product::where('quantity', 0)
        ->where('status', 'active')
        ->with(['category', 'brand'])
        ->get();

    return view('admin.reports.products', compact(
        'topProducts',
        'lowStockProducts',
        'outOfStockProducts'
    ));
}
```

**Ngày 6: Inventory Report**

```php
public function inventory(Request $request) {
    $query = StockMovement::with(['product', 'supplier', 'user']);

    // Filters
    if ($request->type) {
        $query->where('type', $request->type);
    }

    if ($request->date_from) {
        $query->whereDate('created_at', '>=', $request->date_from);
    }

    if ($request->date_to) {
        $query->whereDate('created_at', '<=', $request->date_to);
    }

    $movements = $query->latest()->paginate(50);

    // Summary
    $totalIn = StockMovement::where('type', 'in')
        ->when($request->date_from, fn($q) => $q->whereDate('created_at', '>=', $request->date_from))
        ->when($request->date_to, fn($q) => $q->whereDate('created_at', '<=', $request->date_to))
        ->sum('quantity');

    $totalOut = StockMovement::where('type', 'out')
        ->when($request->date_from, fn($q) => $q->whereDate('created_at', '>=', $request->date_from))
        ->when($request->date_to, fn($q) => $q->whereDate('created_at', '<=', $request->date_to))
        ->sum('quantity');

    return view('admin.reports.inventory', compact('movements', 'totalIn', 'totalOut'));
}
```

**Ngày 7: Polish & Test**

-   Test all reports
-   Verify calculations
-   Charts responsive
-   Export Excel (optional)
-   Screenshots

### **Deliverables Tuần 7:**

✅ Dashboard với 4 cards  
✅ 2 charts (Revenue + Top Products)  
✅ Pending orders list  
✅ Revenue report (với filters)  
✅ Products report (top selling, low stock, out of stock)  
✅ Inventory report (stock movements history)  
✅ All reports với filters

---
# ROADMAP FINAL - WEEK 8, 9-10 & SUMMARY

## 🐛 TUẦN 8: POLISH & FINALIZE (Show thầy: Hoàn thiện)

### **Yêu cầu thầy:**

-   Chỉnh sửa và khắc phục kết quả

### **Implementation Plan:**

**Ngày 1-2: Bug Fixes & Edge Cases**

**Bug Checklist:**

-   [ ] Auth bugs
    -   Google OAuth callback errors
    -   Session timeout issues
    -   Password reset not working
    -   Role-based access bypass
-   [ ] CRUD bugs
    -   Validation not working
    -   Image upload fails
    -   Delete cascade issues
    -   Pagination broken
-   [ ] Order bugs
    -   Cart quantity negative
    -   Promotion not applied
    -   Stock not updated
    -   Order status stuck
-   [ ] UI bugs
    -   Responsive issues
    -   Modal not closing
    -   Form not submitting
    -   Toast not showing

**Edge Cases Testing:**

```php
// Test scenarios
1. Đặt hàng khi sản phẩm hết hàng
2. Apply promotion với đơn hàng < min_order
3. Upload ảnh > 2MB
4. Tạo product với SKU trùng
5. Delete product đang có trong order
6. Lock user đang online
7. Nhập kho với quantity = 0
8. Checkout với cart trống
```

**Ngày 3-4: UI/UX Polish**

**UI Improvements:**

-   **Loading States:**

    ```blade
    <button type="submit" class="btn btn-primary" wire:loading.attr="disabled">
        <span wire:loading.remove>Lưu</span>
        <span wire:loading>
            <span class="loading loading-spinner loading-sm"></span>
            Đang xử lý...
        </span>
    </button>
    ```

-   **Toast Notifications (SweetAlert2):**

    ```javascript
    // Success toast
    Swal.fire({
        toast: true,
        position: "top-end",
        icon: "success",
        title: "Thành công!",
        showConfirmButton: false,
        timer: 3000,
    });

    // Error toast
    Swal.fire({
        toast: true,
        position: "top-end",
        icon: "error",
        title: "Có lỗi xảy ra!",
        showConfirmButton: false,
        timer: 3000,
    });
    ```

-   **Confirm Dialogs:**

    ```javascript
    // Delete confirmation
    function confirmDelete(form) {
        Swal.fire({
            title: "Xác nhận xóa?",
            text: "Bạn không thể hoàn tác hành động này!",
            icon: "warning",
            showCancelButton: true,
            confirmButtonColor: "#d33",
            cancelButtonColor: "#3085d6",
            confirmButtonText: "Xóa",
            cancelButtonText: "Hủy",
        }).then((result) => {
            if (result.isConfirmed) {
                form.submit();
            }
        });
        return false;
    }
    ```

-   **Breadcrumbs:**

    ```blade
    <div class="text-sm breadcrumbs">
      <ul>
        <li><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
        <li><a href="{{ route('admin.products.index') }}">Products</a></li>
        <li>Chi tiết</li>
      </ul>
    </div>
    ```

-   **Active Menu States:**

    ```blade
    <li>
        <a href="{{ route('admin.products.index') }}"
           class="{{ request()->routeIs('admin.products.*') ? 'active' : '' }}">
            📦 Products
        </a>
    </li>
    ```

-   **Error Messages Tiếng Việt:**
    ```php
    // resources/lang/vi/validation.php
    return [
        'required' => ':attribute không được để trống.',
        'email' => ':attribute phải là email hợp lệ.',
        'unique' => ':attribute đã tồn tại.',
        'min' => [
            'string' => ':attribute phải có ít nhất :min ký tự.',
        ],
        'attributes' => [
            'email' => 'Email',
            'password' => 'Mật khẩu',
            'name' => 'Tên',
        ],
    ];
    ```

**Responsive Design:**

-   Test trên mobile (375px)
-   Test trên tablet (768px)
-   Test trên desktop (1920px)
-   Fix overflow issues
-   Fix menu collapse

**Ngày 5: Performance Optimization**

**Database Optimization:**

```php
// Eager loading
$products = Product::with(['category', 'brand', 'specs'])->get();

// Pagination
$products = Product::paginate(20);

// Select specific columns
$products = Product::select('id', 'name', 'price')->get();

// Index important columns
Schema::table('products', function (Blueprint $table) {
    $table->index('sku');
    $table->index('status');
    $table->index(['category_id', 'brand_id']);
});
```

**Cache Configuration:**

```bash
php artisan config:cache
php artisan route:cache
php artisan view:cache
php artisan optimize
```

**Asset Optimization:**

```bash
npm run build
```

**Image Optimization:**

```php
// Compress images
$img = Image::make($image)
    ->resize(800, 800, function($constraint) {
        $constraint->aspectRatio();
        $constraint->upsize();
    })
    ->encode('jpg', 80); // 80% quality
```

**Ngày 6: Security Checklist**

**Security Tasks:**

-   [ ] **CSRF Protection:**

    ```blade
    <form method="POST">
        @csrf
        ...
    </form>
    ```

-   [ ] **SQL Injection Prevention:**

    ```php
    // Use Eloquent or Query Builder (auto-escaped)
    Product::where('name', 'like', "%{$search}%")->get();
    ```

-   [ ] **XSS Prevention:**

    ```blade
    <!-- Blade auto-escapes -->
    {{ $user->name }}

    <!-- Raw HTML (careful!) -->
    {!! $content !!}
    ```

-   [ ] **Rate Limiting:**

    ```php
    // routes/web.php
    Route::post('/login', [AuthController::class, 'login'])
        ->middleware('throttle:5,1'); // 5 attempts per minute
    ```

-   [ ] **Secure Headers:**

    ```php
    // app/Http/Middleware/SecureHeaders.php
    public function handle($request, Closure $next) {
        $response = $next($request);
        $response->headers->set('X-Frame-Options', 'SAMEORIGIN');
        $response->headers->set('X-Content-Type-Options', 'nosniff');
        $response->headers->set('X-XSS-Protection', '1; mode=block');
        return $response;
    }
    ```

-   [ ] **Environment Variables:**

    ```env
    APP_ENV=production
    APP_DEBUG=false
    APP_KEY=base64:...
    ```

-   [ ] **File Upload Security:**

    ```php
    $request->validate([
        'image' => 'required|image|mimes:jpeg,png,jpg|max:2048',
    ]);
    ```

-   [ ] **Password Hashing:**
    ```php
    bcrypt($password); // or Hash::make($password)
    ```

**Ngày 7: Final Testing & Demo Prep**

**Testing Checklist:**

**Auth Flow:**

-   [ ] Register với email
-   [ ] Register với Google
-   [ ] Login với email
-   [ ] Login với Google
-   [ ] Set password cho Google user
-   [ ] Logout
-   [ ] Role-based access

**CRUD Operations (12 modules):**

-   [ ] Roles: List, Create, Edit, Delete
-   [ ] Users: List, Create, Edit, Delete, Lock
-   [ ] Customers: List, Create, Edit, Delete, Lock
-   [ ] Categories: List, Create, Edit, Delete
-   [ ] Brands: List, Create, Edit, Delete
-   [ ] Suppliers: List, Create, Edit, Delete
-   [ ] Products: List, Create, Edit, Delete, Upload ảnh, Specs
-   [ ] Product_specs: Auto-create với product
-   [ ] Stock_movements: List, Create (nhập hàng)
-   [ ] Promotions: List, Create, Edit, Delete
-   [ ] Orders: List, Detail, Status transitions
-   [ ] Order_items: Auto-create với order

**Order Flows:**

-   [ ] Customer: Browse → Add to cart → Checkout → Order created
-   [ ] Admin: View order → Confirm → Ship → Complete
-   [ ] POS: Search product → Add to cart → Select customer → Pay → Order completed

**Reports:**

-   [ ] Dashboard: Cards + Charts
-   [ ] Revenue report: Filter by date
-   [ ] Products report: Top selling, Low stock
-   [ ] Inventory report: Stock movements

**Demo Preparation:**

-   [ ] Seed fresh data: `php artisan db:seed`
-   [ ] Clear cache: `php artisan cache:clear`
-   [ ] Test all features
-   [ ] Prepare demo script
-   [ ] Screenshots cho báo cáo
-   [ ] Backup database
-   [ ] Commit code to Git

### **Deliverables Tuần 8:**

✅ All bugs fixed  
✅ UI/UX polished (loading, toast, confirm, breadcrumbs)  
✅ Responsive design  
✅ Performance optimized  
✅ Security checklist completed  
✅ All features tested  
✅ Demo-ready

---

## 📄 TUẦN 9-10: BÁO CÁO & THUYẾT TRÌNH

### **Yêu cầu thầy:**

-   Hoàn thiện file Word (báo cáo)
-   File PowerPoint (thuyết trình)

### **TUẦN 9: VIẾT BÁO CÁO WORD**

**Cấu trúc báo cáo (6 chương):**

**CHƯƠNG 1: TỔNG QUAN ĐỀ TÀI**

-   1.1. Lý do chọn đề tài
    -   Nhu cầu quản lý cửa hàng điện thoại
    -   Xu hướng O2O (Online to Offline)
    -   Tự động hóa quy trình bán hàng
-   1.2. Mục tiêu đề tài
    -   Xây dựng website quản lý toàn diện
    -   Hỗ trợ bán hàng online và offline
    -   Quản lý kho, đơn hàng, khách hàng
-   1.3. Đối tượng và phạm vi nghiên cứu
    -   Đối tượng: Cửa hàng điện thoại vừa và nhỏ
    -   Phạm vi: Quản lý sản phẩm, đơn hàng, kho, báo cáo
-   1.4. Phương pháp nghiên cứu
    -   Nghiên cứu tài liệu
    -   Phân tích yêu cầu
    -   Thiết kế và triển khai

**CHƯƠNG 2: CƠ SỞ LÝ THUYẾT**

-   2.1. Mô hình O2O (Online to Offline)
    -   Định nghĩa
    -   Ưu điểm
    -   Ứng dụng trong bán lẻ
-   2.2. Công nghệ sử dụng
    -   Laravel Framework
    -   Tailwind CSS + DaisyUI
    -   MySQL Database
    -   Google OAuth API
-   2.3. Mô hình MVC
    -   Model: Quản lý dữ liệu
    -   View: Giao diện người dùng
    -   Controller: Xử lý logic

**CHƯƠNG 3: PHÂN TÍCH VÀ THIẾT KẾ HỆ THỐNG**

-   3.1. Phân tích yêu cầu
    -   Yêu cầu chức năng (12 modules CRUD)
    -   Yêu cầu phi chức năng (bảo mật, hiệu năng)
-   3.2. Thiết kế Use Case
    -   Use Case Diagram
    -   Mô tả các Use Case chính
-   3.3. Thiết kế cơ sở dữ liệu
    -   ERD Diagram (12 bảng)
    -   Mô tả các bảng và quan hệ
    -   Script SQL
-   3.4. Thiết kế giao diện
    -   Wireframes
    -   Mockups (Admin + Customer)

**CHƯƠNG 4: TRIỂN KHAI HỆ THỐNG**

-   4.1. Môi trường phát triển
    -   XAMPP
    -   VS Code
    -   Git
-   4.2. Cài đặt và cấu hình
    -   Laravel installation
    -   Database setup
    -   Package installation
-   4.3. Triển khai các chức năng
    -   **Tuần 1:** Database + Templates
    -   **Tuần 2:** Authentication
    -   **Tuần 3:** Users CRUD
    -   **Tuần 4:** Detail pages
    -   **Tuần 5:** 5 CRUD modules
    -   **Tuần 6:** Orders + Stock + Promotions
    -   **Tuần 7:** Reports + Analytics
    -   **Tuần 8:** Polish + Testing
-   4.4. Screenshots các chức năng
    -   Login/Register
    -   Dashboard
    -   Products management
    -   Orders management
    -   POS interface
    -   Reports

**CHƯƠNG 5: KIỂM THỬ HỆ THỐNG**

-   5.1. Kế hoạch kiểm thử
    -   Test cases
    -   Test scenarios
-   5.2. Kiểm thử chức năng
    -   Auth flow
    -   CRUD operations
    -   Order flows
    -   Reports
-   5.3. Kiểm thử phi chức năng
    -   Performance
    -   Security
    -   Usability
-   5.4. Kết quả kiểm thử
    -   Bugs found
    -   Bugs fixed
    -   Test coverage

**CHƯƠNG 6: KẾT LUẬN VÀ HƯỚNG PHÁT TRIỂN**

-   6.1. Kết quả đạt được
    -   Hoàn thành 12 CRUD modules
    -   O2O functionality
    -   Reports & Analytics
-   6.2. Hạn chế
    -   Chưa có mobile app
    -   Chưa tích hợp payment gateway
    -   Chưa có email notifications
-   6.3. Hướng phát triển
    -   Mobile app (React Native)
    -   Payment integration (VNPay, Momo)
    -   Email/SMS notifications
    -   Advanced analytics (AI/ML)

**PHỤ LỤC**

-   Phụ lục A: Source code quan trọng
-   Phụ lục B: Database script
-   Phụ lục C: User manual

**Timeline Tuần 9:**

-   **Ngày 1-2:** Chương 1-2 (Tổng quan + Cơ sở lý thuyết)
-   **Ngày 3-4:** Chương 3-4 (Phân tích + Triển khai)
-   **Ngày 5-6:** Chương 5-6 (Kiểm thử + Kết luận)
-   **Ngày 7:** Review, format, chèn screenshots

---

### **TUẦN 10: SLIDE THUYẾT TRÌNH**

**Cấu trúc slide (15-20 slides):**

**Slide 1: Title**

-   Tên đề tài: Website Quản lý Cửa hàng Điện thoại (O2O Model)
-   Họ tên sinh viên
-   Lớp, Khoa
-   Giảng viên hướng dẫn

**Slide 2-3: Tổng quan**

-   Lý do chọn đề tài
-   Mục tiêu
-   Phạm vi

**Slide 4-5: Công nghệ**

-   Tech stack: Laravel + Tailwind + MySQL
-   Mô hình O2O
-   Architecture

**Slide 6-7: Database**

-   ERD Diagram (12 bảng)
-   Relationships

**Slide 8-9: Chức năng chính**

-   Authentication (thủ công + Google)
-   12 CRUD modules
-   O2O (Web orders + POS)
-   Reports & Analytics

**Slide 10-14: Demo Screenshots**

-   Slide 10: Login/Register
-   Slide 11: Dashboard
-   Slide 12: Products management
-   Slide 13: Orders management
-   Slide 14: Reports

**Slide 15-16: Kết quả**

-   Hoàn thành đúng tiến độ
-   Đầy đủ chức năng
-   Test coverage

**Slide 17: Hạn chế & Hướng phát triển**

-   Hạn chế hiện tại
-   Kế hoạch tương lai

**Slide 18: Q&A**

-   Câu hỏi và trả lời

**Timeline Tuần 10:**

-   **Ngày 1-3:** Làm slides (15-20 slides)
-   **Ngày 4-5:** Demo video hoặc chuẩn bị live demo
-   **Ngày 6:** Practice thuyết trình (10-15 phút)
-   **Ngày 7:** Final review

---

## 🎯 SUMMARY & SUCCESS METRICS

### **📊 DELIVERABLES CHECKLIST**

**Code Deliverables:**

-   ✅ Source code (Laravel project)
-   ✅ Database script (12 tables)
-   ✅ .env.example (documented)
-   ✅ README.md (installation guide)
-   ✅ Git repository (commit history)

**Documentation Deliverables:**

-   ✅ Báo cáo Word (6 chương)
-   ✅ Slide PowerPoint (15-20 slides)
-   ✅ ERD Diagram
-   ✅ Use Case Diagram
-   ✅ Screenshots (all features)

**Functional Deliverables:**

-   ✅ 12 CRUD modules hoạt động
-   ✅ Authentication (thủ công + Google OAuth)
-   ✅ Role-based access control (4 roles)
-   ✅ Web orders (customer shopping flow)
-   ✅ POS interface (bán tại quầy)
-   ✅ Stock management (nhập/xuất kho)
-   ✅ Promotions & Loyalty points
-   ✅ Reports & Analytics (Dashboard + 3 reports)

---

### **🎯 SUCCESS METRICS**

**Minimum Requirements (Pass môn):**

-   ✅ Follow đúng workflow thầy (8 buổi)
-   ✅ 12 CRUD modules hoạt động
-   ✅ Auth system hoàn chỉnh
-   ✅ Orders management
-   ✅ Basic reports
-   ✅ Báo cáo Word đầy đủ
-   ✅ Thuyết trình tốt

**Good Score (7-8 điểm):**

-   ✅ Minimum Requirements +
-   ✅ O2O functionality (Web + POS)
-   ✅ Stock management với triggers
-   ✅ Promotions system
-   ✅ Advanced reports với charts
-   ✅ UI/UX polished
-   ✅ Responsive design

**Excellent Score (9-10 điểm):**

-   ✅ Good Score +
-   ✅ Feature tests
-   ✅ Performance optimization
-   ✅ Security best practices
-   ✅ Clean code + comments
-   ✅ Complete documentation
-   ✅ Demo mượt mà, không lỗi

---

### **📋 COMPARISON: YÊU CẦU THẦY vs FULL FEATURES**

**Yêu cầu thầy (Must Have):**

| Tuần | Yêu cầu thầy                            | Status |
| ---- | --------------------------------------- | ------ |
| 1    | Database + Templates                    | ✅     |
| 2    | Auth (thủ công + Google)                | ✅     |
| 3    | Users CRUD complete                     | ✅     |
| 4    | Detail pages + Edit/Delete              | ✅     |
| 5    | 5 CRUD modules                          | ✅     |
| 6    | Admin pages (Orders, Stock, Promotions) | ✅     |
| 7    | Reports & Analytics                     | ✅     |
| 8    | Polish & Bug fixes                      | ✅     |

**Full Features (Should Have):**

| Feature                    | Yêu cầu thầy | Roadmap này | Benefit    |
| -------------------------- | ------------ | ----------- | ---------- |
| Database 12 bảng           | ✅           | ✅          | Required   |
| Auth thủ công              | ✅           | ✅          | Required   |
| Google OAuth               | ✅           | ✅          | Required   |
| Users CRUD                 | ✅           | ✅          | Required   |
| 5 CRUD modules             | ✅           | ✅          | Required   |
| Orders management          | ✅           | ✅          | Required   |
| Stock management           | ✅           | ✅          | Required   |
| Promotions                 | ✅           | ✅          | Required   |
| Reports                    | ✅           | ✅          | Required   |
| **Customer shopping flow** | ❌           | ✅          | Cộng điểm  |
| **POS interface**          | ❌           | ✅          | Cộng điểm  |
| **Loyalty points**         | ❌           | ✅          | Cộng điểm  |
| **Charts (Chart.js)**      | ❌           | ✅          | Cộng điểm  |
| **Responsive design**      | ❌           | ✅          | Cộng điểm  |
| **Toast notifications**    | ❌           | ✅          | UX tốt hơn |
| **Loading states**         | ❌           | ✅          | UX tốt hơn |

**Result:** Roadmap này = Yêu cầu thầy + Bonus features → Điểm cao hơn!

---

### **💡 TIPS ĐỂ ĐẠT ĐIỂM CAO**

**Trong quá trình làm:**

1. **Follow đúng tiến độ thầy** - Mỗi tuần hoàn thành đúng checklist
2. **Commit Git thường xuyên** - Chứng minh làm việc đều
3. **Test kỹ trước khi show thầy** - Không được lỗi khi demo
4. **Code clean + comments** - Dễ đọc, dễ hiểu
5. **UI đơn giản nhưng clean** - Không cần fancy, cần hoạt động
6. **Data mẫu đầy đủ** - Thầy không thích database trống

**Khi demo:**

1. **Chuẩn bị demo script** - Biết demo gì trước
2. **Test lại tất cả features** - Đảm bảo không lỗi
3. **Giải thích được logic** - Thầy sẽ hỏi
4. **Tự tin, nói rõ ràng** - Thể hiện hiểu bài
5. **Chuẩn bị trả lời câu hỏi** - Dự đoán thầy sẽ hỏi gì

**Trong báo cáo:**

1. **Đầy đủ 6 chương** - Không thiếu phần nào
2. **Screenshots đẹp** - Chụp màn hình rõ ràng
3. **Format chuẩn** - Font, spacing, numbering
4. **Không copy-paste** - Viết bằng lời của mình
5. **Kiểm tra chính tả** - Không lỗi tiếng Việt

**Khi thuyết trình:**

1. **15-20 slides** - Không quá dài
2. **Focus vào demo** - Ít text, nhiều hình
3. **10-15 phút** - Không quá ngắn, không quá dài
4. **Practice trước** - Nói trôi chảy
5. **Chuẩn bị Q&A** - Trả lời tự tin

---

### **🚨 RED FLAGS (TRÁNH)**

**Trong code:**

-   ❌ Code không chạy được
-   ❌ Lỗi khi demo
-   ❌ Database trống (không có data mẫu)
-   ❌ UI vỡ, không responsive
-   ❌ Không có validation
-   ❌ Security issues (SQL injection, XSS)

**Trong báo cáo:**

-   ❌ Thiếu chương
-   ❌ Không có screenshots
-   ❌ Copy-paste từ internet
-   ❌ Lỗi chính tả nhiều
-   ❌ Format lộn xộn

**Khi thuyết trình:**

-   ❌ Không chuẩn bị
-   ❌ Nói không rõ ràng
-   ❌ Không giải thích được code
-   ❌ Demo bị lỗi
-   ❌ Không trả lời được câu hỏi

---

### **✅ GREEN FLAGS (CẦN CÓ)**

**Trong code:**

-   ✅ Code chạy mượt mà
-   ✅ Không lỗi khi demo
-   ✅ Database có data mẫu đầy đủ
-   ✅ UI clean, responsive
-   ✅ Validation đầy đủ
-   ✅ Security best practices

**Trong báo cáo:**

-   ✅ Đầy đủ 6 chương
-   ✅ Screenshots đẹp, rõ ràng
-   ✅ Viết bằng lời của mình
-   ✅ Không lỗi chính tả
-   ✅ Format chuẩn

**Khi thuyết trình:**

-   ✅ Chuẩn bị kỹ
-   ✅ Nói rõ ràng, tự tin
-   ✅ Giải thích được code
-   ✅ Demo mượt mà
-   ✅ Trả lời tốt câu hỏi

---

## 🎓 FINAL WORDS

**Motto:** "Hoàn thành đúng yêu cầu > Perfect nhưng thiếu features"

**Strategy:**

1. Follow đúng workflow thầy (8 tuần)
2. Hoàn thành yêu cầu thầy trước
3. Thêm bonus features nếu còn thời gian
4. Test kỹ trước khi show thầy
5. Báo cáo + Thuyết trình tốt

**Timeline:**

-   Tuần 1-8: Development (show thầy mỗi tuần)
-   Tuần 9: Viết báo cáo Word
-   Tuần 10: Slide + Practice thuyết trình

**Result:** Pass môn + Điểm cao + Portfolio tốt! 💪

---

**Good luck! 🍀**
