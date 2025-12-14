---
stepsCompleted: [1, 2]
inputDocuments: []
session_topic: "Website Quản lý Cửa hàng Điện thoại O2O - Đồ án 8 buổi"
session_goals: "Khám phá toàn diện: Features + Technical Implementation + UX + Ưu tiên phát triển"
selected_approach: "ai-recommended"
techniques_used:
    [
        "Mind Mapping",
        "Morphological Analysis",
        "Resource Constraints + Decision Tree Mapping",
    ]
ideas_generated: []
context_file: ".bmad/bmm/data/project-context-template.md"
---

# Brainstorming Session - Tact Project

**Date:** 2025-12-14
**Facilitator:** Mary (Business Analyst)
**Participant:** TomiSakae

## Session Overview

**Topic:** Website Quản lý Cửa hàng Điện thoại (O2O Model)

**Goals:**

-   Khám phá toàn diện tính năng và trải nghiệm người dùng
-   Thiết kế kiến trúc kỹ thuật tối ưu
-   Xác định ưu tiên phát triển cho timeline 8 buổi
-   Giải quyết các thách thức kỹ thuật (O2O, Social Login, CRUD 12 bảng)

### Project Context

**Đồ án môn học:**

-   Timeline: 8 buổi học + 2 tuần báo cáo/thuyết trình
-   Stack: Laravel 12 + Tailwind CSS 4 + DaisyUI 5
-   Database: 12 bảng đã thiết kế (roles, users, customers, categories, brands, suppliers, products, product_specs, stock_movements, promotions, orders, order_items)

**Mô hình O2O (Online to Offline):**

-   Bán hàng online qua website
-   Bán hàng tại quầy (POS)
-   Quản lý thống nhất

**Đối tượng sử dụng:**

-   Khách hàng (Front-end): Xem, đặt hàng, quản lý đơn cá nhân
-   Quản trị viên (Back-end): 4 nhóm quyền (Admin, Manager, Sales, Warehouse)

**Chức năng chính:**

1. Authentication (Đăng ký/Đăng nhập thường + Google OAuth)
2. CRUD cho 12 modules
3. Quản lý kho (nhập/xuất với trigger tự động)
4. Xử lý đơn hàng (Online + POS)
5. Khuyến mãi & Tích điểm
6. Thống kê & Báo cáo

### Context Guidance

**Key Exploration Areas:**

-   User Problems and Pain Points - Thách thức của khách hàng và nhân viên
-   Feature Ideas and Capabilities - Tính năng cần có và nice-to-have
-   Technical Approaches - Kiến trúc Laravel, patterns, reusability
-   User Experience - Flow mượt mà cho cả 2 nhóm người dùng
-   Implementation Strategy - Ưu tiên phát triển trong 8 buổi
-   Technical Risks and Challenges - Những phần khó cần giải quyết sớm

### Session Setup

Phiên brainstorming này sẽ khám phá toàn diện dự án Tact từ nhiều góc độ:

-   **Features & UX**: Trải nghiệm người dùng tối ưu
-   **Technical Architecture**: Cấu trúc code, patterns, best practices
-   **Business Logic**: Quy trình nghiệp vụ O2O
-   **Development Strategy**: Roadmap 8 buổi học

## Technique Selection

**Approach:** AI-Recommended Techniques
**Analysis Context:** Website Quản lý Cửa hàng Điện thoại O2O với focus toàn diện

**Recommended Techniques:**

1. **Mind Mapping (Structured):** Visualize toàn bộ 12 modules CRUD + special features để thấy big picture và dependencies
2. **Morphological Analysis (Deep):** Phân tích systematically các technical parameters và options để chọn combination tối ưu
3. **Resource Constraints + Decision Tree (Structured):** Map roadmap 8 buổi với priorities và critical path rõ ràng

**AI Rationale:**
Dự án Tact có complexity cao (12 bảng, O2O model, multiple user roles) và timeline chặt (8 buổi). Sequence này đảm bảo:

-   Phase 1: Big picture trước khi dive deep
-   Phase 2: Technical decisions có cơ sở
-   Phase 3: Actionable roadmap thực tế

---

## Phase 1: Mind Mapping Results

### 🗺️ TACT O2O SYSTEM - Complete Mind Map

```
                                    TACT O2O SYSTEM
                                    (Website Quản lý
                                   Cửa hàng Điện thoại)
                                           |
        ┌──────────────────────────────────┼──────────────────────────────────┐
        |                                  |                                  |
   🔐 AUTH &                          📦 PRODUCT                        🏪 INVENTORY &
   AUTHORIZATION                      CATALOG                           SUPPLIER
        |                                  |                                  |
        ├─ Roles (CRUD)                   ├─ Categories (CRUD)               ├─ Suppliers (CRUD)
        │  └─ 4 roles: Admin,             │  └─ Điện thoại, Phụ kiện...     │  └─ Tên, MST, Contact
        │     Manager, Sales,              │                                  │
        │     Warehouse                    ├─ Brands (CRUD)                   ├─ Stock_movements (CRUD)
        │                                  │  └─ Apple, Samsung...            │  ├─ Stock In (Nhập hàng)
        ├─ Users (CRUD)                    │                                  │  │  └─ Chọn supplier + product
        │  ├─ Nhân viên                    ├─ Products (CRUD - Phức tạp)     │  ├─ Stock Out (Xuất hàng)
        │  ├─ Phân quyền theo role         │  ├─ Tên, Giá bán, Giá vốn      │  └─ Trigger tự động ±
        │  └─ Status: active/inactive      │  ├─ SKU/Barcode Management      │
        │                                  │  │  ├─ Mã duy nhất              └─ Features:
        ├─ Customers (CRUD)                │  │  ├─ Quét tại POS               ├─ Lịch sử nhập xuất
        │  ├─ Email (unique)               │  │  ├─ Check trùng                ├─ Cảnh báo hết hàng
        │  ├─ Password (nullable)          │  │  └─ In barcode                 └─ Báo cáo tồn kho
        │  ├─ Google ID                    │  ├─ Upload ảnh
        │  ├─ Tích điểm (points)           │  ├─ Bảo hành (months)
        │  └─ Address                      │  └─ Soft Delete
        │                                  │     ├─ status: active/inactive
        └─ Auth Features:                  │     ├─ Không xóa hẳn DB
           ├─ Đăng ký thường               │     ├─ Giữ lịch sử đơn cũ
           ├─ Đăng nhập thường             │     └─ Có thể khôi phục
           ├─ Google OAuth                 │
           │  └─ Bắt buộc set password     └─ Product_specs (CRUD)
           │     lần đầu                      ├─ Screen, OS, CPU
           ├─ Phân quyền sau login           ├─ RAM, ROM
           └─ Session management             ├─ Camera, Battery
                                             └─ SIM


        ┌──────────────────────────────────┼──────────────────────────────────┐
        |                                  |                                  |
   🛒 ORDER                            💰 PROMOTIONS                    📊 REPORTS &
   MANAGEMENT (O2O)                    & LOYALTY                         ANALYTICS
        |                                  |                                  |
        ├─ Orders (CRUD)                  ├─ Promotions (CRUD)               ├─ Doanh thu
        │  ├─ order_code (unique)         │  ├─ Code (GIAM100K)              │  ├─ Theo ngày/tháng/năm
        │  ├─ source: web/store           │  ├─ Type: fixed/percent          │  ├─ Bar chart
        │  ├─ customer_id                 │  ├─ Value (số tiền/%)            │  └─ So sánh các tháng
        │  ├─ user_id (nhân viên)         │  ├─ min_order                    │
        │  ├─ Subtotal, Discount, Tax     │  ├─ max_discount                 ├─ Sản phẩm
        │  ├─ total_money                 │  ├─ start_date, end_date         │  ├─ Top bán chạy
        │  ├─ payment_method              │  ├─ usage_limit                  │  └─ Sắp hết hàng (qty<5)
        │  │  └─ cash/card/transfer/COD   │  └─ status: active/inactive      │
        │  ├─ payment_status               │                                  └─ Kho
        │  │  └─ unpaid/paid              └─ Loyalty Features:                  ├─ Lịch sử nhập xuất
        │  └─ order_status                   ├─ Tích điểm tự động              └─ Báo cáo tồn kho
        │     └─ pending → confirmed →       │  └─ Trigger khi order
        │        shipping → completed →      │     completed
        │        cancelled                   └─ Điểm = total_money/100k
        │
        ├─ Order_items (CRUD)
        │  ├─ order_id
        │  ├─ product_id
        │  ├─ quantity
        │  ├─ price
        │  └─ imei_list
        │
        └─ O2O Features:
           ├─ ONLINE ORDERS (Web)
           │  ├─ Khách đặt hàng
           │  ├─ Admin duyệt (pending→confirmed)
           │  ├─ Nhập mã vận đơn (→shipping)
           │  ├─ Hoàn thành (→completed)
           │  └─ Địa chỉ giao hàng
           │
           └─ POS (Point of Sale - Tại quầy)
              ├─ Tìm khách bằng SĐT
              │  └─ Không có → Tạo nhanh
              ├─ Quét SKU/Chọn sản phẩm
              ├─ Thanh toán: cash/card/transfer
              └─ Lưu với status=completed ngay


        ┌──────────────────────────────────┴──────────────────────────────────┐
        |                                                                      |
   🛍️ CUSTOMER                                                    🖥️ ADMIN
   FRONT-END                                                       BACK-END
        |                                                                      |
        ├─ Layout                                                             ├─ Layout
        │  ├─ Header: Logo, Menu, Search,                                    │  ├─ Sidebar: Menu trái
        │  │  Cart, Login/Register                                           │  ├─ Header: User info, Logout
        │  └─ Footer: Contact, Policies                                      │  └─ Content: Main workspace
        │                                                                     │
        ├─ Pages:                                                            ├─ Dashboard
        │  ├─ 1. Trang chủ (Home)                                           │  ├─ Cards thống kê
        │  │  ├─ Banner slider                                              │  │  ├─ Doanh thu hôm nay/tháng
        │  │  ├─ Sản phẩm nổi bật                                           │  │  ├─ Đơn hàng mới
        │  │  ├─ Danh mục                                                   │  │  ├─ Sản phẩm sắp hết
        │  │  └─ Thương hiệu                                                │  │  └─ Khách hàng mới
        │  │                                                                 │  ├─ Biểu đồ doanh thu (Chart.js)
        │  ├─ 2. Danh sách SP (Product List)                                │  └─ Đơn hàng cần xử lý
        │  │  ├─ Grid 3-4 cột                                               │
        │  │  ├─ Phân trang                                                 ├─ CRUD Pages (12 modules)
        │  │  ├─ Filter: Giá, Hãng, Danh mục                                │  ├─ Pattern chung:
        │  │  └─ Sort: Giá, Mới, Bán chạy                                   │  │  ├─ List View (DataTables)
        │  │                                                                 │  │  │  ├─ Search, Filter, Sort
        │  ├─ 3. Chi tiết SP (Detail)                                       │  │  │  ├─ Actions: Edit, Delete
        │  │  ├─ Ảnh lớn + thumbnails                                       │  │  │  └─ Nút "Thêm mới"
        │  │  ├─ Tên, Giá, SKU                                              │  │  └─ Create/Edit Form
        │  │  ├─ Thông số kỹ thuật                                          │  │     ├─ Validation
        │  │  │  └─ Màn hình, CPU, RAM...                                   │  │     └─ Upload ảnh (Products)
        │  │  ├─ Bảo hành                                                   │  │
        │  │  └─ Nút "Thêm giỏ"                                             │  ├─ Products Management
        │  │                                                                 │  │  ├─ List: Ảnh, Tên, SKU,
        │  ├─ 4. Giỏ hàng (Cart)                                            │  │  │  Giá, Tồn, Status
        │  │  ├─ Danh sách SP                                               │  │  └─ Form: 3 tabs
        │  │  ├─ Tăng/giảm số lượng                                         │  │     ├─ Tab 1: Thông tin chung
        │  │  ├─ Xóa SP                                                     │  │     ├─ Tab 2: Upload ảnh
        │  │  ├─ Tổng tiền                                                  │  │     └─ Tab 3: Specs
        │  │  └─ Nút "Thanh toán"                                           │  │
        │  │                                                                 │  ├─ Orders Management
        │  ├─ 5. Checkout (Đặt hàng)                                        │  │  ├─ List: Mã, Khách, Tiền,
        │  │  ├─ Form giao hàng                                             │  │  │  Status, Nguồn (Web/Store)
        │  │  ├─ Chọn payment method                                        │  │  └─ Detail View:
        │  │  ├─ Nhập voucher                                               │  │     ├─ Info khách
        │  │  └─ Xác nhận                                                   │  │     ├─ Danh sách SP
        │  │                                                                 │  │     ├─ Địa chỉ giao
        │  ├─ 6. Tài khoản (My Account)                                     │  │     └─ Actions: Duyệt,
        │  │  ├─ Edit profile                                               │  │        Giao, Hoàn thành, Hủy
        │  │  ├─ Lịch sử đơn hàng                                           │  │
        │  │  ├─ Điểm tích lũy                                              │  ├─ POS Interface (Sales)
        │  │  └─ Đổi mật khẩu                                               │  │  ├─ Layout đặc biệt:
        │  │                                                                 │  │  │  ├─ Trái: Tìm SP (quét SKU)
        │  └─ 7. Auth Pages                                                 │  │  │  ├─ Giữa: SP đã chọn
        │     ├─ Đăng ký (form + Google)                                    │  │  │  └─ Phải: Tổng tiền
        │     ├─ Đăng nhập (form + Google)                                  │  │  ├─ Tìm khách SĐT
        │     └─ Set password (Google lần đầu)                              │  │  │  └─ Không có → Tạo nhanh
        │                                                                     │  │  ├─ Chọn payment
        │                                                                     │  │  └─ Hoàn tất → completed
        └─ UI Framework:                                                     │  │
           ├─ Tailwind CSS 4                                                │  └─ Reports Pages
           ├─ DaisyUI 5                                                     │     ├─ Doanh thu: Chart + Export
           ├─ Responsive (mobile-first)                                     │     ├─ Sản phẩm: Top 10, Alert
           └─ Components: Card, Button,                                     │     └─ Kho: Lịch sử nhập xuất
              Input, Modal, Badge...                                        │
                                                                            └─ Phân quyền:
                                                                               ├─ Admin: Tất cả
                                                                               ├─ Manager: Tất cả trừ Users
                                                                               ├─ Sales: POS + Orders
                                                                               └─ Warehouse: Inventory only


        ┌──────────────────────────────────┴──────────────────────────────────┐
        |                                                                      |
   ⚙️ TECHNICAL                                                    🎯 BUSINESS
   INFRASTRUCTURE                                                  LOGIC
        |                                                                      |
        ├─ Backend                                                            ├─ O2O Model
        │  ├─ Laravel 12                                                      │  ├─ Online: Web orders
        │  ├─ MVC Architecture                                                │  └─ Offline: POS tại quầy
        │  ├─ Eloquent ORM                                                    │
        │  ├─ Middleware (Auth, Role)                                         ├─ Triggers (MySQL)
        │  └─ API Routes                                                      │  ├─ Auto ± stock khi order
        │                                                                      │  └─ Auto + points khi completed
        ├─ Frontend                                                           │
        │  ├─ Blade Templates                                                 ├─ Soft Delete Strategy
        │  ├─ Tailwind CSS 4                                                  │  ├─ Products: status field
        │  ├─ DaisyUI 5                                                       │  └─ Users: status field
        │  └─ Vite (bundling)                                                 │
        │                                                                      ├─ Payment Flow
        ├─ Database                                                           │  ├─ Online: COD/Transfer
        │  ├─ MySQL                                                           │  └─ Store: Cash/Card
        │  ├─ 12 bảng                                                         │
        │  ├─ Foreign Keys                                                    └─ Loyalty Program
        │  ├─ Triggers (2)                                                       ├─ 100k = 1 điểm
        │  └─ Indexes                                                            └─ Tích khi order completed
        │
        ├─ External APIs
        │  ├─ Google OAuth API
        │  └─ (Optional) Payment Gateway
        │
        └─ File Management
           └─ Upload ảnh sản phẩm
              ├─ Storage: public/images/products
              └─ DB: Lưu tên file
```

### 📊 Mind Map Summary

**Tổng số modules:** 12 CRUD + 2 UI layers + Technical + Business Logic

**Độ phức tạp:**

-   ⭐⭐⭐⭐⭐ Products (CRUD phức tạp nhất: SKU, Upload, Specs, Soft Delete)
-   ⭐⭐⭐⭐⭐ Orders (O2O: Web + POS, nhiều status transitions)
-   ⭐⭐⭐⭐ Customers (Google OAuth + Password management)
-   ⭐⭐⭐⭐ Stock_movements (Triggers tự động)
-   ⭐⭐⭐ Promotions (Logic tính discount)
-   ⭐⭐ Các CRUD còn lại (Standard CRUD)

**Dependencies chính:**

1. Auth → Tất cả modules (cần login)
2. Products → Orders, Stock_movements
3. Customers → Orders
4. Suppliers → Stock_movements
5. Promotions → Orders (discount calculation)

---

## Phase 2: Morphological Analysis Results

### 🎯 FINAL RECOMMENDATION MATRIX - TACT O2O SYSTEM

**Optimal Technical Stack & Patterns cho dự án 8 buổi**

---

### **TECHNICAL PARAMETERS (1-10)**

**1. Auth Strategy → Breeze + Custom Google Flow**

-   Laravel Breeze cho base authentication
-   Socialite cho Google OAuth
-   Custom middleware: ForceSetPassword cho Google users lần đầu
-   Session-based authentication

**2. CRUD Pattern → Resource Controllers + Form Requests + Services**

-   Resource Controllers cho structure chuẩn Laravel
-   Form Request classes cho validation (StoreProductRequest, UpdateProductRequest...)
-   Service classes cho complex logic (ProductService, OrderService, StockService)
-   Thin controllers, fat services

**3. UI Component Approach → DaisyUI + Custom Blade Components**

-   DaisyUI components cho base UI (buttons, cards, modals, tables)
-   Custom Blade components cho app-specific (x-product-card, x-order-status-badge, x-stat-card)
-   Tailwind utilities cho fine-tuning
-   Reusable, maintainable

**4. File Upload Strategy → Laravel Storage + Image Intervention**

-   Storage facade (storage/app/public)
-   Symlink: php artisan storage:link
-   Image Intervention cho resize/optimize
-   Thumbnails tự động (200x200 cho list, 800x800 cho detail)

**5. Database Query Approach → Hybrid (Eloquent + Query Builder + Raw)**

-   Eloquent ORM cho CRUD operations
-   Eager loading (with()) cho relationships
-   Query Builder cho complex reports
-   Raw SQL khi cần performance tối đa (analytics)

**6. Validation Strategy → Form Requests + Frontend Validation**

-   Form Request classes cho backend validation
-   Alpine.js + DaisyUI cho frontend instant feedback
-   Custom validation rules khi cần (unique SKU, stock availability)
-   Consistent error messages

**7. API Architecture → Hybrid (Web Routes + AJAX Endpoints)**

-   Web routes cho CRUD pages (Blade rendering)
-   AJAX endpoints cho:
    -   Cart operations (add, remove, update quantity)
    -   POS real-time search
    -   Order status updates
    -   Stock checks
-   JSON responses cho AJAX

**8. State Management → Session + Database Hybrid**

-   Session cart cho guest users
-   Database cart cho logged-in customers
-   Merge cart on login
-   Session cho POS (không cần persist)

**9. POS Architecture → Blade + Alpine.js + AJAX API**

-   Blade template cho layout
-   Alpine.js cho reactivity (search, cart updates)
-   AJAX API cho product search, customer lookup
-   Real-time total calculation
-   Keyboard shortcuts (Enter to add, F2 to pay...)

**10. Error Handling → Hybrid (Custom Pages + Toast + Flash)**

-   Custom 404, 500 error pages (branded)
-   Toast notifications (SweetAlert2) cho AJAX errors
-   Flash messages (session) cho form submissions
-   DaisyUI alert components
-   Logging to storage/logs/laravel.log

---

### **DEVELOPMENT STRATEGY PARAMETERS (11-15)**

**11. Code Reusability → Comprehensive Approach**

-   **Model Traits:**
    -   HasStatus (active/inactive logic)
    -   HasSoftDelete (status-based soft delete)
    -   Searchable (search scopes)
-   **Service Classes:**
    -   ProductService (CRUD + SKU generation)
    -   OrderService (create, update status, calculate totals)
    -   StockService (movements, triggers)
    -   PromotionService (validate, apply discount)
-   **Blade Components:**
    -   x-button (DaisyUI variants)
    -   x-input (with validation errors)
    -   x-card (consistent styling)
    -   x-product-card (thumbnail, price, stock)
    -   x-order-status-badge (color-coded)
-   **Form Requests:**
    -   Store/Update requests cho mỗi model
    -   Reusable validation rules

**12. Testing Strategy → Pragmatic Approach**

-   **Feature Tests (Priority):**
    -   Auth flow (register, login, Google OAuth)
    -   Order placement (web + POS)
    -   Cart operations
    -   Stock movements
    -   Promotion application
-   **Unit Tests:**
    -   Service classes (OrderService, PromotionService)
    -   Helper functions
-   **Manual Testing:**
    -   UI/UX flows
    -   Reports/Analytics
    -   Role-based access
-   **Timeline:** Buổi 8 - viết tests cho critical paths

**13. Migration Strategy → Dependency-Ordered Migrations**

```
2024_12_14_000001_create_roles_table.php
2024_12_14_000002_create_users_table.php
2024_12_14_000003_create_customers_table.php
2024_12_14_000004_create_categories_table.php
2024_12_14_000005_create_brands_table.php
2024_12_14_000006_create_suppliers_table.php
2024_12_14_000007_create_products_table.php
2024_12_14_000008_create_product_specs_table.php
2024_12_14_000009_create_stock_movements_table.php
2024_12_14_000010_create_promotions_table.php
2024_12_14_000011_create_orders_table.php
2024_12_14_000012_create_order_items_table.php
```

-   Clear execution order
-   Foreign keys inline
-   Easy rollback per table

**14. Seeding Approach → Seeder + Factory Hybrid**

-   **Seeders cho Master Data:**
    -   RoleSeeder (4 roles: Admin, Manager, Sales, Warehouse)
    -   CategorySeeder (5 categories: Điện thoại, Phụ kiện, Tai nghe, Sạc, Ốp lưng)
    -   BrandSeeder (10 brands: Apple, Samsung, Xiaomi, Oppo, Vivo...)
-   **Factories cho Transactional Data:**
    -   UserFactory (50 users với roles random)
    -   CustomerFactory (100 customers, 20% có Google ID)
    -   ProductFactory (100 products với specs)
    -   OrderFactory (200 orders với items)
-   **Command:** php artisan db:seed
-   **Demo-ready data**

**15. Deployment Preparation → Complete Prep**

-   **.env.example documented:**
    ```
    APP_NAME=Tact
    APP_ENV=production
    APP_DEBUG=false
    DB_CONNECTION=mysql
    GOOGLE_CLIENT_ID=
    GOOGLE_CLIENT_SECRET=
    ```
-   **Optimization commands:**
    ```bash
    php artisan config:cache
    php artisan route:cache
    php artisan view:cache
    php artisan optimize
    npm run build
    ```
-   **Security checklist:**
    -   HTTPS enforced
    -   CSRF protection enabled
    -   SQL injection prevention (Eloquent)
    -   XSS prevention (Blade {{ }} escaping)
    -   Rate limiting on login
    -   Secure headers (helmet)
-   **Storage setup:**
    ```bash
    php artisan storage:link
    chmod -R 775 storage bootstrap/cache
    ```
-   **Timeline:** Buổi 8 - deployment checklist

---

### **BUSINESS LOGIC PARAMETERS (16-20)**

**16. Soft Delete Implementation → Status Field with Scopes**

```php
// Migration
$table->enum('status', ['active', 'inactive'])->default('active');

// Model
public function scopeActive($query) {
    return $query->where('status', 'active');
}

public function scopeInactive($query) {
    return $query->where('status', 'inactive');
}

// Usage
Product::active()->get(); // Chỉ sản phẩm đang bán
$product->update(['status' => 'inactive']); // Ngừng kinh doanh
```

-   Simple, effective
-   Không cần SoftDeletes trait
-   Clear business intent

**17. Trigger vs Application Logic → Service Layer**

```php
// StockService.php
public function recordMovement(Product $product, int $quantity, string $type, ?Supplier $supplier = null)
{
    DB::transaction(function() use ($product, $quantity, $type, $supplier) {
        // Create stock movement
        StockMovement::create([
            'product_id' => $product->id,
            'supplier_id' => $supplier?->id,
            'type' => $type,
            'quantity' => $quantity,
            'user_id' => auth()->id(),
        ]);

        // Update product quantity
        if ($type === 'in') {
            $product->increment('quantity', $quantity);
        } else {
            $product->decrement('quantity', $quantity);
        }
    });
}

// OrderService.php
public function completeOrder(Order $order)
{
    DB::transaction(function() use ($order) {
        $order->update(['order_status' => 'completed']);

        // Add loyalty points
        if ($order->customer) {
            $points = floor($order->total_money / 100000);
            $order->customer->increment('points', $points);
        }
    });
}
```

-   Testable, debuggable
-   Transaction-safe
-   Clear business logic
-   Có thể thêm DB triggers sau nếu cần

**18. Discount Calculation → Model Method + Service Validation**

```php
// Promotion Model
public function calculateDiscount(float $subtotal): float
{
    if ($this->type === 'fixed') {
        $discount = $this->value;
    } else {
        $discount = $subtotal * ($this->value / 100);
    }

    // Apply max_discount if set
    if ($this->max_discount && $discount > $this->max_discount) {
        $discount = $this->max_discount;
    }

    return $discount;
}

// PromotionService
public function validateAndApply(Order $order, string $code): ?Promotion
{
    $promo = Promotion::where('code', $code)
        ->where('status', 1)
        ->where('start_date', '<=', now())
        ->where('end_date', '>=', now())
        ->first();

    if (!$promo) {
        throw new \Exception('Mã khuyến mãi không hợp lệ');
    }

    if ($order->subtotal < $promo->min_order) {
        throw new \Exception("Đơn hàng tối thiểu {$promo->min_order}đ");
    }

    if ($promo->usage_limit > 0) {
        $used = Order::where('promotion_id', $promo->id)->count();
        if ($used >= $promo->usage_limit) {
            throw new \Exception('Mã khuyến mãi đã hết lượt sử dụng');
        }
    }

    return $promo;
}
```

**19. Order Status Flow → Methods + Events**

```php
// Order Model
public function confirm()
{
    if ($this->order_status !== 'pending') {
        throw new \Exception('Chỉ có thể duyệt đơn ở trạng thái pending');
    }

    $this->update(['order_status' => 'confirmed']);
    event(new OrderConfirmed($this));
}

public function ship(string $trackingCode, string $carrier)
{
    if ($this->order_status !== 'confirmed') {
        throw new \Exception('Chỉ có thể giao đơn đã duyệt');
    }

    $this->update([
        'order_status' => 'shipping',
        'tracking_code' => $trackingCode,
        'shipping_carrier' => $carrier,
    ]);

    event(new OrderShipped($this));
}

public function complete()
{
    if (!in_array($this->order_status, ['confirmed', 'shipping'])) {
        throw new \Exception('Không thể hoàn thành đơn hàng này');
    }

    DB::transaction(function() {
        $this->update([
            'order_status' => 'completed',
            'payment_status' => 'paid',
        ]);

        // Add loyalty points
        if ($this->customer) {
            $points = floor($this->total_money / 100000);
            $this->customer->increment('points', $points);
        }
    });

    event(new OrderCompleted($this));
}

public function cancel(string $reason = null)
{
    if ($this->order_status === 'completed') {
        throw new \Exception('Không thể hủy đơn đã hoàn thành');
    }

    $this->update([
        'order_status' => 'cancelled',
        'note' => $reason,
    ]);

    event(new OrderCancelled($this));
}

// Events
// app/Events/OrderConfirmed.php
// app/Events/OrderShipped.php (send email with tracking)
// app/Events/OrderCompleted.php (send thank you email)
// app/Events/OrderCancelled.php (refund if paid)
```

**20. Role-Based Access Control → Middleware + Gates Hybrid**

```php
// Middleware: app/Http/Middleware/CheckRole.php
public function handle($request, Closure $next, ...$roles)
{
    if (!auth()->check()) {
        return redirect()->route('login');
    }

    $userRole = auth()->user()->role->name;

    if (!in_array($userRole, $roles)) {
        abort(403, 'Bạn không có quyền truy cập trang này');
    }

    return $next($request);
}

// Routes: routes/web.php
Route::middleware(['auth', 'role:admin,manager'])->group(function() {
    Route::resource('products', ProductController::class);
    Route::resource('categories', CategoryController::class);
    Route::resource('brands', BrandController::class);
});

Route::middleware(['auth', 'role:admin,manager,sales'])->group(function() {
    Route::get('/pos', [POSController::class, 'index']);
    Route::resource('orders', OrderController::class);
});

Route::middleware(['auth', 'role:admin,manager,warehouse'])->group(function() {
    Route::resource('stock-movements', StockMovementController::class);
    Route::resource('suppliers', SupplierController::class);
});

Route::middleware(['auth', 'role:admin'])->group(function() {
    Route::resource('users', UserController::class);
    Route::get('/reports', [ReportController::class, 'index']);
});

// Gates: app/Providers/AuthServiceProvider.php
Gate::define('manage-products', function($user) {
    return in_array($user->role->name, ['admin', 'manager']);
});

Gate::define('view-reports', function($user) {
    return in_array($user->role->name, ['admin', 'manager']);
});

Gate::define('manage-users', function($user) {
    return $user->role->name === 'admin';
});

// Usage in Blade
@can('manage-products')
    <a href="{{ route('products.create') }}">Thêm sản phẩm</a>
@endcan

// Usage in Controller
$this->authorize('manage-products');
```

---

### 🎯 **OPTIMAL COMBINATION SUMMARY**

**Tech Stack:**

-   Laravel 12 + Breeze + Socialite
-   Tailwind CSS 4 + DaisyUI 5 + Alpine.js
-   MySQL + Eloquent ORM
-   Vite + Blade
-   Image Intervention
-   SweetAlert2

**Architecture Patterns:**

-   MVC with Service Layer
-   Resource Controllers + Form Requests
-   Blade Components (reusable UI)
-   Event-Driven (Order status changes)
-   Repository-lite (Services handle complex logic)

**Code Organization:**

```
app/
├── Http/
│   ├── Controllers/
│   │   ├── Admin/ (CRUD controllers)
│   │   ├── Auth/ (Breeze + Google)
│   │   ├── Shop/ (Customer-facing)
│   │   └── POSController.php
│   ├── Requests/ (Form validation)
│   └── Middleware/ (CheckRole)
├── Models/ (12 models + traits)
├── Services/ (Business logic)
├── Events/ (Order events)
├── Listeners/ (Email notifications)
└── View/Components/ (Blade components)

resources/
├── views/
│   ├── layouts/
│   │   ├── admin.blade.php
│   │   └── shop.blade.php
│   ├── admin/ (Backend views)
│   ├── shop/ (Frontend views)
│   ├── pos/ (POS interface)
│   └── components/ (Blade components)
└── css/
    └── app.css (Tailwind + DaisyUI)
```

**Development Workflow:**

-   Migrations → Seeders → Models → Controllers → Views
-   Service classes cho complex logic
-   Tests cho critical paths
-   Git commits per feature

**Quality Assurance:**

-   Form validation (backend + frontend)
-   Transaction safety (DB::transaction)
-   Error handling (try-catch + logging)
-   Feature tests cho main flows
-   Manual testing cho UI/UX

**Deployment Ready:**

-   Optimization commands
-   Security checklist
-   .env.example documented
-   Storage symlink
-   Asset compilation

---

## Phase 3: Resource Constraints + Decision Tree Mapping

### 📅 8-BUỔI ROADMAP - TACT O2O SYSTEM

**Constraint:** 8 buổi học (mỗi buổi **4 tiếng**)  
**Total Time:** 32 giờ  
**Goal:** Hoàn thành website O2O với 12 CRUD modules + đầy đủ chức năng + polish

---

### **BUỔI 1: FOUNDATION & DATABASE** 🏗️

**Mục tiêu:** Xây dựng nền móng hệ thống

**Tasks:**

**1.1 Setup Project**

-   Cài đặt Laravel 12
-   Config .env (database, app settings)
-   Cài packages:
    ```bash
    composer require laravel/breeze laravel/socialite intervention/image
    npm install -D tailwindcss daisyui alpinejs
    ```

**1.2 Database Setup**

-   Tạo 12 migrations (dependency-ordered)
-   Chạy migrations: `php artisan migrate`
-   Tạo seeders cho master data (Roles, Categories, Brands)
-   Tạo factories cho transactional data
-   Seed database: `php artisan db:seed`

**1.3 Models & Relationships**

-   Tạo 12 models
-   Define relationships:
    -   User belongsTo Role
    -   Product belongsTo Category, Brand
    -   Product hasOne ProductSpec
    -   Order belongsTo Customer, User
    -   Order hasMany OrderItems
    -   StockMovement belongsTo Product, Supplier, User
-   Add traits: HasStatus, Searchable

**1.4 UI Foundation**

-   Setup Tailwind + DaisyUI config
-   Tạo layouts:
    -   `resources/views/layouts/admin.blade.php` (sidebar, header)
    -   `resources/views/layouts/shop.blade.php` (navbar, footer)
-   Tạo base Blade components:
    -   x-button
    -   x-input
    -   x-card
    -   x-alert

**Deliverables:**

-   ✅ Database với 12 bảng + sample data
-   ✅ 12 models với relationships
-   ✅ 2 layouts (admin + shop)
-   ✅ Base components

**Time Estimate:** 3-4 giờ

**Risks:**

-   ⚠️ Migration errors (foreign keys) → Test từng migration
-   ⚠️ Relationship sai → Dùng tinker để test

---

### **BUỔI 2: AUTHENTICATION & AUTHORIZATION** 🔐

**Mục tiêu:** Hoàn thiện hệ thống xác thực

**Tasks:**

**2.1 Laravel Breeze Setup**

-   Install Breeze: `php artisan breeze:install blade`
-   Customize views với DaisyUI
-   Test đăng ký/đăng nhập thường

**2.2 Google OAuth Integration**

-   Config Google API (Client ID, Secret)
-   Tạo GoogleController:
    -   redirectToGoogle()
    -   handleGoogleCallback()
-   Logic:
    -   Check email exists → Login
    -   Email mới → Create customer → Redirect to "Set Password"
-   Tạo view: `auth/set-password.blade.php`

**2.3 Middleware & Role-Based Access**

-   Tạo CheckRole middleware
-   Define Gates (manage-products, view-reports, manage-users)
-   Setup route groups:
    ```php
    Route::middleware(['auth', 'role:admin,manager'])->group(...)
    Route::middleware(['auth', 'role:sales'])->group(...)
    ```

**2.4 Customer Front-end Auth**

-   Customize Breeze views cho shop layout
-   Add Google login button
-   Test full flow:
    -   Register → Login → Google → Set Password

**Deliverables:**

-   ✅ Auth system hoàn chỉnh (thường + Google)
-   ✅ Role-based access control
-   ✅ Middleware + Gates
-   ✅ Set password flow cho Google users

**Time Estimate:** 3-4 giờ

**Risks:**

-   ⚠️ Google OAuth callback errors → Check redirect URI
-   ⚠️ Session issues → Clear cache

---

### **BUỔI 3: CORE CRUD - PRODUCTS & CATALOG** 📦

**Mục tiêu:** CRUD cho sản phẩm (module phức tạp nhất)

**Tasks:**

**3.1 Simple CRUD (Warm-up)**

-   Categories CRUD (admin)
-   Brands CRUD (admin)
-   Suppliers CRUD (admin)
-   Pattern: Resource Controller + Form Requests + DataTables

**3.2 Products CRUD (Complex)**

-   ProductController (admin)
-   Form Requests: StoreProductRequest, UpdateProductRequest
-   Views:
    -   index: DataTables (ảnh, tên, SKU, giá, tồn, status)
    -   create/edit: 3 tabs
        -   Tab 1: Thông tin chung (tên, SKU, giá, category, brand)
        -   Tab 2: Upload ảnh (Image Intervention)
        -   Tab 3: Thông số kỹ thuật (product_specs)

**3.3 Image Upload**

-   Setup storage symlink: `php artisan storage:link`
-   Image Intervention:
    -   Resize to 800x800 (detail)
    -   Thumbnail 200x200 (list)
-   Store paths in database

**3.4 Product Specs**

-   Auto-create product_specs khi tạo product
-   Form fields: Screen, OS, CPU, RAM, ROM, Camera, Battery, SIM

**3.5 Soft Delete**

-   Nút "Ngừng kinh doanh" (status = inactive)
-   Filter: Active / Inactive
-   Scope: Product::active()

**Deliverables:**

-   ✅ Categories, Brands, Suppliers CRUD
-   ✅ Products CRUD với upload ảnh
-   ✅ Product specs management
-   ✅ Soft delete implementation

**Time Estimate:** 4 giờ

**Risks:**

-   ⚠️ Image upload errors → Check permissions (775)
-   ⚠️ Large images → Optimize với Intervention

---

### **BUỔI 4: INVENTORY & STOCK MANAGEMENT** 🏪

**Mục tiêu:** Quản lý kho và nhập xuất

**Tasks:**

**4.1 Stock Movements CRUD**

-   StockMovementController (admin)
-   Views:
    -   index: Lịch sử nhập xuất (filter by type, date)
    -   create: Form nhập hàng
        -   Chọn supplier (dropdown)
        -   Chọn product (searchable select)
        -   Nhập quantity
        -   Note

**4.2 Stock Service**

-   StockService::recordMovement()
-   Logic:
    -   Create stock_movement record
    -   Update product quantity (increment/decrement)
    -   Wrap in DB::transaction

**4.3 Stock In (Nhập hàng)**

-   Form nhập hàng
-   Validation: quantity > 0
-   Auto update product quantity

**4.4 Stock Out (Xuất hàng)**

-   Tự động khi order completed
-   Validation: quantity <= available stock

**4.5 Inventory Reports**

-   Danh sách tồn kho (products.quantity)
-   Cảnh báo sắp hết hàng (quantity < 5)
-   Lịch sử nhập xuất theo tháng

**Deliverables:**

-   ✅ Stock movements CRUD
-   ✅ StockService với transaction
-   ✅ Nhập hàng functionality
-   ✅ Inventory reports

**Time Estimate:** 3 giờ

**Risks:**

-   ⚠️ Race condition → Dùng DB::transaction
-   ⚠️ Negative stock → Validation

---

### **BUỔI 5: CUSTOMER MANAGEMENT & PROMOTIONS** 💰

**Mục tiêu:** Quản lý khách hàng và khuyến mãi

**Tasks:**

**5.1 Customers CRUD (Admin)**

-   CustomerController (admin)
-   Views:
    -   index: List customers (email, name, phone, points, status)
    -   show: Chi tiết khách (orders history, points)
    -   edit: Update info
-   Actions:
    -   Khóa tài khoản (status = locked)
    -   Reset password

**5.2 Promotions CRUD**

-   PromotionController (admin)
-   Form fields:
    -   Code (GIAM100K)
    -   Name
    -   Type (fixed/percent)
    -   Value
    -   min_order, max_discount
    -   start_date, end_date
    -   usage_limit
    -   status

**5.3 Promotion Service**

-   PromotionService::validateAndApply()
-   Validation:
    -   Code exists & active
    -   Date range valid
    -   min_order met
    -   usage_limit not exceeded
-   Promotion::calculateDiscount()

**5.4 Customer Front-end**

-   My Account page:
    -   Profile info (edit)
    -   Order history
    -   Loyalty points
    -   Change password

**Deliverables:**

-   ✅ Customers CRUD (admin)
-   ✅ Promotions CRUD
-   ✅ PromotionService
-   ✅ Customer account page

**Time Estimate:** 3 giờ

**Risks:**

-   ⚠️ Promotion logic bugs → Unit tests

---

### **BUỔI 6: ORDER MANAGEMENT (O2O) - PART 1: WEB ORDERS** 🛒

**Mục tiêu:** Xử lý đơn hàng online

**Tasks:**

**6.1 Customer Shopping Flow**

-   Shop pages:
    -   Home: Banner, featured products
    -   Product list: Grid, filter (price, brand), pagination
    -   Product detail: Ảnh, specs, add to cart
    -   Cart: List items, update quantity, remove, total
    -   Checkout: Shipping info, payment method, apply voucher

**6.2 Cart Implementation**

-   Session-based cart cho guest
-   Database cart cho logged-in users
-   CartService:
    -   add(product, quantity)
    -   update(product, quantity)
    -   remove(product)
    -   clear()
    -   getTotal()

**6.3 Checkout & Order Creation**

-   CheckoutController
-   OrderService::createOrder()
-   Logic:
    -   Validate stock availability
    -   Apply promotion (if code provided)
    -   Calculate: subtotal, discount, tax, total
    -   Create order + order_items
    -   Clear cart
    -   Redirect to order confirmation

**6.4 Order Management (Admin)**

-   OrderController (admin)
-   Views:
    -   index: List orders (filter by status, source)
    -   show: Order detail (customer, items, shipping, status)
-   Actions:
    -   Duyệt đơn: pending → confirmed
    -   Nhập mã vận đơn: confirmed → shipping
    -   Hoàn thành: shipping → completed
    -   Hủy đơn: → cancelled

**6.5 Order Status Methods**

-   Order::confirm()
-   Order::ship($trackingCode, $carrier)
-   Order::complete() (+ add loyalty points)
-   Order::cancel($reason)

**Deliverables:**

-   ✅ Customer shopping flow (home → cart → checkout)
-   ✅ Cart system (session + DB)
-   ✅ Order creation
-   ✅ Order management (admin)
-   ✅ Order status transitions

**Time Estimate:** 4 giờ

**Risks:**

-   ⚠️ Stock race condition → Lock products during checkout
-   ⚠️ Cart bugs → Test edge cases

---

### **BUỔI 7: ORDER MANAGEMENT (O2O) - PART 2: POS** 🏪

**Mục tiêu:** Bán hàng tại quầy (Point of Sale)

**Tasks:**

**7.1 POS Interface**

-   POSController (sales role)
-   Layout đặc biệt (không sidebar):
    -   Trái: Product search (SKU scan, name search)
    -   Giữa: Cart items (selected products)
    -   Phải: Customer info, Total, Payment

**7.2 POS Features**

-   **Product Search:**

    -   AJAX endpoint: `/api/pos/products/search?q=`
    -   Search by: SKU, name
    -   Return: JSON (id, name, price, stock)
    -   Alpine.js cho real-time search

-   **Customer Lookup:**

    -   Search by phone: `/api/pos/customers/search?phone=`
    -   Không có → Form tạo nhanh (name + phone)
    -   Có → Load info

-   **Cart Management:**

    -   Add product (click or Enter)
    -   Update quantity (+ / -)
    -   Remove item
    -   Real-time total calculation (Alpine.js)

-   **Payment:**
    -   Select payment method (cash/card/transfer)
    -   Nhập tiền khách đưa
    -   Tính tiền thừa
    -   Nút "Hoàn tất"

**7.3 POS Order Creation**

-   OrderService::createPOSOrder()
-   Logic:
    -   source = 'store'
    -   order_status = 'completed' (ngay lập tức)
    -   payment_status = 'paid'
    -   Auto create stock_out movements
    -   Add loyalty points
    -   Print receipt (optional)

**7.4 Keyboard Shortcuts**

-   F1: Focus search
-   Enter: Add to cart
-   F2: Payment
-   Esc: Clear cart

**Deliverables:**

-   ✅ POS interface (Alpine.js + AJAX)
-   ✅ Product search real-time
-   ✅ Customer lookup/create
-   ✅ POS order creation
-   ✅ Keyboard shortcuts

**Time Estimate:** 4 giờ

**Risks:**

-   ⚠️ AJAX errors → Error handling + toast notifications
-   ⚠️ Stock sync issues → Transaction safety

---

### **BUỔI 8: REPORTS, TESTING & DEPLOYMENT** 📊

**Mục tiêu:** Hoàn thiện hệ thống

**Tasks:**

**8.1 Reports & Analytics**

-   ReportController (admin, manager)
-   **Dashboard:**

    -   Cards: Doanh thu hôm nay, Đơn hàng mới, Sản phẩm sắp hết, Khách mới
    -   Chart: Doanh thu theo tháng (Chart.js)
    -   Đơn hàng cần xử lý (pending orders)

-   **Doanh thu Report:**

    -   Filter: Date range
    -   Bar chart: Doanh thu theo tháng
    -   Export Excel (optional)

-   **Sản phẩm Report:**

    -   Top 10 bán chạy (query order_items)
    -   Sản phẩm sắp hết hàng (quantity < 5)

-   **Kho Report:**
    -   Lịch sử nhập xuất (stock_movements)
    -   Filter by date, type

**8.2 Testing**

-   **Feature Tests:**

    -   AuthTest: register, login, Google OAuth
    -   OrderTest: create order, apply promotion
    -   CartTest: add, update, remove
    -   POSTest: create POS order

-   **Manual Testing:**
    -   Test all CRUD operations
    -   Test role-based access
    -   Test order flows (web + POS)
    -   Test reports

**8.3 Bug Fixes**

-   Fix UI issues
-   Fix validation errors
-   Fix edge cases

**8.4 Deployment Preparation**

-   **Optimization:**

    ```bash
    php artisan config:cache
    php artisan route:cache
    php artisan view:cache
    php artisan optimize
    npm run build
    ```

-   **Security Checklist:**

    -   ✅ APP_DEBUG=false
    -   ✅ HTTPS enforced
    -   ✅ CSRF protection
    -   ✅ Rate limiting
    -   ✅ Secure headers

-   **.env.example:**

    -   Document all required variables
    -   Add comments

-   **README.md:**
    -   Installation instructions
    -   Features list
    -   Screenshots
    -   Tech stack

**8.5 Final Polish**

-   UI/UX improvements
-   Error messages tiếng Việt
-   Loading states
-   Toast notifications

**Deliverables:**

-   ✅ Dashboard với charts
-   ✅ 3 reports (Doanh thu, Sản phẩm, Kho)
-   ✅ Feature tests cho critical paths
-   ✅ Bug fixes
-   ✅ Deployment-ready
-   ✅ Documentation

**Time Estimate:** 4 giờ

**Risks:**

-   ⚠️ Không đủ thời gian test → Focus critical paths
-   ⚠️ Performance issues → Optimize queries

---

### 🎯 **DECISION TREE: CRITICAL PATH**

```
START
  │
  ├─ BUỔI 1: Database + Models (CRITICAL)
  │   └─ Không có → Không làm được gì
  │
  ├─ BUỔI 2: Auth (CRITICAL)
  │   └─ Không có → Không phân quyền được
  │
  ├─ BUỔI 3: Products CRUD (CRITICAL)
  │   └─ Không có → Không có gì để bán
  │
  ├─ BUỔI 4: Stock Management (HIGH PRIORITY)
  │   └─ Có thể skip → Nhưng mất tính năng quan trọng
  │
  ├─ BUỔI 5: Customers + Promotions (MEDIUM PRIORITY)
  │   └─ Có thể đơn giản hóa → Bỏ promotions nếu thiếu thời gian
  │
  ├─ BUỔI 6: Web Orders (CRITICAL)
  │   └─ Không có → Mất 50% chức năng O2O
  │
  ├─ BUỔI 7: POS (CRITICAL)
  │   └─ Không có → Mất 50% chức năng O2O
  │
  └─ BUỔI 8: Reports + Testing (HIGH PRIORITY)
      └─ Có thể rút gọn → Focus reports cơ bản
```

---

### ⚠️ **RISK MITIGATION STRATEGIES**

**Nếu thiếu thời gian:**

**Priority 1 (Must Have):**

-   Database + Models
-   Auth (bỏ Google OAuth nếu cần)
-   Products CRUD (đơn giản hóa: bỏ specs)
-   Web Orders (bỏ promotions)
-   POS (basic version)
-   Dashboard cơ bản

**Priority 2 (Should Have):**

-   Google OAuth
-   Product specs
-   Stock management
-   Promotions
-   Reports đầy đủ

**Priority 3 (Nice to Have):**

-   Advanced reports
-   Charts
-   Tests
-   UI polish

**Time-Saving Tips:**

-   Dùng DataTables package cho admin tables
-   Copy-paste CRUD pattern
-   Dùng DaisyUI components (không custom CSS)
-   Skip tests nếu thiếu thời gian (focus manual testing)
-   Bỏ features không critical (promotions, advanced reports)

---

### 📊 **PROGRESS TRACKING**

**Checklist theo buổi:**

**Buổi 1:**

-   [ ] Laravel installed
-   [ ] 12 migrations created & run
-   [ ] 12 models with relationships
-   [ ] Seeders & factories
-   [ ] Layouts (admin + shop)
-   [ ] Base components

**Buổi 2:**

-   [ ] Breeze installed
-   [ ] Google OAuth working
-   [ ] Set password flow
-   [ ] Middleware + Gates
-   [ ] Role-based routes

**Buổi 3:**

-   [ ] Categories CRUD
-   [ ] Brands CRUD
-   [ ] Suppliers CRUD
-   [ ] Products CRUD
-   [ ] Image upload
-   [ ] Product specs
-   [ ] Soft delete

**Buổi 4:**

-   [ ] Stock movements CRUD
-   [ ] StockService
-   [ ] Nhập hàng
-   [ ] Inventory reports

**Buổi 5:**

-   [ ] Customers CRUD
-   [ ] Promotions CRUD
-   [ ] PromotionService
-   [ ] My Account page

**Buổi 6:**

-   [ ] Shop pages (home, list, detail)
-   [ ] Cart system
-   [ ] Checkout
-   [ ] Order creation
-   [ ] Order management (admin)
-   [ ] Order status transitions

**Buổi 7:**

-   [ ] POS interface
-   [ ] Product search (AJAX)
-   [ ] Customer lookup
-   [ ] POS order creation
-   [ ] Keyboard shortcuts

**Buổi 8:**

-   [ ] Dashboard
-   [ ] Reports (3 types)
-   [ ] Feature tests
-   [ ] Bug fixes
-   [ ] Deployment prep
-   [ ] Documentation

---

### 🎯 **SUCCESS METRICS**

**Minimum Viable Product (MVP):**

-   ✅ 12 CRUD modules hoạt động
-   ✅ Auth (thường + Google)
-   ✅ Web orders (khách đặt hàng)
-   ✅ POS (bán tại quầy)
-   ✅ Role-based access (4 roles)
-   ✅ Basic reports

**Full Features:**

-   ✅ MVP +
-   ✅ Stock management với triggers
-   ✅ Promotions & loyalty points
-   ✅ Advanced reports với charts
-   ✅ Feature tests
-   ✅ Deployment-ready

**Excellence:**

-   ✅ Full Features +
-   ✅ UI/UX polish
-   ✅ Performance optimization
-   ✅ Comprehensive tests
-   ✅ Complete documentation
-   ✅ Demo data

---
