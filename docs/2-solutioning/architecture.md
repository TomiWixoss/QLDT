---
stepsCompleted: [1, 2, 3, 4, 5, 6, 7]
inputDocuments:
    - "docs/prd.md"
    - "docs/ux-design-specification.md"
workflowType: "architecture"
lastStep: 7
completed: true
completedDate: "2025-12-14"
project_name: "Tact"
user_name: "TomiSakae"
date: "2025-12-14"
---

# Architecture Decision Document

_This document builds collaboratively through step-by-step discovery. Sections are appended as we work through each architectural decision together._

## Project Context Analysis

### Requirements Overview

**Functional Requirements:**

Tact là hệ thống quản lý cửa hàng điện thoại O2O (Online-to-Offline) với 12 modules chính:

**Customer-Facing Features:**

-   Product catalog với filter, sort, search (categories, brands, price ranges)
-   Shopping cart với voucher và loyalty points integration
-   Checkout flow với multiple payment methods
-   Order tracking với visual timeline
-   Account management (profile, points balance, order history)
-   Google OAuth + Email/Password authentication

**Sales Staff Features (POS):**

-   Quick customer lookup by phone number
-   Product search với barcode scanning
-   IMEI input và validation
-   Real-time voucher validation và points calculation
-   Multiple payment methods (cash, card, transfer)
-   Invoice generation với IMEI display

**Warehouse Features:**

-   Stock in/out management với multi-product support
-   Inventory alerts (low stock < 5 items, dead stock > 30 days)
-   Stock movement history với filtering
-   High-value transaction confirmation (> 50M threshold)

**Manager/Admin Features:**

-   Dashboard với real-time charts (revenue, inventory, alerts)
-   CRUD operations cho 12 modules (roles, users, customers, categories, brands, suppliers, products, product_specs, stock_movements, promotions, orders, order_items)
-   Reports (revenue, products, inventory turnover)
-   User management với role-based access control

**Non-Functional Requirements:**

**Performance:**

-   Page load time: < 2 seconds (First Contentful Paint < 1.5s, LCP < 2.5s)
-   POS transaction response: < 1 second
-   Database query time: < 100ms
-   Concurrent users: 50+ without degradation
-   60fps smooth animations

**Security:**

-   CSRF protection enabled
-   Password hashing (bcrypt)
-   SQL injection prevention (Eloquent ORM)
-   XSS prevention (Blade escaping)
-   100% transactions encrypted
-   Role-based access control (4 roles: Admin, Manager, Sales, Warehouse)

**Reliability:**

-   System uptime: 99%+
-   Data backup: Daily automated
-   Recovery time: < 5 phút if system down
-   Zero data loss incidents

**Scalability:**

-   50+ đơn hàng/ngày processed successfully
-   Support for future multi-store expansion
-   Database design supports growth

**Usability:**

-   Mobile-first responsive design (375px base breakpoint)
-   Touch-optimized interfaces (44x44px minimum touch targets)
-   Browser compatibility: Chrome, Firefox, Safari, Edge (latest 2 versions)
-   WCAG 2.1 Level A accessibility compliance

**SEO (Customer-facing pages only):**

-   Meta tags và Open Graph
-   Structured data (Product schema)
-   Sitemap.xml auto-generated
-   robots.txt configured

**Scale & Complexity:**

-   **Primary domain**: Full-Stack Web Application (Laravel MPA with Progressive Enhancement)
-   **Complexity level**: Medium
    -   12 database tables với complex relationships
    -   4 user roles với granular permissions
    -   O2O integration (online + offline channels)
    -   IMEI-level tracking cho high-value items
    -   Voucher + Loyalty points system với auto-calculation
    -   Real-time inventory sync via database triggers
    -   Dual experience design (Customer vs Admin)
-   **Estimated architectural components**: 15-20 major components
    -   Authentication & Authorization
    -   Product Management
    -   Order Management
    -   POS System
    -   Inventory Management
    -   Voucher System
    -   Loyalty Points System
    -   Dashboard & Analytics
    -   Reporting Engine
    -   Customer Management
    -   User Management
    -   Payment Processing
    -   Notification System
    -   Search & Filtering
    -   Image Management

### Technical Constraints & Dependencies

**Technology Stack (Fixed):**

-   **Backend**: Laravel 12 (PHP 8.2+)
-   **Frontend**: Tailwind CSS 4 + DaisyUI 5
-   **Build Tool**: Vite 7 với Laravel Vite Plugin
-   **Database**: MySQL (12 tables, 2 triggers)
-   **Authentication**: Laravel Breeze + Socialite (Google OAuth)

**Timeline Constraint:**

-   8 buổi học (academic project)
-   Cần architecture rõ ràng để implement nhanh
-   Phải có khả năng scale và commercialize sau này

**Performance Constraints:**

-   < 2s page load time (mobile-first)
-   < 1s POS response time (critical for sales flow)
-   < 100ms database queries
-   60fps animations (CSS-only, no heavy JS)

**Design Constraints:**

-   Nike-inspired aesthetics cho customer site
-   DaisyUI components cho admin site
-   Mobile-first approach (375px base)
-   Performance-first (no heavy animations libraries)

**Business Constraints:**

-   Vietnamese market focus (Google OAuth, local payments)
-   Phone retail specialized (IMEI tracking, warranty info)
-   Trust-building required (85% customers worry about counterfeit)
-   O2O model (seamless online-offline integration)

**Data Constraints:**

-   IMEI must be tracked for every phone sold
-   Inventory accuracy target: 95%+
-   Transaction audit trail required
-   Daily automated backups

### Cross-Cutting Concerns Identified

**1. Authentication & Authorization**

-   Affects: All modules
-   Requirements: 4 roles (Admin, Manager, Sales, Warehouse), Google OAuth + password, Laravel middleware
-   Architectural Impact: Need centralized auth service, role-based route protection, permission checking

**2. Performance Optimization**

-   Affects: All customer-facing pages, POS interface
-   Requirements: < 2s load, 60fps animations, lazy loading, WebP images
-   Architectural Impact: Asset optimization strategy, caching layer, database query optimization, CDN consideration

**3. Security**

-   Affects: All modules handling sensitive data
-   Requirements: CSRF, bcrypt, SQL injection prevention, XSS protection, encrypted transactions
-   Architectural Impact: Security middleware, input validation, output escaping, secure session management

**4. Data Consistency**

-   Affects: Inventory, Orders, Points
-   Requirements: Stock updates automatic, points calculation automatic, no race conditions
-   Architectural Impact: Database triggers, optimistic locking, transaction management

**5. Mobile-First Design**

-   Affects: All customer-facing UI
-   Requirements: 375px base, touch-optimized, bottom navigation, swipe gestures
-   Architectural Impact: Responsive component library, mobile-specific interactions, progressive enhancement

**6. Trust & Transparency**

-   Affects: Product pages, Order pages, Checkout
-   Requirements: IMEI tracking visible, warranty info prominent, trust badges, security signals
-   Architectural Impact: IMEI data model, warranty calculation, badge components, trust signal placement

**7. Monitoring & Alerts**

-   Affects: Inventory, Orders, Dashboard
-   Requirements: Low stock alerts, dead stock alerts, high-value transaction confirmation
-   Architectural Impact: Alert system, notification service, threshold configuration, dashboard widgets

**8. Audit Trail**

-   Affects: Orders, Inventory, Transactions
-   Requirements: Transaction history, IMEI records, order timeline, stock movements
-   Architectural Impact: Audit logging, timeline generation, history tracking, immutable records

**9. Real-Time Updates**

-   Affects: POS, Dashboard, Inventory
-   Requirements: Stock sync immediate, dashboard refresh, order status updates
-   Architectural Impact: Polling strategy (MVP), WebSocket consideration (future), optimistic UI updates

**10. Dual Experience Design**

-   Affects: Frontend architecture
-   Requirements: Customer site (Nike-inspired) vs Admin site (functional DaisyUI)
-   Architectural Impact: Separate layout templates, shared components, different styling approaches

## Starter Template Evaluation

### Primary Technology Domain

**Full-Stack Web Application (Laravel-based)** với Multi-Page Application (MPA) architecture và Progressive Enhancement approach.

### Project Initialization Status

**Status**: ✅ **Project Already Initialized**

Dự án Tact đã được khởi tạo và cấu hình với stack hiện đại:

### Current Technology Stack

**Backend Framework:**

-   Laravel 12.0 (PHP 8.2+)
-   Laravel Framework với routing, middleware, exceptions configured
-   Database: MySQL (configured, migrations pending)

**Frontend Stack:**

-   Tailwind CSS 4.0.0 với @tailwindcss/vite plugin
-   DaisyUI 5.5.13 component library
-   Vite 7.0.7 với Laravel Vite Plugin 2.0.0
-   Axios 1.11.0 cho AJAX requests

**Development Tools:**

-   Concurrently 9.0.1 (multi-process runner)
-   Laravel Pail (real-time log viewer)
-   Laravel Pint (PHP code formatter)
-   PHPUnit 11.5.3 (testing framework)

### Architectural Decisions Made by Current Setup

**Language & Runtime:**

-   PHP 8.2+ với Laravel 12 framework
-   Modern PHP features (attributes, enums, readonly properties)
-   Composer autoloading (PSR-4)

**Styling Solution:**

-   Tailwind CSS 4 với native CSS features (@theme, @source)
-   DaisyUI 5 component library cho pre-built components
-   Custom font: "Instrument Sans" (premium typography)
-   CSS-first approach (no heavy JS for styling)

**Build Tooling:**

-   Vite 7 với hot module replacement (HMR)
-   Laravel Vite Plugin cho seamless integration
-   Asset optimization automatic (code splitting, tree shaking)
-   Fast build times (< 1s for development)

**Testing Framework:**

-   PHPUnit 11.5.3 configured
-   Laravel testing utilities available
-   Feature tests và Unit tests support

**Code Organization:**

-   Laravel MVC structure (Models, Views, Controllers)
-   PSR-4 autoloading (App\, Database\Factories\, Database\Seeders\)
-   Blade templating engine cho views
-   Resource organization (css, js trong resources/)

**Development Experience:**

-   Hot reloading với Vite (instant feedback)
-   Concurrent development servers (artisan serve + vite + queue + logs)
-   Laravel Pail cho real-time log monitoring
-   Laravel Pint cho code formatting consistency

**Security Defaults:**

-   CSRF protection enabled (Laravel default)
-   XSS prevention (Blade escaping)
-   SQL injection prevention (Eloquent ORM)
-   Bcrypt password hashing (Laravel default)

### Remaining Setup Tasks

**Authentication:**

-   [ ] Install Laravel Breeze cho authentication scaffolding
-   [ ] Configure Google OAuth với Laravel Socialite
-   [ ] Setup 4 roles (Admin, Manager, Sales, Warehouse)

**Database:**

-   [ ] Create 12 database migrations
-   [ ] Setup database triggers (stock update, points calculation)
-   [ ] Configure database relationships

**Frontend Components:**

-   [ ] Create layout templates (customer vs admin)
-   [ ] Setup DaisyUI theme configuration
-   [ ] Implement responsive breakpoints (375px base)

**Additional Packages:**

-   [ ] Laravel Socialite cho Google OAuth
-   [ ] Image optimization package (WebP support)
-   [ ] Chart.js cho dashboard analytics

### Architecture Foundation Assessment

**Strengths:**
✅ Modern, performant stack (Laravel 12 + Vite 7 + Tailwind 4)
✅ CSS-first approach aligns với performance goals (< 2s load)
✅ DaisyUI provides component foundation cho rapid development
✅ Vite HMR enables fast development iteration
✅ Laravel MVC structure supports clean separation of concerns

**Alignment with Requirements:**
✅ Mobile-first ready (Tailwind responsive utilities)
✅ Performance-first (Vite optimization, CSS-only animations)
✅ Security defaults (CSRF, XSS, SQL injection prevention)
✅ Scalable architecture (Laravel patterns, database design)
✅ Developer experience optimized (hot reload, concurrent servers)

**Next Steps:**

1. Install Laravel Breeze cho authentication foundation
2. Create database schema (12 tables + 2 triggers)
3. Setup role-based authorization middleware
4. Configure DaisyUI theme cho dual experience (customer vs admin)
5. Implement core modules theo PRD requirements

## Core Architectural Decisions

### Decision Priority Analysis

**Critical Decisions (Block Implementation):**

-   Database schema design approach
-   Authentication & authorization strategy
-   Data consistency mechanisms
-   Frontend architecture patterns

**Important Decisions (Shape Architecture):**

-   API design patterns
-   Caching strategy
-   File storage approach
-   Performance optimization techniques

**Deferred Decisions (Post-MVP):**

-   Multi-store support
-   Advanced analytics
-   Mobile app architecture
-   Microservices migration

### Data Architecture

**Decision 1.1: Database Schema Design Approach**

-   **Choice**: Hybrid Approach (SQL-First with Migration Conversion)
-   **Rationale**:
    -   Database schema đã được thiết kế chi tiết trong `database/db.sql`
    -   12 bảng với relationships phức tạp đã được định nghĩa rõ ràng
    -   2 triggers (update_stock, add_points) đã được implement
    -   Cần convert sang Laravel migrations để version control và team collaboration
-   **Implementation**:
    -   Giữ `db.sql` làm reference documentation
    -   Tạo Laravel migrations tương ứng cho từng bảng
    -   Implement triggers trong migrations hoặc Eloquent observers
    -   Use migrations cho development, db.sql cho quick setup

**Decision 1.2: Database Relationships**

-   **Choice**: Eloquent ORM với Explicit Relationships
-   **Current Schema Analysis**:
    -   **One-to-Many**: roles → users, categories → products, brands → products
    -   **One-to-One**: products → product_specs
    -   **Many-to-Many**: orders ↔ products (through order_items)
    -   **Polymorphic**: None (not needed for MVP)
-   **Implementation**:
    -   Define relationships trong Eloquent models
    -   Use eager loading để prevent N+1 queries
    -   Foreign key constraints đã có trong schema
    -   Cascade deletes cho product_specs và order_items

**Decision 1.3: Data Validation Strategy**

-   **Choice**: Multi-Layer Validation
-   **Layers**:
    1. **Database Level**: NOT NULL, UNIQUE, FOREIGN KEY constraints (đã có)
    2. **Model Level**: Laravel validation rules trong Form Requests
    3. **Business Logic Level**: Custom validation trong Services
-   **Key Validations**:
    -   IMEI format validation (15 digits)
    -   Email uniqueness (customers.email)
    -   SKU uniqueness (products.sku)
    -   Stock quantity >= 0
    -   Price > 0
    -   Promotion dates (start_date < end_date)

**Decision 1.4: IMEI Tracking Implementation**

-   **Choice**: JSON Storage in order_items.imei_list
-   **Current Schema**: TEXT field cho flexibility
-   **Rationale**:
    -   Mỗi order_item có thể có nhiều IMEI (quantity > 1)
    -   JSON format: `["123456789012345", "123456789012346"]`
    -   Easy to query và validate
    -   No need for separate imei table (MVP scope)
-   **Implementation**:
    -   Cast to array trong Eloquent model
    -   Validate IMEI format trước khi save
    -   Display IMEI trên invoice và order detail

**Decision 1.5: Database Triggers vs Eloquent Events**

-   **Choice**: Hybrid Approach
-   **Database Triggers** (Keep existing):
    -   `update_stock`: Auto update products.quantity khi có stock_movements
    -   `add_points`: Auto tích điểm khi order completed
-   **Eloquent Observers** (Add for flexibility):
    -   Order status change notifications
    -   Audit logging
    -   Cache invalidation
-   **Rationale**:
    -   Triggers ensure data consistency at DB level
    -   Observers provide application-level hooks
    -   Best of both worlds

**Decision 1.6: Caching Strategy**

-   **Choice**: Laravel Cache với Redis (Production) / File (Development)
-   **Cache Targets**:
    -   Product catalog (categories, brands, products) - 1 hour TTL
    -   Customer points balance - Invalidate on order complete
    -   Dashboard statistics - 5 minutes TTL
    -   Promotion codes - Until promotion updated
-   **Implementation**:
    -   Use Laravel Cache facade
    -   Cache tags cho group invalidation
    -   Remember queries cho frequently accessed data
    -   Cache warming cho critical data

### Authentication & Security

**Decision 2.1: Authentication Strategy**

-   **Choice**: Laravel Breeze + Socialite (Dual Authentication)
-   **Approaches**:
    1. **Staff Authentication** (users table):
        - Email/Password only
        - Laravel Breeze default
        - Session-based
    2. **Customer Authentication** (customers table):
        - Google OAuth (primary)
        - Email/Password (fallback)
        - Laravel Socialite
-   **Implementation**:
    -   Separate auth guards: `web` (staff), `customer` (customers)
    -   Separate login routes: `/admin/login`, `/login`
    -   Google OAuth callback: `/auth/google/callback`
    -   Password required after first Google login

**Decision 2.2: Authorization Strategy**

-   **Choice**: Role-Based Access Control (RBAC) với Laravel Gates & Policies
-   **4 Roles** (đã có trong schema):
    1. **Admin**: Full access
    2. **Manager**: All except user management
    3. **Sales**: POS, orders, customers (read-only products)
    4. **Warehouse**: Stock management, products (read-only)
-   **Implementation**:
    -   Define Gates trong `AuthServiceProvider`
    -   Create Policies cho major resources (Product, Order, Customer)
    -   Middleware: `role:admin,manager`
    -   Blade directives: `@can('manage-products')`

**Decision 2.3: Security Measures**

-   **Choice**: Laravel Security Defaults + Custom Enhancements
-   **Implemented**:
    -   ✅ CSRF protection (Laravel default)
    -   ✅ XSS prevention (Blade escaping)
    -   ✅ SQL injection prevention (Eloquent ORM)
    -   ✅ Password hashing (bcrypt)
-   **Additional Measures**:
    -   Rate limiting: 60 requests/minute per IP
    -   Login throttling: 5 attempts per minute
    -   HTTPS enforcement (production)
    -   Secure session cookies (httpOnly, secure, sameSite)
    -   Input sanitization (strip_tags, htmlspecialchars)
    -   File upload validation (MIME type, size, extension)

**Decision 2.4: API Security (Future)**

-   **Choice**: Laravel Sanctum (when API needed)
-   **Deferred to Post-MVP**: No API endpoints trong MVP
-   **Future Implementation**:
    -   Token-based authentication
    -   API rate limiting
    -   CORS configuration
    -   API versioning

### API & Communication Patterns

**Decision 3.1: Internal Communication**

-   **Choice**: Traditional MPA với AJAX Enhancement
-   **Patterns**:
    -   **Page Navigation**: Full page reload (Blade rendering)
    -   **Dynamic Updates**: Axios AJAX requests
    -   **Form Submissions**: Standard POST với AJAX fallback
-   **AJAX Use Cases**:
    -   Product search autocomplete
    -   Cart operations (add, remove, update)
    -   Voucher validation
    -   Dashboard data refresh
    -   POS customer lookup

**Decision 3.2: Data Exchange Format**

-   **Choice**: JSON for AJAX, Blade for Pages
-   **Response Formats**:
    -   AJAX endpoints: `{ success: true, data: {...}, message: "..." }`
    -   Error responses: `{ success: false, errors: {...}, message: "..." }`
    -   Pagination: Laravel default format
-   **Implementation**:
    -   Controller methods return `response()->json()`
    -   Consistent error handling
    -   HTTP status codes: 200 (success), 422 (validation), 500 (error)

**Decision 3.3: Real-Time Updates Strategy**

-   **Choice**: Polling (MVP) → WebSocket (Future)
-   **MVP Approach**:
    -   Dashboard: AJAX polling every 30 seconds
    -   POS inventory: Optimistic updates + refresh on conflict
    -   Order status: Manual refresh or page reload
-   **Future Enhancement**:
    -   Laravel Echo + Pusher for real-time
    -   WebSocket for POS multi-terminal sync
    -   Push notifications for order updates

**Decision 3.4: Error Handling Standards**

-   **Choice**: Centralized Error Handling
-   **Layers**:
    1. **Validation Errors**: Form Request validation
    2. **Business Logic Errors**: Custom exceptions
    3. **System Errors**: Laravel exception handler
-   **User-Facing Messages**:
    -   Vietnamese language
    -   Friendly, actionable messages
    -   No technical details exposed
    -   Log technical details for debugging

### Frontend Architecture

**Decision 4.1: Layout Architecture**

-   **Choice**: Dual Layout System
-   **Layouts**:
    1. **Customer Layout** (`resources/views/layouts/customer.blade.php`):
        - Nike-inspired design
        - Bottom navigation (mobile)
        - Generous whitespace
        - Premium typography (Instrument Sans)
    2. **Admin Layout** (`resources/views/layouts/admin.blade.php`):
        - DaisyUI components
        - Sidebar navigation
        - Information-dense
        - Functional design
-   **Shared Components**:
    -   Header, Footer, Alerts, Modals
    -   Reusable Blade components

**Decision 4.2: Component Organization**

-   **Choice**: Blade Components + Tailwind CSS
-   **Structure**:
    ```
    resources/views/
    ├── components/
    │   ├── customer/
    │   │   ├── product-card.blade.php
    │   │   ├── cart-item.blade.php
    │   │   └── order-timeline.blade.php
    │   ├── admin/
    │   │   ├── data-table.blade.php
    │   │   ├── stat-card.blade.php
    │   │   └── chart.blade.php
    │   └── shared/
    │       ├── alert.blade.php
    │       ├── modal.blade.php
    │       └── button.blade.php
    ├── customer/
    │   ├── home.blade.php
    │   ├── products/
    │   └── orders/
    └── admin/
        ├── dashboard.blade.php
        ├── products/
        └── orders/
    ```

**Decision 4.3: State Management**

-   **Choice**: Server-Side State với Session
-   **Approach**:
    -   Cart: Session storage
    -   User data: Session + Database
    -   Filters: Query parameters
    -   No client-side state management library needed
-   **Session Data**:
    -   Shopping cart items
    -   Applied voucher code
    -   Selected filters
    -   Last viewed products

**Decision 4.4: Asset Organization**

-   **Choice**: Vite-Based Asset Pipeline
-   **Structure**:
    ```
    resources/
    ├── css/
    │   ├── app.css (Tailwind + DaisyUI)
    │   ├── customer.css (Nike-inspired styles)
    │   └── admin.css (Admin-specific)
    ├── js/
    │   ├── app.js (Global scripts)
    │   ├── customer.js (Customer-specific)
    │   ├── admin.js (Admin-specific)
    │   └── pos.js (POS interface)
    └── images/
        ├── products/
        ├── brands/
        └── ui/
    ```
-   **Build Strategy**:
    -   Separate bundles: customer.js, admin.js, pos.js
    -   Code splitting automatic (Vite)
    -   Lazy load images (loading="lazy")
    -   WebP format cho product images

**Decision 4.5: Performance Optimization**

-   **Choice**: Multi-Layer Optimization
-   **Techniques**:
    1. **Critical CSS**: Inline above-fold styles
    2. **Lazy Loading**: Images below fold
    3. **Code Splitting**: Separate bundles per section
    4. **Asset Optimization**: Vite minification, tree shaking
    5. **Image Optimization**: WebP format, responsive srcset
    6. **Database**: Eager loading, query optimization, indexing
    7. **Caching**: Laravel cache, browser cache headers
-   **Performance Targets**:
    -   Page load: < 2s (FCP < 1.5s, LCP < 2.5s)
    -   POS response: < 1s
    -   Database queries: < 100ms
    -   Lighthouse score: 90+

### Infrastructure & Deployment

**Decision 5.1: Development Environment**

-   **Choice**: XAMPP (Current) + Laravel Sail (Optional)
-   **Current Setup**:
    -   XAMPP: Apache + MySQL + PHP 8.2
    -   Vite dev server: Port 5173
    -   Laravel serve: Port 8000
-   **Alternative** (Team collaboration):
    -   Laravel Sail (Docker-based)
    -   Consistent environment across team
    -   Easy onboarding

**Decision 5.2: Environment Configuration**

-   **Choice**: .env Files với Environment-Specific Settings
-   **Environments**:
    -   **Development**: `.env` (local database, debug enabled)
    -   **Staging**: `.env.staging` (test database, debug enabled)
    -   **Production**: `.env.production` (production database, debug disabled)
-   **Key Configurations**:
    -   Database credentials
    -   Google OAuth keys
    -   Cache driver (file → redis)
    -   Session driver (file → redis)
    -   Mail configuration

**Decision 5.3: File Storage Strategy**

-   **Choice**: Local Storage (MVP) → Cloud Storage (Production)
-   **MVP Approach**:
    -   Product images: `storage/app/public/products/`
    -   Brand logos: `storage/app/public/brands/`
    -   User avatars: `storage/app/public/avatars/`
    -   Symbolic link: `php artisan storage:link`
-   **Production Enhancement**:
    -   AWS S3 or DigitalOcean Spaces
    -   CDN for image delivery
    -   Image optimization pipeline

**Decision 5.4: Logging & Monitoring**

-   **Choice**: Laravel Log + Laravel Pail (Development)
-   **Log Channels**:
    -   `daily`: Rotate logs daily
    -   `slack`: Critical errors to Slack (production)
    -   `stack`: Multiple channels
-   **Monitoring**:
    -   Laravel Pail: Real-time log viewer (development)
    -   Laravel Telescope: Debugging tool (development only)
    -   Production: Consider Laravel Forge or custom monitoring

**Decision 5.5: Backup Strategy**

-   **Choice**: Automated Daily Backups
-   **Backup Targets**:
    -   Database: MySQL dump daily
    -   Uploaded files: Storage folder daily
    -   .env file: Encrypted backup
-   **Implementation**:
    -   Laravel Task Scheduling: `php artisan schedule:run`
    -   Backup package: `spatie/laravel-backup`
    -   Storage: Local + Cloud (S3)
    -   Retention: 7 days local, 30 days cloud

### Decision Impact Analysis

**Implementation Sequence:**

1. **Phase 1: Foundation** (Week 1-2)

    - Convert db.sql to Laravel migrations
    - Setup authentication (Breeze + Socialite)
    - Create Eloquent models with relationships
    - Setup authorization (Gates & Policies)
    - Create base layouts (customer + admin)

2. **Phase 2: Core Features** (Week 3-4)

    - Implement CRUD operations (12 modules)
    - Product catalog with search/filter
    - Shopping cart with session
    - Order management with IMEI tracking
    - Stock management with triggers

3. **Phase 3: Advanced Features** (Week 5-6)

    - POS interface
    - Voucher system
    - Loyalty points calculation
    - Dashboard with charts
    - Reports generation

4. **Phase 4: Polish & Optimization** (Week 7-8)
    - Performance optimization
    - Mobile responsiveness
    - UX enhancements (timeline, animations)
    - Testing & bug fixes
    - Demo data & presentation

**Cross-Component Dependencies:**

-   **Authentication** → All protected routes
-   **Authorization** → All admin features
-   **Product Model** → Orders, Cart, POS, Inventory
-   **Order Model** → IMEI tracking, Points, Timeline
-   **Stock Triggers** → Inventory accuracy, Alerts
-   **Cache Layer** → Performance, Dashboard
-   **Session** → Cart, Filters, User state
-   **Vite Build** → All frontend assets

## Implementation Patterns & Consistency Rules

### Pattern Categories Defined

**Critical Conflict Points Identified:** 25+ areas where AI agents could make different choices without explicit patterns.

### Naming Patterns

**Database Naming Conventions:**

**Tables:** (Already defined in db.sql)

-   ✅ Plural, lowercase, snake_case: `users`, `products`, `order_items`
-   ✅ Junction tables: `order_items` (not `orders_products`)
-   ✅ Follow existing schema exactly

**Columns:** (Already defined in db.sql)

-   ✅ snake_case: `full_name`, `created_at`, `order_status`
-   ✅ Foreign keys: `{table}_id` format: `user_id`, `product_id`, `category_id`
-   ✅ Boolean: `status` with ENUM or TINYINT(1)
-   ✅ Timestamps: `created_at`, `updated_at` (Laravel convention)

**Indexes:**

-   Format: `idx_{table}_{column}` or `{table}_{column}_index`
-   Example: `idx_products_sku`, `users_email_unique`

**API Naming Conventions:**

**REST Endpoints:**

-   ✅ Plural nouns: `/api/products`, `/api/orders`, `/api/customers`
-   ✅ Resource actions: `/api/products/{id}`, `/api/products/{id}/edit`
-   ✅ Nested resources: `/api/orders/{orderId}/items`
-   ✅ kebab-case for multi-word: `/api/order-items`, `/api/stock-movements`

**Route Parameters:**

-   ✅ Laravel format: `{id}`, `{productId}`, `{orderId}`
-   ✅ camelCase for multi-word: `{orderId}`, not `{order_id}`

**Query Parameters:**

-   ✅ snake_case: `?category_id=1&sort_by=price&order=asc`
-   ✅ Pagination: `?page=1&per_page=20` (Laravel default)
-   ✅ Filters: `?filter[status]=active&filter[brand_id]=2`

**Code Naming Conventions:**

**PHP Classes:**

-   ✅ PascalCase: `ProductController`, `OrderService`, `CustomerRepository`
-   ✅ Suffixes: `Controller`, `Model`, `Request`, `Service`, `Repository`, `Observer`
-   ✅ Namespaces: `App\Http\Controllers\Admin\ProductController`

**PHP Methods:**

-   ✅ camelCase: `getUserData()`, `createOrder()`, `calculatePoints()`
-   ✅ CRUD: `index()`, `create()`, `store()`, `show()`, `edit()`, `update()`, `destroy()`
-   ✅ Boolean methods: `isActive()`, `hasPermission()`, `canEdit()`

**PHP Variables:**

-   ✅ camelCase: `$userId`, `$productData`, `$orderTotal`
-   ✅ Descriptive names: `$customer` not `$c`, `$products` not `$p`

**Blade Files:**

-   ✅ kebab-case: `product-card.blade.php`, `order-timeline.blade.php`
-   ✅ Components: `<x-product-card />`, `<x-admin.data-table />`
-   ✅ Layouts: `layouts/customer.blade.php`, `layouts/admin.blade.php`

**JavaScript:**

-   ✅ camelCase: `getUserData()`, `productId`, `orderTotal`
-   ✅ Constants: `UPPER_SNAKE_CASE`: `API_BASE_URL`, `MAX_QUANTITY`
-   ✅ Files: kebab-case: `product-search.js`, `cart-manager.js`

**CSS Classes:**

-   ✅ Tailwind utilities: `bg-blue-500`, `text-lg`, `p-4`
-   ✅ Custom classes: kebab-case: `.product-card`, `.order-timeline`
-   ✅ BEM for complex: `.product-card__title`, `.product-card--featured`

### Structure Patterns

**Project Organization:**

**Controllers:**

```
app/Http/Controllers/
├── Admin/
│   ├── DashboardController.php
│   ├── ProductController.php
│   ├── OrderController.php
│   ├── CustomerController.php
│   └── UserController.php
├── Customer/
│   ├── HomeController.php
│   ├── ProductController.php
│   ├── CartController.php
│   └── OrderController.php
└── Auth/
    ├── LoginController.php
    └── GoogleAuthController.php
```

**Models:**

```
app/Models/
├── User.php
├── Role.php
├── Customer.php
├── Product.php
├── Category.php
├── Brand.php
├── Order.php
├── OrderItem.php
├── StockMovement.php
└── Promotion.php
```

**Services (Business Logic):**

```
app/Services/
├── OrderService.php
├── CartService.php
├── InventoryService.php
├── PointsService.php
└── VoucherService.php
```

**Repositories (Data Access):**

```
app/Repositories/
├── ProductRepository.php
├── OrderRepository.php
└── CustomerRepository.php
```

**Requests (Validation):**

```
app/Http/Requests/
├── Admin/
│   ├── StoreProductRequest.php
│   └── UpdateProductRequest.php
└── Customer/
    ├── CheckoutRequest.php
    └── UpdateProfileRequest.php
```

**Views:**

```
resources/views/
├── layouts/
│   ├── customer.blade.php
│   ├── admin.blade.php
│   └── guest.blade.php
├── components/
│   ├── customer/
│   │   ├── product-card.blade.php
│   │   ├── cart-item.blade.php
│   │   └── order-timeline.blade.php
│   ├── admin/
│   │   ├── data-table.blade.php
│   │   ├── stat-card.blade.php
│   │   └── sidebar.blade.php
│   └── shared/
│       ├── alert.blade.php
│       ├── modal.blade.php
│       └── button.blade.php
├── customer/
│   ├── home.blade.php
│   ├── products/
│   │   ├── index.blade.php
│   │   └── show.blade.php
│   ├── cart/
│   │   └── index.blade.php
│   └── orders/
│       ├── index.blade.php
│       └── show.blade.php
└── admin/
    ├── dashboard.blade.php
    ├── products/
    │   ├── index.blade.php
    │   ├── create.blade.php
    │   ├── edit.blade.php
    │   └── show.blade.php
    └── orders/
        ├── index.blade.php
        └── show.blade.php
```

**Tests:**

```
tests/
├── Feature/
│   ├── Admin/
│   │   ├── ProductManagementTest.php
│   │   └── OrderManagementTest.php
│   └── Customer/
│       ├── ProductBrowsingTest.php
│       └── CheckoutTest.php
└── Unit/
    ├── Services/
    │   ├── OrderServiceTest.php
    │   └── PointsServiceTest.php
    └── Models/
        └── ProductTest.php
```

**File Structure Patterns:**

**Migrations:**

-   ✅ Format: `YYYY_MM_DD_HHMMSS_create_tablename_table.php`
-   ✅ Order: Create tables before foreign keys
-   ✅ Example: `2024_12_14_000001_create_roles_table.php`

**Seeders:**

-   ✅ Format: `TablenameSeeder.php`
-   ✅ Example: `RoleSeeder.php`, `ProductSeeder.php`
-   ✅ Order in DatabaseSeeder: roles → users → categories → brands → products

**Config Files:**

-   ✅ Location: `config/`
-   ✅ Custom configs: `config/tact.php` (app-specific settings)
-   ✅ Access: `config('tact.points_per_100k')`

**Assets:**

```
resources/
├── css/
│   ├── app.css (main Tailwind + DaisyUI)
│   ├── customer.css (customer-specific)
│   └── admin.css (admin-specific)
├── js/
│   ├── app.js (global)
│   ├── customer.js (customer-specific)
│   ├── admin.js (admin-specific)
│   └── pos.js (POS interface)
└── images/
    ├── logo.png
    └── placeholder.png
```

**Storage:**

```
storage/app/public/
├── products/
│   └── {product_id}/
│       ├── main.webp
│       └── gallery/
├── brands/
│   └── {brand_id}.webp
└── avatars/
    └── {user_id}.webp
```

### Format Patterns

**API Response Formats:**

**Success Response:**

```json
{
    "success": true,
    "data": {
        "id": 1,
        "name": "iPhone 15 Pro"
    },
    "message": "Product retrieved successfully"
}
```

**Error Response:**

```json
{
    "success": false,
    "message": "Validation failed",
    "errors": {
        "email": ["The email field is required."],
        "password": ["The password must be at least 8 characters."]
    }
}
```

**Pagination Response:**

```json
{
  "success": true,
  "data": [...],
  "meta": {
    "current_page": 1,
    "last_page": 10,
    "per_page": 20,
    "total": 200
  },
  "links": {
    "first": "...",
    "last": "...",
    "prev": null,
    "next": "..."
  }
}
```

**Data Exchange Formats:**

**JSON Field Naming:**

-   ✅ snake_case for API responses: `user_id`, `created_at`, `full_name`
-   ✅ Match database column names exactly
-   ✅ Consistent with Laravel default

**Date/Time Formats:**

-   ✅ Database: `YYYY-MM-DD HH:MM:SS` (MySQL DATETIME)
-   ✅ API responses: ISO 8601: `2024-12-14T10:30:00Z`
-   ✅ Display (Vietnamese): `14/12/2024 10:30`
-   ✅ Carbon for manipulation: `Carbon::parse($date)->format('d/m/Y')`

**Boolean Representations:**

-   ✅ Database: TINYINT(1) or ENUM('active', 'inactive')
-   ✅ JSON: `true`/`false` (not 1/0)
-   ✅ PHP: `true`/`false` boolean type

**IMEI Format:**

-   ✅ Storage: JSON array in TEXT field: `["123456789012345", "123456789012346"]`
-   ✅ Validation: 15 digits exactly
-   ✅ Display: Comma-separated: "123456789012345, 123456789012346"
-   ✅ Eloquent cast: `'imei_list' => 'array'`

**Money Format:**

-   ✅ Database: DECIMAL(12,2) for VND (no decimals needed but keep for flexibility)
-   ✅ Display: `number_format($price, 0, ',', '.')` → "25.000.000"
-   ✅ API: Number (not string): `25000000`

### Communication Patterns

**Event System Patterns:**

**Event Naming:**

-   ✅ PascalCase: `OrderCreated`, `ProductUpdated`, `StockLow`
-   ✅ Past tense for completed actions: `OrderCreated` not `CreateOrder`
-   ✅ Namespace: `App\Events\OrderCreated`

**Event Payload:**

```php
class OrderCreated
{
    public function __construct(
        public Order $order,
        public Customer $customer
    ) {}
}
```

**Listeners:**

-   ✅ Naming: `SendOrderConfirmationEmail`, `UpdateInventory`
-   ✅ Location: `app/Listeners/`
-   ✅ Register in `EventServiceProvider`

**Eloquent Observers:**

-   ✅ Naming: `ProductObserver`, `OrderObserver`
-   ✅ Location: `app/Observers/`
-   ✅ Methods: `creating`, `created`, `updating`, `updated`, `deleting`, `deleted`

**State Management Patterns:**

**Session State (Cart, Filters):**

```php
// Store
session()->put('cart', $cartData);

// Retrieve
$cart = session()->get('cart', []);

// Remove
session()->forget('cart');
```

**Cache State (Products, Categories):**

```php
// Store with tags
Cache::tags(['products'])->put('product_' . $id, $product, 3600);

// Retrieve
$product = Cache::tags(['products'])->get('product_' . $id);

// Invalidate
Cache::tags(['products'])->flush();
```

**Database State (Orders, Inventory):**

-   ✅ Use database transactions for critical operations
-   ✅ Eloquent models for data access
-   ✅ Repositories for complex queries

### Process Patterns

**Error Handling Patterns:**

**Controller Level:**

```php
try {
    $order = $this->orderService->createOrder($request->validated());
    return response()->json([
        'success' => true,
        'data' => $order,
        'message' => 'Đơn hàng đã được tạo thành công'
    ]);
} catch (ValidationException $e) {
    return response()->json([
        'success' => false,
        'message' => 'Dữ liệu không hợp lệ',
        'errors' => $e->errors()
    ], 422);
} catch (\Exception $e) {
    Log::error('Order creation failed: ' . $e->getMessage());
    return response()->json([
        'success' => false,
        'message' => 'Có lỗi xảy ra, vui lòng thử lại'
    ], 500);
}
```

**Service Level:**

```php
public function createOrder(array $data): Order
{
    DB::beginTransaction();
    try {
        $order = Order::create($data);
        $this->updateInventory($order);
        DB::commit();
        return $order;
    } catch (\Exception $e) {
        DB::rollBack();
        throw $e;
    }
}
```

**Validation:**

-   ✅ Use Form Requests for validation
-   ✅ Custom validation rules in `app/Rules/`
-   ✅ Vietnamese error messages in `lang/vi/validation.php`

**Loading State Patterns:**

**Blade (Server-Side):**

```blade
@if($loading)
    <div class="loading loading-spinner"></div>
@else
    <!-- Content -->
@endif
```

**JavaScript (AJAX):**

```javascript
// Show loading
const button = document.querySelector("#submit-btn");
button.disabled = true;
button.innerHTML =
    '<span class="loading loading-spinner"></span> Đang xử lý...';

// Hide loading after response
button.disabled = false;
button.innerHTML = "Hoàn tất";
```

**Skeleton Screens:**

-   ✅ Use DaisyUI skeleton components
-   ✅ Match actual content layout
-   ✅ Animate with pulse effect

### Enforcement Guidelines

**All AI Agents MUST:**

1. **Follow Laravel Conventions:**

    - Use Eloquent ORM (no raw SQL except complex queries)
    - Follow MVC pattern strictly
    - Use Form Requests for validation
    - Use Resource Controllers for CRUD
    - Follow PSR-12 coding standards

2. **Match Database Schema Exactly:**

    - Table names, column names, data types from `database/db.sql`
    - Foreign key constraints as defined
    - Triggers: `update_stock`, `add_points` (keep or convert to observers)
    - No schema changes without updating `db.sql` first

3. **Use Consistent Naming:**

    - PHP: PascalCase classes, camelCase methods/variables
    - Database: snake_case tables/columns
    - Blade: kebab-case files
    - Routes: kebab-case URLs
    - JSON: snake_case fields

4. **Follow Project Structure:**

    - Controllers in `app/Http/Controllers/{Admin|Customer}/`
    - Services in `app/Services/`
    - Views in `resources/views/{customer|admin}/`
    - Components in `resources/views/components/{customer|admin|shared}/`

5. **Maintain Response Consistency:**

    - Always use `{success, data, message}` format
    - HTTP status codes: 200 (success), 422 (validation), 500 (error)
    - Vietnamese error messages
    - Log errors, don't expose to users

6. **Security First:**

    - CSRF protection on all forms
    - Validate all inputs (Form Requests)
    - Authorize all actions (Gates/Policies)
    - Escape all outputs (Blade automatic)
    - Hash passwords (bcrypt)

7. **Performance Optimization:**
    - Eager load relationships (prevent N+1)
    - Cache frequently accessed data
    - Lazy load images
    - Use database indexes
    - Optimize queries (explain analyze)

**Pattern Enforcement:**

**Code Review Checklist:**

-   [ ] Naming conventions followed
-   [ ] Project structure adhered to
-   [ ] Response formats consistent
-   [ ] Error handling implemented
-   [ ] Validation rules defined
-   [ ] Authorization checks present
-   [ ] Performance optimized (no N+1)
-   [ ] Tests written (Feature + Unit)

**Automated Checks:**

-   Laravel Pint for code formatting
-   PHPStan for static analysis
-   Laravel Telescope for debugging
-   Browser DevTools for performance

**Documentation:**

-   Update `database/db.sql` for schema changes
-   Document custom validation rules
-   Comment complex business logic
-   Update this architecture doc for pattern changes

### Pattern Examples

**Good Examples:**

**Controller (CRUD):**

```php
namespace App\Http\Controllers\Admin;

class ProductController extends Controller
{
    public function index()
    {
        $products = Product::with(['category', 'brand'])
            ->paginate(20);

        return view('admin.products.index', compact('products'));
    }

    public function store(StoreProductRequest $request)
    {
        $product = Product::create($request->validated());

        return redirect()
            ->route('admin.products.index')
            ->with('success', 'Sản phẩm đã được tạo thành công');
    }
}
```

**Model (Relationships):**

```php
namespace App\Models;

class Product extends Model
{
    protected $fillable = ['category_id', 'brand_id', 'sku', 'name', 'price', 'cost', 'quantity'];

    protected $casts = [
        'price' => 'decimal:2',
        'cost' => 'decimal:2',
    ];

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function brand()
    {
        return $this->belongsTo(Brand::class);
    }

    public function specs()
    {
        return $this->hasOne(ProductSpec::class);
    }
}
```

**Service (Business Logic):**

```php
namespace App\Services;

class OrderService
{
    public function createOrder(array $data): Order
    {
        DB::beginTransaction();
        try {
            $order = Order::create([
                'order_code' => $this->generateOrderCode(),
                'customer_id' => $data['customer_id'],
                'subtotal' => $data['subtotal'],
                'total_money' => $data['total_money'],
            ]);

            foreach ($data['items'] as $item) {
                $order->items()->create($item);
            }

            DB::commit();
            return $order;
        } catch (\Exception $e) {
            DB::rollBack();
            throw $e;
        }
    }
}
```

**Blade Component:**

```blade
{{-- resources/views/components/customer/product-card.blade.php --}}
@props(['product'])

<div class="card bg-base-100 shadow-xl">
    <figure>
        <img src="{{ $product->image }}" alt="{{ $product->name }}" loading="lazy">
    </figure>
    <div class="card-body">
        <h2 class="card-title">{{ $product->name }}</h2>
        <p class="text-2xl font-bold text-primary">
            {{ number_format($product->price, 0, ',', '.') }}đ
        </p>
        <div class="card-actions justify-end">
            <button class="btn btn-primary">Thêm vào giỏ</button>
        </div>
    </div>
</div>
```

**Anti-Patterns (AVOID):**

❌ **Raw SQL in Controllers:**

```php
// BAD
$products = DB::select('SELECT * FROM products WHERE category_id = ?', [$id]);

// GOOD
$products = Product::where('category_id', $id)->get();
```

❌ **Inconsistent Response Format:**

```php
// BAD
return ['data' => $product]; // Missing success, message

// GOOD
return response()->json([
    'success' => true,
    'data' => $product,
    'message' => 'Product retrieved successfully'
]);
```

❌ **N+1 Query Problem:**

```php
// BAD
$orders = Order::all();
foreach ($orders as $order) {
    echo $order->customer->name; // N+1 queries
}

// GOOD
$orders = Order::with('customer')->get();
foreach ($orders as $order) {
    echo $order->customer->name; // 2 queries total
}
```

❌ **Missing Validation:**

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

❌ **Hardcoded Values:**

```php
// BAD
$points = floor($total / 100000); // Magic number

// GOOD
$points = floor($total / config('tact.points_per_100k', 100000));
```

## Project Structure & Boundaries

### Complete Project Directory Structure

```
Tact/
├── README.md
├── composer.json
├── composer.lock
├── package.json
├── package-lock.json
├── vite.config.js
├── phpunit.xml
├── .env
├── .env.example
├── .gitignore
├── .gitattributes
├── .editorconfig
├── artisan
│
├── app/
│   ├── Console/
│   │   └── Kernel.php
│   ├── Exceptions/
│   │   └── Handler.php
│   ├── Http/
│   │   ├── Controllers/
│   │   │   ├── Controller.php
│   │   │   ├── Admin/
│   │   │   │   ├── DashboardController.php
│   │   │   │   ├── ProductController.php
│   │   │   │   ├── CategoryController.php
│   │   │   │   ├── BrandController.php
│   │   │   │   ├── SupplierController.php
│   │   │   │   ├── OrderController.php
│   │   │   │   ├── CustomerController.php
│   │   │   │   ├── UserController.php
│   │   │   │   ├── RoleController.php
│   │   │   │   ├── StockMovementController.php
│   │   │   │   ├── PromotionController.php
│   │   │   │   ├── ReportController.php
│   │   │   │   └── POSController.php
│   │   │   ├── Customer/
│   │   │   │   ├── HomeController.php
│   │   │   │   ├── ProductController.php
│   │   │   │   ├── CartController.php
│   │   │   │   ├── CheckoutController.php
│   │   │   │   ├── OrderController.php
│   │   │   │   └── ProfileController.php
│   │   │   └── Auth/
│   │   │       ├── LoginController.php
│   │   │       ├── RegisterController.php
│   │   │       ├── GoogleAuthController.php
│   │   │       └── LogoutController.php
│   │   ├── Middleware/
│   │   │   ├── Authenticate.php
│   │   │   ├── CheckRole.php
│   │   │   ├── CustomerAuth.php
│   │   │   └── TrustProxies.php
│   │   └── Requests/
│   │       ├── Admin/
│   │       │   ├── StoreProductRequest.php
│   │       │   ├── UpdateProductRequest.php
│   │       │   ├── StoreOrderRequest.php
│   │       │   ├── StoreCustomerRequest.php
│   │       │   ├── StoreUserRequest.php
│   │       │   ├── StorePromotionRequest.php
│   │       │   └── StockMovementRequest.php
│   │       └── Customer/
│   │           ├── CheckoutRequest.php
│   │           ├── UpdateProfileRequest.php
│   │           └── ApplyVoucherRequest.php
│   ├── Models/
│   │   ├── User.php
│   │   ├── Role.php
│   │   ├── Customer.php
│   │   ├── Product.php
│   │   ├── ProductSpec.php
│   │   ├── Category.php
│   │   ├── Brand.php
│   │   ├── Supplier.php
│   │   ├── Order.php
│   │   ├── OrderItem.php
│   │   ├── StockMovement.php
│   │   └── Promotion.php
│   ├── Services/
│   │   ├── OrderService.php
│   │   ├── CartService.php
│   │   ├── InventoryService.php
│   │   ├── PointsService.php
│   │   ├── VoucherService.php
│   │   ├── ReportService.php
│   │   └── DashboardService.php
│   ├── Repositories/
│   │   ├── ProductRepository.php
│   │   ├── OrderRepository.php
│   │   ├── CustomerRepository.php
│   │   └── InventoryRepository.php
│   ├── Observers/
│   │   ├── OrderObserver.php
│   │   ├── ProductObserver.php
│   │   └── StockMovementObserver.php
│   ├── Policies/
│   │   ├── ProductPolicy.php
│   │   ├── OrderPolicy.php
│   │   ├── CustomerPolicy.php
│   │   └── UserPolicy.php
│   └── Providers/
│       ├── AppServiceProvider.php
│       ├── AuthServiceProvider.php
│       ├── EventServiceProvider.php
│       └── RouteServiceProvider.php
│
├── bootstrap/
│   ├── app.php
│   ├── providers.php
│   └── cache/
│
├── config/
│   ├── app.php
│   ├── auth.php
│   ├── cache.php
│   ├── database.php
│   ├── filesystems.php
│   ├── logging.php
│   ├── mail.php
│   ├── queue.php
│   ├── services.php
│   ├── session.php
│   └── tact.php (custom app config)
│
├── database/
│   ├── .gitignore
│   ├── database.sqlite
│   ├── db.sql (reference schema)
│   ├── factories/
│   │   ├── UserFactory.php
│   │   ├── CustomerFactory.php
│   │   ├── ProductFactory.php
│   │   └── OrderFactory.php
│   ├── migrations/
│   │   ├── 2024_12_14_000001_create_roles_table.php
│   │   ├── 2024_12_14_000002_create_users_table.php
│   │   ├── 2024_12_14_000003_create_customers_table.php
│   │   ├── 2024_12_14_000004_create_categories_table.php
│   │   ├── 2024_12_14_000005_create_brands_table.php
│   │   ├── 2024_12_14_000006_create_suppliers_table.php
│   │   ├── 2024_12_14_000007_create_products_table.php
│   │   ├── 2024_12_14_000008_create_product_specs_table.php
│   │   ├── 2024_12_14_000009_create_stock_movements_table.php
│   │   ├── 2024_12_14_000010_create_promotions_table.php
│   │   ├── 2024_12_14_000011_create_orders_table.php
│   │   ├── 2024_12_14_000012_create_order_items_table.php
│   │   ├── 2024_12_14_000013_create_update_stock_trigger.php
│   │   └── 2024_12_14_000014_create_add_points_trigger.php
│   └── seeders/
│       ├── DatabaseSeeder.php
│       ├── RoleSeeder.php
│       ├── UserSeeder.php
│       ├── CategorySeeder.php
│       ├── BrandSeeder.php
│       ├── SupplierSeeder.php
│       ├── ProductSeeder.php
│       └── CustomerSeeder.php
│
├── public/
│   ├── index.php
│   ├── .htaccess
│   ├── robots.txt
│   └── favicon.ico
│
├── resources/
│   ├── css/
│   │   ├── app.css (Tailwind + DaisyUI main)
│   │   ├── customer.css (customer-specific styles)
│   │   └── admin.css (admin-specific styles)
│   ├── js/
│   │   ├── app.js (global scripts)
│   │   ├── customer.js (customer-specific)
│   │   ├── admin.js (admin-specific)
│   │   ├── pos.js (POS interface)
│   │   └── utils/
│   │       ├── cart.js
│   │       ├── validation.js
│   │       └── api.js
│   ├── views/
│   │   ├── layouts/
│   │   │   ├── customer.blade.php
│   │   │   ├── admin.blade.php
│   │   │   └── guest.blade.php
│   │   ├── components/
│   │   │   ├── customer/
│   │   │   │   ├── product-card.blade.php
│   │   │   │   ├── product-grid.blade.php
│   │   │   │   ├── cart-item.blade.php
│   │   │   │   ├── order-timeline.blade.php
│   │   │   │   ├── filter-sidebar.blade.php
│   │   │   │   └── bottom-nav.blade.php
│   │   │   ├── admin/
│   │   │   │   ├── data-table.blade.php
│   │   │   │   ├── stat-card.blade.php
│   │   │   │   ├── chart.blade.php
│   │   │   │   ├── sidebar.blade.php
│   │   │   │   └── breadcrumb.blade.php
│   │   │   └── shared/
│   │   │       ├── alert.blade.php
│   │   │       ├── modal.blade.php
│   │   │       ├── button.blade.php
│   │   │       ├── input.blade.php
│   │   │       └── pagination.blade.php
│   │   ├── customer/
│   │   │   ├── home.blade.php
│   │   │   ├── products/
│   │   │   │   ├── index.blade.php
│   │   │   │   └── show.blade.php
│   │   │   ├── cart/
│   │   │   │   └── index.blade.php
│   │   │   ├── checkout/
│   │   │   │   ├── index.blade.php
│   │   │   │   └── success.blade.php
│   │   │   ├── orders/
│   │   │   │   ├── index.blade.php
│   │   │   │   └── show.blade.php
│   │   │   └── profile/
│   │   │       ├── index.blade.php
│   │   │       └── edit.blade.php
│   │   ├── admin/
│   │   │   ├── dashboard.blade.php
│   │   │   ├── products/
│   │   │   │   ├── index.blade.php
│   │   │   │   ├── create.blade.php
│   │   │   │   ├── edit.blade.php
│   │   │   │   └── show.blade.php
│   │   │   ├── categories/
│   │   │   │   ├── index.blade.php
│   │   │   │   ├── create.blade.php
│   │   │   │   └── edit.blade.php
│   │   │   ├── brands/
│   │   │   │   ├── index.blade.php
│   │   │   │   ├── create.blade.php
│   │   │   │   └── edit.blade.php
│   │   │   ├── suppliers/
│   │   │   │   ├── index.blade.php
│   │   │   │   ├── create.blade.php
│   │   │   │   └── edit.blade.php
│   │   │   ├── orders/
│   │   │   │   ├── index.blade.php
│   │   │   │   └── show.blade.php
│   │   │   ├── customers/
│   │   │   │   ├── index.blade.php
│   │   │   │   ├── create.blade.php
│   │   │   │   ├── edit.blade.php
│   │   │   │   └── show.blade.php
│   │   │   ├── users/
│   │   │   │   ├── index.blade.php
│   │   │   │   ├── create.blade.php
│   │   │   │   └── edit.blade.php
│   │   │   ├── stock-movements/
│   │   │   │   ├── index.blade.php
│   │   │   │   └── create.blade.php
│   │   │   ├── promotions/
│   │   │   │   ├── index.blade.php
│   │   │   │   ├── create.blade.php
│   │   │   │   └── edit.blade.php
│   │   │   ├── reports/
│   │   │   │   ├── revenue.blade.php
│   │   │   │   ├── products.blade.php
│   │   │   │   └── inventory.blade.php
│   │   │   └── pos/
│   │   │       └── index.blade.php
│   │   └── auth/
│   │       ├── login.blade.php
│   │       ├── register.blade.php
│   │       └── admin-login.blade.php
│   └── lang/
│       └── vi/
│           ├── auth.php
│           ├── pagination.php
│           ├── passwords.php
│           └── validation.php
│
├── routes/
│   ├── web.php
│   ├── api.php (future)
│   └── console.php
│
├── storage/
│   ├── app/
│   │   ├── public/
│   │   │   ├── products/
│   │   │   │   └── {product_id}/
│   │   │   │       ├── main.webp
│   │   │   │       └── gallery/
│   │   │   ├── brands/
│   │   │   │   └── {brand_id}.webp
│   │   │   └── avatars/
│   │   │       └── {user_id}.webp
│   │   └── private/
│   ├── framework/
│   │   ├── cache/
│   │   ├── sessions/
│   │   ├── testing/
│   │   └── views/
│   └── logs/
│       └── laravel.log
│
├── tests/
│   ├── Feature/
│   │   ├── Admin/
│   │   │   ├── ProductManagementTest.php
│   │   │   ├── OrderManagementTest.php
│   │   │   ├── CustomerManagementTest.php
│   │   │   ├── UserManagementTest.php
│   │   │   ├── StockManagementTest.php
│   │   │   └── DashboardTest.php
│   │   ├── Customer/
│   │   │   ├── ProductBrowsingTest.php
│   │   │   ├── CartTest.php
│   │   │   ├── CheckoutTest.php
│   │   │   ├── OrderTrackingTest.php
│   │   │   └── ProfileTest.php
│   │   └── Auth/
│   │       ├── LoginTest.php
│   │       ├── RegisterTest.php
│   │       └── GoogleAuthTest.php
│   ├── Unit/
│   │   ├── Services/
│   │   │   ├── OrderServiceTest.php
│   │   │   ├── CartServiceTest.php
│   │   │   ├── InventoryServiceTest.php
│   │   │   ├── PointsServiceTest.php
│   │   │   └── VoucherServiceTest.php
│   │   ├── Models/
│   │   │   ├── ProductTest.php
│   │   │   ├── OrderTest.php
│   │   │   └── CustomerTest.php
│   │   └── Repositories/
│   │       ├── ProductRepositoryTest.php
│   │       └── OrderRepositoryTest.php
│   ├── TestCase.php
│   └── CreatesApplication.php
│
├── vendor/ (Composer dependencies)
└── node_modules/ (NPM dependencies)
```

### Architectural Boundaries

**Authentication Boundaries:**

**Staff Authentication (Admin/Manager/Sales/Warehouse):**

-   Guard: `web` (default Laravel session)
-   Middleware: `auth` + `role:{role_name}`
-   Login route: `/admin/login`
-   User table: `users`
-   Controllers: `app/Http/Controllers/Admin/*`

**Customer Authentication:**

-   Guard: `customer` (custom guard)
-   Middleware: `customer.auth`
-   Login routes: `/login`, `/auth/google/callback`
-   User table: `customers`
-   Controllers: `app/Http/Controllers/Customer/*`

**Authorization Boundaries:**

-   Gates: Defined in `AuthServiceProvider`
-   Policies: `app/Policies/*`
-   Middleware: `CheckRole` for role-based access
-   Blade directives: `@can`, `@role`

**API Boundaries (Future):**

**Internal AJAX Endpoints:**

-   Prefix: `/ajax/*`
-   Authentication: Same as web (session-based)
-   Response format: `{success, data, message}`
-   Controllers: Same controllers, different methods

**External API (Post-MVP):**

-   Prefix: `/api/v1/*`
-   Authentication: Laravel Sanctum (token-based)
-   Rate limiting: 60 requests/minute
-   Documentation: API docs in `/docs/api`

**Component Boundaries:**

**Customer Frontend:**

-   Layout: `resources/views/layouts/customer.blade.php`
-   Components: `resources/views/components/customer/*`
-   Styles: `resources/css/customer.css`
-   Scripts: `resources/js/customer.js`
-   Routes: `/`, `/products`, `/cart`, `/checkout`, `/orders`, `/profile`

**Admin Frontend:**

-   Layout: `resources/views/layouts/admin.blade.php`
-   Components: `resources/views/components/admin/*`
-   Styles: `resources/css/admin.css`
-   Scripts: `resources/js/admin.js`
-   Routes: `/admin/*`

**Shared Components:**

-   Location: `resources/views/components/shared/*`
-   Usage: Both customer and admin
-   Examples: alerts, modals, buttons, inputs

**Service Boundaries:**

**Business Logic Layer:**

-   Location: `app/Services/*`
-   Responsibility: Complex business logic, orchestration
-   Examples: `OrderService`, `CartService`, `InventoryService`
-   Called by: Controllers
-   Calls: Repositories, Models, External services

**Data Access Layer:**

-   Location: `app/Repositories/*`
-   Responsibility: Complex queries, data aggregation
-   Examples: `ProductRepository`, `OrderRepository`
-   Called by: Services
-   Calls: Eloquent models

**Model Layer:**

-   Location: `app/Models/*`
-   Responsibility: Data representation, relationships, simple queries
-   Examples: `Product`, `Order`, `Customer`
-   Called by: Services, Repositories, Controllers
-   Calls: Database (via Eloquent)

**Data Boundaries:**

**Database Schema:**

-   Primary database: MySQL
-   Schema definition: `database/db.sql` (reference)
-   Migrations: `database/migrations/*`
-   Seeders: `database/seeders/*`
-   Triggers: `update_stock`, `add_points`

**Eloquent Models:**

-   One model per table
-   Relationships defined in models
-   Casts for data types (JSON, dates, decimals)
-   Accessors/Mutators for data transformation

**Caching Layer:**

-   Driver: Redis (production), File (development)
-   Cache keys: Prefixed by type (`product_`, `category_`, `customer_points_`)
-   Cache tags: For group invalidation (`products`, `categories`)
-   TTL: Varies by data type (1 hour for products, 5 minutes for dashboard)

**Session Storage:**

-   Driver: File (development), Redis (production)
-   Session data: Cart, filters, user state
-   Lifetime: 120 minutes (configurable)

**File Storage:**

-   Driver: Local (MVP), S3 (production)
-   Public files: `storage/app/public/*` (symlinked to `public/storage`)
-   Private files: `storage/app/private/*`
-   Image optimization: WebP format, responsive srcset

### Requirements to Structure Mapping

**Feature: Product Management**

-   Controllers: `Admin/ProductController`, `Customer/ProductController`
-   Models: `Product`, `ProductSpec`, `Category`, `Brand`
-   Services: `ProductRepository`
-   Views: `admin/products/*`, `customer/products/*`
-   Components: `customer/product-card`, `customer/product-grid`
-   Migrations: `create_products_table`, `create_product_specs_table`
-   Tests: `Feature/Admin/ProductManagementTest`, `Feature/Customer/ProductBrowsingTest`

**Feature: Order Management**

-   Controllers: `Admin/OrderController`, `Customer/OrderController`, `Customer/CheckoutController`
-   Models: `Order`, `OrderItem`
-   Services: `OrderService`, `CartService`
-   Views: `admin/orders/*`, `customer/orders/*`, `customer/checkout/*`
-   Components: `customer/order-timeline`, `customer/cart-item`
-   Migrations: `create_orders_table`, `create_order_items_table`
-   Tests: `Feature/Admin/OrderManagementTest`, `Feature/Customer/CheckoutTest`

**Feature: Inventory Management**

-   Controllers: `Admin/StockMovementController`
-   Models: `StockMovement`, `Product`
-   Services: `InventoryService`
-   Repositories: `InventoryRepository`
-   Views: `admin/stock-movements/*`
-   Migrations: `create_stock_movements_table`, `create_update_stock_trigger`
-   Observers: `StockMovementObserver`
-   Tests: `Feature/Admin/StockManagementTest`, `Unit/Services/InventoryServiceTest`

**Feature: POS System**

-   Controllers: `Admin/POSController`
-   Services: `OrderService`, `InventoryService`, `VoucherService`
-   Views: `admin/pos/index`
-   Scripts: `resources/js/pos.js`
-   Tests: `Feature/Admin/POSTest`

**Feature: Voucher & Loyalty Points**

-   Controllers: `Admin/PromotionController`, `Customer/CheckoutController`
-   Models: `Promotion`, `Customer`
-   Services: `VoucherService`, `PointsService`
-   Views: `admin/promotions/*`
-   Migrations: `create_promotions_table`, `create_add_points_trigger`
-   Tests: `Unit/Services/VoucherServiceTest`, `Unit/Services/PointsServiceTest`

**Feature: Dashboard & Reports**

-   Controllers: `Admin/DashboardController`, `Admin/ReportController`
-   Services: `DashboardService`, `ReportService`
-   Views: `admin/dashboard`, `admin/reports/*`
-   Components: `admin/stat-card`, `admin/chart`
-   Tests: `Feature/Admin/DashboardTest`

**Feature: Authentication**

-   Controllers: `Auth/LoginController`, `Auth/RegisterController`, `Auth/GoogleAuthController`
-   Models: `User`, `Customer`, `Role`
-   Middleware: `Authenticate`, `CheckRole`, `CustomerAuth`
-   Views: `auth/*`
-   Tests: `Feature/Auth/*`

**Cross-Cutting Concerns:**

**Authorization:**

-   Location: `app/Policies/*`, `app/Providers/AuthServiceProvider.php`
-   Affects: All admin features
-   Implementation: Gates, Policies, Middleware

**Validation:**

-   Location: `app/Http/Requests/*`
-   Affects: All form submissions
-   Implementation: Form Request classes

**Error Handling:**

-   Location: `app/Exceptions/Handler.php`
-   Affects: All requests
-   Implementation: Global exception handler

**Logging:**

-   Location: `storage/logs/*`
-   Affects: All operations
-   Configuration: `config/logging.php`

**Performance Optimization:**

-   Caching: `app/Services/*` (cache frequently accessed data)
-   Eager Loading: Models (prevent N+1 queries)
-   Asset Optimization: Vite (code splitting, minification)
-   Image Optimization: WebP format, lazy loading

### Integration Points

**Internal Communication:**

**Controller → Service → Repository → Model:**

```
ProductController::index()
  → ProductRepository::getFilteredProducts()
    → Product::with(['category', 'brand'])->where(...)->get()
```

**Controller → Service → Multiple Services:**

```
CheckoutController::store()
  → OrderService::createOrder()
    → CartService::getCart()
    → VoucherService::validateVoucher()
    → InventoryService::checkStock()
    → PointsService::calculatePoints()
```

**Event-Driven Communication:**

```
OrderService::createOrder()
  → event(new OrderCreated($order))
    → SendOrderConfirmationEmail (Listener)
    → UpdateInventory (Listener)
    → NotifyManager (Listener)
```

**Observer Pattern:**

```
Order::update(['status' => 'completed'])
  → OrderObserver::updated()
    → PointsService::addPoints()
    → NotificationService::sendNotification()
```

**External Integrations:**

**Google OAuth:**

-   Entry point: `Auth/GoogleAuthController`
-   Package: Laravel Socialite
-   Callback: `/auth/google/callback`
-   User creation: `Customer` model

**Payment Gateways (Future):**

-   Entry point: `Customer/PaymentController`
-   Packages: VNPay, MoMo, ZaloPay SDKs
-   Webhooks: `/webhooks/payment/{provider}`

**Email Service:**

-   Entry point: Listeners (e.g., `SendOrderConfirmationEmail`)
-   Package: Laravel Mail
-   Configuration: `config/mail.php`
-   Templates: `resources/views/emails/*`

**Data Flow:**

**Customer Order Flow:**

```
1. Customer adds products to cart
   → CartController::add()
   → session()->put('cart', $cart)

2. Customer proceeds to checkout
   → CheckoutController::index()
   → CartService::getCart()
   → Display checkout form

3. Customer submits order
   → CheckoutController::store()
   → OrderService::createOrder()
     → Validate stock (InventoryService)
     → Validate voucher (VoucherService)
     → Create order (Order::create())
     → Create order items (OrderItem::create())
     → Update stock (trigger: update_stock)
     → Clear cart (session()->forget('cart'))
     → Fire event (OrderCreated)

4. Order confirmation
   → OrderCreated event
   → SendOrderConfirmationEmail listener
   → Customer receives email
```

**POS Order Flow:**

```
1. Sales staff searches customer
   → POSController::searchCustomer()
   → Customer::where('phone', $phone)->first()

2. Sales staff adds products
   → POSController::addProduct()
   → Product::find($id)
   → session()->put('pos_cart', $cart)

3. Sales staff scans IMEI
   → POSController::addIMEI()
   → Validate IMEI format
   → session()->put('pos_imei', $imei)

4. Sales staff completes payment
   → POSController::complete()
   → OrderService::createOrder()
     → Create order (source='store', status='completed')
     → Create order items (with IMEI)
     → Update stock (trigger: update_stock)
     → Add points (trigger: add_points)
     → Print invoice
```

**Inventory Update Flow:**

```
1. Warehouse staff creates stock movement
   → StockMovementController::store()
   → StockMovement::create([type='in', quantity=100])

2. Database trigger fires
   → update_stock trigger
   → UPDATE products SET quantity = quantity + 100

3. Observer fires (optional)
   → StockMovementObserver::created()
   → Cache::tags(['products'])->flush()
   → Notify manager if low stock resolved
```

### File Organization Patterns

**Configuration Files:**

-   Laravel configs: `config/*.php`
-   Custom app config: `config/tact.php`
-   Environment: `.env` (not committed), `.env.example` (committed)
-   Build config: `vite.config.js`, `tailwind.config.js` (Tailwind 4 uses CSS)

**Source Organization:**

-   Controllers: Grouped by user type (Admin, Customer, Auth)
-   Models: Flat structure in `app/Models/`
-   Services: Flat structure in `app/Services/`
-   Repositories: Flat structure in `app/Repositories/`
-   Views: Grouped by user type and feature

**Test Organization:**

-   Feature tests: Mirror controller structure
-   Unit tests: Mirror service/model structure
-   Test utilities: `tests/TestCase.php`, `tests/CreatesApplication.php`

**Asset Organization:**

-   CSS: Separate files per user type
-   JS: Separate files per user type + utilities
-   Images: Organized by type (products, brands, avatars)
-   Public assets: `public/*` (directly accessible)

### Development Workflow Integration

**Development Server Structure:**

```bash
# Start all development servers concurrently
composer dev

# Runs:
# - php artisan serve (Laravel server on :8000)
# - php artisan queue:listen (Queue worker)
# - php artisan pail (Log viewer)
# - npm run dev (Vite dev server on :5173)
```

**Build Process Structure:**

```bash
# Production build
npm run build

# Generates:
# - public/build/manifest.json (asset manifest)
# - public/build/assets/*.css (compiled CSS)
# - public/build/assets/*.js (compiled JS)
```

**Deployment Structure:**

```bash
# Setup project
composer setup

# Runs:
# - composer install (Install PHP dependencies)
# - Copy .env.example to .env
# - php artisan key:generate (Generate app key)
# - php artisan migrate --force (Run migrations)
# - npm install (Install JS dependencies)
# - npm run build (Build assets)
```

**Testing Structure:**

```bash
# Run all tests
composer test

# Runs:
# - php artisan config:clear (Clear config cache)
# - php artisan test (Run PHPUnit tests)
```

## Architecture Validation Results

### Coherence Validation ✅

**Decision Compatibility:**
All architectural decisions are fully compatible and work together seamlessly:

-   Laravel 12 + PHP 8.2+ provides modern framework foundation
-   Tailwind CSS 4 + DaisyUI 5 are compatible and provide complete styling solution
-   Vite 7 + Laravel Vite Plugin 2.0 enable fast development and optimized builds
-   MySQL + Eloquent ORM provide robust data layer with Laravel native support
-   Laravel Breeze + Socialite offer complete authentication solution
-   Axios 1.11.0 integrates smoothly with Vite build process

**Pattern Consistency:**
Implementation patterns fully support architectural decisions:

-   Naming conventions align with Laravel standards (snake_case for DB, camelCase for PHP)
-   Project structure follows Laravel MVC pattern with clear separation of concerns
-   Response formats are consistent across all endpoints (`{success, data, message}`)
-   Error handling patterns align with Laravel exception handling mechanisms
-   Validation strategy uses Laravel Form Requests consistently
-   Security patterns leverage Laravel built-in protections

**Structure Alignment:**
Project structure fully supports all architectural decisions:

-   Directory organization enables dual experience design (Customer vs Admin)
-   Boundaries are clearly defined and enforceable (Authentication, Authorization, Services)
-   Integration points follow consistent patterns (Controller → Service → Repository → Model)
-   Component organization supports feature-based development
-   Test structure mirrors application structure for easy navigation

### Requirements Coverage Validation ✅

**Functional Requirements Coverage:**

✅ **Product Management** (12 modules CRUD):

-   Controllers: `Admin/ProductController`, `Customer/ProductController`
-   Models: `Product`, `ProductSpec`, `Category`, `Brand`, `Supplier`
-   Services: `ProductRepository`
-   Views: Complete CRUD views + customer catalog
-   Tests: Feature + Unit tests

✅ **Order Management** (O2O + IMEI tracking):

-   Controllers: `Admin/OrderController`, `Customer/OrderController`, `CheckoutController`
-   Models: `Order`, `OrderItem` with IMEI JSON storage
-   Services: `OrderService`, `CartService`
-   Features: Timeline, cancel, IMEI tracking, warranty info
-   Tests: Checkout flow, order management

✅ **Inventory Management** (Smart alerts + triggers):

-   Controllers: `Admin/StockMovementController`
-   Models: `StockMovement`, `Product`
-   Services: `InventoryService`, `InventoryRepository`
-   Features: Stock in/out, low stock alerts, dead stock alerts, triggers
-   Tests: Stock management, inventory accuracy

✅ **POS System** (< 5 min transactions):

-   Controllers: `Admin/POSController`
-   Services: `OrderService`, `InventoryService`, `VoucherService`
-   Features: Customer lookup, barcode scan, IMEI scan, quick payment
-   Scripts: `resources/js/pos.js`
-   Tests: POS workflow

✅ **Voucher & Loyalty Points** (Auto-calculation):

-   Controllers: `Admin/PromotionController`
-   Models: `Promotion`, `Customer` (points field)
-   Services: `VoucherService`, `PointsService`
-   Features: Voucher validation, points calculation trigger
-   Tests: Voucher application, points calculation

✅ **Dashboard & Reports** (Real-time data):

-   Controllers: `Admin/DashboardController`, `Admin/ReportController`
-   Services: `DashboardService`, `ReportService`
-   Components: `admin/stat-card`, `admin/chart`
-   Features: Revenue charts, inventory alerts, product performance
-   Tests: Dashboard data accuracy

✅ **Authentication** (Dual auth + Google OAuth):

-   Controllers: `Auth/LoginController`, `Auth/GoogleAuthController`
-   Models: `User`, `Customer`, `Role`
-   Guards: `web` (staff), `customer` (customers)
-   Features: Email/password, Google OAuth, role-based access
-   Tests: Login, register, OAuth flow

✅ **Customer Features** (Nike-inspired UX):

-   Product catalog with filter, sort, search
-   Shopping cart with session storage
-   Checkout with voucher + points
-   Order tracking with timeline
-   Profile management
-   Google OAuth login

✅ **Sales Features** (POS efficiency):

-   Quick customer lookup (< 3 seconds)
-   Product search with barcode
-   IMEI input and validation
-   Voucher/points application
-   Multiple payment methods
-   Invoice generation

✅ **Warehouse Features** (Inventory accuracy):

-   Stock in/out management
-   Inventory alerts (low stock, dead stock)
-   Stock movement history
-   High-value transaction confirmation
-   Supplier management

✅ **Manager Features** (Data-driven decisions):

-   Dashboard with real-time charts
-   CRUD operations for all modules
-   Reports (revenue, products, inventory)
-   User management with roles
-   Performance analytics

**Non-Functional Requirements Coverage:**

✅ **Performance** (< 2s load, < 1s POS, < 100ms queries):

-   **Asset Optimization**: Vite code splitting, tree shaking, minification
-   **Caching Strategy**: Redis (production), File (dev), cache tags, TTL configuration
-   **Database Optimization**: Eager loading, query optimization, indexes
-   **Image Optimization**: WebP format, lazy loading, responsive srcset
-   **CSS-First Animations**: No heavy JS libraries, 60fps target

✅ **Security** (CSRF, XSS, SQL injection, bcrypt):

-   **Laravel Defaults**: CSRF protection, XSS prevention, SQL injection prevention
-   **Authentication**: Bcrypt password hashing, secure session cookies
-   **Authorization**: Gates, Policies, role-based middleware
-   **Input Validation**: Form Requests for all submissions
-   **Rate Limiting**: 60 requests/minute, login throttling
-   **HTTPS**: Enforced in production

✅ **Reliability** (99%+ uptime, daily backup, < 5min recovery):

-   **Database Transactions**: For critical operations with rollback
-   **Error Handling**: Try-catch with proper logging
-   **Backup Strategy**: Automated daily backups (database + files)
-   **Logging**: Laravel Log with daily rotation
-   **Monitoring**: Laravel Pail (dev), production monitoring ready

✅ **Scalability** (50+ concurrent users, 50+ orders/day):

-   **Database Design**: Proper relationships, indexes, triggers
-   **Cache Layer**: Reduces database load
-   **Session Management**: Redis for production
-   **Queue System**: Ready for async processing
-   **Multi-Store Support**: Architecture supports future expansion

✅ **Usability** (Mobile-first, responsive, WCAG Level A):

-   **Responsive Design**: Tailwind utilities, 375px base breakpoint
-   **Touch Optimization**: 44x44px minimum touch targets
-   **Mobile Navigation**: Bottom navigation for thumb-friendly access
-   **Accessibility**: DaisyUI components, semantic HTML, ARIA attributes
-   **Browser Support**: Chrome, Firefox, Safari, Edge (latest 2 versions)

✅ **SEO** (Customer-facing pages):

-   **Meta Tags**: Title, description, keywords for all pages
-   **Open Graph**: Product pages with og:image, og:price
-   **Structured Data**: Product schema (JSON-LD)
-   **Sitemap**: Auto-generated with Laravel package
-   **robots.txt**: Configured to allow customer pages, disallow admin

### Implementation Readiness Validation ✅

**Decision Completeness:**

-   ✅ All technology versions verified and documented
-   ✅ Database schema fully defined (12 tables, 2 triggers, relationships)
-   ✅ Authentication strategy clear (dual guards, Google OAuth)
-   ✅ Authorization approach documented (RBAC, 4 roles, Gates, Policies)
-   ✅ Caching strategy defined (Redis/File, cache tags, TTL)
-   ✅ File storage approach specified (Local MVP, S3 production)
-   ✅ Performance targets set (< 2s load, < 1s POS, < 100ms queries)
-   ✅ Security measures comprehensive (CSRF, XSS, SQL injection, bcrypt)

**Structure Completeness:**

-   ✅ Complete directory tree with 200+ specific files and directories
-   ✅ All controllers mapped to features (Admin, Customer, Auth)
-   ✅ All models defined matching database tables
-   ✅ All services identified (Order, Cart, Inventory, Points, Voucher, Report, Dashboard)
-   ✅ All repositories specified for complex queries
-   ✅ All views organized (layouts, components, pages)
-   ✅ All tests structured (Feature tests mirror controllers, Unit tests mirror services)
-   ✅ Asset organization clear (CSS, JS, images by type)

**Pattern Completeness:**

-   ✅ Naming conventions comprehensive (Database, PHP, Blade, JavaScript, CSS)
-   ✅ Response formats standardized (`{success, data, message}`)
-   ✅ Error handling patterns defined (try-catch, transactions, logging)
-   ✅ Validation strategy clear (Form Requests mandatory)
-   ✅ Loading state patterns specified (DaisyUI components, skeleton screens)
-   ✅ Communication patterns documented (Events, Observers, Session, Cache)
-   ✅ Good examples provided (Controller, Model, Service, Blade component)
-   ✅ Anti-patterns documented (Raw SQL, N+1 queries, missing validation)

### Gap Analysis Results

**Critical Gaps:** ✅ NONE - All critical architectural elements are defined

**Important Gaps Identified:**

⚠️ **Laravel Breeze Not Installed**

-   **Impact**: Authentication scaffolding not available
-   **Resolution**: Install in Phase 1 implementation
-   **Command**: `composer require laravel/breeze --dev && php artisan breeze:install blade`
-   **Priority**: High (required for authentication features)

⚠️ **Laravel Socialite Not Installed**

-   **Impact**: Google OAuth not available
-   **Resolution**: Install in Phase 1 implementation
-   **Command**: `composer require laravel/socialite`
-   **Priority**: High (required for customer Google login)

⚠️ **Database Migrations Not Created**

-   **Impact**: Schema not version controlled
-   **Resolution**: Convert db.sql to Laravel migrations in Phase 1
-   **Action**: Create 14 migration files matching db.sql schema
-   **Priority**: High (required for team collaboration and deployment)

⚠️ **Custom Customer Guard Not Configured**

-   **Impact**: Customer authentication won't work
-   **Resolution**: Configure in Phase 1 after Breeze installation
-   **File**: `config/auth.php` - add 'customer' guard and provider
-   **Priority**: High (required for customer login)

**Nice-to-Have Gaps:**

💡 **Chart.js Not Installed**

-   **Impact**: Dashboard charts not available
-   **Resolution**: Install in Phase 3 (Advanced Features)
-   **Command**: `npm install chart.js`
-   **Priority**: Medium (dashboard functional without charts initially)

💡 **Image Optimization Package Not Installed**

-   **Impact**: Manual WebP conversion required
-   **Resolution**: Install in Phase 4 (Polish & Optimization)
-   **Package**: Consider `intervention/image` or `spatie/laravel-image-optimizer`
-   **Priority**: Low (can optimize images manually initially)

💡 **Backup Package Not Installed**

-   **Impact**: Manual backups required
-   **Resolution**: Install in Phase 4 (Polish & Optimization)
-   **Command**: `composer require spatie/laravel-backup`
-   **Priority**: Low (can backup manually initially)

### Validation Issues Addressed

**Issue 1: Missing Authentication Packages**

-   **Status**: ✅ Identified and documented
-   **Resolution**: Added to Phase 1 implementation checklist
-   **Action Items**:
    1. Install Laravel Breeze for authentication scaffolding
    2. Install Laravel Socialite for Google OAuth
    3. Configure custom 'customer' guard in `config/auth.php`
    4. Create GoogleAuthController for OAuth flow
-   **Priority**: Critical (blocks authentication features)

**Issue 2: Database Schema Not in Migrations**

-   **Status**: ✅ Identified and documented
-   **Resolution**: Added to Phase 1 implementation checklist
-   **Action Items**:
    1. Create 12 table migrations matching db.sql
    2. Create 2 trigger migrations (update_stock, add_points)
    3. Test migrations: `php artisan migrate:fresh`
    4. Keep db.sql as reference documentation
-   **Priority**: Critical (blocks version control and team collaboration)

**Issue 3: Custom Guard Configuration**

-   **Status**: ✅ Identified and documented
-   **Resolution**: Added to Phase 1 implementation checklist
-   **Action Items**:
    1. Add 'customer' guard to `config/auth.php`
    2. Add 'customers' provider to `config/auth.php`
    3. Create CustomerAuth middleware
    4. Test customer authentication flow
-   **Priority**: Critical (blocks customer login)

**All Other Aspects**: ✅ Validated Successfully

### Architecture Completeness Checklist

**✅ Requirements Analysis**

-   [x] Project context thoroughly analyzed (12 tables, 4 roles, O2O model)
-   [x] Scale and complexity assessed (Medium complexity, 15-20 components)
-   [x] Technical constraints identified (8 weeks, Laravel 12, performance < 2s)
-   [x] Cross-cutting concerns mapped (10 concerns: Auth, Performance, Security, etc.)

**✅ Architectural Decisions**

-   [x] Critical decisions documented with versions (Laravel 12, Tailwind 4, DaisyUI 5, Vite 7)
-   [x] Technology stack fully specified (Backend, Frontend, Database, Build tools)
-   [x] Integration patterns defined (Controller → Service → Repository → Model)
-   [x] Performance considerations addressed (Caching, eager loading, lazy loading, WebP)
-   [x] Security measures comprehensive (CSRF, XSS, SQL injection, bcrypt, RBAC)
-   [x] Data architecture complete (12 tables, 2 triggers, relationships, validation)

**✅ Implementation Patterns**

-   [x] Naming conventions established (Database, PHP, Blade, JavaScript, CSS)
-   [x] Structure patterns defined (Controllers, Models, Services, Repositories, Views)
-   [x] Communication patterns specified (Events, Observers, Session, Cache)
-   [x] Process patterns documented (Error handling, validation, loading states)
-   [x] Format patterns standardized (API responses, dates, IMEI, money)
-   [x] Good examples provided (Controller, Model, Service, Blade component)
-   [x] Anti-patterns documented (Raw SQL, N+1 queries, missing validation)

**✅ Project Structure**

-   [x] Complete directory structure defined (200+ files and directories)
-   [x] Component boundaries established (Customer, Admin, Shared, Auth)
-   [x] Integration points mapped (Internal communication, external integrations)
-   [x] Requirements to structure mapping complete (7 major features mapped)
-   [x] Data flow documented (Customer order, POS order, inventory update)
-   [x] Development workflow integrated (composer dev, npm run build, composer test)

### Architecture Readiness Assessment

**Overall Status:** ✅ **READY FOR IMPLEMENTATION**

**Confidence Level:** **HIGH** - Architecture is comprehensive, coherent, and implementation-ready

**Key Strengths:**

1. **Complete Technology Stack**: All versions verified and compatible
2. **Comprehensive Database Design**: 12 tables with relationships, triggers, and validation
3. **Clear Separation of Concerns**: MVC pattern with Services and Repositories
4. **Dual Experience Architecture**: Customer (Nike-inspired) vs Admin (DaisyUI) clearly separated
5. **Security-First Approach**: Multiple layers of protection (CSRF, XSS, SQL injection, RBAC)
6. **Performance Optimized**: Caching, eager loading, lazy loading, asset optimization
7. **Mobile-First Design**: Responsive, touch-optimized, bottom navigation
8. **Detailed Implementation Patterns**: Naming, structure, communication, process patterns
9. **Complete Project Structure**: 200+ files mapped to features
10. **Comprehensive Validation**: All requirements covered, gaps identified and addressed

**Areas for Future Enhancement:**

1. **API Development**: RESTful API with Laravel Sanctum (post-MVP)
2. **Real-Time Features**: Laravel Echo + Pusher for WebSocket (post-MVP)
3. **Multi-Store Support**: Franchise management, centralized reporting (future)
4. **Advanced Analytics**: Predictive forecasting, customer segmentation (future)
5. **Mobile App**: Native iOS/Android app (future)
6. **Payment Gateway Integration**: VNPay, MoMo, ZaloPay (post-MVP)
7. **Advanced Inventory**: Multi-location, stock transfers, cycle counting (future)
8. **Warranty Management**: Claim module, repair tracking (future)

### Implementation Handoff

**AI Agent Guidelines:**

1. **Follow Architectural Decisions Exactly**:

    - Use specified technology versions (Laravel 12, Tailwind 4, DaisyUI 5, Vite 7)
    - Implement database schema exactly as defined in db.sql
    - Follow authentication strategy (dual guards: web + customer)
    - Implement authorization with RBAC (4 roles: Admin, Manager, Sales, Warehouse)

2. **Use Implementation Patterns Consistently**:

    - Naming: snake_case (DB), PascalCase (classes), camelCase (methods), kebab-case (Blade)
    - Structure: Controllers in Admin/Customer/Auth, Services for business logic, Repositories for complex queries
    - Responses: Always use `{success, data, message}` format
    - Validation: Use Form Requests for all form submissions
    - Error Handling: Try-catch with transactions and logging

3. **Respect Project Structure and Boundaries**:

    - Place files in correct directories as defined in project structure
    - Respect component boundaries (Customer vs Admin vs Shared)
    - Follow integration patterns (Controller → Service → Repository → Model)
    - Use proper authentication guards (web for staff, customer for customers)

4. **Refer to This Document for All Architectural Questions**:
    - Technology choices and versions
    - Naming conventions and patterns
    - Project structure and file locations
    - Integration points and data flow
    - Security measures and validation rules

**First Implementation Priority:**

**Phase 1: Foundation (Week 1-2)**

1. **Install Authentication Packages**:

    ```bash
    composer require laravel/breeze --dev
    php artisan breeze:install blade
    composer require laravel/socialite
    ```

2. **Configure Custom Customer Guard**:

    - Edit `config/auth.php`
    - Add 'customer' guard and 'customers' provider
    - Create CustomerAuth middleware

3. **Convert Database Schema to Migrations**:

    - Create 12 table migrations from db.sql
    - Create 2 trigger migrations (update_stock, add_points)
    - Run migrations: `php artisan migrate:fresh`

4. **Create Eloquent Models**:

    - 12 models matching database tables
    - Define relationships (belongsTo, hasMany, hasOne)
    - Add casts (JSON for imei_list, decimal for prices)

5. **Setup Authorization**:

    - Define Gates in AuthServiceProvider
    - Create Policies for major resources
    - Create CheckRole middleware

6. **Create Base Layouts**:
    - `resources/views/layouts/customer.blade.php` (Nike-inspired)
    - `resources/views/layouts/admin.blade.php` (DaisyUI)
    - `resources/views/layouts/guest.blade.php`

**Success Criteria for Phase 1**:

-   ✅ Authentication working (email/password + Google OAuth)
-   ✅ Database migrations complete and tested
-   ✅ All models created with relationships
-   ✅ Authorization working (4 roles with permissions)
-   ✅ Base layouts created and functional

**Next Phases**:

-   **Phase 2**: Core Features (CRUD operations, product catalog, cart, orders)
-   **Phase 3**: Advanced Features (POS, vouchers, points, dashboard, reports)
-   **Phase 4**: Polish & Optimization (performance, mobile responsiveness, UX enhancements)

---

**Architecture Document Complete** ✅

This architecture provides a comprehensive, coherent, and implementation-ready foundation for the Tact project. All AI agents should follow this document precisely to ensure consistent, high-quality implementation.

## Architecture Completion Summary

### Workflow Completion

**Architecture Decision Workflow:** ✅ COMPLETED
**Total Steps Completed:** 8
**Date Completed:** 2025-12-14
**Document Location:** docs/architecture.md

### Final Architecture Deliverables

**📋 Complete Architecture Document**

-   All architectural decisions documented with specific versions
-   Implementation patterns ensuring AI agent consistency
-   Complete project structure with 200+ files and directories
-   Requirements to architecture mapping
-   Validation confirming coherence and completeness

**🏗️ Implementation Ready Foundation**

-   25+ architectural decisions made
-   50+ implementation patterns defined
-   15-20 architectural components specified
-   11 functional requirements + 6 NFRs fully supported

**📚 AI Agent Implementation Guide**

-   Technology stack with verified versions (Laravel 12, Tailwind 4, DaisyUI 5, Vite 7)
-   Consistency rules that prevent implementation conflicts
-   Project structure with clear boundaries (Customer vs Admin vs Shared)
-   Integration patterns and communication standards

### Implementation Handoff

**For AI Agents:**
This architecture document is your complete guide for implementing Tact. Follow all decisions, patterns, and structures exactly as documented.

**First Implementation Priority:**

**Phase 1: Foundation (Week 1-2)**

```bash
# 1. Install Authentication Packages
composer require laravel/breeze --dev
php artisan breeze:install blade
composer require laravel/socialite

# 2. Configure Custom Customer Guard
# Edit config/auth.php - add 'customer' guard and 'customers' provider

# 3. Convert Database Schema to Migrations
# Create 12 table migrations + 2 trigger migrations from database/db.sql

# 4. Run Migrations
php artisan migrate:fresh

# 5. Create Eloquent Models
# 12 models with relationships, casts, and validation

# 6. Setup Authorization
# Define Gates in AuthServiceProvider, create Policies, create CheckRole middleware

# 7. Create Base Layouts
# customer.blade.php (Nike-inspired), admin.blade.php (DaisyUI), guest.blade.php
```

**Development Sequence:**

1. **Phase 1 (Week 1-2)**: Foundation - Authentication, database, models, authorization, layouts
2. **Phase 2 (Week 3-4)**: Core Features - CRUD operations, product catalog, cart, orders
3. **Phase 3 (Week 5-6)**: Advanced Features - POS, vouchers, points, dashboard, reports
4. **Phase 4 (Week 7-8)**: Polish & Optimization - Performance, mobile responsiveness, UX enhancements

### Quality Assurance Checklist

**✅ Architecture Coherence**

-   [x] All decisions work together without conflicts
-   [x] Technology choices are compatible (Laravel 12 + Tailwind 4 + DaisyUI 5 + Vite 7)
-   [x] Patterns support the architectural decisions
-   [x] Structure aligns with all choices

**✅ Requirements Coverage**

-   [x] All 11 functional requirements are supported
-   [x] All 6 non-functional requirements are addressed (Performance, Security, Reliability, Scalability, Usability, SEO)
-   [x] 10 cross-cutting concerns are handled (Auth, Performance, Security, Data Consistency, Mobile-First, Trust, Monitoring, Audit Trail, Real-Time, Dual Experience)
-   [x] Integration points are defined (Internal, External, Data Flow)

**✅ Implementation Readiness**

-   [x] Decisions are specific and actionable (all versions verified)
-   [x] Patterns prevent agent conflicts (naming, structure, format, communication, process)
-   [x] Structure is complete and unambiguous (200+ files mapped)
-   [x] Examples are provided for clarity (good examples + anti-patterns)

### Project Success Factors

**🎯 Clear Decision Framework**
Every technology choice was made collaboratively with clear rationale, ensuring all stakeholders understand the architectural direction. Laravel 12 + Tailwind CSS 4 + DaisyUI 5 provides a modern, performant foundation.

**🔧 Consistency Guarantee**
Implementation patterns and rules ensure that multiple AI agents will produce compatible, consistent code that works together seamlessly. Naming conventions, response formats, and error handling are standardized.

**📋 Complete Coverage**
All project requirements are architecturally supported, with clear mapping from business needs to technical implementation. 11 functional requirements and 6 NFRs are fully addressed.

**🏗️ Solid Foundation**
The Laravel 12 framework with Breeze authentication and Socialite OAuth provides a production-ready foundation following current best practices. Database schema is comprehensive with 12 tables, 2 triggers, and proper relationships.

**🚀 Performance Optimized**
Architecture includes multiple optimization layers: Vite asset optimization, Laravel caching strategy, eager loading, lazy loading images, WebP format, and database indexes. Target: < 2s page load, < 1s POS response.

**🔒 Security First**
Multiple security layers implemented: CSRF protection, XSS prevention, SQL injection prevention, bcrypt password hashing, role-based access control (4 roles), rate limiting, and HTTPS enforcement.

**📱 Mobile-First Design**
Responsive design with 375px base breakpoint, touch-optimized interfaces (44x44px targets), bottom navigation for thumb-friendly access, and DaisyUI accessibility features.

---

**Architecture Status:** ✅ **READY FOR IMPLEMENTATION**

**Next Phase:** Begin implementation using the architectural decisions and patterns documented herein.

**Document Maintenance:** Update this architecture when major technical decisions are made during implementation.

---

**🎉 Architecture Workflow Complete!**

Your architecture for **Tact** is comprehensive, validated, and ready for implementation by AI agents.
