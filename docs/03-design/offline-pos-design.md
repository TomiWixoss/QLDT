# Offline POS Design - Tact

**Date:** 2025-12-14
**Purpose:** Design offline-first POS capability for business continuity

## Problem Statement

**Critical Business Need:** POS must work during internet outages

-   Sales staff cannot process transactions if system is down
-   Lost revenue during outages (average 2-3 hours/month in Vietnam)
-   Customer frustration and lost sales

**Current Gap:** UX and Architecture assume online connectivity

## Offline-First POS Strategy

### Architecture Approach

**Technology:** Service Worker + IndexedDB + Background Sync

1. **Service Worker**

    - Cache POS interface (HTML, CSS, JS)
    - Cache product data (images, prices, specs)
    - Cache customer data (recent customers)
    - Intercept network requests

2. **IndexedDB**

    - Store pending transactions locally
    - Store product catalog (synced daily)
    - Store customer list (synced hourly)
    - Store voucher/points data

3. **Background Sync API**
    - Queue transactions when offline
    - Auto-sync when connection restored
    - Retry failed syncs

### Data Sync Strategy

**What to Cache (Critical for Offline POS):**

1. **Product Catalog** (Daily Sync)

    - Product ID, name, SKU, price
    - Current stock quantity
    - Images (thumbnail only, WebP)
    - Category, brand
    - Size: ~5MB for 500 products

2. **Customer Data** (Hourly Sync)

    - Recent customers (last 100)
    - Customer ID, name, phone
    - Loyalty points balance
    - Size: ~500KB

3. **Voucher Data** (Hourly Sync)

    - Active vouchers only
    - Voucher code, discount, rules
    - Size: ~100KB

4. **POS Interface** (On Load)
    - HTML, CSS, JS files
    - Icons, fonts
    - Size: ~2MB

**What NOT to Cache:**

-   Full customer database (too large)
-   Order history (not needed for POS)
-   Reports and analytics
-   Admin features

### Offline Transaction Flow

**Scenario: Internet connection lost during transaction**

```
1. Sales staff searches for customer
   → Service Worker returns cached customer data
   → Shows "OFFLINE MODE" indicator (yellow banner)

2. Sales staff adds products to cart
   → Service Worker returns cached product data
   → Validates stock from cached quantity
   → Shows warning: "Stock will be verified when online"

3. Sales staff applies voucher/points
   → Service Worker validates from cached data
   → Shows warning: "Voucher will be verified when online"

4. Sales staff completes payment
   → Transaction saved to IndexedDB (pending)
   → Shows "Transaction queued - will sync when online"
   → Prints receipt with "PENDING SYNC" watermark
   → Optimistically updates local stock quantity

5. Internet connection restored
   → Background Sync API triggers
   → Sends pending transactions to server
   → Server validates stock, voucher, points
   → If valid: Transaction confirmed, receipt updated
   → If invalid: Transaction rejected, staff notified
```

### UX Design for Offline Mode

**1. Offline Indicator**

```
┌─────────────────────────────────────────┐
│ ⚠️ OFFLINE MODE - Transactions queued   │
│ [Retry Connection] [View Queue: 3]      │
└─────────────────────────────────────────┘
```

-   Yellow banner at top of POS interface
-   Shows number of pending transactions
-   Button to manually retry connection
-   Button to view queued transactions

**2. Transaction Queue View**

```
Pending Transactions (3)
─────────────────────────────────────────
1. Order #POS-001 - 25,000,000đ
   Customer: Nguyễn Văn A
   Status: Queued (2 minutes ago)
   [View Details] [Cancel]

2. Order #POS-002 - 15,000,000đ
   Customer: Trần Thị B
   Status: Queued (5 minutes ago)
   [View Details] [Cancel]

3. Order #POS-003 - 30,000,000đ
   Customer: Lê Văn C
   Status: Queued (8 minutes ago)
   [View Details] [Cancel]
```

**3. Offline Transaction Confirmation**

```
┌─────────────────────────────────────────┐
│ ⚠️ OFFLINE TRANSACTION                  │
│                                         │
│ This transaction will be queued and     │
│ synced when internet is restored.       │
│                                         │
│ Stock and voucher will be verified      │
│ when syncing. You will be notified if   │
│ there are any issues.                   │
│                                         │
│ [Cancel] [Confirm & Queue Transaction]  │
└─────────────────────────────────────────┘
```

**4. Receipt Watermark (Offline)**

```
─────────────────────────────────────────
         CỬA HÀNG ĐIỆN THOẠI TACT
─────────────────────────────────────────
Order: #POS-001 (PENDING SYNC)
Date: 14/12/2025 15:30

⚠️ TRANSACTION PENDING VERIFICATION
This receipt is temporary. You will receive
a confirmed receipt when transaction is synced.

Customer: Nguyễn Văn A
Phone: 0912345678

iPhone 15 Pro 128GB        25,000,000đ
IMEI: 123456789012345

Total: 25,000,000đ
Payment: Cash

⚠️ Please keep this receipt until confirmed.
─────────────────────────────────────────
```

**5. Sync Success Notification**

```
┌─────────────────────────────────────────┐
│ ✅ TRANSACTION SYNCED                   │
│                                         │
│ Order #POS-001 confirmed successfully   │
│ Customer: Nguyễn Văn A                  │
│ Amount: 25,000,000đ                     │
│                                         │
│ [Print Confirmed Receipt] [Close]       │
└─────────────────────────────────────────┘
```

**6. Sync Failure Notification**

```
┌─────────────────────────────────────────┐
│ ❌ TRANSACTION FAILED                   │
│                                         │
│ Order #POS-002 could not be confirmed   │
│ Reason: Product out of stock            │
│                                         │
│ Customer: Trần Thị B                    │
│ Amount: 15,000,000đ                     │
│                                         │
│ Action Required: Contact customer       │
│ [View Details] [Mark Resolved]          │
└─────────────────────────────────────────┘
```

### Conflict Resolution

**Scenario: Stock conflict during sync**

1. **Optimistic Stock Update**

    - Offline: Product A has 10 units cached
    - Sales staff sells 2 units offline
    - Local cache: 8 units remaining

2. **Concurrent Sale Online**

    - Meanwhile, 5 units sold online
    - Server: 5 units remaining (10 - 5)

3. **Sync Conflict**

    - Offline transaction tries to sync (sell 2 units)
    - Server has 5 units, offline expected 10 units
    - Conflict detected!

4. **Resolution Strategy**
    - **If server stock >= offline sale:** Approve transaction
        - Server: 5 units - 2 units = 3 units ✅
    - **If server stock < offline sale:** Reject transaction
        - Server: 1 unit, offline wants 2 units ❌
        - Notify staff, contact customer

### Implementation Plan

**Week 1: Design & Setup**

-   ✅ Create offline POS design document (this doc)
-   ✅ Setup Service Worker boilerplate
-   ✅ Setup IndexedDB schema
-   ✅ Design offline UI components

**Week 2: Core Offline Features**

-   ✅ Implement Service Worker caching
-   ✅ Implement IndexedDB storage
-   ✅ Implement offline indicator UI
-   ✅ Implement transaction queue

**Week 3: Sync & Conflict Resolution**

-   ✅ Implement Background Sync API
-   ✅ Implement conflict detection
-   ✅ Implement conflict resolution logic
-   ✅ Implement sync notifications

**Week 4: Testing & Polish**

-   ✅ Test offline scenarios (airplane mode)
-   ✅ Test sync scenarios (connection restored)
-   ✅ Test conflict scenarios (concurrent sales)
-   ✅ Polish offline UX

### Technical Specifications

**Service Worker (sw.js)**

```javascript
// Cache POS interface and assets
const CACHE_NAME = "tact-pos-v1";
const urlsToCache = [
    "/admin/pos",
    "/css/app.css",
    "/js/app.js",
    "/fonts/inter.woff2",
];

// Cache product data (daily sync)
const PRODUCT_CACHE = "tact-products-v1";

// IndexedDB schema
const DB_NAME = "tact-offline";
const DB_VERSION = 1;
const STORES = {
    transactions: "pending_transactions",
    products: "products",
    customers: "customers",
    vouchers: "vouchers",
};
```

**IndexedDB Schema**

```javascript
// pending_transactions store
{
  id: 'POS-001',
  customer_id: 123,
  items: [
    { product_id: 456, quantity: 1, imei: '123456789012345' }
  ],
  voucher_code: 'GIAM100K',
  points_used: 50,
  total: 25000000,
  payment_method: 'cash',
  created_at: '2025-12-14T15:30:00Z',
  status: 'pending'
}

// products store
{
  id: 456,
  name: 'iPhone 15 Pro 128GB',
  sku: 'IP15P-128',
  price: 25000000,
  quantity: 10,
  image: 'data:image/webp;base64,...',
  synced_at: '2025-12-14T10:00:00Z'
}
```

**Background Sync**

```javascript
// Register sync when online
self.addEventListener("sync", (event) => {
    if (event.tag === "sync-transactions") {
        event.waitUntil(syncPendingTransactions());
    }
});

async function syncPendingTransactions() {
    const transactions = await getPendingTransactions();
    for (const tx of transactions) {
        try {
            const response = await fetch("/api/pos/sync", {
                method: "POST",
                body: JSON.stringify(tx),
            });
            if (response.ok) {
                await markTransactionSynced(tx.id);
                showNotification("Transaction synced", tx.id);
            } else {
                await markTransactionFailed(tx.id, response.statusText);
                showNotification("Transaction failed", tx.id);
            }
        } catch (error) {
            // Retry later
        }
    }
}
```

### Success Metrics

**Offline POS Success:**

-   ✅ POS works 100% offline (no errors)
-   ✅ Transactions queue successfully
-   ✅ Sync success rate > 95%
-   ✅ Conflict resolution < 5% of transactions
-   ✅ Staff trained on offline mode

**Performance:**

-   ✅ Offline mode activates < 1 second
-   ✅ Transaction queue < 500ms
-   ✅ Sync completes < 10 seconds per transaction

## Conclusion

Offline POS is **critical for business continuity**. This design ensures sales can continue during internet outages with minimal disruption. Implement in Week 1-4 to ensure POS reliability from day one.

**Key Takeaway:** Better to have a simple offline POS that works than a fancy online-only POS that fails during outages.
