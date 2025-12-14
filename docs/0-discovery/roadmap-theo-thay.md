# ROADMAP 8 BUỔI THEO YÊU CẦU CỦA THẦY

**Dự án:** Website Quản lý Cửa hàng Điện thoại O2O

---

## 🎯 MAPPING: YÊU CẦU THẦY vs FEATURES DỰ ÁN

### **BUỔI 1: FOUNDATION**

**Yêu cầu thầy:**

-   Chọn nhóm
-   Chọn đề tài
-   Xây dựng cơ sở dữ liệu
-   Thiết kế giao diện
-   Option đăng ký: (1) Thông tin thủ công (2) Google/Facebook/Microsoft

**Áp dụng cho dự án Tact:**

**1.1 Chọn nhóm & Đề tài** ✅

-   Đề tài: Website Quản lý Cửa hàng Điện thoại (O2O Model)
-   Nhóm: [Số thành viên]

**1.2 Xây dựng CSDL** ✅

**Database Schema (12 bảng):**

-   roles (4 quyền: Admin, Manager, Sales, Warehouse)
-   users (Nhân viên - có ảnh đại diện, trạng thái, quyền)
-   customers (Khách hàng - đăng ký thủ công + Google)
-   categories, brands, suppliers
-   products, product_specs
-   stock_movements
-   promotions
-   orders, order_items

**Migrations:**

```bash
php artisan make:migration create_roles_table
php artisan make:migration create_users_table
# ... (12 migrations total)
php artisan migrate
```

**1.3 Thiết kế giao diện** ✅

**Admin Template:**

-   Chọn template: AdminLTE hoặc SB Admin 2
-   Hoặc tự build với Tailwind + DaisyUI
-   Layout: Sidebar (menu trái) + Header + Content

**Customer Template:**

-   Template bán hàng (Bootstrap/Tailwind)
-   Layout: Header (navbar) + Content + Footer

**1.4 Auth Options** ✅

**Option 1: Đăng ký thủ công**

-   Form fields:
    -   Tên đăng nhập (username)
    -   Mật khẩu + Nhập lại mật khẩu
    -   Tên đầy đủ (full_name)
    -   Email
    -   Số điện thoại (phone)
    -   Ảnh đại diện (avatar) - upload
    -   Trạng thái (status): active/inactive
    -   Quyền (role_id): dropdown chọn role

**Option 2: Đăng ký bằng Google**

-   Setup Google API Console
-   Get Client ID + Client Secret
-   Install Socialite: `composer require laravel/socialite`
-   Config trong .env:
    ```
    GOOGLE_CLIENT_ID=your-client-id
    GOOGLE_CLIENT_SECRET=your-secret
    GOOGLE_REDIRECT_URI=http://localhost:8000/auth/google/callback
    ```
-   Lưu gmail vào CSDL (customers table)
-   Flow: Click "Login with Google" → Redirect Google → Callback → Lưu DB

**Deliverables Buổi 1:**

-   ✅ Database 12 bảng đã migrate
-   ✅ Template admin + customer đã chọn/setup
-   ✅ Auth options đã research (Google API setup)

---

## 🔐 BUỔI 2: AUTHENTICATION

**Yêu cầu thầy:**

-   Liệt kê các chức năng cần thực hiện
-   Thiết kế và xây dựng chức năng đăng ký
-   Thiết kế và xây dựng chức năng đăng nhập

**Áp dụng cho dự án Tact:**

**2.1 Liệt kê chức năng** ✅

**Chức năng chính:**

1. Auth: Đăng ký, Đăng nhập (thủ công + Google), Đăng xuất
2. CRUD: 12 modules (roles, users, customers, categories, brands, suppliers, products, product_specs, stock_movements, promotions, orders, order_items)
3. O2O: Đơn hàng online (web) + Đơn hàng tại quầy (POS)
4. Quản lý kho: Nhập/xuất hàng, trigger tự động
5. Khuyến mãi: Voucher, tích điểm
6. Thống kê: Doanh thu, sản phẩm, kho

**2.2 Chức năng Đăng ký** ✅

**Đăng ký thủ công (Users - Nhân viên):**

```php
// routes/web.php
Route::get('/register', [RegisterController::class, 'showForm']);
Route::post('/register', [RegisterController::class, 'register']);

// RegisterController.php
public function register(Request $request) {
    $validated = $request->validate([
        'username' => 'required|unique:users',
        'password' => 'required|min:6|confirmed',
        'full_name' => 'required',
        'email' => 'required|email|unique:users',
        'phone' => 'nullable',
        'avatar' => 'nullable|image|max:2048',
        'role_id' => 'required|exists:roles,id',
    ]);

    // Upload avatar
    if ($request->hasFile('avatar')) {
        $path = $request->file('avatar')->store('avatars', 'public');
        $validated['avatar'] = $path;
    }

    // Hash password
    $validated['password'] = bcrypt($validated['password']);

    User::create($validated);

    return redirect()->route('login')->with('success', 'Đăng ký thành công!');
}
```

**Đăng ký bằng Google (Customers):**

```php
// routes/web.php
Route::get('/auth/google', [GoogleController::class, 'redirect']);
Route::get('/auth/google/callback', [GoogleController::class, 'callback']);

// GoogleController.php
public function redirect() {
    return Socialite::driver('google')->redirect();
}

public function callback() {
    $googleUser = Socialite::driver('google')->user();

    // Check email exists
    $customer = Customer::where('email', $googleUser->email)->first();

    if (!$customer) {
        // Create new customer
        $customer = Customer::create([
            'email' => $googleUser->email,
            'full_name' => $googleUser->name,
            'avatar' => $googleUser->avatar,
            'google_id' => $googleUser->id,
            'password' => null, // Chưa có password
        ]);

        // Redirect to set password page
        session(['new_google_user' => $customer->id]);
        return redirect()->route('set-password');
    }

    // Login
    auth()->guard('customer')->login($customer);
    return redirect()->route('home');
}
```

**2.3 Chức năng Đăng nhập** ✅

**Đăng nhập thủ công:**

```php
// LoginController.php
public function login(Request $request) {
    $credentials = $request->validate([
        'username' => 'required',
        'password' => 'required',
    ]);

    if (auth()->attempt($credentials)) {
        $request->session()->regenerate();

        // Redirect based on role
        $role = auth()->user()->role->name;

        if (in_array($role, ['admin', 'manager', 'sales', 'warehouse'])) {
            return redirect()->route('admin.dashboard');
        }

        return redirect()->route('home');
    }

    return back()->withErrors([
        'username' => 'Thông tin đăng nhập không chính xác.',
    ]);
}
```

**Deliverables Buổi 2:**

-   ✅ Form đăng ký (thủ công) với upload avatar
-   ✅ Google OAuth integration
-   ✅ Form đăng nhập
-   ✅ Redirect based on role

---

## 🚪 BUỔI 3: CRUD FOUNDATION

**Yêu cầu thầy:**

-   Thiết kế và xây dựng chức năng thoát
-   Xây dựng Template cho quản trị
-   Thiết kế và xây dựng chức năng thêm dữ liệu
-   **Hôm nay làm xong chức năng quản lý người dùng** (Hiển thị, thêm mới, sửa, xóa/khóa tài khoản, tìm kiếm - table filter)

**Áp dụng cho dự án Tact:**

**3.1 Chức năng Thoát (Logout)** ✅

```php
// routes/web.php
Route::post('/logout', [LoginController::class, 'logout'])->name('logout');

// LoginController.php
public function logout(Request $request) {
    auth()->logout();
    $request->session()->invalidate();
    $request->session()->regenerateToken();

    return redirect()->route('login');
}
```

**3.2 Template Quản trị** ✅

**Tích hợp AdminLTE hoặc tự build:**

```blade
{{-- resources/views/layouts/admin.blade.php --}}
<!DOCTYPE html>
<html>
<head>
    <title>Tact Admin</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body>
    <!-- Sidebar -->
    <aside class="sidebar">
        <ul>
            <li><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
            <li><a href="{{ route('admin.users.index') }}">Quản lý Users</a></li>
            <li><a href="{{ route('admin.customers.index') }}">Quản lý Customers</a></li>
            <li><a href="{{ route('admin.products.index') }}">Quản lý Sản phẩm</a></li>
            <!-- ... more menu items -->
        </ul>
    </aside>

    <!-- Header -->
    <header>
        <div>{{ auth()->user()->full_name }}</div>
        <form method="POST" action="{{ route('logout') }}">
            @csrf
            <button type="submit">Đăng xuất</button>
        </form>
    </header>

    <!-- Content -->
    <main>
        @yield('content')
    </main>
</body>
</html>
```

**3.3 Chức năng THÊM dữ liệu** ✅

**Users Management (CRUD đầy đủ):**

**Hiển thị (Index):**

```php
// UserController.php
public function index(Request $request) {
    $query = User::with('role');

    // Search/Filter
    if ($request->has('search')) {
        $search = $request->search;
        $query->where(function($q) use ($search) {
            $q->where('username', 'like', "%{$search}%")
              ->orWhere('full_name', 'like', "%{$search}%")
              ->orWhere('email', 'like', "%{$search}%");
        });
    }

    if ($request->has('role_id')) {
        $query->where('role_id', $request->role_id);
    }

    if ($request->has('status')) {
        $query->where('status', $request->status);
    }

    $users = $query->paginate(10);
    $roles = Role::all();

    return view('admin.users.index', compact('users', 'roles'));
}
```

**View với DataTables/Filter:**

```blade
{{-- resources/views/admin/users/index.blade.php --}}
@extends('layouts.admin')

@section('content')
<div class="container">
    <h1>Quản lý Users</h1>

    <!-- Search & Filter -->
    <form method="GET">
        <input type="text" name="search" placeholder="Tìm kiếm..." value="{{ request('search') }}">

        <select name="role_id">
            <option value="">Tất cả quyền</option>
            @foreach($roles as $role)
                <option value="{{ $role->id }}" {{ request('role_id') == $role->id ? 'selected' : '' }}>
                    {{ $role->name }}
                </option>
            @endforeach
        </select>

        <select name="status">
            <option value="">Tất cả trạng thái</option>
            <option value="active" {{ request('status') == 'active' ? 'selected' : '' }}>Active</option>
            <option value="inactive" {{ request('status') == 'inactive' ? 'selected' : '' }}>Inactive</option>
        </select>

        <button type="submit">Lọc</button>
        <a href="{{ route('admin.users.index') }}">Reset</a>
    </form>

    <a href="{{ route('admin.users.create') }}" class="btn btn-primary">Thêm User</a>

    <!-- Table -->
    <table class="table">
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
                    @if($user->avatar)
                        <img src="{{ asset('storage/' . $user->avatar) }}" width="50">
                    @endif
                </td>
                <td>{{ $user->username }}</td>
                <td>{{ $user->full_name }}</td>
                <td>{{ $user->email }}</td>
                <td>{{ $user->role->name }}</td>
                <td>
                    <span class="badge {{ $user->status == 'active' ? 'bg-success' : 'bg-danger' }}">
                        {{ $user->status }}
                    </span>
                </td>
                <td>
                    <a href="{{ route('admin.users.edit', $user) }}">Sửa</a>

                    @if($user->status == 'active')
                        <form method="POST" action="{{ route('admin.users.lock', $user) }}" style="display:inline">
                            @csrf
                            @method('PATCH')
                            <button type="submit">Khóa</button>
                        </form>
                    @else
                        <form method="POST" action="{{ route('admin.users.unlock', $user) }}" style="display:inline">
                            @csrf
                            @method('PATCH')
                            <button type="submit">Mở khóa</button>
                        </form>
                    @endif

                    <form method="POST" action="{{ route('admin.users.destroy', $user) }}" style="display:inline">
                        @csrf
                        @method('DELETE')
                        <button type="submit" onclick="return confirm('Xác nhận xóa?')">Xóa</button>
                    </form>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>

    {{ $users->links() }}
</div>
@endsection
```

**Thêm mới (Create):**

```php
// UserController.php
public function create() {
    $roles = Role::all();
    return view('admin.users.create', compact('roles'));
}

public function store(Request $request) {
    $validated = $request->validate([
        'username' => 'required|unique:users',
        'password' => 'required|min:6|confirmed',
        'full_name' => 'required',
        'email' => 'required|email|unique:users',
        'phone' => 'nullable',
        'avatar' => 'nullable|image|max:2048',
        'role_id' => 'required|exists:roles,id',
        'status' => 'required|in:active,inactive',
    ]);

    if ($request->hasFile('avatar')) {
        $validated['avatar'] = $request->file('avatar')->store('avatars', 'public');
    }

    $validated['password'] = bcrypt($validated['password']);

    User::create($validated);

    return redirect()->route('admin.users.index')->with('success', 'Thêm user thành công!');
}
```

**Deliverables Buổi 3:**

-   ✅ Logout functionality
-   ✅ Admin template hoàn chỉnh
-   ✅ Users CRUD đầy đủ (List + Search/Filter + Create + Edit + Delete/Lock)
-   ✅ Upload avatar
-   ✅ Table với pagination

---

## ✏️ BUỔI 4: CRUD COMPLETION

**Yêu cầu thầy:**

-   Thiết kế và hiển thị kết quả
-   Sửa và xóa dữ liệu

**Áp dụng cho dự án Tact:**

**4.1 Hiển thị kết quả (Show/Detail)** ✅

```php
// UserController.php
public function show(User $user) {
    $user->load('role');
    return view('admin.users.show', compact('user'));
}
```

**4.2 Sửa dữ liệu (Edit/Update)** ✅

```php
// UserController.php
public function edit(User $user) {
    $roles = Role::all();
    return view('admin.users.edit', compact('user', 'roles'));
}

public function update(Request $request, User $user) {
    $validated = $request->validate([
        'username' => 'required|unique:users,username,' . $user->id,
        'password' => 'nullable|min:6|confirmed',
        'full_name' => 'required',
        'email' => 'required|email|unique:users,email,' . $user->id,
        'phone' => 'nullable',
        'avatar' => 'nullable|image|max:2048',
        'role_id' => 'required|exists:roles,id',
        'status' => 'required|in:active,inactive',
    ]);

    // Update avatar if uploaded
    if ($request->hasFile('avatar')) {
        // Delete old avatar
        if ($user->avatar) {
            Storage::disk('public')->delete($user->avatar);
        }
        $validated['avatar'] = $request->file('avatar')->store('avatars', 'public');
    }

    // Update password if provided
    if ($request->filled('password')) {
        $validated['password'] = bcrypt($validated['password']);
    } else {
        unset($validated['password']);
    }

    $user->update($validated);

    return redirect()->route('admin.users.index')->with('success', 'Cập nhật thành công!');
}
```

**4.3 Xóa dữ liệu (Delete)** ✅

```php
// UserController.php
public function destroy(User $user) {
    // Delete avatar
    if ($user->avatar) {
        Storage::disk('public')->delete($user->avatar);
    }

    $user->delete();

    return redirect()->route('admin.users.index')->with('success', 'Xóa thành công!');
}

// Khóa tài khoản (Soft delete alternative)
public function lock(User $user) {
    $user->update(['status' => 'inactive']);
    return back()->with('success', 'Đã khóa tài khoản!');
}

public function unlock(User $user) {
    $user->update(['status' => 'active']);
    return back()->with('success', 'Đã mở khóa tài khoản!');
}
```

**4.4 Áp dụng pattern cho modules khác** ✅

**Nhân bản CRUD pattern cho:**

-   Customers (tương tự Users)
-   Categories (đơn giản hơn: chỉ name, description)
-   Brands (name, origin, logo)
-   Suppliers (name, tax_code, phone, email, address)

**Deliverables Buổi 4:**

-   ✅ Edit/Update functionality
-   ✅ Delete functionality
-   ✅ Lock/Unlock accounts
-   ✅ Show/Detail pages
-   ✅ CRUD pattern template để nhân bản

---

## 🔄 BUỔI 5: CRUD REPLICATION

**Yêu cầu thầy:**

-   Tùy theo tình hình buổi 3 và 4 sẽ chỉnh sửa
-   Hoặc làm chức năng tương tự cho các trang trong hệ thống

**Áp dụng cho dự án Tact:**

**5.1 Hoàn thiện CRUD còn thiếu** ✅

**Customers CRUD:**

-   Copy pattern từ Users
-   Thêm fields: google_id, facebook_id, points, address, city
-   Không có password field nếu đăng ký bằng Google

**Categories CRUD:**

-   Đơn giản: name, description
-   Không có avatar

**Brands CRUD:**

-   Fields: name, origin, logo (upload)

**Suppliers CRUD:**

-   Fields: name, tax_code, phone, email, address

**5.2 Products CRUD (Phức tạp)** ✅

**Products có thêm:**

-   Upload ảnh (Image Intervention)
-   SKU generation
-   Product specs (relationship)
-   Soft delete (status field)

```php
// ProductController.php
public function store(Request $request) {
    $validated = $request->validate([
        'category_id' => 'required|exists:categories,id',
        'brand_id' => 'required|exists:brands,id',
        'sku' => 'required|unique:products',
        'name' => 'required',
        'price' => 'required|numeric',
        'cost' => 'nullable|numeric',
        'quantity' => 'required|integer',
        'image' => 'nullable|image',
        'warranty_months' => 'required|integer',
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
        $filename = time() . '.' . $image->extension();

        // Resize to 800x800
        $img = Image::make($image)->resize(800, 800, function($constraint) {
            $constraint->aspectRatio();
        });
        $img->save(storage_path('app/public/products/' . $filename));

        $validated['image'] = 'products/' . $filename;
    }

    // Create product
    $product = Product::create($validated);

    // Create product specs
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

**Deliverables Buổi 5:**

-   ✅ Customers CRUD
-   ✅ Categories CRUD
-   ✅ Brands CRUD
-   ✅ Suppliers CRUD
-   ✅ Products CRUD (với upload ảnh + specs)
-   ✅ 9/12 modules đã có CRUD

---

## 🏪 BUỔI 6: TRANG QUẢN TRỊ (ORDERS & INVENTORY)

**Yêu cầu thầy:**

-   Thực hiện trang quản trị

**Áp dụng cho dự án Tact:**

**6.1 Stock Movements (Quản lý kho)** ✅

**Nhập hàng:**

```php
// StockMovementController.php
public function create() {
    $products = Product::active()->get();
    $suppliers = Supplier::all();
    return view('admin.stock-movements.create', compact('products', 'suppliers'));
}

public function store(Request $request) {
    $validated = $request->validate([
        'product_id' => 'required|exists:products,id',
        'supplier_id' => 'required|exists:suppliers,id',
        'type' => 'required|in:in,out',
        'quantity' => 'required|integer|min:1',
        'note' => 'nullable',
    ]);

    DB::transaction(function() use ($validated) {
        // Create movement
        $movement = StockMovement::create([
            ...$validated,
            'user_id' => auth()->id(),
        ]);

        // Update product quantity
        $product = Product::find($validated['product_id']);
        if ($validated['type'] === 'in') {
            $product->increment('quantity', $validated['quantity']);
        } else {
            $product->decrement('quantity', $validated['quantity']);
        }
    });

    return redirect()->route('admin.stock-movements.index')->with('success', 'Nhập hàng thành công!');
}
```

**6.2 Promotions CRUD** ✅

```php
// PromotionController.php
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

**6.3 Orders Management (Quản lý đơn hàng)** ✅

**Danh sách đơn hàng:**

```php
// OrderController.php
public function index(Request $request) {
    $query = Order::with(['customer', 'user']);

    // Filter by status
    if ($request->has('order_status')) {
        $query->where('order_status', $request->order_status);
    }

    // Filter by source
    if ($request->has('source')) {
        $query->where('source', $request->source);
    }

    // Filter by date
    if ($request->has('date_from')) {
        $query->whereDate('created_at', '>=', $request->date_from);
    }

    $orders = $query->latest()->paginate(20);

    return view('admin.orders.index', compact('orders'));
}
```

**Chi tiết đơn hàng:**

```php
public function show(Order $order) {
    $order->load(['customer', 'user', 'items.product']);
    return view('admin.orders.show', compact('order'));
}
```

**Xử lý đơn hàng:**

```php
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
        'tracking_code' => $validated['tracking_code'],
        'shipping_carrier' => $validated['shipping_carrier'],
    ]);

    return back()->with('success', 'Đã chuyển sang trạng thái giao hàng!');
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

// Hủy đơn
public function cancel(Request $request, Order $order) {
    $order->update([
        'order_status' => 'cancelled',
        'note' => $request->reason,
    ]);

    return back()->with('success', 'Đã hủy đơn hàng!');
}
```

**Deliverables Buổi 6:**

-   ✅ Stock movements CRUD (nhập/xuất kho)
-   ✅ Promotions CRUD
-   ✅ Orders management (list, detail, actions)
-   ✅ Order status transitions (confirm, ship, complete, cancel)
-   ✅ 12/12 modules đã có CRUD

---

## 📊 BUỔI 7: THỐNG KÊ DỮ LIỆU

**Yêu cầu thầy:**

-   Thực hiện thống kê dữ liệu

**Áp dụng cho dự án Tact:**

**7.1 Dashboard** ✅

```php
// DashboardController.php
public function index() {
    // Cards thống kê
    $todayRevenue = Order::whereDate('created_at', today())
        ->where('order_status', 'completed')
        ->sum('total_money');

    $pendingOrders = Order::where('order_status', 'pending')->count();

    $lowStockProducts = Product::where('quantity', '<', 5)->count();

    $newCustomers = Customer::whereDate('created_at', today())->count();

    // Doanh thu theo tháng (6 tháng gần nhất)
    $monthlyRevenue = Order::where('order_status', 'completed')
        ->selectRaw('MONTH(created_at) as month, SUM(total_money) as revenue')
        ->whereYear('created_at', date('Y'))
        ->groupBy('month')
        ->orderBy('month')
        ->get();

    // Đơn hàng cần xử lý
    $pendingOrdersList = Order::where('order_status', 'pending')
        ->with('customer')
        ->latest()
        ->take(10)
        ->get();

    return view('admin.dashboard', compact(
        'todayRevenue',
        'pendingOrders',
        'lowStockProducts',
        'newCustomers',
        'monthlyRevenue',
        'pendingOrdersList'
    ));
}
```

**Dashboard View với Chart.js:**

```blade
@extends('layouts.admin')

@section('content')
<div class="container">
    <h1>Dashboard</h1>

    <!-- Cards -->
    <div class="row">
        <div class="col-md-3">
            <div class="card">
                <div class="card-body">
                    <h5>Doanh thu hôm nay</h5>
                    <h2>{{ number_format($todayRevenue) }}đ</h2>
                </div>
            </div>
        </div>

        <div class="col-md-3">
            <div class="card">
                <div class="card-body">
                    <h5>Đơn hàng mới</h5>
                    <h2>{{ $pendingOrders }}</h2>
                </div>
            </div>
        </div>

        <div class="col-md-3">
            <div class="card">
                <div class="card-body">
                    <h5>Sản phẩm sắp hết</h5>
                    <h2>{{ $lowStockProducts }}</h2>
                </div>
            </div>
        </div>

        <div class="col-md-3">
            <div class="card">
                <div class="card-body">
                    <h5>Khách hàng mới</h5>
                    <h2>{{ $newCustomers }}</h2>
                </div>
            </div>
        </div>
    </div>

    <!-- Chart -->
    <div class="card mt-4">
        <div class="card-body">
            <h5>Doanh thu theo tháng</h5>
            <canvas id="revenueChart"></canvas>
        </div>
    </div>

    <!-- Pending Orders -->
    <div class="card mt-4">
        <div class="card-body">
            <h5>Đơn hàng cần xử lý</h5>
            <table class="table">
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
                            <a href="{{ route('admin.orders.show', $order) }}">Xem</a>
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
const ctx = document.getElementById('revenueChart');
new Chart(ctx, {
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
        scales: {
            y: {
                beginAtZero: true
            }
        }
    }
});
</script>
@endpush
@endsection
```

**7.2 Reports** ✅

**Báo cáo Doanh thu:**

```php
// ReportController.php
public function revenue(Request $request) {
    $query = Order::where('order_status', 'completed');

    if ($request->has('date_from')) {
        $query->whereDate('created_at', '>=', $request->date_from);
    }

    if ($request->has('date_to')) {
        $query->whereDate('created_at', '<=', $request->date_to);
    }

    $totalRevenue = $query->sum('total_money');
    $totalOrders = $query->count();
    $avgOrderValue = $totalOrders > 0 ? $totalRevenue / $totalOrders : 0;

    // Group by date
    $dailyRevenue = $query->selectRaw('DATE(created_at) as date, SUM(total_money) as revenue, COUNT(*) as orders')
        ->groupBy('date')
        ->orderBy('date')
        ->get();

    return view('admin.reports.revenue', compact('totalRevenue', 'totalOrders', 'avgOrderValue', 'dailyRevenue'));
}
```

**Báo cáo Sản phẩm:**

```php
public function products() {
    // Top bán chạy
    $topProducts = DB::table('order_items')
        ->join('products', 'order_items.product_id', '=', 'products.id')
        ->join('orders', 'order_items.order_id', '=', 'orders.id')
        ->where('orders.order_status', 'completed')
        ->select('products.name', DB::raw('SUM(order_items.quantity) as total_sold'))
        ->groupBy('products.id', 'products.name')
        ->orderByDesc('total_sold')
        ->limit(10)
        ->get();

    // Sắp hết hàng
    $lowStockProducts = Product::where('quantity', '<', 5)
        ->where('status', 'active')
        ->get();

    return view('admin.reports.products', compact('topProducts', 'lowStockProducts'));
}
```

**Báo cáo Kho:**

```php
public function inventory(Request $request) {
    $query = StockMovement::with(['product', 'supplier', 'user']);

    if ($request->has('type')) {
        $query->where('type', $request->type);
    }

    if ($request->has('date_from')) {
        $query->whereDate('created_at', '>=', $request->date_from);
    }

    $movements = $query->latest()->paginate(50);

    return view('admin.reports.inventory', compact('movements'));
}
```

**Deliverables Buổi 7:**

-   ✅ Dashboard với cards + chart
-   ✅ Báo cáo doanh thu (theo ngày/tháng)
-   ✅ Báo cáo sản phẩm (top bán chạy, sắp hết hàng)
-   ✅ Báo cáo kho (lịch sử nhập xuất)

---

## 🐛 BUỔI 8: CHỈNH SỬA & KHẮC PHỤC

**Yêu cầu thầy:**

-   Chỉnh sửa và khắc phục kết quả

**Áp dụng cho dự án Tact:**

**8.1 Bug Fixes** ✅

**Common bugs to fix:**

-   Validation errors không hiển thị đúng
-   Upload ảnh lỗi permissions
-   Foreign key constraints khi xóa
-   Session timeout issues
-   CSRF token mismatch
-   Pagination không hoạt động
-   Search/filter không chính xác

**8.2 UI/UX Polish** ✅

-   Responsive design (mobile-friendly)
-   Loading states
-   Toast notifications (SweetAlert2)
-   Confirm dialogs trước khi xóa
-   Error messages tiếng Việt
-   Success messages
-   Breadcrumbs
-   Active menu states

**8.3 Performance Optimization** ✅

```bash
# Cache config
php artisan config:cache
php artisan route:cache
php artisan view:cache

# Optimize autoloader
composer dump-autoload --optimize

# Asset compilation
npm run build
```

**8.4 Security Checklist** ✅

-   [ ] CSRF protection enabled
-   [ ] SQL injection prevention (Eloquent)
-   [ ] XSS prevention (Blade escaping)
-   [ ] Password hashing (bcrypt)
-   [ ] File upload validation
-   [ ] Rate limiting on login
-   [ ] HTTPS (production)
-   [ ] .env not in git

**8.5 Testing** ✅

**Manual testing checklist:**

-   [ ] Đăng ký (thủ công + Google)
-   [ ] Đăng nhập (thủ công + Google)
-   [ ] Đăng xuất
-   [ ] CRUD cho 12 modules
-   [ ] Upload ảnh (users, products)
-   [ ] Search/filter
-   [ ] Pagination
-   [ ] Nhập hàng (stock in)
-   [ ] Đặt hàng (web)
-   [ ] Xử lý đơn hàng (admin)
-   [ ] Áp dụng khuyến mãi
-   [ ] Tích điểm
-   [ ] Dashboard
-   [ ] Reports
-   [ ] Role-based access

**8.6 Code Cleanup** ✅

-   Remove unused code
-   Format code (Laravel Pint)
-   Add comments
-   Remove debug statements (dd(), dump())
-   Clean up routes
-   Organize views

**Deliverables Buổi 8:**

-   ✅ All bugs fixed
-   ✅ UI/UX polished
-   ✅ Performance optimized
-   ✅ Security checklist completed
-   ✅ Manual testing done
-   ✅ Code cleaned up
-   ✅ Ready for demo

---

## 📄 TUẦN 1-2 SAU MÔN: BÁO CÁO & THUYẾT TRÌNH

**Yêu cầu thầy:**

-   Hoàn thiện file Word (báo cáo)
-   File thuyết trình

**Áp dụng cho dự án Tact:**

### **File Word - Báo cáo**

**Cấu trúc báo cáo:**

**CHƯƠNG 1: TỔNG QUAN**
1.1 Lý do chọn đề tài

-   Nhu cầu quản lý cửa hàng điện thoại
-   Xu hướng O2O (Online to Offline)
-   Tự động hóa quy trình bán hàng

    1.2 Mục tiêu đề tài

-   Xây dựng website quản lý toàn diện
-   Hỗ trợ bán hàng online và tại quầy
-   Quản lý kho, khách hàng, báo cáo

    1.3 Đối tượng và phạm vi

-   Đối tượng: Cửa hàng điện thoại vừa và nhỏ
-   Phạm vi: Quản lý sản phẩm, đơn hàng, kho, khách hàng, báo cáo

    1.4 Phương pháp nghiên cứu

-   Nghiên cứu tài liệu
-   Phân tích yêu cầu
-   Thiết kế và triển khai
-   Kiểm thử

**CHƯƠNG 2: CƠ SỞ LÝ THUYẾT**
2.1 Công nghệ sử dụng

-   Laravel 12 Framework
-   MySQL Database
-   Tailwind CSS + DaisyUI
-   Google OAuth API

    2.2 Mô hình MVC

-   Model: Eloquent ORM
-   View: Blade Templates
-   Controller: Request handling

    2.3 Mô hình O2O

-   Online: Website bán hàng
-   Offline: POS tại quầy
-   Tích hợp thống nhất

**CHƯƠNG 3: PHÂN TÍCH & THIẾT KẾ**
3.1 Phân tích yêu cầu

-   Chức năng chính
-   Người dùng hệ thống
-   Use case diagram

    3.2 Thiết kế cơ sở dữ liệu

-   ERD (Entity Relationship Diagram) - 12 bảng
-   Mô tả các bảng và quan hệ
-   Script SQL

    3.3 Thiết kế giao diện

-   Wireframes
-   Mockups
-   User flow

**CHƯƠNG 4: TRIỂN KHAI**
4.1 Môi trường phát triển

-   XAMPP/Laragon
-   Composer, NPM
-   VS Code

    4.2 Cài đặt và cấu hình

-   Laravel installation
-   Database setup
-   Package installation

    4.3 Triển khai chức năng

-   Authentication (thủ công + Google)
-   CRUD modules (12 modules)
-   Orders management (O2O)
-   Reports & Analytics

    4.4 Screenshots

-   Chụp ảnh màn hình từng chức năng
-   Mô tả chi tiết

**CHƯƠNG 5: KIỂM THỬ**
5.1 Kế hoạch kiểm thử

-   Test cases
-   Test data

    5.2 Kết quả kiểm thử

-   Bảng test cases với kết quả
-   Bugs found & fixed

    5.3 Đánh giá

-   Ưu điểm
-   Nhược điểm
-   Hướng phát triển

**CHƯƠNG 6: KẾT LUẬN**
6.1 Kết quả đạt được

-   Hoàn thành 12 CRUD modules
-   O2O functionality
-   Reports & Analytics

    6.2 Hạn chế

-   Chưa có mobile app
-   Chưa có payment gateway
-   Chưa có email notifications

    6.3 Hướng phát triển

-   Tích hợp payment gateway
-   Mobile app
-   Advanced analytics
-   Multi-store support

**TÀI LIỆU THAM KHẢO**

-   Laravel Documentation
-   MySQL Documentation
-   Google OAuth Documentation
-   Các tài liệu khác

**PHỤ LỤC**

-   Source code quan trọng
-   Database script
-   User manual

---

### **File PowerPoint - Thuyết trình**

**Slide Structure (15-20 slides):**

**Slide 1: Trang bìa**

-   Tên đề tài
-   Nhóm thực hiện
-   Giảng viên hướng dẫn

**Slide 2-3: Giới thiệu**

-   Lý do chọn đề tài
-   Mục tiêu
-   Phạm vi

**Slide 4-5: Công nghệ**

-   Laravel 12
-   MySQL
-   Tailwind CSS + DaisyUI
-   Google OAuth

**Slide 6-7: Thiết kế Database**

-   ERD diagram (12 bảng)
-   Mô tả quan hệ

**Slide 8-10: Chức năng chính**

-   Authentication (screenshot)
-   CRUD modules (screenshot)
-   O2O Orders (screenshot)
-   Reports (screenshot với charts)

**Slide 11-12: Demo**

-   Video demo hoặc live demo
-   Các tính năng nổi bật

**Slide 13: Kiểm thử**

-   Test cases summary
-   Kết quả

**Slide 14: Kết luận**

-   Kết quả đạt được
-   Ưu điểm
-   Hạn chế

**Slide 15: Hướng phát triển**

-   Payment gateway
-   Mobile app
-   Advanced features

**Slide 16: Q&A**

-   Câu hỏi và trả lời

---

## 🎯 TỔNG KẾT ROADMAP THEO THẦY

### **Timeline Overview:**

**8 Buổi học (32 giờ):**

-   Buổi 1: Foundation (DB + UI + Auth options)
-   Buổi 2: Authentication (Register + Login)
-   Buổi 3: CRUD Foundation (Logout + Template + Users CRUD)
-   Buổi 4: CRUD Completion (Edit + Delete)
-   Buổi 5: CRUD Replication (Customers, Categories, Brands, Suppliers, Products)
-   Buổi 6: Admin Pages (Stock, Promotions, Orders)
-   Buổi 7: Reports & Analytics (Dashboard + 3 reports)
-   Buổi 8: Bug Fixes + Polish

**2 Tuần sau môn:**

-   Tuần 1: Viết báo cáo Word (6 chương)
-   Tuần 2: Làm slide thuyết trình + chuẩn bị demo

### **Deliverables:**

✅ Website hoàn chỉnh với 12 CRUD modules  
✅ Authentication (thủ công + Google OAuth)  
✅ O2O functionality (Web orders + POS)  
✅ Reports & Analytics với charts  
✅ Báo cáo Word đầy đủ  
✅ Slide thuyết trình  
✅ Demo video/live

---

## 📊 SO SÁNH: ROADMAP THẦY vs ROADMAP TỐI ƯU

**Roadmap của thầy:**

-   ✅ Phù hợp với tiến độ học
-   ✅ Từng bước, dễ theo dõi
-   ✅ Focus vào CRUD trước
-   ⚠️ Chưa có POS (bán tại quầy)
-   ⚠️ Chưa có customer shopping flow

**Roadmap tối ưu (từ brainstorming):**

-   ✅ Comprehensive (đầy đủ features)
-   ✅ Có POS + Customer shopping
-   ✅ Technical best practices
-   ⚠️ Phức tạp hơn
-   ⚠️ Cần nhiều thời gian hơn

**Recommendation:**

-   **Follow roadmap của thầy** cho đúng tiến độ
-   **Thêm features từ roadmap tối ưu** nếu còn thời gian:
    -   Customer shopping flow (Buổi 6)
    -   POS interface (Buổi 6-7)
    -   Advanced reports (Buổi 7)

---
