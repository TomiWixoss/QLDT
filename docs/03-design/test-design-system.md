# System-Level Test Design - Tact

**Generated**: 2025-12-14
**Project**: Tact - Phone Retail O2O Management System
**Phase**: Phase 3 - Solutioning (Implementation Readiness)
**Workflow**: `.bmad/bmm/workflows/testarch/test-design`
**Test Architect**: Murat (Master Test Architect)

---

## Executive Summary

This document provides a comprehensive system-level testability assessment for Tact, a Laravel 12-based phone retail O2O management system. The assessment evaluates architecture testability across three dimensions: **Controllability**, **Observability**, and **Reliability**. Based on this analysis, we identify Architecturally Significant Requirements (ASRs), define test levels strategy, assess NFR testing approaches, and flag testability concerns that must be addressed before implementation.

**Overall Testability Assessment: ⚠️ CONCERNS**

-   **Controllability**: ✅ PASS (9/10) - Excellent API seeding, dependency injection, mockable boundaries
-   **Observability**: ✅ PASS (7/10) - Good logging and inspection, needs APM and health checks
-   **Reliability**: ⚠️ CONCERNS (6/10) - Needs health checks, error tracking, observer pattern

**Critical Blockers (3):**

1. Database triggers tightly coupled (CONCERN-001)
2. No health check endpoint (CONCERN-002)
3. Missing error tracking (CONCERN-003)

**Recommendation**: Address critical concerns before Sprint 0. System is testable but requires infrastructure improvements for production readiness.

---

## Testability Assessment

### 1. Controllability: ✅ PASS (9/10)

**Can we control system state for testing?**

✅ **API seeding available**

-   Laravel factories for 12 models (User, Customer, Product, Order, etc.)
-   Database seeders with realistic data
-   `php artisan migrate:fresh --seed` for quick reset
-   Separate test database via `.env.testing`

✅ **Session management**

-   Session-based cart and filters (easy to mock)
-   Laravel session fake for testing
-   No global state pollution

✅ **Dependency injection**

-   Laravel service container supports mocking
-   Services, Repositories, Policies injectable
-   Easy to swap implementations in tests

✅ **Test database**

-   Separate `.env.testing` configuration
-   Database transactions for test isolation
-   In-memory SQLite option for speed

**Are external dependencies mockable?**

✅ **Google OAuth**: Laravel Socialite has mock support
✅ **Payment gateways**: COD + Bank Transfer (no external API in MVP)
✅ **Email**: Laravel Mail fake for testing
✅ **File storage**: Storage fake for image uploads
✅ **Cache**: Cache fake for testing

**Can we trigger error conditions?**

✅ **Database triggers testable**: `update_stock`, `add_points` can be tested with factories
✅ **Validation errors**: Form Requests easy to test
✅ **Business logic errors**: Services layer isolated, easy to unit test
⚠️ **Network failures**: Need to mock HTTP client for external services (future)

**Controllability Score: 9/10** (Excellent)

**Minor Gap**: Network failure simulation for future external services (Google OAuth, payment gateways)

### 2. Observability: ✅ PASS (7/10)

**Can we inspect system state?**

✅ **Logging**

-   Laravel Log with daily rotation
-   Contextual logging (user, action, timestamp)
-   Log levels (debug, info, warning, error, critical)
-   Laravel Pail for real-time log viewing (development)

✅ **Database inspection**

-   Eloquent models easy to query in tests
-   Database transactions visible in tests
-   Laravel Telescope for query profiling (development)

✅ **Session inspection**

-   Session data accessible in tests
-   Cart and filter state inspectable

✅ **Cache inspection**

-   Cache::get() in tests
-   Cache tags for group inspection

⚠️ **Metrics**: No APM (Application Performance Monitoring) - **recommend adding Server-Timing headers**

**Are test results deterministic?**

✅ **Database transactions**: Tests can wrap in transactions for isolation
✅ **Factories with seeds**: Faker can be seeded for reproducible data
✅ **No race conditions**: Database triggers atomic, Eloquent ORM prevents SQL injection
✅ **Clear success/failure**: Response format consistent `{success, data, message}`

**Can we validate NFRs?**

✅ **Performance metrics**: Laravel Telescope (development), can add Server-Timing headers
⚠️ **Security audit logs**: Need to add audit trail for financial transactions
⚠️ **Reliability metrics**: No health check endpoint `/api/health` - **recommend adding**

**Observability Score: 7/10** (Good, needs APM + health checks)

**Gaps:**

1. No APM or Server-Timing headers for performance profiling
2. No health check endpoint for reliability monitoring
3. No audit trail for financial transactions (orders, payments)

### 3. Reliability: ⚠️ CONCERNS (6/10)

**Are tests isolated?**

✅ **Parallel-safe**: Laravel tests have database transactions
✅ **Stateless**: Session-based, no global state
✅ **Cleanup discipline**: Factories + seeders have rollback
⚠️ **Shared database**: Need to ensure test database isolation (separate `.env.testing`)

**Can we reproduce failures?**

✅ **Deterministic waits**: Laravel HTTP tests synchronous
⚠️ **HAR capture**: No network recording for debugging - **recommend adding for E2E tests**
✅ **Seed data**: Factories can reproduce exact data
⚠️ **Error tracking**: No Sentry/monitoring integration - **CRITICAL GAP**

**Are components loosely coupled?**

✅ **Mockable boundaries**: Services, Repositories, Policies
✅ **Testable boundaries**: Controllers thin, logic in Services
⚠️ **Database triggers**: `update_stock`, `add_points` tightly coupled with DB (hard to mock) - **CRITICAL GAP**
⚠️ **Eloquent Observers**: Not implemented yet - **recommend adding for flexibility**

**Reliability Score: 6/10** (Concerns - needs health checks, error tracking, observer pattern)

**Critical Gaps:**

1. **Database triggers tightly coupled** - Cannot unit test Services in isolation
2. **No health check endpoint** - Cannot validate system reliability
3. **No error tracking** - Production errors not monitored

**High-Priority Gaps:**

1. No HAR capture for E2E debugging
2. No Eloquent Observers (only database triggers)
3. No retry logic for external services

---

## Architecturally Significant Requirements (ASRs)

ASRs are quality requirements that drive architecture decisions and pose testability challenges. Scored using **Probability (1-3) × Impact (1-3) = Risk Score (1-9)**.

### High-Priority ASRs (Score ≥6)

| ASR ID      | Category | Requirement                             | Probability | Impact | Score | Rationale                                                            |
| ----------- | -------- | --------------------------------------- | ----------- | ------ | ----- | -------------------------------------------------------------------- |
| **ASR-002** | PERF     | POS response < 1s                       | 3           | 3      | **9** | Blocks sales flow, revenue-critical, affects 100% POS transactions   |
| **ASR-008** | DATA     | Inventory accuracy 95%+                 | 3           | 3      | **9** | Database triggers critical, affects stock management and revenue     |
| **ASR-001** | PERF     | Page load < 2s (FCP < 1.5s, LCP < 2.5s) | 2           | 3      | **6** | Affects 100% customer users, mobile-first market (65% ROPO behavior) |
| **ASR-007** | SEC      | Role-based access control (4 roles)     | 2           | 3      | **6** | Complex permissions, affects security and data integrity             |
| **ASR-009** | DATA     | Zero data loss for transactions         | 2           | 3      | **6** | Database transactions required, affects financial integrity          |

### Medium-Priority ASRs (Score 3-5)

| ASR ID  | Category | Requirement              | Probability | Impact | Score | Rationale                                          |
| ------- | -------- | ------------------------ | ----------- | ------ | ----- | -------------------------------------------------- |
| ASR-003 | PERF     | Database queries < 100ms | 2           | 2      | **4** | N+1 queries common in Laravel, needs eager loading |
| ASR-010 | PERF     | 50+ concurrent users     | 2           | 2      | **4** | Single-store MVP, moderate load expected           |
| ASR-011 | OPS      | System uptime 99%+       | 2           | 2      | **4** | Academic project, not production-critical yet      |

### Low-Priority ASRs (Score 1-2)

| ASR ID  | Category | Requirement               | Probability | Impact | Score | Rationale                   |
| ------- | -------- | ------------------------- | ----------- | ------ | ----- | --------------------------- |
| ASR-004 | SEC      | CSRF protection enabled   | 1           | 3      | **3** | Laravel default, low risk   |
| ASR-005 | SEC      | Password hashing (bcrypt) | 1           | 3      | **3** | Laravel default, low risk   |
| ASR-006 | SEC      | SQL injection prevention  | 1           | 3      | **3** | Eloquent ORM, low risk      |
| ASR-012 | OPS      | Database backup daily     | 1           | 3      | **3** | Laravel scheduler, low risk |

**Critical ASRs (Score 9):**

-   **ASR-002**: POS response < 1s - **MUST validate with k6 load testing**
-   **ASR-008**: Inventory accuracy 95%+ - **MUST validate database triggers with integration tests**

---

## Test Levels Strategy

Based on architecture (Laravel 12 MPA + MySQL + Vite 7), we recommend a **40% Unit / 30% Integration / 30% E2E** split.

### Rationale

**40% Unit Tests:**

-   Business logic in Services (OrderService, CartService, InventoryService, PointsService, VoucherService)
-   Pure functions (price calculations, discount logic, points calculation)
-   Policies (authorization logic)
-   Validation rules (Form Requests)

**30% Integration Tests:**

-   API endpoints (Laravel Feature tests)
-   Database operations (Eloquent relationships, scopes, accessors)
-   Database triggers (`update_stock`, `add_points`)
-   Service-to-repository interactions
-   Middleware (authentication, authorization)

**30% E2E Tests:**

-   Critical user journeys (checkout, POS, order tracking)
-   Visual regression (product pages, dashboard)
-   Cross-system workflows (online order → POS fulfillment)
-   Mobile responsiveness (375px base breakpoint)

### Test Levels by Component

| Component                                                                                 | Unit          | Integration       | E2E           | Rationale                                              |
| ----------------------------------------------------------------------------------------- | ------------- | ----------------- | ------------- | ------------------------------------------------------ |
| **Services** (OrderService, CartService, InventoryService, PointsService, VoucherService) | ✅ Primary    | ⚠️ Supplement     | ❌ Overkill   | Pure business logic, isolated from framework           |
| **Controllers** (Admin, Customer, Auth)                                                   | ❌ Skip       | ✅ Primary        | ⚠️ Supplement | Thin controllers, test via API (Laravel Feature tests) |
| **Models** (Eloquent: Product, Order, Customer, etc.)                                     | ⚠️ Partial    | ✅ Primary        | ❌ Overkill   | Relationships, scopes, accessors, casts                |
| **Database Triggers** (`update_stock`, `add_points`)                                      | ❌ Can't test | ✅ Primary        | ❌ Overkill   | Database-level logic, test with factories              |
| **Policies** (ProductPolicy, OrderPolicy, CustomerPolicy, UserPolicy)                     | ✅ Primary    | ⚠️ Supplement     | ❌ Overkill   | Gate logic isolated, easy to unit test                 |
| **Form Requests** (StoreProductRequest, CheckoutRequest, etc.)                            | ✅ Primary    | ⚠️ Supplement     | ❌ Overkill   | Validation rules isolated                              |
| **Blade Components** (product-card, order-timeline, data-table)                           | ⚠️ Partial    | ⚠️ Component test | ✅ Primary    | Visual regression critical for customer site           |
| **User Journeys** (Checkout, POS, Order Tracking)                                         | ❌ Can't test | ❌ Can't test     | ✅ Primary    | Multi-page flows, cross-system interactions            |
| **API Endpoints** (AJAX for cart, search, dashboard)                                      | ❌ Can't test | ✅ Primary        | ⚠️ Supplement | Contract validation, response format                   |
| **Middleware** (auth, role, customer.auth)                                                | ⚠️ Partial    | ✅ Primary        | ❌ Overkill   | Request/response manipulation                          |

### Test Environment Requirements

**Local Development:**

-   XAMPP (Apache + MySQL + PHP 8.2)
-   Vite dev server (port 5173)
-   Laravel serve (port 8000)
-   Separate test database (`.env.testing`)

**CI/CD (GitHub Actions):**

-   Ubuntu latest
-   MySQL service container
-   PHP 8.2 with extensions (bcmath, ctype, fileinfo, json, mbstring, openssl, pdo, tokenizer, xml)
-   Node.js 20+ for Vite build
-   Composer 2.x
-   Playwright browsers (chromium, firefox, webkit)

**Staging (Optional):**

-   Not required for academic project
-   Can use same setup as local for demo

**Production-like (Future):**

-   Not needed for MVP
-   Consider for commercialization phase

---

## NFR Testing Approach

### Security Testing

**Approach**: Playwright E2E + Laravel Feature Tests + Static Analysis

**Tools:**

-   **Playwright**: Auth/authz E2E tests (login, RBAC, session management)
-   **Laravel Feature Tests**: CSRF, XSS, SQL injection prevention
-   **PHPStan**: Static analysis for security vulnerabilities
-   **npm audit**: Dependency vulnerability scanning
-   **Composer audit**: PHP dependency vulnerability scanning

**Test Coverage:**

✅ **Authentication**

-   Email/password login (Laravel Breeze)
-   Google OAuth (Laravel Socialite)
-   Session management (web guard for staff, customer guard for customers)
-   Password reset flow
-   Email verification (optional for customers)

✅ **Authorization**

-   4 roles: Admin, Manager, Sales, Warehouse
-   Gates & Policies for resource access
-   Middleware: `auth`, `role:{role_name}`, `customer.auth`
-   RBAC enforcement at route level

✅ **CSRF Protection**

-   Laravel default CSRF middleware
-   Test with Feature tests (POST/PUT/DELETE without token should fail)

✅ **XSS Prevention**

-   Blade escaping automatic
-   Test with Feature tests (inject `<script>` tags, verify escaped)

✅ **SQL Injection Prevention**

-   Eloquent ORM parameterized queries
-   Test with Feature tests (inject SQL, verify blocked)

⚠️ **Secret Handling** (RECOMMEND ADD)

-   Passwords never logged
-   API keys not exposed in errors
-   Test with Feature tests (trigger error, verify no secrets in response)

⚠️ **OWASP Top 10** (RECOMMEND ADD)

-   Security audit checklist
-   Penetration testing (optional for MVP)

**Security NFR Criteria:**

-   ✅ **PASS**: Auth/authz tests green, CSRF/XSS/SQL injection blocked, no critical vulnerabilities
-   ⚠️ **CONCERNS**: Missing secret handling tests, no security audit
-   ❌ **FAIL**: Critical exposure (unauthenticated access, password leak, SQL injection succeeds)

### Performance Testing

**Approach**: k6 Load Testing + Lighthouse + Laravel Telescope

**Tools:**

-   **k6**: Load/stress/spike testing for API endpoints and POS
-   **Lighthouse**: Core Web Vitals (FCP, LCP, CLS) for customer pages
-   **Laravel Telescope**: Query profiling (development)
-   **Server-Timing headers**: APM tracking (RECOMMEND ADD)

**Test Coverage:**

✅ **Page Load Performance**

-   Lighthouse audit for customer pages
-   Target: FCP < 1.5s, LCP < 2.5s, CLS < 0.1
-   Test: Homepage, product list, product detail, checkout

✅ **POS Response Time** (CRITICAL - ASR-002)

-   k6 load test for POS endpoints
-   Target: < 1s response time
-   Test: Customer lookup, product search, cart operations, checkout

✅ **Database Query Performance**

-   Laravel Telescope query profiling
-   Target: < 100ms average
-   Test: N+1 query detection, eager loading validation

⚠️ **Concurrent Users** (RECOMMEND ADD)

-   k6 load test with 50+ virtual users
-   Target: No performance degradation
-   Test: Sustained load for 5 minutes

⚠️ **API Endpoints** (RECOMMEND ADD)

-   k6 stress test to find breaking point
-   Target: Identify resource limits
-   Test: Ramp up to 100+ users, measure p95/p99 latency

**k6 Test Scenarios:**

1. **Load Test**: 50 VUs for 5 minutes (expected load)
2. **Stress Test**: Ramp up to 100 VUs, find breaking point
3. **Spike Test**: Sudden spike from 10 to 100 VUs
4. **Endurance Test**: 50 VUs for 30 minutes (memory leak detection)

**Performance NFR Criteria:**

-   ✅ **PASS**: Lighthouse score 90+, k6 p95 < 500ms, POS < 1s, no errors
-   ⚠️ **CONCERNS**: Trending toward limits (p95 = 480ms approaching 500ms) or missing baselines
-   ❌ **FAIL**: SLO/SLA breached (p95 > 500ms, POS > 1s, error rate > 1%)

### Reliability Testing

**Approach**: Playwright E2E + Laravel Feature Tests + Health Checks

**Tools:**

-   **Playwright**: Error handling E2E (500 errors, network failures, offline mode)
-   **Laravel Feature Tests**: Database transactions, trigger validation
-   **PHPUnit**: Unit tests for retry logic, circuit breakers (future)
-   **Health Check Endpoint**: `/api/health` (RECOMMEND ADD)

**Test Coverage:**

✅ **Error Handling**

-   Graceful degradation (500 → user-friendly message + retry button)
-   Test with Playwright: Mock API failure, verify error UI
-   Test with Feature tests: Trigger exception, verify response format

⚠️ **Retries** (RECOMMEND ADD)

-   Retry logic for external services (Google OAuth, future payment gateways)
-   Test with Playwright: Mock transient failure, verify 3 retries
-   Exponential backoff strategy

⚠️ **Health Checks** (CRITICAL GAP - RECOMMEND ADD)

-   `/api/health` endpoint monitoring database, cache, queue
-   Test with Feature tests: Verify health check returns 200 with service status
-   Response format: `{status: 'healthy', services: {database: 'UP', cache: 'UP', queue: 'UP'}}`

⚠️ **Circuit Breaker** (RECOMMEND ADD FOR FUTURE)

-   Circuit breaker for external services
-   Test with Playwright: Mock 5 consecutive failures, verify circuit opens
-   Fallback UI when circuit open

✅ **Database Transactions**

-   Laravel transactions for critical operations (order creation, stock updates)
-   Test with Feature tests: Trigger exception mid-transaction, verify rollback

✅ **Trigger Validation** (CRITICAL - ASR-008)

-   Test `update_stock` trigger: Create stock movement, verify product quantity updated
-   Test `add_points` trigger: Complete order, verify customer points updated
-   Test with Integration tests using factories

**Reliability NFR Criteria:**

-   ✅ **PASS**: Error handling graceful, transactions validated, triggers tested, health checks monitored
-   ⚠️ **CONCERNS**: Missing health checks, no retry logic, no circuit breaker
-   ❌ **FAIL**: No recovery path (500 error crashes app), unresolved crash scenarios

### Maintainability Testing

**Approach**: CI Tools (GitHub Actions) + Playwright Observability

**Tools:**

-   **GitHub Actions**: Coverage (PHPUnit), code quality (Laravel Pint, PHPStan)
-   **Playwright**: Observability validation (error tracking, telemetry headers)
-   **Composer audit**: Dependency vulnerability scanning
-   **npm audit**: Frontend dependency vulnerability scanning
-   **PHPCPD**: PHP Copy/Paste Detector (RECOMMEND ADD)

**Test Coverage:**

✅ **Test Coverage**

-   PHPUnit coverage report (target: 80%+)
-   CI job: Run tests with `--coverage-html` flag
-   Fail build if coverage < 80%

✅ **Code Quality**

-   Laravel Pint (PSR-12 formatting)
-   PHPStan (static analysis, level 5+)
-   CI job: Run Pint and PHPStan, fail on errors

⚠️ **Code Duplication** (RECOMMEND ADD)

-   PHPCPD (PHP Copy/Paste Detector)
-   Target: < 5% duplication
-   CI job: Run PHPCPD, fail if duplication > 5%

⚠️ **Vulnerability Scan** (RECOMMEND ADD)

-   Composer audit for PHP dependencies
-   npm audit for Node.js dependencies
-   CI job: Run audits, fail on critical/high vulnerabilities

⚠️ **Error Tracking** (CRITICAL GAP - RECOMMEND ADD)

-   Sentry integration for production error monitoring
-   Test with Playwright: Trigger error, verify Sentry capture
-   Validate error tracking contract (trace IDs, stack traces)

⚠️ **Structured Logging** (RECOMMEND ADD)

-   Trace IDs in logs for correlation
-   Server-Timing headers for APM
-   Test with Playwright: Validate telemetry headers in responses

**Maintainability NFR Criteria:**

-   ✅ **PASS**: Coverage 80%+, code quality enforced, no critical vulnerabilities, observability validated
-   ⚠️ **CONCERNS**: Duplication >5%, coverage 60-79%, no observability
-   ❌ **FAIL**: Coverage <60%, tangled code (>10% duplication), no error tracking

---

## Testability Concerns

### 🚨 Critical Concerns (BLOCKERS)

#### CONCERN-001: Database Triggers Tightly Coupled

**Category**: TECH (Technical/Architecture)
**Severity**: HIGH
**Risk Score**: 6 (Probability: 2, Impact: 3)

**Issue:**

-   `update_stock` and `add_points` triggers are in database, hard to mock in unit tests
-   Cannot test Services in isolation without database
-   Forces integration-level testing for business logic

**Impact:**

-   Slower test execution (database required)
-   Harder to test edge cases (trigger logic opaque)
-   Tight coupling between application and database

**Recommendation:**

-   Implement Eloquent Observers alongside triggers for flexibility
-   Observers: `ProductObserver::updated()`, `OrderObserver::completed()`
-   Keep triggers for production reliability, use observers for testing

**Owner**: Backend team
**Deadline**: Before Sprint 0
**Mitigation Plan**:

1. Create `app/Observers/ProductObserver.php` with `updated()` method
2. Create `app/Observers/OrderObserver.php` with `completed()` method
3. Register observers in `AppServiceProvider`
4. Add unit tests for observers (mock database)
5. Keep triggers as fallback for production

---

#### CONCERN-002: No Health Check Endpoint

**Category**: OPS (Operations)
**Severity**: HIGH
**Risk Score**: 6 (Probability: 2, Impact: 3)

**Issue:**

-   No `/api/health` endpoint to monitor system status
-   Cannot validate reliability NFR (database, cache, queue status)
-   No automated health monitoring for production

**Impact:**

-   Cannot detect system degradation early
-   No automated alerting for service failures
-   Harder to debug production issues

**Recommendation:**

-   Add `/api/health` endpoint with database/cache/queue checks
-   Response format: `{status: 'healthy', timestamp: '...', services: {database: 'UP', cache: 'UP', queue: 'UP'}}`
-   Return 200 if healthy, 503 if unhealthy

**Owner**: Backend team
**Deadline**: Before Sprint 0
**Mitigation Plan**:

1. Create `app/Http/Controllers/HealthController.php`
2. Add route `GET /api/health`
3. Check database connection (`DB::connection()->getPdo()`)
4. Check cache connection (`Cache::get('health_check')`)
5. Check queue connection (optional for MVP)
6. Add Feature test for health endpoint

---

#### CONCERN-003: Missing Error Tracking

**Category**: OPS (Operations)
**Severity**: CRITICAL
**Risk Score**: 9 (Probability: 3, Impact: 3)

**Issue:**

-   No Sentry or monitoring integration
-   Production errors not tracked or alerted
-   Hard to debug production issues without error context

**Impact:**

-   Cannot detect production errors early
-   No stack traces or context for debugging
-   User-reported bugs hard to reproduce

**Recommendation:**

-   Add Sentry integration with error capture
-   Capture exceptions with context (user, request, stack trace)
-   Set up alerts for critical errors

**Owner**: DevOps team
**Deadline**: Before production deployment
**Mitigation Plan**:

1. Install `sentry/sentry-laravel` package
2. Configure `SENTRY_LARAVEL_DSN` in `.env`
3. Add Sentry to `app/Exceptions/Handler.php`
4. Test error capture with Playwright (trigger error, verify Sentry event)
5. Set up Slack/email alerts for critical errors

### ⚠️ High Concerns (SHOULD FIX)

#### CONCERN-004: No Retry Logic for External Services

**Category**: TECH (Technical/Architecture)
**Severity**: MEDIUM
**Risk Score**: 4 (Probability: 2, Impact: 2)

**Issue:**

-   No retry logic for Google OAuth, future payment gateways
-   Transient failures not handled (503, network timeouts)
-   Single failure causes user-facing error

**Impact:**

-   Poor user experience during transient failures
-   Higher support burden (users report "login broken")
-   Missed revenue opportunities (payment failures)

**Recommendation:**

-   Implement retry logic with exponential backoff
-   Use Laravel HTTP client retry feature: `Http::retry(3, 100)->get()`
-   Test with Playwright: Mock transient failure, verify 3 retries

**Owner**: Backend team
**Deadline**: Before adding external services
**Mitigation Plan**:

1. Wrap Google OAuth calls in retry logic (3 attempts, 100ms delay)
2. Add exponential backoff (100ms, 200ms, 400ms)
3. Log retry attempts for debugging
4. Add Feature test for retry logic

---

#### CONCERN-005: Missing APM (Application Performance Monitoring)

**Category**: OPS (Operations)
**Severity**: MEDIUM
**Risk Score**: 4 (Probability: 2, Impact: 2)

**Issue:**

-   No Server-Timing headers or APM tool
-   Hard to profile performance bottlenecks
-   Cannot validate performance NFRs with evidence

**Impact:**

-   Slower debugging of performance issues
-   Cannot identify slow queries or endpoints
-   No baseline for performance regression detection

**Recommendation:**

-   Add Server-Timing headers for API responses
-   Header format: `Server-Timing: db;dur=45, total;dur=123`
-   Test with Playwright: Validate telemetry headers in responses

**Owner**: Backend team
**Deadline**: Before performance testing
**Mitigation Plan**:

1. Create middleware `app/Http/Middleware/ServerTiming.php`
2. Track database query time (Laravel Telescope integration)
3. Track total processing time
4. Add Server-Timing header to response
5. Add Playwright test to validate headers

---

#### CONCERN-006: No Code Duplication Tracking

**Category**: OPS (Operations)
**Severity**: LOW
**Risk Score**: 3 (Probability: 1, Impact: 3)

**Issue:**

-   No tool to track code duplication
-   Maintainability degradation not detected
-   Technical debt accumulates silently

**Impact:**

-   Harder to maintain codebase over time
-   Increased bug risk (fix in one place, miss duplicates)
-   Slower feature development

**Recommendation:**

-   Add PHP Copy/Paste Detector (PHPCPD) to CI
-   Target: < 5% duplication
-   Fail build if duplication > 5%

**Owner**: DevOps team
**Deadline**: Before Sprint 1
**Mitigation Plan**:

1. Install PHPCPD: `composer require --dev sebastian/phpcpd`
2. Add CI job: `vendor/bin/phpcpd app/ --min-lines=5 --min-tokens=50`
3. Parse output, fail if duplication > 5%
4. Add to GitHub Actions workflow

---

## Recommendations for Sprint 0

Based on testability assessment and ASR analysis, here are prioritized recommendations for Sprint 0 (test framework setup):

### Phase 1: Critical Infrastructure (Week 1)

**Priority: MUST HAVE**

1. **Implement Eloquent Observers** (CONCERN-001)

    - Create `ProductObserver`, `OrderObserver`
    - Register in `AppServiceProvider`
    - Add unit tests for observers
    - Keep database triggers as fallback

2. **Add Health Check Endpoint** (CONCERN-002)

    - Create `/api/health` endpoint
    - Check database, cache, queue status
    - Add Feature test
    - Document response format

3. **Setup Sentry Error Tracking** (CONCERN-003)

    - Install `sentry/sentry-laravel`
    - Configure DSN in `.env`
    - Add to exception handler
    - Test error capture with Playwright

4. **Setup Test Database** (Reliability)
    - Create `.env.testing` with separate database
    - Configure PHPUnit with test database
    - Add database transactions for test isolation

### Phase 2: Testing Tools (Week 1-2)

**Priority: SHOULD HAVE**

5. **Setup Playwright for E2E Tests**

    - Install Playwright: `npm install -D @playwright/test`
    - Configure `playwright.config.ts`
    - Add fixtures for auth, database seeding
    - Add first smoke test (homepage loads)

6. **Setup k6 for Performance Tests** (ASR-002)

    - Install k6: `brew install k6` or download binary
    - Create `tests/performance/pos.k6.js`
    - Add load test for POS endpoints (target: < 1s)
    - Add to CI pipeline

7. **Setup Laravel Telescope** (Development)

    - Install Telescope: `composer require laravel/telescope --dev`
    - Publish assets: `php artisan telescope:install`
    - Configure for development only
    - Use for query profiling

8. **Setup Code Quality Tools**
    - Laravel Pint: `composer require laravel/pint --dev`
    - PHPStan: `composer require phpstan/phpstan --dev`
    - Add to CI pipeline
    - Configure pre-commit hooks

### Phase 3: NFR Testing (Week 2)

**Priority: NICE TO HAVE**

9. **Add Server-Timing Headers** (CONCERN-005)

    - Create `ServerTiming` middleware
    - Track database query time
    - Track total processing time
    - Add Playwright test to validate

10. **Add Retry Logic** (CONCERN-004)

    - Wrap Google OAuth in retry logic
    - Add exponential backoff
    - Add Feature test for retries

11. **Add Code Duplication Tracking** (CONCERN-006)

    - Install PHPCPD
    - Add to CI pipeline
    - Set threshold: < 5% duplication

12. **Setup Lighthouse CI**
    - Install Lighthouse CI: `npm install -D @lhci/cli`
    - Configure `lighthouserc.json`
    - Add to CI pipeline
    - Target: Lighthouse score 90+

### Phase 4: Documentation (Week 2)

**Priority: MUST HAVE**

13. **Document Test Strategy**

    -   Update `README.md` with test commands
    -   Document test levels (unit, integration, E2E)
    -   Document NFR testing approach
    -   Add examples for each test level

14. **Create Test Templates**
    -   Unit test template (Services, Policies)
    -   Integration test template (Controllers, Models)
    -   E2E test template (User journeys)
    -   Document naming conventions

---

## Gate Decision Criteria

Before proceeding to implementation (Sprint 1+), the following criteria must be met:

### ✅ PASS Criteria

**Testability:**

-   [ ] Controllability: API seeding, dependency injection, mockable boundaries
-   [ ] Observability: Logging, database inspection, health checks
-   [ ] Reliability: Test isolation, reproducible failures, loosely coupled components

**Infrastructure:**

-   [ ] Test database configured (`.env.testing`)
-   [ ] PHPUnit configured with database transactions
-   [ ] Playwright installed and configured
-   [ ] k6 installed for performance testing
-   [ ] Sentry configured for error tracking

**Critical Concerns Resolved:**

-   [ ] CONCERN-001: Eloquent Observers implemented
-   [ ] CONCERN-002: Health check endpoint added
-   [ ] CONCERN-003: Sentry error tracking configured

**Documentation:**

-   [ ] Test strategy documented in `README.md`
-   [ ] Test templates created
-   [ ] NFR testing approach documented

### ⚠️ CONCERNS Criteria

**Acceptable with Mitigation Plan:**

-   [ ] High concerns (CONCERN-004, CONCERN-005, CONCERN-006) have owners and deadlines
-   [ ] Mitigation plans documented
-   [ ] Risks accepted by stakeholders

### ❌ FAIL Criteria

**Blockers:**

-   Critical concerns (CONCERN-001, CONCERN-002, CONCERN-003) not resolved
-   No test database configured
-   No testing tools installed
-   No documentation

---

## Summary

**Overall Assessment: ⚠️ CONCERNS**

Tact architecture is **testable** with excellent controllability (9/10) and good observability (7/10), but has reliability concerns (6/10) that must be addressed before implementation.

**Critical Blockers (3):**

1. Database triggers tightly coupled (CONCERN-001) - **Implement Eloquent Observers**
2. No health check endpoint (CONCERN-002) - **Add `/api/health`**
3. Missing error tracking (CONCERN-003) - **Setup Sentry**

**High-Priority ASRs (2):**

1. POS response < 1s (ASR-002, Score 9) - **Validate with k6 load testing**
2. Inventory accuracy 95%+ (ASR-008, Score 9) - **Validate database triggers with integration tests**

**Recommendations:**

-   Address critical concerns in Sprint 0 (Week 1)
-   Setup testing tools in Sprint 0 (Week 1-2)
-   Validate NFRs with automated tests before release
-   Document test strategy and templates

**Next Steps:**

1. Review this document with team
2. Prioritize Sprint 0 tasks (critical concerns first)
3. Run `*framework` workflow to scaffold test infrastructure
4. Run `*ci` workflow to setup CI/CD pipeline
5. Begin implementation with test-first approach (ATDD)

---

**Document Status**: ✅ COMPLETE
**Generated By**: Murat (Master Test Architect)
**Date**: 2025-12-14
**Next Review**: Before Sprint 1 (after Sprint 0 completion)
