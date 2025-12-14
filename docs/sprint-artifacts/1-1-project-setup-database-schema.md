# Story 1.1: Project Setup & Database Schema

Status: ready-for-dev

## Story

As a **Developer**,
I want to initialize the Laravel 12 project with complete database schema,
So that the foundation is ready for all features to be built upon.

## Acceptance Criteria

**Given** a fresh Laravel 12 installation
**When** I run the database migrations
**Then** all 12 tables are created successfully with proper relationships
**And** the 2 database triggers (update_stock, add_points) are created
**And** foreign key constraints are properly set up
**And** indexes are created for performance optimization
**And** the database seeder creates initial data (4 roles: Admin, Manager, Sales, Warehouse)

**Technical Details:**

-   Tables: roles, users, customers, categories, brands, suppliers, products, product_specs, stock_movements, promotions, orders, order_items
-   Triggers: update_stock (auto-update products.quantity on stock_movements), add_points (auto-calculate loyalty points on order complete)
-   Relationships: All foreign keys as defined in database/db.sql
-   Seeders: RoleSeeder creates 4 roles with proper permissions

## Tasks / Subtasks

-   [ ] Task 1: Convert database/db.sql to Laravel migrations (AC: All 12 tables)

    -   [ ] 1.1: Create migration for roles table
    -   [ ] 1.2: Create migration for users table with role_id foreign key
    -   [ ] 1.3: Create migration for customers table (email, password, google_id, points)
    -   [ ] 1.4: Create migration for categories table
    -   [ ] 1.5: Create migration for brands table
    -   [ ] 1.6: Create migration for suppliers table
    -   [ ] 1.7: Create migration for products table (category_id, brand_id, sku unique)
    -   [ ] 1.8: Create migration for product_specs table (one-to-one with products)
    -   [ ] 1.9: Create migration for stock_movements table
    -   [ ] 1.10: Create migration for promotions table (vouchers)
    -   [ ] 1.11: Create migration for orders table (customer_id, user_id, source enum)
    -   [ ] 1.12: Create migration for order_items table (imei_list TEXT field)

-   [ ] Task 2: Create database triggers (AC: 2 triggers working)

    -   [ ] 2.1: Create migration for update_stock trigger (auto-update products.quantity)
    -   [ ] 2.2: Create migration for add_points trigger (auto-calculate loyalty points)
    -   [ ] 2.3: Test triggers with sample data

-   [ ] Task 3: Create Eloquent models with relationships (AC: All 12 models)

    -   [ ] 3.1: Create Role model
    -   [ ] 3.2: Create User model (belongsTo Role)
    -   [ ] 3.3: Create Customer model (casts for google_id, points)
    -   [ ] 3.4: Create Category model (hasMany Products)
    -   [ ] 3.5: Create Brand model (hasMany Products)
    -   [ ] 3.6: Create Supplier model (hasMany StockMovements)
    -   [ ] 3.7: Create Product model (belongsTo Category, Brand; hasOne ProductSpec)
    -   [ ] 3.8: Create ProductSpec model (belongsTo Product)
    -   [ ] 3.9: Create StockMovement model (belongsTo Product, User, Supplier)
    -   [ ] 3.10: Create Promotion model (voucher logic)
    -   [ ] 3.11: Create Order model (belongsTo Customer, User; hasMany OrderItems)
    -   [ ] 3.12: Create OrderItem model (belongsTo Order, Product; cast imei_list to array)

-   [ ] Task 4: Create database seeders (AC: Initial data populated)

    -   [ ] 4.1: Create RoleSeeder (4 roles: Admin, Manager, Sales, Warehouse)
    -   [ ] 4.2: Create UserSeeder (1 admin user for testing)
    -   [ ] 4.3: Create CustomerSeeder (1 guest customer for walk-in sales)
    -   [ ] 4.4: Create CategorySeeder (2 categories: Điện thoại, Phụ kiện)
    -   [ ] 4.5: Create BrandSeeder (2 brands: Apple, Samsung)
    -   [ ] 4.6: Update DatabaseSeeder to call all seeders in correct order

-   [ ] Task 5: Add indexes for performance (AC: Query time < 100ms)

    -   [ ] 5.1: Add index on products.sku (unique, for barcode scanning)
    -   [ ] 5.2: Add index on customers.email (unique, for login)
    -   [ ] 5.3: Add index on customers.google_id (unique, for OAuth)
    -   [ ] 5.4: Add index on orders.order_code (unique, for tracking)
    -   [ ] 5.5: Add index on orders.customer_id (for customer order history)
    -   [ ] 5.6: Add index on order_items.order_id (for order details)
    -   [ ] 5.7: Add index on stock_movements.product_id (for stock history)

-   [ ] Task 6: Test database setup (AC: All tests pass)
    -   [ ] 6.1: Run migrations fresh with seed
    -   [ ] 6.2: Verify all 12 tables exist with correct columns
    -   [ ] 6.3: Verify foreign key constraints work (try deleting parent record)
    -   [ ] 6.4: Test update_stock trigger (insert stock_movement, check products.quantity)
    -   [ ] 6.5: Test add_points trigger (update order status to completed, check customers.points)
    -   [ ] 6.6: Verify seeders created correct initial data

## Dev Notes

### Critical Project Rules (MUST FOLLOW)

**From project-context.md:**

1. **Technology Stack (EXACT VERSIONS)**

    - Laravel 12.0, PHP 8.2+, MySQL
    - Tailwind CSS 4.0.0, DaisyUI 5.5.13, Vite 7.0.7
    - Laravel Breeze + Socialite for authentication

2. **Database Schema (NEVER MODIFY WITHOUT UPDATING db.sql)**

    - 12 tables: roles, users, customers, categories, brands, suppliers, products, product_specs, stock_movements, promotions, orders, order_items
    - 2 triggers: update_stock, add_points (MUST KEEP)
    - Critical fields: order_items.imei_list (TEXT, JSON array), customers.google_id (VARCHAR 50), products.sku (VARCHAR 50 UNIQUE), orders.source (ENUM 'web', 'store')

3. **Naming Conventions (STRICTLY ENFORCE)**

    - Tables: plural, lowercase, snake_case (users, products, order_items)
    - Columns: snake_case (full_name, created_at, order_status)
    - Foreign keys: {table}\_id (user_id, product_id, category_id)
    - PHP Classes: PascalCase (ProductController, OrderService)
    - PHP Methods: camelCase (getUserData(), createOrder())
    - Blade Files: kebab-case (product-card.blade.php)

4. **Database Triggers vs Eloquent Events**

    - Keep existing database triggers: update_stock, add_points
    - Triggers ensure data consistency at DB level
    - Add Eloquent Observers for application-level hooks (notifications, cache invalidation)

5. **Performance Requirements**
    - Database queries: < 100ms
    - Use eager loading to prevent N+1 queries
    - Add indexes on frequently queried columns

### Architecture Patterns (From architecture.md)

**Database Relationships:**

-   One-to-Many: roles → users, categories → products, brands → products
-   One-to-One: products → product_specs
-   Many-to-Many: orders ↔ products (through order_items)

**Migration Strategy:**

-   Convert db.sql to Laravel migrations for version control
-   Keep db.sql as reference documentation
-   Use migrations for development, db.sql for quick setup

**Eloquent Model Patterns:**

```php
// Product.php example
class Product extends Model
{
    protected $fillable = ['category_id', 'brand_id', 'sku', 'name', 'price', 'cost', 'quantity', 'image', 'warranty_months', 'status'];

    public function category() {
        return $this->belongsTo(Category::class);
    }

    public function brand() {
        return $this->belongsTo(Brand::class);
    }

    public function productSpec() {
        return $this->hasOne(ProductSpec::class);
    }
}
```

**Trigger Implementation:**

```php
// In migration file
DB::unprepared('
    CREATE TRIGGER update_stock AFTER INSERT ON stock_movements
    FOR EACH ROW BEGIN
        IF NEW.type = "in" THEN
            UPDATE products SET quantity = quantity + NEW.quantity WHERE id = NEW.product_id;
        ELSEIF NEW.type = "out" THEN
            UPDATE products SET quantity = quantity - NEW.quantity WHERE id = NEW.product_id;
        END IF;
    END
');
```

### Implementation Guidance Documents

**1. Database Trigger Performance Plan (docs/database-trigger-performance-plan.md)**

-   Performance target: POS transaction < 100ms
-   Test triggers with realistic data in Week 1-2
-   Fallback: Application-level logic or queues if triggers slow
-   Critical testing: Create order + items + stock movement + complete order < 100ms

**2. Image Optimization SLA (docs/image-optimization-sla.md)**

-   File size limits: Thumbnail 50KB, Detail 200KB, Banner 300KB
-   Format: WebP required, JPEG fallback
-   Responsive: srcset with 400w, 800w, 1200w breakpoints
-   Lazy loading: All images below fold

**3. UX Implementation Priorities (docs/ux-implementation-priorities.md)**

-   Core UX (P0 - Week 1-6): Trust signals, speed, clarity, mobile-first
-   Polish UX (P2-P4 - Week 7-8): Animations, micro-interactions, visual polish
-   Decision framework: Trust/Speed/Clarity = P0, Polish = defer if time constrained

**4. Offline POS Design (docs/offline-pos-design.md)**

-   Technology: Service Worker + IndexedDB + Background Sync API
-   Data sync: Product catalog (daily), customers (hourly), vouchers (hourly)
-   Transaction queue: Store offline transactions, sync when online
-   Conflict resolution: Server stock validation on sync

### Project Structure Notes

**Alignment with unified project structure:**

```
database/
├── migrations/
│   ├── 2024_01_01_000001_create_roles_table.php
│   ├── 2024_01_01_000002_create_users_table.php
│   ├── 2024_01_01_000003_create_customers_table.php
│   ├── 2024_01_01_000004_create_categories_table.php
│   ├── 2024_01_01_000005_create_brands_table.php
│   ├── 2024_01_01_000006_create_suppliers_table.php
│   ├── 2024_01_01_000007_create_products_table.php
│   ├── 2024_01_01_000008_create_product_specs_table.php
│   ├── 2024_01_01_000009_create_stock_movements_table.php
│   ├── 2024_01_01_000010_create_promotions_table.php
│   ├── 2024_01_01_000011_create_orders_table.php
│   ├── 2024_01_01_000012_create_order_items_table.php
│   ├── 2024_01_01_000013_create_update_stock_trigger.php
│   └── 2024_01_01_000014_create_add_points_trigger.php
├── seeders/
│   ├── RoleSeeder.php
│   ├── UserSeeder.php
│   ├── CustomerSeeder.php
│   ├── CategorySeeder.php
│   ├── BrandSeeder.php
│   └── DatabaseSeeder.php
└── db.sql (reference only)

app/Models/
├── Role.php
├── User.php
├── Customer.php
├── Category.php
├── Brand.php
├── Supplier.php
├── Product.php
├── ProductSpec.php
├── StockMovement.php
├── Promotion.php
├── Order.php
└── OrderItem.php
```

### References

**Source Documents:**

-   [Database Schema: database/db.sql] - Complete schema with 12 tables and 2 triggers
-   [Architecture: docs/architecture.md#data-architecture] - Database design decisions and patterns
-   [Project Context: project-context.md#critical-project-rules] - Naming conventions and critical rules
-   [PRD: docs/prd.md#technical-foundation] - Technical requirements for Epic 1
-   [Epics: docs/epics.md#epic-1-story-1.1] - Story requirements and acceptance criteria
-   [Database Trigger Performance Plan: docs/database-trigger-performance-plan.md] - Performance validation plan
-   [UX Implementation Priorities: docs/ux-implementation-priorities.md] - Core UX priorities for Week 1

**Key Technical Decisions:**

-   Hybrid approach: SQL-first with migration conversion (Architecture Decision 1.1)
-   Eloquent ORM with explicit relationships (Architecture Decision 1.2)
-   Multi-layer validation: Database, Model, Business Logic (Architecture Decision 1.3)
-   JSON storage for IMEI tracking in order_items.imei_list (Architecture Decision 1.4)
-   Hybrid triggers + observers approach (Architecture Decision 1.5)

### Anti-Patterns to Avoid

**From project-context.md:**

❌ **Raw SQL in Controllers**

```php
// BAD
$products = DB::select('SELECT * FROM products WHERE category_id = ?', [$id]);

// GOOD
$products = Product::where('category_id', $id)->get();
```

❌ **Missing Foreign Key Constraints**

```php
// BAD - No foreign key
$table->integer('category_id');

// GOOD - With foreign key
$table->foreignId('category_id')->constrained()->onDelete('cascade');
```

❌ **Hardcoded Values**

```php
// BAD
$points = floor($total / 100000); // Magic number

// GOOD
$points = floor($total / config('tact.points_per_100k', 100000));
```

❌ **Manual Stock Updates**

```php
// BAD - Bypasses trigger
$product->quantity -= $quantity;
$product->save();

// GOOD - Let trigger handle it
StockMovement::create([
    'product_id' => $product->id,
    'type' => 'out',
    'quantity' => $quantity
]);
```

### Testing Requirements

**Feature Tests:**

```php
// tests/Feature/Database/MigrationTest.php
public function test_all_tables_created()
{
    $tables = ['roles', 'users', 'customers', 'categories', 'brands',
               'suppliers', 'products', 'product_specs', 'stock_movements',
               'promotions', 'orders', 'order_items'];

    foreach ($tables as $table) {
        $this->assertTrue(Schema::hasTable($table));
    }
}

public function test_update_stock_trigger_works()
{
    $product = Product::factory()->create(['quantity' => 10]);

    StockMovement::create([
        'product_id' => $product->id,
        'type' => 'in',
        'quantity' => 5
    ]);

    $this->assertEquals(15, $product->fresh()->quantity);
}

public function test_add_points_trigger_works()
{
    $customer = Customer::factory()->create(['points' => 0]);
    $order = Order::factory()->create([
        'customer_id' => $customer->id,
        'total_money' => 250000,
        'order_status' => 'pending'
    ]);

    $order->update(['order_status' => 'completed']);

    $this->assertEquals(2, $customer->fresh()->points); // floor(250000 / 100000) = 2
}
```

**Unit Tests:**

```php
// tests/Unit/Models/ProductTest.php
public function test_product_belongs_to_category()
{
    $product = Product::factory()->create();
    $this->assertInstanceOf(Category::class, $product->category);
}

public function test_product_has_one_product_spec()
{
    $product = Product::factory()->create();
    ProductSpec::factory()->create(['product_id' => $product->id]);
    $this->assertInstanceOf(ProductSpec::class, $product->productSpec);
}
```

### Week 1 Implementation Checklist

**Before starting Story 1.1:**

-   [x] Read all 4 implementation guidance documents
-   [x] Understand UX priorities (Core vs Polish)
-   [x] Plan offline POS architecture
-   [x] Setup image optimization workflow
-   [x] Prepare database trigger performance tests

**During Story 1.1 (Project Setup):**

-   [ ] Create database schema with 2 triggers
-   [ ] Test trigger performance with realistic data
-   [ ] Setup image optimization service (deferred to Story 3.2)
-   [ ] Configure Service Worker for offline POS (deferred to Epic 8)
-   [ ] Validate all performance targets met

**After Story 1.1:**

-   [ ] Confirm POS transaction < 100ms (test with triggers)
-   [ ] Document any deviations or issues
-   [ ] Update sprint-status.yaml to ready-for-dev

## Dev Agent Record

### Context Reference

<!-- Path(s) to story context XML will be added here by context workflow -->

### Agent Model Used

<!-- Will be filled by dev agent -->

### Debug Log References

<!-- Will be added during implementation -->

### Completion Notes List

<!-- Will be added during implementation -->

### File List

<!-- Will be added during implementation -->
