# Project Context: Tact - Phone Retail O2O Management System

**Last Updated:** 2025-12-14
**Project:** Tact - Quản lý cửa hàng điện thoại O2O
**Architecture:** docs/architecture.md

---

## Critical Project Rules

### 1. Technology Stack (MUST USE EXACT VERSIONS)

```json
{
    "backend": {
        "framework": "Laravel 12.0",
        "php": "^8.2",
        "database": "MySQL"
    },
    "frontend": {
        "css": "Tailwind CSS 4.0.0",
        "components": "DaisyUI 5.5.13",
        "build": "Vite 7.0.7"
    },
    "authentication": {
        "staff": "Laravel Breeze (blade)",
        "customer": "Laravel Socialite (Google OAuth)"
    }
}
```

### 2. Database Schema (NEVER MODIFY WITHOUT UPDATING db.sql)

**12 Tables (Exact Names):**

-   `roles`, `users`, `customers`, `categories`, `brands`, `suppliers`
-   `products`, `product_specs`, `stock_movements`, `promotions`
-   `orders`, `order_items`

**2 Triggers (MUST KEEP):**

-   `update_stock`: Auto update products.quantity on stock_movements insert
-   `add_points`: Auto add customer points when order status = 'completed'

**Critical Fields:**

-   `order_items.imei_list`: TEXT field storing JSON array of IMEI numbers
-   `customers.google_id`: VARCHAR(50) for Google OAuth
-   `products.sku`: VARCHAR(50) UNIQUE for barcode scanning
-   `orders.source`: ENUM('web', 'store') for O2O tracking

### 3. Naming Conventions (STRICTLY ENFORCE)

**Database:**

```php
// Tables: plural, lowercase, snake_case
'users', 'products', 'order_items'

// Columns: snake_case
'full_name', 'created_at', 'order_status'

// Foreign keys: {table}_id
'user_id', 'product_id', 'category_id'
```

**PHP:**

```php
// Classes: PascalCase
ProductController, OrderService, CustomerRepository

// Methods: camelCase
getUserData(), createOrder(), calculatePoints()

// Variables: camelCase
$userId, $productData, $orderTotal

// Constants: UPPER_SNAKE_CASE
MAX_QUANTITY, API_BASE_URL
```

**Blade:**

```blade
{{-- Files: kebab-case --}}
product-card.blade.php, order-timeline.blade.php

{{-- Components: kebab-case --}}
<x-product-card />
<x-admin.data-table />
```

**Routes:**

```php
// URLs: kebab-case, plural nouns
/products, /order-items, /stock-movements

// Parameters: camelCase
{id}, {productId}, {orderId}
```

### 4. Response Format (ALWAYS USE THIS)

**Success:**

```json
{
  "success": true,
  "data": { ... },
  "message": "Vietnamese message here"
}
```

**Error:**

```json
{
    "success": false,
    "message": "Vietnamese error message",
    "errors": {
        "field": ["Validation error in Vietnamese"]
    }
}
```

**HTTP Status Codes:**

-   200: Success
-   422: Validation error
-   500: Server error

### 5. Authentication & Authorization

**Two Separate Guards:**

```php
// Staff (Admin, Manager, Sales, Warehouse)
Guard: 'web' (default Laravel session)
Table: 'users'
Login: /admin/login
Middleware: auth, role:{role_name}

// Customer
Guard: 'customer' (custom guard)
Table: 'customers'
Login: /login, /auth/google/callback
Middleware: customer.auth
```

**4 Roles (EXACT PERMISSIONS):**

```php
'Admin'     => 'Full access to everything'
'Manager'   => 'All except user management'
'Sales'     => 'POS, orders, customers (read-only products)'
'Warehouse' => 'Stock management, products (read-only)'
```

### 6. Project Structure (MUST FOLLOW)

```
app/
├── Http/Controllers/
│   ├── Admin/          # Admin features
│   ├── Customer/       # Customer features
│   └── Auth/           # Authentication
├── Models/             # Eloquent models (12 models)
├── Services/           # Business logic
├── Repositories/       # Complex queries
├── Observers/          # Model lifecycle hooks
└── Policies/           # Authorization

resources/views/
├── layouts/
│   ├── customer.blade.php  # Nike-inspired
│   ├── admin.blade.php     # DaisyUI
│   └── guest.blade.php
├── components/
│   ├── customer/       # Customer components
│   ├── admin/          # Admin components
│   └── shared/         # Shared components
├── customer/           # Customer pages
└── admin/              # Admin pages
```

### 7. Critical Business Rules

**IMEI Tracking:**

```php
// MUST store IMEI for every phone sold
// Format: JSON array in order_items.imei_list
["123456789012345", "123456789012346"]

// Validation: Exactly 15 digits
preg_match('/^\d{15}$/', $imei)

// Display on invoice and order detail
```

**Stock Management:**

```php
// ALWAYS use database trigger for stock updates
// Trigger: update_stock (automatic on stock_movements insert)
// DO NOT manually update products.quantity

// Low stock alert: quantity < 5
// Dead stock alert: no sales > 30 days
```

**Loyalty Points:**

```php
// Auto-calculate via trigger: add_points
// Formula: floor(total_money / 100000) points
// Example: 25,000,000 VND = 250 points

// Trigger fires when: order_status = 'completed'
// DO NOT manually calculate points
```

**Voucher Validation:**

```php
// Check: code exists, status active, dates valid
// Check: min_order <= order total
// Apply: type='fixed' ? subtract value : subtract (total * value / 100)
// Respect: max_discount if type='percent'
```

### 8. Performance Requirements (MUST MEET)

```php
// Page load: < 2 seconds
// POS response: < 1 second
// Database queries: < 100ms
// Animations: 60fps (CSS-only)

// ALWAYS use:
- Eager loading: Product::with(['category', 'brand'])
- Cache: Cache::tags(['products'])->remember()
- Lazy loading: <img loading="lazy">
- WebP images: product images in WebP format
```

### 9. Security Rules (NON-NEGOTIABLE)

```php
// ALWAYS validate input
use App\Http\Requests\StoreProductRequest;
public function store(StoreProductRequest $request) { ... }

// ALWAYS authorize actions
$this->authorize('update', $product);

// ALWAYS use transactions for critical operations
DB::beginTransaction();
try {
    // ... operations
    DB::commit();
} catch (\Exception $e) {
    DB::rollBack();
    throw $e;
}

// NEVER expose sensitive data
Log::error('Error: ' . $e->getMessage()); // OK
return response()->json(['message' => 'Có lỗi xảy ra']); // OK
return response()->json(['error' => $e->getMessage()]); // NEVER!
```

### 10. Testing Requirements

```php
// Feature tests: Mirror controller structure
tests/Feature/Admin/ProductManagementTest.php
tests/Feature/Customer/CheckoutTest.php

// Unit tests: Mirror service structure
tests/Unit/Services/OrderServiceTest.php
tests/Unit/Services/PointsServiceTest.php

// ALWAYS test:
- CRUD operations
- Business logic (vouchers, points, stock)
- Authorization (role-based access)
- Validation rules
```

---

## Anti-Patterns (NEVER DO THIS)

### ❌ Raw SQL in Controllers

```php
// BAD
$products = DB::select('SELECT * FROM products WHERE category_id = ?', [$id]);

// GOOD
$products = Product::where('category_id', $id)->get();
```

### ❌ N+1 Query Problem

```php
// BAD
$orders = Order::all();
foreach ($orders as $order) {
    echo $order->customer->name; // N+1 queries!
}

// GOOD
$orders = Order::with('customer')->get();
foreach ($orders as $order) {
    echo $order->customer->name; // 2 queries total
}
```

### ❌ Missing Validation

```php
// BAD
public function store(Request $request) {
    Product::create($request->all()); // No validation!
}

// GOOD
public function store(StoreProductRequest $request) {
    Product::create($request->validated());
}
```

### ❌ Hardcoded Values

```php
// BAD
$points = floor($total / 100000); // Magic number

// GOOD
$points = floor($total / config('tact.points_per_100k', 100000));
```

### ❌ Inconsistent Response Format

```php
// BAD
return ['data' => $product]; // Missing success, message

// GOOD
return response()->json([
    'success' => true,
    'data' => $product,
    'message' => 'Lấy sản phẩm thành công'
]);
```

### ❌ Manual Stock Updates

```php
// BAD
$product->quantity -= $quantity;
$product->save();

// GOOD
StockMovement::create([
    'product_id' => $product->id,
    'type' => 'out',
    'quantity' => $quantity
]);
// Trigger automatically updates product.quantity
```

---

## Quick Reference

### Common Commands

```bash
# Development
composer dev              # Start all servers (Laravel + Vite + Queue + Logs)
npm run dev              # Vite dev server only
php artisan serve        # Laravel server only

# Database
php artisan migrate:fresh --seed  # Reset database with seeders
php artisan db:seed              # Run seeders only

# Testing
composer test            # Run all tests
php artisan test --filter ProductTest  # Run specific test

# Code Quality
./vendor/bin/pint        # Format code (Laravel Pint)
```

### Important Files

```
database/db.sql          # Reference schema (DO NOT MODIFY without migrations)
config/tact.php          # Custom app configuration
docs/architecture.md     # Complete architecture document
```

### Key Relationships

```php
// Product
Product::belongsTo(Category::class)
Product::belongsTo(Brand::class)
Product::hasOne(ProductSpec::class)

// Order
Order::belongsTo(Customer::class)
Order::belongsTo(User::class) // Sales staff
Order::hasMany(OrderItem::class)

// OrderItem
OrderItem::belongsTo(Order::class)
OrderItem::belongsTo(Product::class)
```

### Vietnamese Messages

```php
// Success messages
'Tạo thành công', 'Cập nhật thành công', 'Xóa thành công'

// Error messages
'Có lỗi xảy ra, vui lòng thử lại'
'Dữ liệu không hợp lệ'
'Không tìm thấy dữ liệu'
'Bạn không có quyền thực hiện thao tác này'
```

---

## Implementation Checklist

**Before implementing any feature:**

-   [ ] Read relevant section in docs/architecture.md
-   [ ] Check database schema in database/db.sql
-   [ ] Follow naming conventions exactly
-   [ ] Use correct response format
-   [ ] Implement validation (Form Request)
-   [ ] Add authorization checks (Gates/Policies)
-   [ ] Use eager loading (prevent N+1)
-   [ ] Add error handling (try-catch with transactions)
-   [ ] Write tests (Feature + Unit)
-   [ ] Use Vietnamese messages

**Code Review Checklist:**

-   [ ] Naming conventions followed
-   [ ] Response format consistent
-   [ ] Validation implemented
-   [ ] Authorization checked
-   [ ] No N+1 queries
-   [ ] Error handling present
-   [ ] Tests written
-   [ ] Vietnamese messages used

---

**For AI Agents:** This file contains critical rules that MUST be followed for every implementation. When in doubt, refer to docs/architecture.md for complete details.

**Last Architecture Update:** 2025-12-14
**Architecture Status:** ✅ READY FOR IMPLEMENTATION
