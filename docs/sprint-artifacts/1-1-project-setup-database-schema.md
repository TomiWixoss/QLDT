# Story 1.1: Project Setup & Database Schema

Status: Ready for Review

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

-   [x] Task 1: Convert database/db.sql to Laravel migrations (AC: All 12 tables)

    -   [x] 1.1: Create migration for roles table
    -   [x] 1.2: Create migration for users table with role_id foreign key
    -   [x] 1.3: Create migration for customers table (email, password, google_id, points)
    -   [x] 1.4: Create migration for categories table
    -   [x] 1.5: Create migration for brands table
    -   [x] 1.6: Create migration for suppliers table
    -   [x] 1.7: Create migration for products table (category_id, brand_id, sku unique)
    -   [x] 1.8: Create migration for product_specs table (one-to-one with products)
    -   [x] 1.9: Create migration for stock_movements table
    -   [x] 1.10: Create migration for promotions table (vouchers)
    -   [x] 1.11: Create migration for orders table (customer_id, user_id, source enum)
    -   [x] 1.12: Create migration for order_items table (imei_list TEXT field)

-   [x] Task 2: Create database triggers (AC: 2 triggers working)

    -   [x] 2.1: Create migration for update_stock trigger (auto-update products.quantity)
    -   [x] 2.2: Create migration for add_points trigger (auto-calculate loyalty points)
    -   [x] 2.3: Test triggers with sample data

-   [x] Task 3: Create Eloquent models with relationships (AC: All 12 models)

    -   [x] 3.1: Create Role model
    -   [x] 3.2: Create User model (belongsTo Role)
    -   [x] 3.3: Create Customer model (casts for google_id, points)
    -   [x] 3.4: Create Category model (hasMany Products)
    -   [x] 3.5: Create Brand model (hasMany Products)
    -   [x] 3.6: Create Supplier model (hasMany StockMovements)
    -   [x] 3.7: Create Product model (belongsTo Category, Brand; hasOne ProductSpec)
    -   [x] 3.8: Create ProductSpec model (belongsTo Product)
    -   [x] 3.9: Create StockMovement model (belongsTo Product, User, Supplier)
    -   [x] 3.10: Create Promotion model (voucher logic)
    -   [x] 3.11: Create Order model (belongsTo Customer, User; hasMany OrderItems)
    -   [x] 3.12: Create OrderItem model (belongsTo Order, Product; cast imei_list to array)

-   [x] Task 4: Create database seeders (AC: Initial data populated)

    -   [x] 4.1: Create RoleSeeder (4 roles: Admin, Manager, Sales, Warehouse)
    -   [x] 4.2: Create UserSeeder (1 admin user for testing)
    -   [x] 4.3: Create CustomerSeeder (1 guest customer for walk-in sales)
    -   [x] 4.4: Create CategorySeeder (2 categories: Điện thoại, Phụ kiện)
    -   [x] 4.5: Create BrandSeeder (2 brands: Apple, Samsung)
    -   [x] 4.6: Update DatabaseSeeder to call all seeders in correct order

**Seeder Data Specifications:**

```
RoleSeeder (4 roles):
- Admin: full_access = true
- Manager: full_access = false, permissions = ['view-all', 'manage-products', 'manage-orders', 'manage-inventory', 'view-reports']
- Sales: permissions = ['access-pos', 'manage-orders', 'view-products', 'view-customers']
- Warehouse: permissions = ['manage-inventory', 'view-products']

UserSeeder (1 admin for testing):
- Email: admin@tact.vn
- Password: password (will be changed in production)
- Role: Admin
- Full Name: Admin User

CustomerSeeder (1 guest customer):
- Email: guest@tact.vn
- Full Name: Khách vãng lai
- Phone: 0000000000
- Points: 0
- Purpose: For walk-in sales without customer info

CategorySeeder (2 categories):
- Điện thoại
- Phụ kiện

BrandSeeder (2 brands):
- Apple
- Samsung
```

-   [x] Task 5: Add indexes for performance (AC: Query time < 100ms)

    -   [x] 5.1: Add index on products.sku (unique, for barcode scanning)
    -   [x] 5.2: Add index on customers.email (unique, for login)
    -   [x] 5.3: Add index on customers.google_id (unique, for OAuth)
    -   [x] 5.4: Add index on orders.order_code (unique, for tracking)
    -   [x] 5.5: Add index on orders.customer_id (for customer order history)
    -   [x] 5.6: Add index on order_items.order_id (for order details)
    -   [x] 5.7: Add index on stock_movements.product_id (for stock history)

-   [x] Task 6: Test database setup (AC: All tests pass)
    -   [x] 6.1: Run migrations fresh with seed
    -   [x] 6.2: Verify all 12 tables exist with correct columns
    -   [x] 6.3: Verify foreign key constraints work (try deleting parent record)
    -   [x] 6.4: Test update_stock trigger (insert stock_movement, check products.quantity)
    -   [x] 6.5: Test add_points trigger (update order status to completed, check customers.points)
    -   [x] 6.6: Verify seeders created correct initial data

## Dev Notes

### 📋 Quick Reference Card

```
┌─────────────────────────────────────────────────────────────┐
│ STORY 1.1 QUICK REFERENCE CARD                              │
├─────────────────────────────────────────────────────────────┤
│ MUST DO:                                                     │
│ ✓ 12 tables + 2 triggers                                    │
│ ✓ Migration order: roles → users → products → orders       │
│ ✓ Test triggers: < 100ms for POS transaction               │
│ ✓ Seed 4 roles + 1 admin + 1 guest customer                │
│                                                              │
│ MUST NOT DO:                                                 │
│ ✗ Raw SQL in controllers                                    │
│ ✗ Manual stock updates (use triggers)                       │
│ ✗ Hardcoded values (use config)                             │
│                                                              │
│ KEY FILES:                                                   │
│ • database/db.sql (reference schema)                        │
│ • project-context.md (critical rules)                       │
│ • docs/database-trigger-performance-plan.md                 │
└─────────────────────────────────────────────────────────────┘
```

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

**MIGRATION ORDER (CRITICAL):**

```
1. Independent tables first: roles, categories, brands, suppliers
2. Tables with single FK: users (role_id), customers (no FK)
3. Products table: depends on categories, brands
4. Product_specs: depends on products
5. Stock_movements: depends on products, users, suppliers
6. Promotions: independent
7. Orders: depends on customers, users
8. Order_items: depends on orders, products
9. Triggers: MUST be last (after all tables exist)

REASON: Foreign key constraints will fail if parent tables don't exist yet.
```

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

### Implementation Guidance (Story 1.1 Relevant)

**Database Trigger Performance Plan:**

Test triggers with realistic data in Week 1-2. Performance target: POS transaction < 100ms.

**Critical Performance Tests (MUST RUN):**

```php
// Test Scenario 1: Single product order
$start = microtime(true);
StockMovement::create(['product_id' => 1, 'type' => 'out', 'quantity' => 1]);
$duration = (microtime(true) - $start) * 1000;
// Assert: < 50ms (half of 100ms budget)

// Test Scenario 2: Multi-product order (5 items)
$start = microtime(true);
foreach ($items as $item) {
    StockMovement::create(['product_id' => $item['id'], 'type' => 'out', 'quantity' => $item['qty']]);
}
$duration = (microtime(true) - $start) * 1000;
// Assert: < 100ms total

// Test Scenario 3: Points calculation trigger
$start = microtime(true);
$order->update(['order_status' => 'completed']);
$duration = (microtime(true) - $start) * 1000;
// Assert: < 20ms
```

**Fallback Strategy:** If triggers exceed 100ms, use application-level logic or queues.

**Other Guidance Documents (Not Relevant for Story 1.1):**

-   Image Optimization SLA → Deferred to Story 3.2
-   UX Implementation Priorities → Not applicable (backend only)
-   Offline POS Design → Deferred to Epic 8

### Project Structure (Story 1.1 Relevant)

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

(Other directories not relevant for Story 1.1)
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

| ❌ BAD                                                                               | ✅ GOOD                                                                 | WHY                  |
| ------------------------------------------------------------------------------------ | ----------------------------------------------------------------------- | -------------------- |
| `DB::select('SELECT * FROM products WHERE category_id = ?', [$id])`                  | `Product::where('category_id', $id)->get()`                             | Use Eloquent ORM     |
| `$products = Product::all(); foreach ($products as $p) { echo $p->category->name; }` | `Product::with('category')->get()`                                      | Prevent N+1 queries  |
| `$table->integer('category_id');`                                                    | `$table->foreignId('category_id')->constrained()->onDelete('cascade');` | Enforce foreign keys |
| `Product::create($request->all())`                                                   | `Product::create($request->validated())`                                | Validate inputs      |
| `$product->quantity -= $qty; $product->save();`                                      | `StockMovement::create([...])`                                          | Use triggers         |
| `$points = floor($total / 100000);`                                                  | `$points = floor($total / config('tact.points_per_100k'));`             | No hardcoded values  |

### Testing Requirements

**Required Tests (Story 1.1):**

```php
// Feature Tests
✓ test_all_tables_created() - Assert 12 tables exist
✓ test_update_stock_trigger_works() - Insert stock_movement, check quantity
✓ test_add_points_trigger_works() - Complete order, check points
✓ test_foreign_keys_enforced() - Try delete parent, expect error

// Unit Tests
✓ test_product_belongs_to_category() - Check relationship
✓ test_product_has_one_product_spec() - Check relationship
✓ test_order_has_many_order_items() - Check relationship

// Performance Tests
✓ test_pos_transaction_under_100ms() - Full transaction flow
```

**Test Implementation Examples:**

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
    StockMovement::create(['product_id' => $product->id, 'type' => 'in', 'quantity' => 5]);
    $this->assertEquals(15, $product->fresh()->quantity);
}

public function test_add_points_trigger_works()
{
    $customer = Customer::factory()->create(['points' => 0]);
    $order = Order::factory()->create(['customer_id' => $customer->id, 'total_money' => 250000, 'order_status' => 'pending']);
    $order->update(['order_status' => 'completed']);
    $this->assertEquals(2, $customer->fresh()->points);
}
```

### Troubleshooting Guide

**Common Issues & Solutions:**

```
Issue 1: Migration fails with "foreign key constraint"
→ Solution: Check migration order. Parent tables must exist first.
→ Command: php artisan migrate:fresh (reset and re-run)

Issue 2: Trigger not firing
→ Solution: Check trigger syntax. Use DB::unprepared() in migration.
→ Test: Insert stock_movement, check products.quantity updated

Issue 3: Seeder fails with "duplicate entry"
→ Solution: Run migrate:fresh --seed (not just seed)
→ Or: Add unique checks in seeder

Issue 4: Performance test fails (> 100ms)
→ Solution: Check indexes on foreign keys
→ Add: $table->index('product_id') in stock_movements migration

Issue 5: Eloquent relationships not working
→ Solution: Check foreign key naming (must be {table}_id)
→ Check: belongsTo/hasMany definitions in models
```

### Week 1 Implementation Checklist

**Before starting Story 1.1:**

-   [x] Read database trigger performance plan
-   [x] Understand migration order dependencies
-   [x] Review seeder data specifications

**During Story 1.1 (Project Setup):**

-   [ ] Create database schema with 2 triggers (follow migration order)
-   [ ] Test trigger performance with realistic data (< 100ms target)
-   [ ] Validate all performance targets met

**After Story 1.1:**

-   [ ] Confirm POS transaction < 100ms (test with triggers)
-   [ ] Document any deviations or issues
-   [ ] Update sprint-status.yaml to in-progress

## Dev Agent Record

### Context Reference

-   database/db.sql (reference schema)
-   project-context.md (coding standards)

### Agent Model Used

Claude (Kiro IDE)

### Debug Log References

-   Configured phpunit.xml to use MySQL instead of SQLite for trigger testing
-   Fixed test files to use dynamic IDs instead of hardcoded values
-   Fixed UserSeeder to lookup Admin role dynamically

### Completion Notes List

-   ✅ Created 12 migrations matching db.sql schema exactly
-   ✅ Created 2 database triggers (update_stock, add_points) working correctly
-   ✅ Created 12 Eloquent models with proper relationships
-   ✅ Created 6 seeders (Role, User, Customer, Category, Brand, DatabaseSeeder)
-   ✅ Added indexes on all required columns for performance
-   ✅ All 25 tests passing (MigrationTest, TriggerTest, SeederTest, ProductTest, OrderTest)
-   ✅ Stock trigger performance < 100ms verified

### File List

**Migrations (14 files):**

-   database/migrations/2024_01_01_000001_create_roles_table.php
-   database/migrations/2024_01_01_000002_create_users_table.php
-   database/migrations/2024_01_01_000003_create_customers_table.php
-   database/migrations/2024_01_01_000004_create_categories_table.php
-   database/migrations/2024_01_01_000005_create_brands_table.php
-   database/migrations/2024_01_01_000006_create_suppliers_table.php
-   database/migrations/2024_01_01_000007_create_products_table.php
-   database/migrations/2024_01_01_000008_create_product_specs_table.php
-   database/migrations/2024_01_01_000009_create_stock_movements_table.php
-   database/migrations/2024_01_01_000010_create_promotions_table.php
-   database/migrations/2024_01_01_000011_create_orders_table.php
-   database/migrations/2024_01_01_000012_create_order_items_table.php
-   database/migrations/2024_01_01_000013_create_update_stock_trigger.php
-   database/migrations/2024_01_01_000014_create_add_points_trigger.php

**Models (12 files):**

-   app/Models/Role.php
-   app/Models/User.php
-   app/Models/Customer.php
-   app/Models/Category.php
-   app/Models/Brand.php
-   app/Models/Supplier.php
-   app/Models/Product.php
-   app/Models/ProductSpec.php
-   app/Models/StockMovement.php
-   app/Models/Promotion.php
-   app/Models/Order.php
-   app/Models/OrderItem.php

**Seeders (6 files):**

-   database/seeders/RoleSeeder.php
-   database/seeders/UserSeeder.php
-   database/seeders/CustomerSeeder.php
-   database/seeders/CategorySeeder.php
-   database/seeders/BrandSeeder.php
-   database/seeders/DatabaseSeeder.php

**Factories (5 files):**

-   database/factories/CategoryFactory.php
-   database/factories/BrandFactory.php
-   database/factories/ProductFactory.php
-   database/factories/CustomerFactory.php
-   database/factories/OrderFactory.php

**Tests (5 files):**

-   tests/Feature/Database/MigrationTest.php
-   tests/Feature/Database/TriggerTest.php
-   tests/Feature/Database/SeederTest.php
-   tests/Unit/Models/ProductTest.php
-   tests/Unit/Models/OrderTest.php

**Config (1 file modified):**

-   phpunit.xml (changed to MySQL for testing)
