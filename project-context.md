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

## 🗺️ Document Map (Headers + Line Ranges)

### Phase 0: Discovery

#### `docs/0-discovery/brainstorming-session-2025-12-14.md` (1225 lines)

| Header                                     | Lines     |
| ------------------------------------------ | --------- |
| # Brainstorming Session - Tact Project     | 17        |
| ## Session Overview                        | 23-81     |
| ### Project Context                        | 34-61     |
| ### Context Guidance                       | 62-72     |
| ### Session Setup                          | 73-81     |
| ## Technique Selection                     | 82-101    |
| ## Phase 1: Mind Mapping Results           | 102-313   |
| ### 🗺️ TACT O2O SYSTEM - Complete Mind Map | 104-291   |
| ### 📊 Mind Map Summary                    | 292-313   |
| ## Phase 2: Morphological Analysis Results | 315-841   |
| ### 🎯 FINAL RECOMMENDATION MATRIX         | 317-401   |
| ### **TECHNICAL PARAMETERS (1-10)**        | 323-401   |
| ### **DEVELOPMENT STRATEGY PARAMETERS**    | 402-510   |
| ### **BUSINESS LOGIC PARAMETERS (16-20)**  | 512-766   |
| ### 🎯 **OPTIMAL COMBINATION SUMMARY**     | 768-841   |
| ## Phase 3: Resource Constraints           | 843-1225  |
| ### 📅 8-BUỔI ROADMAP                      | 845-852   |
| ### **BUỔI 1: FOUNDATION & DATABASE**      | 853-916   |
| ### **BUỔI 2: AUTHENTICATION**             | 917-972   |
| ### **BUỔI 3: CORE CRUD - PRODUCTS**       | 973-1031  |
| ### **BUỔI 4: INVENTORY & STOCK**          | 1032-1089 |
| ### **BUỔI 5: CUSTOMER & PROMOTIONS**      | 1090-1152 |
| ### **BUỔI 6: ORDER MANAGEMENT - WEB**     | 1153-1226 |
| ### **BUỔI 7: ORDER MANAGEMENT - POS**     | 1227-1303 |
| ### **BUỔI 8: REPORTS & DEPLOYMENT**       | 1304-1410 |
| ### 🎯 **DECISION TREE: CRITICAL PATH**    | 1411-1442 |
| ### ⚠️ **RISK MITIGATION STRATEGIES**      | 1443-1480 |
| ### 📊 **PROGRESS TRACKING**               | 1481-1553 |
| ### 🎯 **SUCCESS METRICS**                 | 1554-1225 |

#### `docs/0-discovery/product-brief-Tact-2025-12-14.md` (699 lines)

| Header                         | Lines   |
| ------------------------------ | ------- |
| # Product Brief: Tact          | 16-22   |
| ## Executive Summary           | 23-30   |
| ## Core Vision                 | 31-178  |
| ### Problem Statement          | 33-60   |
| ### Problem Impact             | 61-81   |
| ### Why Existing Solutions     | 82-101  |
| ### Proposed Solution          | 102-141 |
| ### Key Differentiators        | 142-178 |
| ## Target Users                | 180-357 |
| ### Primary Users              | 182-258 |
| ### Secondary Users            | 259-310 |
| ### User Journey               | 311-357 |
| ## Success Metrics             | 359-595 |
| ### User Success Metrics       | 361-479 |
| ### Business Objectives        | 480-536 |
| ### Key Performance Indicators | 537-595 |
| ## MVP Scope                   | 597-873 |
| ### Core Features (Must-Have)  | 599-725 |
| ### Out of Scope for MVP       | 726-808 |
| ### MVP Success Criteria       | 809-873 |
| ### Future Vision              | 875-699 |

---

### Phase 1: Planning

#### `docs/1-planning/prd.md` (1003 lines)

| Header                              | Lines    |
| ----------------------------------- | -------- |
| # Product Requirements Document     | 24-28    |
| ## Executive Summary                | 29-72    |
| ### Vấn đề giải quyết               | 33-41    |
| ### Giải pháp                       | 42-52    |
| ### Người dùng mục tiêu             | 53-60    |
| ### What Makes This Special         | 61-72    |
| ## Project Classification           | 73-113   |
| ### Classification Rationale        | 83-97    |
| ### Domain-Specific Considerations  | 98-113   |
| ## Success Criteria                 | 114-239  |
| ### User Success                    | 116-149  |
| ### Business Success                | 150-176  |
| ### Technical Success               | 177-207  |
| ### Measurable Outcomes             | 208-239  |
| ## Product Scope                    | 240-367  |
| ### MVP - Minimum Viable Product    | 242-284  |
| ### Growth Features (Post-MVP)      | 285-322  |
| ### Vision (Future)                 | 323-367  |
| ## User Journeys                    | 368-523  |
| ### Journey 1: Minh - Khách hàng    | 370-377  |
| ### Journey 2: Lan - Sales          | 378-394  |
| ### Journey 3: Anh Tuấn - Warehouse | 395-414  |
| ### Journey 4: Chị Hương - Manager  | 415-440  |
| ### Journey 5: Anh Nam - Admin      | 441-469  |
| ### Journey Requirements Summary    | 470-523  |
| ## Web Application Requirements     | 524-881  |
| ### Architecture Overview           | 526-543  |
| ### Browser Compatibility Matrix    | 544-569  |
| ### Responsive Design Requirements  | 570-610  |
| ### Performance Targets             | 611-641  |
| ### SEO Strategy                    | 642-725  |
| ### Real-Time Features              | 726-768  |
| ### Accessibility Level             | 769-835  |
| ### Implementation Considerations   | 836-881  |
| ## Project Scoping & Strategy       | 882-1003 |
| ### MVP Strategy & Philosophy       | 884-900  |
| ### MVP Boundaries (Week 1-8)       | 901-931  |
| ### Post-MVP Roadmap                | 932-951  |
| ### Risk Mitigation Strategy        | 952-978  |
| ### Success Criteria Alignment      | 979-1003 |
| ## Functional Requirements          | 1044-end |

#### `docs/1-planning/ux-design-specification.md` (2362 lines)

| Header                           | Lines     |
| -------------------------------- | --------- |
| # UX Design Specification Tact   | 20-28     |
| ## Executive Summary             | 29-196    |
| ### Project Vision               | 31-48     |
| ### Target Users                 | 49-81     |
| ### Key Design Challenges        | 82-137    |
| ### Design Opportunities         | 138-196   |
| ## Core User Experience          | 197-391   |
| ### Defining Experience          | 199-214   |
| ### Platform Strategy            | 215-240   |
| ### Effortless Interactions      | 241-300   |
| ### Critical Success Moments     | 301-353   |
| ### Experience Principles        | 354-391   |
| ## Desired Emotional Response    | 393-634   |
| ### Primary Emotional Goals      | 395-442   |
| ### Emotional Journey Mapping    | 443-508   |
| ### Micro-Emotions               | 509-540   |
| ### Design Implications          | 541-589   |
| ### Emotional Design Principles  | 590-634   |
| ## UX Pattern Analysis           | 636-894   |
| ### Inspiring Products Analysis  | 638-710   |
| ### Transferable UX Patterns     | 711-795   |
| ### Anti-Patterns to Avoid       | 796-833   |
| ### Design Inspiration Strategy  | 834-894   |
| ## Design System Foundation      | 896-1299  |
| ### Design System Choice         | 898-908   |
| ### Rationale for Selection      | 909-959   |
| ### Implementation Approach      | 960-1135  |
| ### Customization Strategy       | 1136-1299 |
| ### Defining Experience          | 1301-1377 |
| ### User Mental Model            | 1337-1377 |
| ### Success Criteria             | 1378-1421 |
| ### Novel UX Patterns            | 1422-1487 |
| ### Experience Mechanics         | 1488-1636 |
| ## Visual Design Foundation      | 1637-1900 |
| ### Color System                 | 1639-1690 |
| ### Typography System            | 1691-1767 |
| ### Spacing & Layout Foundation  | 1768-1839 |
| ### Accessibility Considerations | 1840-1900 |
| ## Design Direction Decision     | 1902-2038 |
| ### Design Directions Explored   | 1904-1914 |
| ### Chosen Direction             | 1915-1930 |
| ### Design Rationale             | 1931-1965 |
| ### Implementation Approach      | 1966-2038 |
| ## User Journey Flows            | 2039-2320 |
| ### Customer Purchase Journey    | 2041-2110 |
| ### POS Transaction Journey      | 2111-2177 |
| ### Stock In Journey             | 2178-2228 |
| ### Dashboard Analytics Journey  | 2229-2281 |
| ### Journey Patterns             | 2282-2320 |
| ### Flow Optimization Principles | 2321-2362 |

---

### Phase 2: Solutioning

#### `docs/2-solutioning/architecture.md` (2345 lines)

| Header                                | Lines     |
| ------------------------------------- | --------- |
| # Architecture Decision Document      | 15        |
| ## Project Context Analysis           | 19-237    |
| ### Requirements Overview             | 21-132    |
| ### Technical Constraints             | 133-176   |
| ### Cross-Cutting Concerns            | 177-237   |
| ## Starter Template Evaluation        | 239-370   |
| ### Primary Technology Domain         | 241-244   |
| ### Project Initialization Status     | 245-250   |
| ### Current Technology Stack          | 251-272   |
| ### Architectural Decisions Made      | 273-321   |
| ### Remaining Setup Tasks             | 322-347   |
| ### Architecture Foundation           | 348-370   |
| ## Core Architectural Decisions       | 372-814   |
| ### Decision Priority Analysis        | 374-396   |
| ### Data Architecture                 | 397-484   |
| ### Authentication & Security         | 485-544   |
| ### API & Communication Patterns      | 545-597   |
| ### Frontend Architecture             | 598-703   |
| ### Infrastructure & Deployment       | 704-769   |
| ### Decision Impact Analysis          | 770-814   |
| ## Implementation Patterns            | 816-1406  |
| ### Pattern Categories Defined        | 818-821   |
| ### Naming Patterns                   | 822-900   |
| ### Structure Patterns                | 901-1086  |
| ### Format Patterns                   | 1087-1171 |
| ### Communication Patterns            | 1172-1239 |
| ### Process Patterns                  | 1240-1324 |
| ### Enforcement Guidelines            | 1325-1406 |
| ### Pattern Examples                  | 1408-1588 |
| ## Project Structure & Boundaries     | 1589-2345 |
| ### Complete Directory Structure      | 1591-1934 |
| ### Architectural Boundaries          | 1935-2064 |
| ### Requirements to Structure Mapping | 2065-2163 |
| ### Integration Points                | 2164-2302 |
| ### File Organization Patterns        | 2303-2332 |
| ### Development Workflow Integration  | 2333-2345 |

#### `docs/2-solutioning/epics.md` (3025 lines)

| Header                               | Lines     |
| ------------------------------------ | --------- |
| # Tact - Epic Breakdown              | 19        |
| ## Overview                          | 21-24     |
| ## Requirements Inventory            | 25-580    |
| ### Functional Requirements          | 27-168    |
| ### NonFunctional Requirements       | 169-243   |
| ### Additional Requirements          | 244-408   |
| ### FR Coverage Map                  | 409-580   |
| ## Epic List                         | 582-839   |
| ### Epic 1: Foundation & Auth        | 584-607   |
| ### Epic 2: Master Data Management   | 608-628   |
| ### Epic 3: Product Management       | 629-651   |
| ### Epic 4: Product Discovery        | 652-678   |
| ### Epic 5: Shopping Cart & Checkout | 679-704   |
| ### Epic 6: Promotion & Loyalty      | 705-729   |
| ### Epic 7: Order Management         | 730-755   |
| ### Epic 8: Point of Sale (POS)      | 756-786   |
| ### Epic 9: Inventory Management     | 787-815   |
| ### Epic 10: Dashboard & Reports     | 816-839   |
| ## Epic 1: Stories (1.1-1.8)         | 840-1181  |
| ### Story 1.1: Project Setup         | 844-868   |
| ### Story 1.2: Customer Registration | 869-907   |
| ### Story 1.3: Customer Login        | 908-949   |
| ### Story 1.4: Google OAuth          | 950-991   |
| ### Story 1.5: Profile Management    | 992-1037  |
| ### Story 1.6: Staff Authentication  | 1038-1082 |
| ### Story 1.7: RBAC                  | 1083-1131 |
| ### Story 1.8: User Management       | 1132-1181 |
| ## Epic 2: Stories (2.1-2.3)         | 1182-1321 |
| ### Story 2.1: Category Management   | 1186-1230 |
| ### Story 2.2: Brand Management      | 1231-1274 |
| ### Story 2.3: Supplier Management   | 1275-1321 |
| ## Epic 3: Stories (3.1-3.5)         | 1322-1558 |
| ### Story 3.1: Create Product        | 1326-1367 |
| ### Story 3.2: Product Image Upload  | 1368-1412 |
| ### Story 3.3: Product Specs         | 1413-1453 |
| ### Story 3.4: Product List & Search | 1454-1508 |
| ### Story 3.5: Update & Delete       | 1509-1558 |
| ## Epic 4: Stories (4.1-4.7)         | 1559-1905 |
| ### Story 4.1: Homepage              | 1563-1608 |
| ### Story 4.2: Product Listing       | 1609-1663 |
| ### Story 4.3: Product Search        | 1664-1713 |
| ### Story 4.4: Product Detail        | 1714-1763 |
| ### Story 4.5: Image Gallery         | 1764-1811 |
| ### Story 4.6: Stock Availability    | 1812-1861 |
| ### Story 4.7: Warranty Info         | 1862-1905 |
| ## Epic 5: Stories (5.1-5.5)         | 1906-2067 |
| ### Story 5.1: Add to Cart           | 1910-1956 |
| ### Story 5.2: Cart Management       | 1957-2011 |
| ### Story 5.3: Voucher & Points      | 2012-2067 |
| ### Story 5.4: Checkout Process      | 2068-...  |
| ## Epic 6-10: Stories                | ...       |

#### `docs/2-solutioning/test-design-system.md` (628 lines)

| Header                               | Lines   |
| ------------------------------------ | ------- |
| # System-Level Test Design - Tact    | 1-10    |
| ## Executive Summary                 | 11-30   |
| ## Testability Assessment            | 31-168  |
| ### 1. Controllability: ✅ PASS      | 33-80   |
| ### 2. Observability: ✅ PASS        | 81-130  |
| ### 3. Reliability: ⚠️ CONCERNS      | 131-168 |
| ## Architecturally Significant Reqs  | 170-206 |
| ### High-Priority ASRs (Score ≥6)    | 174-183 |
| ### Medium-Priority ASRs             | 184-191 |
| ### Low-Priority ASRs                | 192-206 |
| ## Test Levels Strategy              | 208-279 |
| ### Rationale                        | 212-235 |
| ### Test Levels by Component         | 236-250 |
| ### Test Environment Requirements    | 251-279 |
| ## NFR Testing Approach              | 281-510 |
| ### Security Testing                 | 283-343 |
| ### Performance Testing              | 344-399 |
| ### Reliability Testing              | 400-453 |
| ### Maintainability Testing          | 454-510 |
| ## Testability Concerns              | 512-729 |
| ### 🚨 Critical Concerns (BLOCKERS)  | 514-622 |
| ### ⚠️ High Concerns (SHOULD FIX)    | 623-729 |
| ## Recommendations for Sprint 0      | 731-842 |
| ### Phase 1: Critical Infrastructure | 735-764 |
| ### Phase 2: Testing Tools           | 765-795 |
| ### Phase 3: NFR Testing             | 796-824 |
| ### Phase 4: Documentation           | 825-842 |
| ## Gate Decision Criteria            | 844-893 |
| ### ✅ PASS Criteria                 | 848-875 |
| ### ⚠️ CONCERNS Criteria             | 876-883 |
| ### ❌ FAIL Criteria                 | 884-893 |
| ## Summary                           | 895-628 |

#### `docs/2-solutioning/implementation-readiness-report-2025-12-14.md` (924 lines)

| Header                              | Lines     |
| ----------------------------------- | --------- |
| # Implementation Readiness Report   | 20        |
| ## Document Inventory               | 25-49     |
| ### Documents Discovered            | 27-49     |
| ## PRD Analysis                     | 51-353    |
| ### Document Overview               | 53-60     |
| ### Functional Requirements         | 61-231    |
| ### Non-Functional Requirements     | 232-329   |
| ### PRD Completeness Assessment     | 330-353   |
| ## Epic Coverage Validation         | 355-489   |
| ### Document Overview               | 357-365   |
| ### Epic FR Coverage Extracted      | 366-419   |
| ### FR Coverage Analysis            | 420-439   |
| ### Missing Requirements            | 440-449   |
| ### Coverage Statistics             | 450-457   |
| ### Coverage Quality Assessment     | 458-489   |
| ## UX Alignment Assessment          | 491-751   |
| ### UX Document Status              | 493-502   |
| ### UX Document Structure           | 503-519   |
| ### UX ↔ PRD Alignment Analysis     | 520-599   |
| ### UX ↔ Architecture Alignment     | 600-675   |
| ### Alignment Issues Summary        | 676-694   |
| ### Warnings                        | 695-717   |
| ### UX Completeness Score           | 718-751   |
| ## Epic Quality Review              | 753-1019  |
| ### Review Methodology              | 755-764   |
| ### Epic Structure Validation       | 765-821   |
| ### Story Quality Assessment        | 822-893   |
| ### Dependency Analysis             | 894-936   |
| ### Special Implementation Checks   | 937-959   |
| ### Best Practices Compliance       | 960-981   |
| ### Quality Findings by Severity    | 982-1020  |
| ### Epic Quality Score              | 1021-1059 |
| ## Summary and Recommendations      | 1061-924  |
| ### Overall Readiness Status        | 1063-1068 |
| ### Assessment Summary by Category  | 1069-1106 |
| ### Critical Issues                 | 1107-1112 |
| ### High Priority Recommendations   | 1113-1142 |
| ### Medium Priority Recommendations | 1143-1172 |
| ### Recommended Next Steps          | 1173-1190 |
| ### Strengths to Leverage           | 1191-1222 |
| ### Risks to Monitor                | 1223-1248 |
| ### Final Note                      | 1249-924  |

#### `docs/2-solutioning/ux-implementation-priorities.md` (164 lines)

| Header                                 | Lines   |
| -------------------------------------- | ------- |
| # UX Implementation Priorities         | 1-6     |
| ## Core UX (Week 1-6) - MUST HAVE      | 8-82    |
| ### Trust Signals (Critical)           | 10-30   |
| ### Speed & Performance (Critical)     | 32-48   |
| ### Clarity & Usability (Critical)     | 50-66   |
| ### Mobile-First Basics (Critical)     | 68-82   |
| ## Polish UX (Week 7-8) - NICE TO HAVE | 84-122  |
| ### Micro-Interactions (Defer)         | 86-102  |
| ### Advanced Visual Polish (Defer)     | 104-116 |
| ### Advanced Interactions (Defer)      | 118-122 |
| ## Implementation Strategy             | 124-142 |
| ## Decision Framework                  | 144-152 |
| ## Success Metrics                     | 154-164 |

#### `docs/2-solutioning/offline-pos-design.md` (300 lines)

| Header                         | Lines   |
| ------------------------------ | ------- |
| # Offline POS Design - Tact    | 1-6     |
| ## Problem Statement           | 8-14    |
| ## Offline-First POS Strategy  | 16-68   |
| ### Architecture Approach      | 18-38   |
| ### Data Sync Strategy         | 40-68   |
| ### Offline Transaction Flow   | 70-90   |
| ### UX Design for Offline Mode | 92-180  |
| ### Conflict Resolution        | 182-210 |
| ### Implementation Plan        | 212-240 |
| ### Technical Specifications   | 242-280 |
| ### Success Metrics            | 282-300 |

#### `docs/2-solutioning/image-optimization-sla.md` (257 lines)

| Header                          | Lines   |
| ------------------------------- | ------- |
| # Image Optimization SLA - Tact | 1-6     |
| ## Problem Statement            | 8-14    |
| ## Image Optimization Standards | 16-80   |
| ### File Size Limits            | 18-50   |
| ### Format Requirements         | 52-66   |
| ### Responsive Images           | 68-80   |
| ### Lazy Loading                | 82-96   |
| ### Image Optimization Workflow | 98-140  |
| ### Implementation Code         | 142-190 |
| ### Validation Rules            | 192-220 |
| ### Performance Monitoring      | 222-240 |
| ### Staff Training              | 242-257 |

#### `docs/2-solutioning/database-trigger-performance-plan.md` (322 lines)

| Header                              | Lines   |
| ----------------------------------- | ------- |
| # Database Trigger Performance Plan | 1-6     |
| ## Problem Statement                | 8-16    |
| ## Triggers Overview                | 18-70   |
| ### Trigger 1: update_stock         | 20-45   |
| ### Trigger 2: add_points           | 46-70   |
| ## Performance Testing Plan         | 72-180  |
| ### Test Environment Setup          | 74-90   |
| ### Test Scenarios                  | 92-180  |
| ### Performance Benchmarks          | 182-200 |
| ### Optimization Strategies         | 202-260 |
| ### Testing Schedule                | 262-290 |
| ### Monitoring & Alerts             | 292-310 |
| ### Success Criteria                | 312-322 |

---

### Phase 3: Implementation

#### `docs/3-implementation/sprint-status.yaml` (123 lines)

| Section              | Lines   |
| -------------------- | ------- |
| # STATUS DEFINITIONS | 1-30    |
| # WORKFLOW NOTES     | 32-40   |
| development_status   | 42-123  |
| - epic-1 (8 stories) | 44-58   |
| - epic-2 (3 stories) | 60-66   |
| - epic-3 (5 stories) | 68-76   |
| - epic-4 (7 stories) | 78-88   |
| - epic-5 (5 stories) | 90-98   |
| - epic-6 (4 stories) | 100-107 |
| - epic-7 (5 stories) | 109-117 |
| - epic-8 (6 stories) | 119-123 |

#### `docs/3-implementation/1-1-project-setup-database-schema.md` (448 lines)

| Header                      | Lines   |
| --------------------------- | ------- |
| # Story 1.1: Project Setup  | 1-6     |
| ## Story                    | 8-14    |
| ## Acceptance Criteria      | 16-40   |
| ## Tasks / Subtasks         | 42-120  |
| ## Dev Notes                | 122-380 |
| ### 📋 Quick Reference Card | 124-140 |
| ### Critical Project Rules  | 142-180 |
| ### Architecture Patterns   | 182-240 |
| ### Implementation Guidance | 242-280 |
| ### Project Structure       | 282-320 |
| ### References              | 322-340 |
| ### Anti-Patterns to Avoid  | 342-360 |
| ### Testing Requirements    | 362-380 |
| ### Troubleshooting Guide   | 382-400 |
| ## Dev Agent Record         | 402-448 |
| ### Context Reference       | 404-406 |
| ### Code Review Fixes       | 408-420 |
| ### Completion Notes List   | 422-435 |
| ### File List               | 436-448 |

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
