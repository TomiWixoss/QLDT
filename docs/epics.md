---
stepsCompleted: ["step-01-validate-prerequisites", "step-02-design-epics"]
inputDocuments:
    - "docs/prd.md"
    - "docs/architecture.md"
    - "docs/ux-design-specification.md"
epicCount: 10
totalFRs: 139
---

# Tact - Epic Breakdown

## Overview

This document provides the complete epic and story breakdown for Tact, decomposing the requirements from the PRD, UX Design, and Architecture requirements into implementable stories.

## Requirements Inventory

### Functional Requirements

FR1: Customers can register using email and password
FR2: Customers can register using Google OAuth
FR3: Customers who register via Google OAuth must set a password on first login
FR4: Customers can log in using email/password or Google OAuth
FR5: Customers can view their profile information
FR6: Customers can update their profile information
FR7: Customers can change their password
FR8: Customers can view their current loyalty points balance
FR9: Customers can view homepage with featured products and banners
FR10: Customers can browse products by category
FR11: Customers can browse products by brand
FR12: Customers can filter products by price range
FR13: Customers can filter products by brand
FR14: Customers can filter products by category
FR15: Customers can sort products by price (low to high, high to low)
FR16: Customers can sort products by newest arrivals
FR17: Customers can sort products by best sellers
FR18: Customers can search products by name or SKU
FR19: Customers can view product details including specs, price, stock availability, warranty info, and IMEI tracking notice
FR20: Customers can view product images with thumbnails
FR21: Customers can view product technical specifications
FR22: Customers can add products to shopping cart
FR23: Customers can update product quantities in cart
FR24: Customers can remove products from cart
FR25: Customers can view cart summary with subtotal
FR26: Customers can proceed to checkout from cart
FR27: Customers can enter shipping information during checkout
FR28: Customers can apply voucher codes during checkout
FR29: Customers can use loyalty points for discount during checkout
FR30: Customers can select payment method (COD or Bank Transfer)
FR31: Customers can view order summary with price breakdown (subtotal, voucher discount, points discount, total)
FR32: Customers can confirm and place orders
FR33: Customers can view list of their orders
FR34: Customers can filter orders by status
FR35: Customers can view order details including products, IMEI numbers, warranty information, shipping address, and price breakdown
FR36: Customers can view order status timeline (pending → confirmed → shipping → completed → cancelled)
FR37: Customers can cancel orders that are in pending status
FR38: Customers can view IMEI numbers for purchased devices
FR39: Customers can view warranty expiration dates for purchased devices
FR40: Customers automatically earn loyalty points when orders are completed (100,000 VND = 1 point)
FR41: Customers can redeem loyalty points for discounts (1 point = 1,000 VND)
FR42: Customers can view available vouchers
FR43: Customers can apply vouchers to orders (both online and POS)
FR44: Sales staff can access POS interface
FR45: Sales staff can search customers by phone number
FR46: Sales staff can create new customer records quickly
FR47: Sales staff can search products by name or SKU
FR48: Sales staff can add products to POS cart
FR49: Sales staff can update quantities in POS cart
FR50: Sales staff can remove products from POS cart
FR51: Sales staff can apply voucher codes to POS transactions
FR52: Sales staff can apply customer loyalty points to POS transactions
FR53: Sales staff can enter IMEI numbers for devices
FR54: Sales staff can select payment method (Cash, Card, Bank Transfer)
FR55: Sales staff can complete POS transactions
FR56: System automatically creates completed orders for POS transactions
FR57: System automatically records IMEI numbers in order items
FR58: System automatically updates inventory when POS transactions complete
FR59: System automatically deducts used loyalty points
FR60: System automatically awards new loyalty points for POS transactions
FR61: Sales staff can print invoices with IMEI numbers
FR62: Staff can view list of all orders
FR63: Staff can filter orders by status
FR64: Staff can filter orders by source (web or store)
FR65: Staff can view order details
FR66: Staff can approve pending orders (change status to confirmed)
FR67: Staff can mark orders as shipping and enter tracking numbers
FR68: Staff can mark orders as completed
FR69: Staff can cancel orders with reason
FR70: Staff can view order timeline history
FR71: Warehouse staff can create stock-in transactions
FR72: Warehouse staff can select supplier for stock-in
FR73: Warehouse staff can enter stock-in reference number
FR74: Warehouse staff can add multiple products to stock-in transaction
FR75: Warehouse staff can specify quantities for each product
FR76: Warehouse staff can add notes to stock-in transactions
FR77: System prompts confirmation for high-value transactions (> 50M VND)
FR78: System automatically creates stock movement records (type='in')
FR79: System automatically updates product quantities via database trigger
FR80: Staff can view stock movement history
FR81: Staff can filter stock movements by type (in/out)
FR82: Staff can filter stock movements by date range
FR83: Staff can filter stock movements by product
FR84: Staff can view low stock alerts (< 5 items)
FR85: Staff can view dead stock alerts (> 30 days without sales)
FR86: Staff can view total inventory value by cost price
FR87: Dashboard displays color-coded stock level indicators (red: < 5, yellow: 5-10, green: > 10)
FR88: Staff can create new products
FR89: Staff can upload product images
FR90: Staff can assign products to categories
FR91: Staff can assign products to brands
FR92: Staff can set product SKU (unique)
FR93: Staff can set product prices
FR94: Staff can set warranty period in months
FR95: Staff can set product status (active/inactive)
FR96: Staff can update product information
FR97: Staff can delete products
FR98: Staff can view product list with pagination
FR99: Staff can add product technical specifications
FR100: Staff can update product technical specifications
FR101: Staff can delete product technical specifications
FR102: Staff can create vouchers with fixed discount amount
FR103: Staff can create vouchers with percentage discount
FR104: Staff can set minimum order value for vouchers
FR105: Staff can set maximum discount amount for percentage vouchers
FR106: Staff can set voucher validity period (start/end dates)
FR107: Staff can set usage limit for vouchers
FR108: Staff can set voucher codes
FR109: Staff can update voucher information
FR110: Staff can deactivate vouchers
FR111: System validates voucher eligibility during checkout/POS
FR112: System tracks voucher usage count
FR113: Staff can view dashboard with key metrics (revenue, orders, products, customers)
FR114: Staff can view revenue charts by time period
FR115: Staff can view stock alerts on dashboard
FR116: Staff can view dead stock alerts on dashboard
FR117: Staff can view inventory value on dashboard
FR118: Staff can generate revenue reports by date range
FR119: Staff can generate product performance reports (top sellers, slow movers)
FR120: Staff can generate inventory reports (stock levels, value, turnover)
FR121: Staff can generate customer reports (new customers, top customers, loyalty points)
FR122: Admin can create staff user accounts
FR123: Admin can assign roles to staff (Admin, Manager, Sales, Warehouse)
FR124: Admin can update staff information
FR125: Admin can deactivate staff accounts
FR126: System enforces role-based access control
FR127: Sales role can only access POS, orders, products, customers
FR128: Warehouse role can only access inventory management
FR129: Manager role can access all modules except user management
FR130: Admin role has full system access
FR131: Staff can view customer list
FR132: Staff can view customer details including order history and loyalty points
FR133: Staff can search customers by name, email, or phone
FR134: Staff can view customer loyalty points balance
FR135: Staff can view customer order history
FR136: Staff can create, update, and delete categories
FR137: Staff can create, update, and delete brands
FR138: Staff can create, update, and delete suppliers
FR139: Staff can view lists of categories, brands, and suppliers

### NonFunctional Requirements

NFR1: Customer-facing pages load within 2 seconds (First Contentful Paint < 1.5s, Largest Contentful Paint < 2.5s)
NFR2: POS transactions complete within 1 second from click to confirmation
NFR3: Product search returns results within 500ms
NFR4: Shopping cart operations (add/remove/update) complete within 300ms
NFR5: Dashboard loads with charts and data within 2 seconds
NFR6: Database queries execute within 100ms average
NFR7: System supports 50+ concurrent users without performance degradation
NFR8: POS interface handles 3 transactions per minute per sales staff
NFR9: System processes 50+ orders per day without performance issues
NFR10: Page sizes optimized (< 2MB including images)
NFR11: Images lazy-loaded below the fold
NFR12: Vite code splitting reduces initial bundle size
NFR13: All passwords hashed using bcrypt with minimum 10 rounds
NFR14: Session cookies are HTTP-only and secure (HTTPS only in production)
NFR15: CSRF protection enabled for all state-changing requests
NFR16: Role-based access control enforced at middleware level
NFR17: Failed login attempts logged for security monitoring
NFR18: All database connections use encrypted channels (SSL/TLS)
NFR19: Sensitive data (passwords, payment info) never logged in plain text
NFR20: IMEI numbers stored securely with access audit trail
NFR21: Customer personal information (email, phone, address) protected per GDPR principles
NFR22: All user inputs validated and sanitized server-side
NFR23: SQL injection prevented through Eloquent ORM parameterized queries
NFR24: XSS attacks prevented through Blade template escaping
NFR25: File uploads restricted to allowed types (images only) with size limits (< 5MB)
NFR26: System maintains audit logs for financial transactions (orders, payments)
NFR27: Customer data deletion capability (GDPR right to be forgotten)
NFR28: VAT invoice generation complies with Vietnamese tax regulations
NFR29: System uptime target of 99%+ (< 7.2 hours downtime per month)
NFR30: Planned maintenance windows communicated 24 hours in advance
NFR31: Critical bugs (blocking operations) resolved within 4 hours
NFR32: Zero data loss for completed transactions
NFR33: Database backups performed daily with 30-day retention
NFR34: Database triggers ensure inventory consistency (stock movements auto-update quantities)
NFR35: Loyalty points calculations are atomic (no partial updates)
NFR36: All errors logged with context (user, action, timestamp, stack trace)
NFR37: User-friendly error messages displayed (no technical details exposed)
NFR38: System gracefully handles database connection failures with retry logic
NFR39: Failed transactions rolled back completely (no partial state)
NFR40: Database restore time < 1 hour from backup
NFR41: System recovery time < 5 minutes after crash
NFR42: Transaction logs enable point-in-time recovery
NFR43: All customer-facing pages fully functional on mobile (320px width minimum)
NFR44: Admin pages functional on tablets (768px width minimum)
NFR45: POS interface optimized for desktop/laptop (1024px+ width)
NFR46: Touch targets minimum 44x44px for mobile interactions
NFR47: Full support for Chrome, Firefox, Safari, Edge (latest 2 versions)
NFR48: Graceful degradation for older browsers (no crashes, basic functionality)
NFR49: Mobile browser support for iOS Safari and Chrome Mobile
NFR50: WCAG 2.1 Level A compliance (keyboard navigation, alt text, semantic HTML)
NFR51: All interactive elements accessible via keyboard (Tab, Enter, Escape)
NFR52: Form inputs have associated labels (for/id attributes)
NFR53: Color contrast ratio minimum 4.5:1 for text
NFR54: Focus indicators visible on all interactive elements
NFR55: Lighthouse accessibility score 80+ target
NFR56: Consistent UI patterns across all pages (DaisyUI components)
NFR57: Loading indicators displayed for operations > 500ms
NFR58: Success/error feedback provided for all user actions
NFR59: Form validation errors displayed inline with clear messages
NFR60: Confirmation prompts for destructive actions (delete, cancel order)
NFR61: Laravel best practices followed (PSR-12 coding standards)
NFR62: Code organized following MVC pattern (Models, Views, Controllers)
NFR63: Database migrations version-controlled for schema changes
NFR64: Environment-specific configuration via .env files (no hardcoded values)
NFR65: Database ERD documented with relationships
NFR66: API endpoints documented (if any)
NFR67: User manual provided for admin features
NFR68: Setup instructions documented (installation, configuration, deployment)
NFR69: Critical user journeys manually tested before demo
NFR70: Database triggers tested with realistic data
NFR71: Role-based access control tested for all roles
NFR72: Cross-browser testing performed on target browsers

### Additional Requirements

**Architecture Requirements:**

-   Laravel 12 + PHP 8.2+ framework foundation
-   Tailwind CSS 4 + DaisyUI 5 for styling
-   Vite 7 + Laravel Vite Plugin 2.0 for asset compilation
-   MySQL database with 12 tables and 2 triggers (update_stock, add_points)
-   Laravel Breeze for authentication scaffolding (blade stack)
-   Laravel Socialite for Google OAuth integration
-   Dual authentication guards (web for staff, customer for customers)
-   Session-based authentication for both guards
-   Role-based access control with 4 roles (Admin, Manager, Sales, Warehouse)
-   Laravel Gates & Policies for authorization
-   Database triggers for stock updates (update_stock) and loyalty points calculation (add_points)
-   Eloquent ORM with eager loading for N+1 prevention
-   Eloquent Observers for application-level hooks (optional alongside triggers)
-   Session-based cart storage
-   Polling-based dashboard updates (AJAX every 30 seconds)
-   WebP image format with responsive srcset
-   Lazy loading for images below fold (native loading="lazy")
-   CSS-first animations (no heavy JS libraries like GSAP or Three.js)
-   Separate layouts for customer (Nike-inspired) and admin (DaisyUI functional)
-   Inter Variable Font for premium typography
-   Mobile-first responsive design (375px base breakpoint)
-   Axios 1.11.0 for AJAX requests
-   Chart.js for dashboard analytics
-   Laravel MVC structure (Models, Views, Controllers)
-   Service layer for business logic (OrderService, CartService, InventoryService, PointsService, VoucherService)
-   Repository pattern for complex queries (ProductRepository, OrderRepository, CustomerRepository)
-   Form Request validation for all inputs
-   PSR-12 coding standards
-   Database migrations version-controlled
-   Environment-specific configuration via .env files
-   Redis for caching (production) / File cache (development)
-   Cache tags for group invalidation
-   Optimistic locking for concurrent transactions (POS)
-   Database transactions for critical operations
-   CSRF protection enabled (Laravel default)
-   XSS prevention (Blade escaping)
-   SQL injection prevention (Eloquent ORM)
-   Bcrypt password hashing (minimum 10 rounds)
-   HTTP-only and secure session cookies
-   Rate limiting (60 requests/minute per IP)
-   Login throttling (5 attempts per minute)
-   Input sanitization (strip_tags, htmlspecialchars)
-   File upload validation (MIME type, size < 5MB, images only)
-   Audit logs for financial transactions
-   Daily automated database backups (30-day retention)
-   Error logging with context (user, action, timestamp, stack trace)
-   Laravel Pail for real-time log viewing (development)
-   Laravel Telescope for debugging (development only)
-   Vite code splitting and tree shaking
-   Critical CSS inline (above fold)
-   Asset minification in production
-   Browser cache headers

**UX Design Requirements:**

**Design Direction: Hybrid Sophisticated (Direction 6)**

-   Nike-inspired generous whitespace and clean layouts for customer site
-   Apple-inspired minimalism and premium typography (Inter Variable Font)
-   Stripe-inspired micro-interactions (button hovers, form feedback, smooth transitions)
-   Mobbin-inspired mobile app-like patterns (bottom nav, swipe gestures, floating buttons)

**Navigation Patterns:**

-   Bottom navigation bar for mobile customer site (Home, Search, Cart, Account - 4 items max, 56px height)
-   Sidebar navigation for admin (collapsible on mobile, 256px width desktop)
-   Breadcrumbs for navigation path on detail pages
-   Tab navigation with underline style (not boxed)

**Interaction Patterns:**

-   Swipe gestures for product image galleries
-   Floating "Add to Cart" button on product pages (sticky, thumb-friendly)
-   Pull-to-refresh for order status updates
-   One-tap add to cart with animation confirmation
-   Instant feedback on all actions (< 100ms response time)
-   Smooth transitions with cubic-bezier easing (0.3s duration, cubic-bezier(0.4, 0, 0.2, 1))
-   Hover effects: translateY(-2px) + shadow increase (0.2s ease)
-   Touch targets 44x44px minimum for all interactive elements
-   8px minimum gap between touch targets

**Trust & Transparency:**

-   IMEI tracking badge prominent on product cards (green, top-right position)
-   Warranty countdown timer on product pages (visual progress bar)
-   Trust section with 3 icons (IMEI, Warranty, Delivery) on product detail
-   "Chính hãng" seal with premium styling
-   Security badges in footer (SSL, payment methods)
-   IMEI printed prominently on invoices (large font, easy to read)

**Loading & Feedback States:**

-   Skeleton screens for loading states (pulse animation, match content shape)
-   Loading indicators for operations > 500ms
-   Success animations for order completion (checkmark, subtle confetti CSS-only)
-   Toast notifications (top-right desktop, top-center mobile, auto-dismiss 3s)
-   Inline validation errors (red border, red text below field)
-   Progress indicators for multi-step flows

**Visual Design:**

-   Color palette: Tact Blue (#3b82f6), Tact Gray (#6b7280), Tact Orange (#f97316)
-   Semantic colors: Success (#10b981), Warning (#f59e0b), Error (#ef4444), Info (#3b82f6)
-   Typography: Inter Variable Font (400-700 weights), 1.5 line-height for body text
-   Spacing: 4px base unit (8px, 12px, 16px, 24px, 32px, 48px, 64px scale)
-   Border radius: rounded-xl for cards and buttons
-   Shadows: Subtle on cards, increase on hover
-   Color-coded stock level indicators (red: < 5, yellow: 5-10, green: > 10)

**Responsive Design:**

-   Mobile-first approach (375px base breakpoint)
-   Breakpoints: sm (640px), md (768px), lg (1024px), xl (1280px), 2xl (1536px)
-   Mobile: Single column, bottom navigation, full-width cards
-   Tablet: 2-column grid, collapsible sidebar
-   Desktop: 3-4 column grid, full sidebar, multi-widget dashboard

**Forms & Inputs:**

-   Auto-fill forms where possible (saved addresses, payment methods)
-   Inline validation on blur
-   Clear error messages in Vietnamese
-   Labels above inputs (font-medium)
-   Rounded inputs (rounded-lg)
-   Focus state: blue border + subtle shadow

**Accessibility:**

-   WCAG 2.1 Level A compliance
-   Keyboard navigation support (Tab, Enter, Escape)
-   Visible focus indicators on all interactive elements
-   ARIA labels on interactive elements
-   Alt text for all images
-   Color contrast ratio minimum 4.5:1 for text
-   Semantic HTML (nav, main, article, aside tags)

**Performance Optimization:**

-   High-quality product photography (WebP format, responsive srcset)
-   Lazy loading for images below fold (native loading="lazy")
-   Critical CSS inline (above fold)
-   Defer non-critical JavaScript
-   Preload key assets (fonts, hero image)
-   Code splitting via Vite (automatic)

**Component Requirements:**

-   Product card with IMEI badge and hover lift effect
-   Order timeline component (vertical, animated progress)
-   POS cart component (optimized for speed)
-   Customer lookup component (quick search by phone)
-   Stock alert cards (color-coded severity)
-   Trust badges component (reusable)
-   Bottom navigation component (mobile)
-   Sidebar navigation component (admin)
-   Data table component (admin CRUD)
-   Chart containers (dashboard analytics)
-   Modal dialogs (confirmation, forms)
-   Toast notification system
-   Skeleton loading components

### FR Coverage Map

**Epic 1: Project Foundation & Authentication (17 FRs)**

-   FR1: Customer register with email/password → Epic 1
-   FR2: Customer register with Google OAuth → Epic 1
-   FR3: Google OAuth users set password on first login → Epic 1
-   FR4: Customer login with email/password or Google OAuth → Epic 1
-   FR5: Customer view profile → Epic 1
-   FR6: Customer update profile → Epic 1
-   FR7: Customer change password → Epic 1
-   FR8: Customer view loyalty points balance → Epic 1
-   FR122: Admin create staff accounts → Epic 1
-   FR123: Admin assign roles (Admin, Manager, Sales, Warehouse) → Epic 1
-   FR124: Admin update staff information → Epic 1
-   FR125: Admin deactivate staff accounts → Epic 1
-   FR126: System enforce role-based access control → Epic 1
-   FR127: Sales role access (POS, orders, products, customers) → Epic 1
-   FR128: Warehouse role access (inventory only) → Epic 1
-   FR129: Manager role access (all except user management) → Epic 1
-   FR130: Admin role full access → Epic 1

**Epic 2: Master Data Management (4 FRs)**

-   FR136: Staff CRUD categories → Epic 2
-   FR137: Staff CRUD brands → Epic 2
-   FR138: Staff CRUD suppliers → Epic 2
-   FR139: Staff view lists of categories, brands, suppliers → Epic 2

**Epic 3: Product Management (14 FRs)**

-   FR88: Staff create products → Epic 3
-   FR89: Staff upload product images → Epic 3
-   FR90: Staff assign products to categories → Epic 3
-   FR91: Staff assign products to brands → Epic 3
-   FR92: Staff set product SKU (unique) → Epic 3
-   FR93: Staff set product prices → Epic 3
-   FR94: Staff set warranty period → Epic 3
-   FR95: Staff set product status (active/inactive) → Epic 3
-   FR96: Staff update product information → Epic 3
-   FR97: Staff delete products → Epic 3
-   FR98: Staff view product list with pagination → Epic 3
-   FR99: Staff add product technical specifications → Epic 3
-   FR100: Staff update product specifications → Epic 3
-   FR101: Staff delete product specifications → Epic 3

**Epic 4: Product Discovery & Browsing (Customer) (13 FRs)**

-   FR9: Customer view homepage with featured products → Epic 4
-   FR10: Customer browse products by category → Epic 4
-   FR11: Customer browse products by brand → Epic 4
-   FR12: Customer filter products by price range → Epic 4
-   FR13: Customer filter products by brand → Epic 4
-   FR14: Customer filter products by category → Epic 4
-   FR15: Customer sort by price (low to high, high to low) → Epic 4
-   FR16: Customer sort by newest arrivals → Epic 4
-   FR17: Customer sort by best sellers → Epic 4
-   FR18: Customer search products by name or SKU → Epic 4
-   FR19: Customer view product details (specs, price, stock, warranty, IMEI) → Epic 4
-   FR20: Customer view product images with thumbnails → Epic 4
-   FR21: Customer view product technical specifications → Epic 4

**Epic 5: Shopping Cart & Checkout (11 FRs)**

-   FR22: Customer add products to cart → Epic 5
-   FR23: Customer update quantities in cart → Epic 5
-   FR24: Customer remove products from cart → Epic 5
-   FR25: Customer view cart summary with subtotal → Epic 5
-   FR26: Customer proceed to checkout → Epic 5
-   FR27: Customer enter shipping information → Epic 5
-   FR28: Customer apply voucher codes → Epic 5
-   FR29: Customer use loyalty points for discount → Epic 5
-   FR30: Customer select payment method (COD/Bank Transfer) → Epic 5
-   FR31: Customer view order summary with price breakdown → Epic 5
-   FR32: Customer confirm and place orders → Epic 5

**Epic 6: Promotion & Loyalty System (15 FRs)**

-   FR40: Customer auto earn points when order completed (100K VND = 1 point) → Epic 6
-   FR41: Customer redeem points for discount (1 point = 1K VND) → Epic 6
-   FR42: Customer view available vouchers → Epic 6
-   FR43: Customer apply vouchers (online and POS) → Epic 6
-   FR102: Staff create vouchers with fixed discount → Epic 6
-   FR103: Staff create vouchers with percentage discount → Epic 6
-   FR104: Staff set minimum order value for vouchers → Epic 6
-   FR105: Staff set maximum discount for percentage vouchers → Epic 6
-   FR106: Staff set voucher validity period → Epic 6
-   FR107: Staff set usage limit for vouchers → Epic 6
-   FR108: Staff set voucher codes → Epic 6
-   FR109: Staff update voucher information → Epic 6
-   FR110: Staff deactivate vouchers → Epic 6
-   FR111: System validate voucher eligibility → Epic 6
-   FR112: System track voucher usage count → Epic 6

**Epic 7: Order Management (Customer & Staff) (16 FRs)**

-   FR33: Customer view list of orders → Epic 7
-   FR34: Customer filter orders by status → Epic 7
-   FR35: Customer view order details (products, IMEI, warranty, shipping, price) → Epic 7
-   FR36: Customer view order status timeline → Epic 7
-   FR37: Customer cancel pending orders → Epic 7
-   FR38: Customer view IMEI numbers for devices → Epic 7
-   FR39: Customer view warranty expiration dates → Epic 7
-   FR62: Staff view all orders → Epic 7
-   FR63: Staff filter orders by status → Epic 7
-   FR64: Staff filter orders by source (web/store) → Epic 7
-   FR65: Staff view order details → Epic 7
-   FR66: Staff approve pending orders → Epic 7
-   FR67: Staff mark orders as shipping with tracking → Epic 7
-   FR68: Staff mark orders as completed → Epic 7
-   FR69: Staff cancel orders with reason → Epic 7
-   FR70: Staff view order timeline history → Epic 7

**Epic 8: Point of Sale (POS) System (18 FRs)**

-   FR44: Sales staff access POS interface → Epic 8
-   FR45: Sales staff search customers by phone → Epic 8
-   FR46: Sales staff create new customer records → Epic 8
-   FR47: Sales staff search products by name/SKU → Epic 8
-   FR48: Sales staff add products to POS cart → Epic 8
-   FR49: Sales staff update quantities in POS cart → Epic 8
-   FR50: Sales staff remove products from POS cart → Epic 8
-   FR51: Sales staff apply voucher codes → Epic 8
-   FR52: Sales staff apply customer loyalty points → Epic 8
-   FR53: Sales staff enter IMEI numbers → Epic 8
-   FR54: Sales staff select payment method → Epic 8
-   FR55: Sales staff complete POS transactions → Epic 8
-   FR56: System auto create completed orders for POS → Epic 8
-   FR57: System auto record IMEI in order items → Epic 8
-   FR58: System auto update inventory on POS complete → Epic 8
-   FR59: System auto deduct used loyalty points → Epic 8
-   FR60: System auto award new loyalty points → Epic 8
-   FR61: Sales staff print invoices with IMEI → Epic 8

**Epic 9: Inventory Management (17 FRs)**

-   FR71: Warehouse create stock-in transactions → Epic 9
-   FR72: Warehouse select supplier for stock-in → Epic 9
-   FR73: Warehouse enter stock-in reference number → Epic 9
-   FR74: Warehouse add multiple products to stock-in → Epic 9
-   FR75: Warehouse specify quantities per product → Epic 9
-   FR76: Warehouse add notes to stock-in → Epic 9
-   FR77: System prompt confirmation for high-value (> 50M) → Epic 9
-   FR78: System auto create stock movement records → Epic 9
-   FR79: System auto update product quantities via trigger → Epic 9
-   FR80: Staff view stock movement history → Epic 9
-   FR81: Staff filter movements by type (in/out) → Epic 9
-   FR82: Staff filter movements by date range → Epic 9
-   FR83: Staff filter movements by product → Epic 9
-   FR84: Staff view low stock alerts (< 5 items) → Epic 9
-   FR85: Staff view dead stock alerts (> 30 days) → Epic 9
-   FR86: Staff view total inventory value → Epic 9
-   FR87: Dashboard display color-coded stock indicators → Epic 9

**Epic 10: Dashboard, Reports & Customer Management (14 FRs)**

-   FR113: Staff view dashboard with key metrics → Epic 10
-   FR114: Staff view revenue charts by time period → Epic 10
-   FR115: Staff view stock alerts on dashboard → Epic 10
-   FR116: Staff view dead stock alerts on dashboard → Epic 10
-   FR117: Staff view inventory value on dashboard → Epic 10
-   FR118: Staff generate revenue reports by date range → Epic 10
-   FR119: Staff generate product performance reports → Epic 10
-   FR120: Staff generate inventory reports → Epic 10
-   FR121: Staff generate customer reports → Epic 10
-   FR131: Staff view customer list → Epic 10
-   FR132: Staff view customer details (order history, points) → Epic 10
-   FR133: Staff search customers by name/email/phone → Epic 10
-   FR134: Staff view customer loyalty points balance → Epic 10
-   FR135: Staff view customer order history → Epic 10

**Total: 139 FRs mapped to 10 Epics ✅**

## Epic List

### Epic 1: Project Foundation & Authentication

**Goal:** Hệ thống được setup với authentication hoàn chỉnh. Staff và customers có thể đăng nhập an toàn với role-based access control.

**User Outcomes:**

-   Customers có thể register/login bằng email/password hoặc Google OAuth
-   Customers có thể quản lý profile và xem loyalty points
-   Admin có thể tạo staff accounts và assign roles
-   System enforce RBAC cho 4 roles: Admin, Manager, Sales, Warehouse

**FRs Covered:** FR1-FR8, FR122-FR130 (17 FRs)

**Technical Foundation:**

-   Laravel 12 project initialized
-   Database schema: 12 tables + 2 triggers (update_stock, add_points)
-   Laravel Breeze + Socialite for dual authentication
-   Dual guards: web (staff), customer (customers)
-   Gates & Policies for authorization
-   Session-based authentication

---

### Epic 2: Master Data Management

**Goal:** Admin/Manager có thể quản lý dữ liệu cơ bản (categories, brands, suppliers) để chuẩn bị cho product management.

**User Outcomes:**

-   Admin/Manager có thể CRUD categories (iPhone, Samsung, Xiaomi, etc.)
-   Admin/Manager có thể CRUD brands (Apple, Samsung, Xiaomi, etc.)
-   Admin/Manager có thể CRUD suppliers (FPT, Thế Giới Di Động, etc.)
-   Foundation data ready cho product management

**FRs Covered:** FR136-FR139 (4 FRs)

**Technical Notes:**

-   Simple CRUD operations
-   DaisyUI admin interface
-   Validation rules (unique names, required fields)

---

### Epic 3: Product Management

**Goal:** Admin/Manager có thể tạo, cập nhật, xóa sản phẩm với specs đầy đủ, upload ảnh, set giá và warranty.

**User Outcomes:**

-   Admin/Manager có thể create products với đầy đủ thông tin
-   Admin/Manager có thể upload product images (WebP format)
-   Admin/Manager có thể assign categories và brands
-   Admin/Manager có thể manage product specs (screen, RAM, storage, etc.)
-   Admin/Manager có thể set SKU, prices, warranty period, status

**FRs Covered:** FR88-FR101 (14 FRs)

**Technical Notes:**

-   Product CRUD với image upload (< 5MB, WebP)
-   Product specs as separate table (one-to-one relationship)
-   SKU uniqueness validation
-   IMEI tracking setup trong data model (order_items.imei_list)

---

### Epic 4: Product Discovery & Browsing (Customer)

**Goal:** Khách hàng có thể tìm kiếm, lọc, sort sản phẩm và xem chi tiết với IMEI badge, warranty info, trust signals.

**User Outcomes:**

-   Customers có thể view homepage với featured products
-   Customers có thể browse products by category/brand
-   Customers có thể filter by price range, brand, category
-   Customers có thể sort by price, newest, best sellers
-   Customers có thể search by name or SKU
-   Customers có thể view product details với IMEI badge, warranty countdown, trust signals

**FRs Covered:** FR9-FR21 (13 FRs)

**UX/Design Notes:**

-   Nike-inspired whitespace và clean layouts
-   IMEI badge prominent (green, top-right)
-   Warranty countdown timer (visual progress bar)
-   Trust section with 3 icons (IMEI, Warranty, Delivery)
-   Product card with hover lift effect
-   Mobile-first responsive (375px base)
-   Bottom navigation for mobile

---

### Epic 5: Shopping Cart & Checkout

**Goal:** Khách hàng có thể thêm sản phẩm vào giỏ, apply voucher/points, và hoàn thành checkout.

**User Outcomes:**

-   Customers có thể add/update/remove products trong cart
-   Customers có thể view cart summary với subtotal
-   Customers có thể apply voucher codes
-   Customers có thể use loyalty points for discount
-   Customers có thể enter shipping information
-   Customers có thể select payment method (COD/Bank Transfer)
-   Customers có thể confirm và place orders

**FRs Covered:** FR22-FR32 (11 FRs)

**Technical Notes:**

-   Session-based cart storage
-   Voucher validation (eligibility, expiry, usage limit)
-   Points redemption calculation (1 point = 1,000 VND)
-   Order creation with order_items
-   Price breakdown (subtotal, voucher discount, points discount, total)

---

### Epic 6: Promotion & Loyalty System

**Goal:** Admin/Manager có thể tạo vouchers. Customers tự động earn và redeem loyalty points.

**User Outcomes:**

-   Admin/Manager có thể create vouchers (fixed amount or percentage)
-   Admin/Manager có thể set voucher rules (min order, max discount, validity, usage limit)
-   Customers tự động earn points khi order completed (100K VND = 1 point)
-   Customers có thể redeem points for discount (1 point = 1K VND)
-   Customers có thể view available vouchers
-   Vouchers work cả online và POS

**FRs Covered:** FR40-FR43, FR102-FR112 (15 FRs)

**Technical Notes:**

-   Voucher CRUD với validation rules
-   Database trigger: add_points (auto calculate on order complete)
-   Points calculation: floor(total_money / 100000)
-   Voucher validation: check eligibility, expiry, usage count
-   Unified system cho online và offline

---

### Epic 7: Order Management (Customer & Staff)

**Goal:** Customers có thể xem orders với timeline visual, cancel pending orders, verify IMEI. Staff có thể approve, ship, complete, cancel orders.

**User Outcomes:**

-   Customers có thể view list of orders với filter by status
-   Customers có thể view order details (products, IMEI, warranty, shipping, price breakdown)
-   Customers có thể view order status timeline (pending → confirmed → shipping → completed)
-   Customers có thể cancel pending orders
-   Customers có thể view IMEI numbers và warranty expiration dates
-   Staff có thể view all orders với filter by status/source
-   Staff có thể approve, ship, complete, cancel orders

**FRs Covered:** FR33-FR39, FR62-FR70 (16 FRs)

**UX/Design Notes:**

-   Order timeline component (vertical, animated progress)
-   IMEI display prominent on order details
-   Warranty expiration date calculated and displayed
-   Order status workflow: pending → confirmed → shipping → completed → cancelled
-   Color-coded status indicators

---

### Epic 8: Point of Sale (POS) System

**Goal:** Sales staff có thể bán hàng tại quầy < 5 phút với customer lookup, IMEI scan, voucher/points apply, và invoice printing.

**User Outcomes:**

-   Sales staff có thể access POS interface (optimized for speed)
-   Sales staff có thể search customers by phone < 3 seconds
-   Sales staff có thể create new customer records quickly
-   Sales staff có thể search products by name/SKU với autocomplete
-   Sales staff có thể add/update/remove products trong POS cart
-   Sales staff có thể apply voucher codes và customer loyalty points
-   Sales staff có thể enter IMEI numbers (scan or manual)
-   Sales staff có thể complete transactions < 5 minutes
-   System tự động: create order, record IMEI, update stock, calculate points
-   Sales staff có thể print invoices với IMEI prominent

**FRs Covered:** FR44-FR61 (18 FRs)

**Technical Notes:**

-   POS interface optimized cho desktop/tablet (1024px+)
-   Customer lookup by phone (instant search)
-   Product search với autocomplete
-   IMEI validation (15 digits)
-   Database triggers: update_stock, add_points (auto-fire on transaction complete)
-   Invoice generation với IMEI large font
-   Transaction time target: < 5 minutes

---

### Epic 9: Inventory Management

**Goal:** Warehouse staff có thể nhập hàng, xem stock movements, nhận alerts. Manager có thể monitor inventory value và dead stock.

**User Outcomes:**

-   Warehouse staff có thể create stock-in transactions với multi-product support
-   Warehouse staff có thể select supplier, enter reference number, add notes
-   System prompt confirmation cho high-value transactions (> 50M VND)
-   System tự động create stock movement records và update quantities via trigger
-   Staff có thể view stock movement history với filters (type, date, product)
-   Staff có thể view low stock alerts (< 5 items)
-   Staff có thể view dead stock alerts (> 30 days without sales)
-   Staff có thể view total inventory value by cost price
-   Dashboard display color-coded stock indicators (red/yellow/green)

**FRs Covered:** FR71-FR87 (17 FRs)

**Technical Notes:**

-   Stock in module với multi-product support
-   High-value confirmation modal (> 50M threshold)
-   Database trigger: update_stock (auto update products.quantity)
-   Stock alerts: low stock (< 5), dead stock (> 30 days)
-   Inventory value calculation: SUM(quantity \* cost)
-   Color-coded indicators: red (< 5), yellow (5-10), green (> 10)

---

### Epic 10: Dashboard, Reports & Customer Management

**Goal:** Manager/Admin có thể xem dashboard với charts, generate reports, và manage customers. Sales có thể view customer details và order history.

**User Outcomes:**

-   Manager/Admin có thể view dashboard với key metrics (revenue, orders, products, customers)
-   Manager/Admin có thể view revenue charts by time period (Chart.js)
-   Manager/Admin có thể view stock alerts và dead stock alerts on dashboard
-   Manager/Admin có thể view inventory value on dashboard
-   Manager/Admin có thể generate reports (revenue, products, inventory, customers)
-   Staff có thể view customer list với search
-   Staff có thể view customer details (order history, loyalty points balance)

**FRs Covered:** FR113-FR121, FR131-FR135 (14 FRs)

**Technical Notes:**

-   Dashboard với Chart.js for analytics
-   Real-time metrics (polling every 30 seconds)
-   Reports: revenue by date range, product performance, inventory turnover, customer analytics
-   Customer management CRUD
-   Customer lookup với order history và points balance

## Epic 1: Project Foundation & Authentication

**Goal:** Hệ thống được setup với authentication hoàn chỉnh. Staff và customers có thể đăng nhập an toàn với role-based access control.

### Story 1.1: Project Setup & Database Schema

As a **Developer**,
I want to initialize the Laravel 12 project with complete database schema,
So that the foundation is ready for all features to be built upon.

**Acceptance Criteria:**

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

---

### Story 1.2: Customer Registration with Email/Password

As a **Customer**,
I want to register an account using my email and password,
So that I can shop online and track my orders.

**Acceptance Criteria:**

**Given** I am on the registration page
**When** I enter valid email, password (min 8 characters), password confirmation, full name, and phone number
**Then** my account is created in the customers table
**And** my password is hashed using bcrypt (min 10 rounds)
**And** I receive a success message "Đăng ký thành công"
**And** I am automatically logged in with the 'customer' guard
**And** I am redirected to the homepage

**Given** I enter an email that already exists
**When** I submit the registration form
**Then** I see an error message "Email đã được sử dụng"
**And** the form retains my input (except password)

**Given** I enter a password shorter than 8 characters
**When** I submit the registration form
**Then** I see an error message "Mật khẩu phải có ít nhất 8 ký tự"

**Given** I enter mismatched passwords
**When** I submit the registration form
**Then** I see an error message "Mật khẩu xác nhận không khớp"

**Technical Details:**

-   Route: POST /register
-   Controller: Auth\RegisterController@store
-   Validation: StoreCustomerRequest (email unique, password min 8, phone format)
-   Guard: 'customer' (session-based)
-   Table: customers (email, password, full_name, phone, points default 0, status default 'active')

---

### Story 1.3: Customer Login with Email/Password

As a **Customer**,
I want to login using my email and password,
So that I can access my account and make purchases.

**Acceptance Criteria:**

**Given** I have a registered account
**When** I enter correct email and password on the login page
**Then** I am logged in with the 'customer' guard
**And** I see a success message "Đăng nhập thành công"
**And** I am redirected to the homepage
**And** my session is created with HTTP-only and secure cookies

**Given** I enter incorrect email or password
**When** I submit the login form
**Then** I see an error message "Email hoặc mật khẩu không đúng"
**And** I remain on the login page
**And** the failed attempt is logged for security monitoring

**Given** I attempt to login 5 times with wrong credentials within 1 minute
**When** I try the 6th attempt
**Then** I see an error message "Quá nhiều lần đăng nhập sai. Vui lòng thử lại sau 1 phút"
**And** my IP is temporarily throttled

**Given** I am logged in
**When** I click the logout button
**Then** my session is destroyed
**And** I am redirected to the homepage
**And** I see a message "Đã đăng xuất thành công"

**Technical Details:**

-   Routes: GET /login, POST /login, POST /logout
-   Controller: Auth\LoginController
-   Guard: 'customer' (session-based)
-   Throttling: 5 attempts per minute per IP (Laravel rate limiting)
-   Security: CSRF protection, bcrypt password verification

---

### Story 1.4: Customer Google OAuth Registration & Login

As a **Customer**,
I want to register and login using my Google account,
So that I can quickly access the system without creating a new password.

**Acceptance Criteria:**

**Given** I am on the registration or login page
**When** I click the "Đăng nhập với Google" button
**Then** I am redirected to Google OAuth consent screen
**And** I can authorize the application to access my Google profile

**Given** I authorize the application and this is my first time
**When** Google redirects me back to the application
**Then** a new customer account is created with my Google email and name
**And** the password field is set to a random secure value
**And** I am logged in with the 'customer' guard
**And** I see a modal prompting "Vui lòng đặt mật khẩu để bảo mật tài khoản"
**And** I am redirected to the set password page

**Given** I have previously registered with Google
**When** Google redirects me back after authorization
**Then** I am logged in to my existing account
**And** I am redirected to the homepage

**Given** I registered with Google but haven't set a password yet
**When** I try to login with email/password
**Then** I see an error message "Tài khoản này đăng ký qua Google. Vui lòng đặt mật khẩu trước khi đăng nhập"
**And** I see a link to the set password page

**Technical Details:**

-   Routes: GET /auth/google, GET /auth/google/callback
-   Controller: Auth\GoogleAuthController
-   Package: Laravel Socialite
-   Guard: 'customer' (session-based)
-   Table: customers (google_id nullable, password nullable initially)
-   Config: GOOGLE_CLIENT_ID, GOOGLE_CLIENT_SECRET in .env

---

### Story 1.5: Customer Profile Management

As a **Customer**,
I want to view and update my profile information,
So that I can keep my account details current and view my loyalty points.

**Acceptance Criteria:**

**Given** I am logged in as a customer
**When** I navigate to the profile page (/profile)
**Then** I see my current information: full name, email, phone, loyalty points balance
**And** I see a form to update my full name and phone number
**And** I see a separate section to change my password

**Given** I update my full name and phone number with valid data
**When** I submit the update form
**Then** my information is updated in the database
**And** I see a success message "Cập nhật thông tin thành công"
**And** the form shows my updated information

**Given** I want to change my password
**When** I enter my current password, new password (min 8 chars), and confirmation
**Then** my password is updated and hashed with bcrypt
**And** I see a success message "Đổi mật khẩu thành công"
**And** I remain logged in (session is not destroyed)

**Given** I enter an incorrect current password
**When** I submit the change password form
**Then** I see an error message "Mật khẩu hiện tại không đúng"
**And** my password is not changed

**Given** I view my profile
**When** I look at the loyalty points section
**Then** I see my current points balance (e.g., "500 điểm = 500.000đ")
**And** I see a brief explanation "Tích 1 điểm cho mỗi 100.000đ mua hàng"

**Technical Details:**

-   Routes: GET /profile, PUT /profile, PUT /profile/password
-   Controller: Customer\ProfileController
-   Validation: UpdateProfileRequest (phone format, password min 8)
-   Guard: 'customer' middleware
-   Display: Points balance from customers.points column

---

### Story 1.6: Staff Authentication & Role Setup

As a **Staff Member**,
I want to login to the admin panel using my email and password,
So that I can access the management features based on my role.

**Acceptance Criteria:**

**Given** an Admin has created a staff account for me
**When** I navigate to /admin/login
**Then** I see a staff login form (separate from customer login)
**And** I can enter my email and password

**Given** I enter correct staff credentials
**When** I submit the login form
**Then** I am logged in with the 'web' guard (staff guard)
**And** I am redirected to /admin/dashboard
**And** I see a welcome message with my name and role

**Given** I enter incorrect credentials
**When** I submit the login form
**Then** I see an error message "Email hoặc mật khẩu không đúng"
**And** the failed attempt is logged

**Given** I am logged in as staff
**When** I click logout
**Then** my session is destroyed
**And** I am redirected to /admin/login

**Given** I try to access /admin/\* routes without being logged in
**When** I navigate to any admin route
**Then** I am redirected to /admin/login
**And** I see a message "Vui lòng đăng nhập để tiếp tục"

**Technical Details:**

-   Routes: GET /admin/login, POST /admin/login, POST /admin/logout
-   Controller: Auth\AdminLoginController
-   Guard: 'web' (default Laravel guard for staff)
-   Middleware: 'auth' for all /admin/\* routes
-   Table: users (email, password, role_id, full_name, status)
-   Separate login UI from customer login (admin theme)

---

### Story 1.7: Role-Based Access Control (RBAC)

As a **System**,
I want to enforce role-based access control,
So that staff members can only access features appropriate for their role.

**Acceptance Criteria:**

**Given** I am logged in as a Sales staff
**When** I try to access allowed routes (POS, orders, products view, customers)
**Then** I can access these features successfully
**And** I see the appropriate navigation menu items

**Given** I am logged in as a Sales staff
**When** I try to access restricted routes (user management, reports, inventory management)
**Then** I see a 403 Forbidden error page
**And** I see a message "Bạn không có quyền truy cập trang này"
**And** the attempt is logged for security audit

**Given** I am logged in as a Warehouse staff
**When** I try to access allowed routes (inventory management, stock movements, products view)
**Then** I can access these features successfully

**Given** I am logged in as a Warehouse staff
**When** I try to access restricted routes (POS, orders, customers, user management)
**Then** I see a 403 Forbidden error page

**Given** I am logged in as a Manager
**When** I try to access any route except user management
**Then** I can access all features successfully

**Given** I am logged in as an Admin
**When** I try to access any route in the system
**Then** I have full access to all features

**Technical Details:**

-   Middleware: CheckRole middleware for route protection
-   Gates: Define in AuthServiceProvider (manage-users, manage-products, manage-orders, manage-inventory, view-reports, access-pos)
-   Policies: UserPolicy, ProductPolicy, OrderPolicy for resource-level authorization
-   Role Permissions:
    -   Admin: All permissions
    -   Manager: All except manage-users
    -   Sales: access-pos, manage-orders, view-products, view-customers
    -   Warehouse: manage-inventory, view-products
-   Navigation: Dynamic menu based on user role and permissions

---

### Story 1.8: User Management (Admin Only)

As an **Admin**,
I want to create, update, and deactivate staff accounts,
So that I can manage who has access to the system and their roles.

**Acceptance Criteria:**

**Given** I am logged in as an Admin
**When** I navigate to /admin/users
**Then** I see a list of all staff users with their name, email, role, and status
**And** I see a "Tạo người dùng mới" button

**Given** I click "Tạo người dùng mới"
**When** I fill in email, full name, password, and select a role (Admin/Manager/Sales/Warehouse)
**Then** a new user account is created in the users table
**And** the password is hashed with bcrypt
**And** I see a success message "Tạo người dùng thành công"
**And** the new user appears in the user list

**Given** I want to update a user's information
**When** I click "Sửa" on a user row and update their name or role
**Then** the user's information is updated
**And** I see a success message "Cập nhật người dùng thành công"

**Given** I want to deactivate a user
**When** I click "Vô hiệu hóa" on a user row
**Then** the user's status is set to 'inactive'
**And** the user can no longer login
**And** I see a success message "Đã vô hiệu hóa người dùng"

**Given** I try to deactivate my own account
**When** I click "Vô hiệu hóa" on my own user row
**Then** I see an error message "Không thể vô hiệu hóa tài khoản của chính mình"
**And** my account remains active

**Given** I am logged in as a Manager, Sales, or Warehouse staff
**When** I try to access /admin/users
**Then** I see a 403 Forbidden error
**And** I cannot manage users

**Technical Details:**

-   Routes: GET /admin/users, GET /admin/users/create, POST /admin/users, GET /admin/users/{id}/edit, PUT /admin/users/{id}, DELETE /admin/users/{id}
-   Controller: Admin\UserController
-   Validation: StoreUserRequest, UpdateUserRequest (email unique, password min 8, role_id exists)
-   Authorization: Gate 'manage-users' (Admin only)
-   Table: users (role_id foreign key to roles table)
-   UI: DaisyUI data table with action buttons

## Epic 2: Master Data Management

**Goal:** Admin/Manager có thể quản lý dữ liệu cơ bản (categories, brands, suppliers) để chuẩn bị cho product management.

### Story 2.1: Category Management

As an **Admin or Manager**,
I want to create, view, update, and delete product categories,
So that products can be organized into logical groups for customers to browse.

**Acceptance Criteria:**

**Given** I am logged in as Admin or Manager
**When** I navigate to /admin/categories
**Then** I see a list of all categories with their name and product count
**And** I see a "Tạo danh mục mới" button

**Given** I click "Tạo danh mục mới"
**When** I enter a category name (e.g., "iPhone", "Samsung", "Xiaomi") and submit
**Then** a new category is created in the categories table
**And** I see a success message "Tạo danh mục thành công"
**And** the new category appears in the list

**Given** I want to update a category name
**When** I click "Sửa" on a category row and change the name
**Then** the category name is updated
**And** I see a success message "Cập nhật danh mục thành công"

**Given** I want to delete a category that has no products
**When** I click "Xóa" on a category row
**Then** I see a confirmation modal "Bạn có chắc muốn xóa danh mục này?"
**And** when I confirm, the category is deleted
**And** I see a success message "Đã xóa danh mục"

**Given** I try to delete a category that has products
**When** I click "Xóa" and confirm
**Then** I see an error message "Không thể xóa danh mục đang có sản phẩm"
**And** the category is not deleted

**Technical Details:**

-   Routes: GET /admin/categories, POST /admin/categories, PUT /admin/categories/{id}, DELETE /admin/categories/{id}
-   Controller: Admin\CategoryController
-   Authorization: Gate 'manage-products' (Admin, Manager)
-   Validation: Name required, unique
-   Table: categories (name unique)

---

### Story 2.2: Brand Management

As an **Admin or Manager**,
I want to create, view, update, and delete product brands,
So that products can be associated with their manufacturers.

**Acceptance Criteria:**

**Given** I am logged in as Admin or Manager
**When** I navigate to /admin/brands
**Then** I see a list of all brands with their name and product count
**And** I see a "Tạo thương hiệu mới" button

**Given** I click "Tạo thương hiệu mới"
**When** I enter a brand name (e.g., "Apple", "Samsung", "Xiaomi") and submit
**Then** a new brand is created in the brands table
**And** I see a success message "Tạo thương hiệu thành công"
**And** the new brand appears in the list

**Given** I want to update a brand name
**When** I click "Sửa" on a brand row and change the name
**Then** the brand name is updated
**And** I see a success message "Cập nhật thương hiệu thành công"

**Given** I want to delete a brand that has no products
**When** I click "Xóa" on a brand row and confirm
**Then** the brand is deleted
**And** I see a success message "Đã xóa thương hiệu"

**Given** I try to delete a brand that has products
**When** I click "Xóa" and confirm
**Then** I see an error message "Không thể xóa thương hiệu đang có sản phẩm"
**And** the brand is not deleted

**Technical Details:**

-   Routes: GET /admin/brands, POST /admin/brands, PUT /admin/brands/{id}, DELETE /admin/brands/{id}
-   Controller: Admin\BrandController
-   Authorization: Gate 'manage-products' (Admin, Manager)
-   Validation: Name required, unique
-   Table: brands (name unique)

---

### Story 2.3: Supplier Management

As an **Admin or Manager**,
I want to create, view, update, and delete suppliers,
So that I can track where products are sourced from for inventory management.

**Acceptance Criteria:**

**Given** I am logged in as Admin or Manager
**When** I navigate to /admin/suppliers
**Then** I see a list of all suppliers with their name, contact info, and address
**And** I see a "Tạo nhà cung cấp mới" button

**Given** I click "Tạo nhà cung cấp mới"
**When** I enter supplier name, contact person, phone, email, and address
**Then** a new supplier is created in the suppliers table
**And** I see a success message "Tạo nhà cung cấp thành công"
**And** the new supplier appears in the list

**Given** I want to update supplier information
**When** I click "Sửa" on a supplier row and modify any field
**Then** the supplier information is updated
**And** I see a success message "Cập nhật nhà cung cấp thành công"

**Given** I want to delete a supplier that has no stock movements
**When** I click "Xóa" on a supplier row and confirm
**Then** the supplier is deleted
**And** I see a success message "Đã xóa nhà cung cấp"

**Given** I try to delete a supplier that has stock movement history
**When** I click "Xóa" and confirm
**Then** I see an error message "Không thể xóa nhà cung cấp đã có lịch sử nhập hàng"
**And** the supplier is not deleted

**Given** I am logged in as Sales or Warehouse staff
**When** I try to access /admin/suppliers
**Then** I can view the supplier list (read-only)
**And** I cannot create, update, or delete suppliers

**Technical Details:**

-   Routes: GET /admin/suppliers, POST /admin/suppliers, PUT /admin/suppliers/{id}, DELETE /admin/suppliers/{id}
-   Controller: Admin\SupplierController
-   Authorization: Gate 'manage-products' for write operations (Admin, Manager)
-   Validation: Name required, phone format, email format
-   Table: suppliers (name, contact_person, phone, email, address)
