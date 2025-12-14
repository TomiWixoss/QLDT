# Project Context: Tact - Phone Retail O2O Management System

**Last Updated:** 2025-12-14
**Project:** Tact - Quản lý cửa hàng điện thoại O2O
**Status:** ✅ READY FOR IMPLEMENTATION (Story 1.1 DONE)

---

## 🎯 Project Overview

**Tact** là website quản lý cửa hàng điện thoại với mô hình O2O (Online-to-Offline), được xây dựng trên Laravel 12, Tailwind CSS 4, và DaisyUI 5.

### Vấn đề giải quyết

-   **Trải nghiệm O2O không liền mạch**: 65% khách hàng có hành vi ROPO nhưng thông tin không đồng bộ
-   **Quản lý tồn kho phức tạp**: Điện thoại là high-value items cần IMEI-level tracking
-   **Quy trình bán hàng chậm**: Thiếu POS tối ưu, không có voucher/điểm thống nhất
-   **Thiếu minh bạch**: 85% khách hàng lo ngại hàng giả

### Giải pháp

-   **O2O Integration**: Khách đặt online hoặc nhân viên bán tại quầy (POS), thống nhất trong một hệ thống
-   **IMEI Tracking**: Lưu IMEI trong order_items để track từng máy cụ thể
-   **Voucher & Loyalty Points**: Hệ thống giảm giá và tích điểm tự động, dùng được cả online và offline
-   **Smart Inventory**: Cảnh báo tồn kho thấp, sản phẩm chậm bán, triggers tự động

---

## 🛠️ Technology Stack (EXACT VERSIONS - MUST USE)

```json
{
    "backend": {
        "framework": "Laravel 12.0",
        "php": "^8.2",
        "database": "MySQL 8.0+"
    },
    "frontend": {
        "css": "Tailwind CSS 4.0.0",
        "components": "DaisyUI 5.5.13",
        "build": "Vite 7.0.7",
        "font": "Inter Variable Font"
    },
    "authentication": {
        "staff": "Laravel Breeze (blade)",
        "customer": "Laravel Socialite (Google OAuth)"
    }
}
```

---

## 📊 Database Schema (12 Tables + 2 Triggers)

### Tables (EXACT NAMES - NEVER MODIFY WITHOUT UPDATING db.sql)

```
roles, users, customers, categories, brands, suppliers,
products, product_specs, stock_movements, promotions,
orders, order_items
```

### 2 Database Triggers (CRITICAL)

```sql
-- Trigger 1: Auto update stock on stock_movements insert
update_stock: IF type='in' THEN quantity + NEW.quantity
              IF type='out' THEN quantity - NEW.quantity

-- Trigger 2: Auto add points when order completed
add_points: IF NEW.status='completed' THEN
            points + FLOOR(total_money / 100000)
```

### Critical Fields

-   `order_items.imei_list`: TEXT field storing JSON array of IMEI numbers
-   `customers.google_id`: VARCHAR(50) for Google OAuth
-   `products.sku`: VARCHAR(50) UNIQUE for barcode scanning
-   `orders.source`: ENUM('web', 'store') for O2O tracking

---

## 👥 User Roles & Permissions (4 Roles)

| Role          | Permissions                                 |
| ------------- | ------------------------------------------- |
| **Admin**     | Full access to everything                   |
| **Manager**   | All except user management                  |
| **Sales**     | POS, orders, customers (read-only products) |
| **Warehouse** | Stock management, products (read-only)      |

### Authentication Guards

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

---

## 📁 Project Structure (MUST FOLLOW)

```
app/
├── Http/Controllers/
│   ├── Admin/          # Admin features (ProductController, OrderController, etc.)
│   ├── Customer/       # Customer features (HomeController, CartController, etc.)
│   └── Auth/           # Authentication (LoginController, GoogleAuthController)
├── Models/             # Eloquent models (12 models matching 12 tables)
├── Services/           # Business logic (OrderService, CartService, InventoryService, etc.)
├── Repositories/       # Complex queries (ProductRepository, OrderRepository)
├── Observers/          # Model lifecycle hooks
└── Policies/           # Authorization

resources/views/
├── layouts/
│   ├── customer.blade.php  # Nike-inspired design
│   ├── admin.blade.php     # DaisyUI functional
│   └── guest.blade.php
├── components/
│   ├── customer/       # product-card, cart-item, order-timeline
│   ├── admin/          # data-table, stat-card, sidebar
│   └── shared/         # alert, modal, button
├── customer/           # Customer pages
└── admin/              # Admin pages
```

---

## 🎨 Design System (Hybrid Sophisticated - Direction 6)

### Design Philosophy

-   **Nike-inspired**: Generous whitespace, clean layouts
-   **Apple-inspired**: Premium typography (Inter Variable Font)
-   **Stripe-inspired**: Smooth micro-interactions
-   **Mobile-first**: 375px base breakpoint, bottom navigation

### Color Palette

```css
/* Brand Colors */
--tact-blue: #3b82f6; /* Primary actions, trust signals */
--tact-gray: #6b7280; /* Neutral, text, borders */
--tact-orange: #f97316; /* Accent, urgency */

/* Semantic Colors */
--success: #10b981; /* Warranty, chính hãng, completed */
--warning: #f59e0b; /* Low stock, pending */
--error: #ef4444; /* Out of stock, errors */
--info: #3b82f6; /* Information, tips */
```

### Trust Signals (CRITICAL FOR PHONE RETAIL)

-   **IMEI Badge**: Green badge on product cards, prominent
-   **Warranty Info**: "Bảo hành X tháng chính hãng"
-   **Trust Section**: 3 icons (IMEI, Warranty, Delivery)
-   **Stock Indicators**: Red (< 5), Yellow (5-10), Green (> 10)

---

## 📝 Naming Conventions (STRICTLY ENFORCE)

### Database

```php
// Tables: plural, lowercase, snake_case
'users', 'products', 'order_items'

// Columns: snake_case
'full_name', 'created_at', 'order_status'

// Foreign keys: {table}_id
'user_id', 'product_id', 'category_id'
```

### PHP

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

### Blade

```blade
{{-- Files: kebab-case --}}
product-card.blade.php, order-timeline.blade.php

{{-- Components: kebab-case --}}
<x-product-card />
<x-admin.data-table />
```

### Routes

```php
// URLs: kebab-case, plural nouns
/products, /order-items, /stock-movements

// Parameters: camelCase
{id}, {productId}, {orderId}
```

---

## 📤 Response Format (ALWAYS USE THIS)

### Success Response

```json
{
  "success": true,
  "data": { ... },
  "message": "Vietnamese message here"
}
```

### Error Response

```json
{
    "success": false,
    "message": "Vietnamese error message",
    "errors": {
        "field": ["Validation error in Vietnamese"]
    }
}
```

### HTTP Status Codes

-   200: Success
-   422: Validation error
-   500: Server error

---

## ⚡ Performance Requirements (MUST MEET)

| Metric           | Target           |
| ---------------- | ---------------- |
| Page load        | < 2 seconds      |
| POS response     | < 1 second       |
| Database queries | < 100ms          |
| Animations       | 60fps (CSS-only) |
| Image size       | < 200KB (WebP)   |

### Optimization Strategies

-   **Eager loading**: `Product::with(['category', 'brand'])`
-   **Cache**: `Cache::tags(['products'])->remember()`
-   **Lazy loading**: `<img loading="lazy">`
-   **WebP images**: All product images in WebP format
-   **Code splitting**: Vite automatic

---

## 🔒 Security Rules (NON-NEGOTIABLE)

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

---

## 🏪 Critical Business Rules

### IMEI Tracking

```php
// MUST store IMEI for every phone sold
// Format: JSON array in order_items.imei_list
["123456789012345", "123456789012346"]

// Validation: Exactly 15 digits
preg_match('/^\d{15}$/', $imei)

// Display on invoice and order detail
```

### Stock Management

```php
// ALWAYS use database trigger for stock updates
// Trigger: update_stock (automatic on stock_movements insert)
// DO NOT manually update products.quantity

// Low stock alert: quantity < 5
// Dead stock alert: no sales > 30 days
```

### Loyalty Points

```php
// Auto-calculate via trigger: add_points
// Formula: floor(total_money / 100000) points
// Example: 25,000,000 VND = 250 points

// Trigger fires when: order_status = 'completed'
// DO NOT manually calculate points
```

### Voucher Validation

```php
// Check: code exists, status active, dates valid
// Check: min_order <= order total
// Apply: type='fixed' ? subtract value : subtract (total * value / 100)
// Respect: max_discount if type='percent'
```

---

## ❌ Anti-Patterns (NEVER DO THIS)

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

## 📋 Implementation Status

### Current Sprint Status

-   **Epic 1**: in-progress
-   **Story 1.1**: ✅ DONE (Project Setup & Database Schema)
-   **Story 1.2-1.8**: backlog

### 10 Epics Overview

1. **Epic 1**: Project Foundation & Authentication (8 stories)
2. **Epic 2**: Master Data Management (3 stories)
3. **Epic 3**: Product Management (5 stories)
4. **Epic 4**: Product Discovery & Browsing (7 stories)
5. **Epic 5**: Shopping Cart & Checkout (5 stories)
6. **Epic 6**: Promotion & Loyalty System (4 stories)
7. **Epic 7**: Order Management (5 stories)
8. **Epic 8**: Point of Sale System (6 stories)
9. **Epic 9**: Inventory Management (5 stories)
10. **Epic 10**: Dashboard, Reports & Customer Management (5 stories)

**Total: 47 stories covering 139 FRs**

---

## 📚 Key Documents Reference

| Document                                     | Purpose                                  |
| -------------------------------------------- | ---------------------------------------- |
| `docs/1-planning/prd.md`                     | Product Requirements (139 FRs + 76 NFRs) |
| `docs/2-solutioning/architecture.md`         | Complete technical architecture          |
| `docs/2-solutioning/epics.md`                | Epic breakdown with stories              |
| `docs/1-planning/ux-design-specification.md` | UX design guidelines                     |
| `database/db.sql`                            | Reference database schema                |
| `docs/3-implementation/sprint-status.yaml`   | Sprint tracking                          |

### Week 1 Priority Documents

| Document                                                  | Purpose                      |
| --------------------------------------------------------- | ---------------------------- |
| `docs/2-solutioning/ux-implementation-priorities.md`      | Core UX vs Polish UX         |
| `docs/2-solutioning/offline-pos-design.md`                | Offline POS architecture     |
| `docs/2-solutioning/image-optimization-sla.md`            | Image optimization standards |
| `docs/2-solutioning/database-trigger-performance-plan.md` | Trigger performance testing  |

---

## ✅ Implementation Checklist

**Before implementing any feature:**

-   [ ] Read relevant section in docs/2-solutioning/architecture.md
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

## 🚀 Quick Commands

```bash
# Development
composer dev              # Start all servers
npm run dev              # Vite dev server only
php artisan serve        # Laravel server only

# Database
php artisan migrate:fresh --seed  # Reset database
php artisan db:seed              # Run seeders only

# Testing
composer test            # Run all tests
php artisan test --filter ProductTest  # Run specific test

# Code Quality
./vendor/bin/pint        # Format code (Laravel Pint)
```

---

## 🔑 Key Relationships

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

---

## 🇻🇳 Vietnamese Messages

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

**For AI Agents:** This file contains critical rules that MUST be followed for every implementation. When in doubt, refer to docs/2-solutioning/architecture.md for complete details.

**Architecture Status:** ✅ READY FOR IMPLEMENTATION
**Last Updated:** 2025-12-14
