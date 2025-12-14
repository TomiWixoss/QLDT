# Database Trigger Performance Validation Plan - Tact

**Date:** 2025-12-14
**Purpose:** Validate database triggers meet POS < 1s performance target

## Problem Statement

**Critical Dependencies:**

-   **update_stock trigger:** Auto-updates products.quantity on stock_movements
-   **add_points trigger:** Auto-calculates loyalty points on order completion

**Performance Requirement:** POS transactions must complete < 1s (NFR2)

**Risk:** Trigger performance issues may impact POS speed

## Triggers Overview

### Trigger 1: update_stock

**Purpose:** Automatically update product quantity when stock movements occur

**Trigger Definition:**

```sql
DELIMITER $$

CREATE TRIGGER update_stock
AFTER INSERT ON stock_movements
FOR EACH ROW
BEGIN
    IF NEW.type = 'in' THEN
        UPDATE products
        SET quantity = quantity + NEW.quantity
        WHERE id = NEW.product_id;
    ELSEIF NEW.type = 'out' THEN
        UPDATE products
        SET quantity = quantity - NEW.quantity
        WHERE id = NEW.product_id;
    END IF;
END$$

DELIMITER ;
```

**When Triggered:**

-   Stock-in transaction (Warehouse)
-   POS transaction (Sales) - **CRITICAL PATH**
-   Order fulfillment (Warehouse)

**Performance Impact:**

-   1 UPDATE query per stock movement
-   Affects products table (indexed by id)
-   Expected time: < 10ms

### Trigger 2: add_points

**Purpose:** Automatically calculate and award loyalty points on order completion

**Trigger Definition:**

```sql
DELIMITER $$

CREATE TRIGGER add_points
AFTER UPDATE ON orders
FOR EACH ROW
BEGIN
    IF NEW.status = 'completed' AND OLD.status != 'completed' THEN
        DECLARE points_earned INT;
        SET points_earned = FLOOR(NEW.total_money / 100000);

        UPDATE customers
        SET points = points + points_earned
        WHERE id = NEW.customer_id;
    END IF;
END$$

DELIMITER ;
```

**When Triggered:**

-   Order status changes to 'completed'
-   POS transaction completion - **CRITICAL PATH**
-   Online order completion

**Performance Impact:**

-   1 calculation + 1 UPDATE query per order
-   Affects customers table (indexed by id)
-   Expected time: < 10ms

## Performance Testing Plan

### Test Environment Setup

**Database:**

-   MySQL 8.0+ (same as production)
-   Realistic data volume:
    -   500 products
    -   1,000 customers
    -   10,000 orders (historical)
    -   5,000 stock movements (historical)

**Test Tools:**

-   MySQL EXPLAIN for query analysis
-   MySQL slow query log
-   Custom performance test script

### Test Scenarios

#### Scenario 1: POS Transaction (Critical Path)

**Workflow:**

1. Create order (INSERT orders)
2. Create order items (INSERT order_items)
3. Create stock movements (INSERT stock_movements) → **Trigger: update_stock**
4. Update order status to 'completed' (UPDATE orders) → **Trigger: add_points**

**Expected Total Time:** < 100ms (well under 1s target)

**Test Script:**

```php
// tests/Performance/POSTransactionTest.php
public function test_pos_transaction_performance()
{
    // Setup
    $customer = Customer::factory()->create(['points' => 100]);
    $product = Product::factory()->create(['quantity' => 10, 'price' => 25000000]);

    // Start timer
    $start = microtime(true);

    // Create order
    $order = Order::create([
        'customer_id' => $customer->id,
        'source' => 'store',
        'status' => 'pending',
        'total_money' => 25000000,
        'points_used' => 0,
    ]);

    // Create order item
    OrderItem::create([
        'order_id' => $order->id,
        'product_id' => $product->id,
        'quantity' => 1,
        'price' => 25000000,
        'imei_list' => '123456789012345',
    ]);

    // Create stock movement (triggers update_stock)
    StockMovement::create([
        'product_id' => $product->id,
        'type' => 'out',
        'quantity' => 1,
        'reference_type' => 'order',
        'reference_id' => $order->id,
    ]);

    // Complete order (triggers add_points)
    $order->update(['status' => 'completed']);

    // End timer
    $end = microtime(true);
    $duration = ($end - $start) * 1000; // Convert to ms

    // Assert performance
    $this->assertLessThan(100, $duration, "POS transaction took {$duration}ms, expected < 100ms");

    // Verify trigger results
    $this->assertEquals(9, $product->fresh()->quantity); // 10 - 1
    $this->assertEquals(350, $customer->fresh()->points); // 100 + 250 (25M / 100K)
}
```

#### Scenario 2: Bulk Stock-In (Warehouse)

**Workflow:**

1. Create 100 stock movements (INSERT stock_movements × 100) → **Trigger: update_stock × 100**

**Expected Total Time:** < 1s (10ms × 100 = 1000ms)

**Test Script:**

```php
public function test_bulk_stock_in_performance()
{
    // Setup
    $products = Product::factory()->count(100)->create(['quantity' => 0]);

    // Start timer
    $start = microtime(true);

    // Bulk stock-in
    foreach ($products as $product) {
        StockMovement::create([
            'product_id' => $product->id,
            'type' => 'in',
            'quantity' => 10,
            'reference_type' => 'purchase',
            'reference_id' => 1,
        ]);
    }

    // End timer
    $end = microtime(true);
    $duration = ($end - $start) * 1000;

    // Assert performance
    $this->assertLessThan(1000, $duration, "Bulk stock-in took {$duration}ms, expected < 1000ms");

    // Verify trigger results
    foreach ($products as $product) {
        $this->assertEquals(10, $product->fresh()->quantity);
    }
}
```

#### Scenario 3: Concurrent POS Transactions

**Workflow:**

1. Simulate 10 concurrent POS transactions
2. Measure total time and individual transaction times

**Expected Total Time:** < 1s per transaction (concurrent)

**Test Script:**

```php
public function test_concurrent_pos_transactions()
{
    // Setup
    $customers = Customer::factory()->count(10)->create();
    $product = Product::factory()->create(['quantity' => 100]);

    // Start timer
    $start = microtime(true);

    // Simulate concurrent transactions
    $promises = [];
    foreach ($customers as $customer) {
        $promises[] = $this->createPOSTransaction($customer, $product);
    }

    // Wait for all to complete
    Promise::all($promises)->wait();

    // End timer
    $end = microtime(true);
    $duration = ($end - $start) * 1000;

    // Assert performance
    $this->assertLessThan(2000, $duration, "10 concurrent transactions took {$duration}ms, expected < 2000ms");

    // Verify trigger results
    $this->assertEquals(90, $product->fresh()->quantity); // 100 - 10
}
```

### Performance Benchmarks

**Target Performance:**

-   Single POS transaction: < 100ms ✅
-   Bulk stock-in (100 items): < 1s ✅
-   Concurrent transactions (10): < 2s total ✅

**Acceptable Performance:**

-   Single POS transaction: < 200ms ⚠️
-   Bulk stock-in (100 items): < 2s ⚠️
-   Concurrent transactions (10): < 5s total ⚠️

**Unacceptable Performance:**

-   Single POS transaction: > 500ms ❌
-   Bulk stock-in (100 items): > 5s ❌
-   Concurrent transactions (10): > 10s total ❌

### Optimization Strategies

**If Triggers Are Slow (> 100ms):**

**Option 1: Optimize Trigger Logic**

```sql
-- Add index on products.id (should already exist)
CREATE INDEX idx_products_id ON products(id);

-- Add index on customers.id (should already exist)
CREATE INDEX idx_customers_id ON customers(id);

-- Ensure foreign keys are indexed
CREATE INDEX idx_stock_movements_product_id ON stock_movements(product_id);
CREATE INDEX idx_orders_customer_id ON orders(customer_id);
```

**Option 2: Use Application-Level Logic**

```php
// Instead of trigger, update in application code
class StockMovementService
{
    public function createStockMovement(array $data): StockMovement
    {
        DB::transaction(function () use ($data) {
            // Create stock movement
            $movement = StockMovement::create($data);

            // Update product quantity manually
            if ($data['type'] === 'in') {
                Product::where('id', $data['product_id'])
                    ->increment('quantity', $data['quantity']);
            } else {
                Product::where('id', $data['product_id'])
                    ->decrement('quantity', $data['quantity']);
            }

            return $movement;
        });
    }
}
```

**Option 3: Queue Points Calculation**

```php
// Instead of trigger, queue points calculation
class OrderService
{
    public function completeOrder(Order $order): void
    {
        $order->update(['status' => 'completed']);

        // Queue points calculation (async)
        CalculateLoyaltyPoints::dispatch($order);
    }
}

// Job
class CalculateLoyaltyPoints implements ShouldQueue
{
    public function handle(): void
    {
        $pointsEarned = floor($this->order->total_money / 100000);

        Customer::where('id', $this->order->customer_id)
            ->increment('points', $pointsEarned);
    }
}
```

### Testing Schedule

**Week 1: Initial Testing**

-   Day 1: Setup test environment with realistic data
-   Day 2: Run Scenario 1 (POS transaction)
-   Day 3: Run Scenario 2 (Bulk stock-in)
-   Day 4: Run Scenario 3 (Concurrent transactions)
-   Day 5: Analyze results, decide on optimizations

**Week 2: Optimization (If Needed)**

-   Day 1-2: Implement optimizations (indexes, app-level logic, or queues)
-   Day 3-4: Re-run all scenarios
-   Day 5: Validate performance meets targets

**Week 3-8: Continuous Monitoring**

-   Monitor slow query log
-   Track POS transaction times in production
-   Alert if any transaction > 500ms

### Monitoring & Alerts

**MySQL Slow Query Log:**

```ini
# my.cnf
slow_query_log = 1
slow_query_log_file = /var/log/mysql/slow-query.log
long_query_time = 0.1  # Log queries > 100ms
```

**Application Monitoring:**

```php
// Log POS transaction times
Log::info('POS transaction completed', [
    'order_id' => $order->id,
    'duration_ms' => $duration,
    'trigger_update_stock' => $updateStockTime,
    'trigger_add_points' => $addPointsTime,
]);

// Alert if slow
if ($duration > 500) {
    Log::warning('Slow POS transaction detected', [
        'order_id' => $order->id,
        'duration_ms' => $duration,
    ]);
}
```

### Success Criteria

**Trigger Performance Success:**

-   ✅ Single POS transaction < 100ms (95th percentile)
-   ✅ Bulk stock-in (100 items) < 1s
-   ✅ Concurrent transactions (10) < 2s total
-   ✅ No slow queries > 100ms in production
-   ✅ Zero trigger-related errors

**If Success Criteria Not Met:**

-   ⚠️ Implement application-level logic (Option 2)
-   ⚠️ Queue points calculation (Option 3)
-   ⚠️ Optimize database indexes
-   ⚠️ Consider database scaling (read replicas)

## Conclusion

**Database triggers are critical for data consistency** but must be validated for performance. Test early (Week 1-2) with realistic data to ensure POS < 1s target is met. If triggers are slow, have backup plans ready (application-level logic or queues).

**Key Takeaway:** Test triggers early, optimize if needed, monitor continuously.
