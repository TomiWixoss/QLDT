# UX Implementation Priorities - Tact

**Date:** 2025-12-14
**Purpose:** Prioritize core UX features for 8-week timeline

## Core UX (Week 1-6) - MUST HAVE

### Trust Signals (Critical for Phone Retail)

**Priority:** P0 - Implement First

1. **IMEI Badge on Product Cards**

    - Simple badge with "IMEI Tracking" text
    - Green color (#10b981)
    - No animation required (static is fine)
    - Implementation: 2 hours

2. **Warranty Information Display**

    - Clear warranty period on product detail
    - Simple text format: "Bảo hành: 12 tháng chính hãng"
    - No countdown timer needed (defer to polish)
    - Implementation: 1 hour

3. **Trust Section on Product Detail**
    - 3 simple icons: IMEI, Warranty, Delivery
    - Static layout, no animations
    - Clear text descriptions
    - Implementation: 3 hours

### Speed & Performance (Critical for User Experience)

**Priority:** P0 - Non-negotiable

1. **Image Optimization**

    - WebP format for all product images
    - Lazy loading below fold (native loading="lazy")
    - Max 200KB per image
    - Implementation: Ongoing

2. **Fast Page Load**

    - < 2s page load time
    - Minimize CSS/JS bundle size
    - Defer non-critical resources
    - Implementation: Ongoing

3. **Instant Feedback**
    - Loading states for operations > 500ms
    - Simple spinner or skeleton screens
    - No fancy animations needed
    - Implementation: 2 hours per feature

### Clarity & Usability (Critical for Conversion)

**Priority:** P0 - Essential

1. **Clear Navigation**

    - Simple top navbar (desktop)
    - Bottom navigation (mobile) - 4 items max
    - Breadcrumbs on detail pages
    - Implementation: 4 hours

2. **Obvious CTAs**

    - Large "Thêm vào giỏ" button
    - Clear "Đặt hàng" button
    - High contrast colors
    - Implementation: 2 hours

3. **Form Validation**
    - Inline error messages (red text below field)
    - Clear success states (green checkmark)
    - No fancy animations needed
    - Implementation: 3 hours per form

### Mobile-First Basics (Critical for 70% Traffic)

**Priority:** P0 - Must Work on Mobile

1. **Responsive Layout**

    - Single column on mobile
    - Touch targets 44x44px minimum
    - Readable text (16px minimum)
    - Implementation: Ongoing

2. **Bottom Navigation (Mobile)**

    - Fixed bottom bar
    - 4 key actions: Home, Search, Cart, Account
    - Simple icons + labels
    - Implementation: 4 hours

3. **Touch-Friendly Interactions**
    - Large tap areas
    - No hover-only features
    - Swipe for image galleries (simple)
    - Implementation: 3 hours

## Polish UX (Week 7-8) - NICE TO HAVE

### Micro-Interactions (Defer if Time Constrained)

**Priority:** P2 - Add if time permits

1. **Button Hover Effects**

    - Subtle transform: translateY(-2px)
    - Shadow increase on hover
    - 0.2s transition
    - Implementation: 1 hour

2. **Smooth Transitions**

    - Page transitions (fade)
    - Modal animations (slide up)
    - Accordion expand/collapse
    - Implementation: 3 hours

3. **Success Animations**
    - Checkmark animation on order complete
    - Subtle confetti (CSS-only)
    - Toast notifications with slide-in
    - Implementation: 4 hours

### Advanced Visual Polish (Defer if Time Constrained)

**Priority:** P3 - Optional

1. **Generous Whitespace**

    - Nike-inspired spacing
    - Breathing room around elements
    - Implementation: 2 hours

2. **Premium Typography**

    - Inter Variable Font
    - Perfect hierarchy
    - Implementation: 2 hours

3. **Sophisticated Colors**
    - Subtle gradients on CTAs
    - Refined color palette
    - Implementation: 2 hours

### Advanced Interactions (Defer to Post-MVP)

**Priority:** P4 - Future enhancement

1. **Instant Color Preview**

    - Hover to see color variants
    - Implementation: 6 hours

2. **360° Product Views**

    - Swipe to rotate product
    - Implementation: 8 hours

3. **Product Videos**
    - Auto-play on hover
    - Implementation: 4 hours

## Implementation Strategy

### Week 1-2: Foundation + Core UX

-   ✅ Setup project (Story 1.1)
-   ✅ Implement authentication with basic UI
-   ✅ Add trust signals (IMEI badge, warranty info)
-   ✅ Setup image optimization workflow
-   ✅ Implement responsive layout basics

### Week 3-4: Core Features + Core UX

-   ✅ Product management with clear forms
-   ✅ Product discovery with simple filters
-   ✅ Cart & checkout with inline validation
-   ✅ Mobile bottom navigation
-   ✅ Loading states for all operations

### Week 5-6: Advanced Features + Core UX

-   ✅ Order management with simple timeline
-   ✅ POS system with fast interactions
-   ✅ Inventory management with clear alerts
-   ✅ All touch targets 44x44px minimum
-   ✅ Performance optimization

### Week 7-8: Polish (If Time Permits)

-   🎨 Add button hover effects
-   🎨 Add smooth transitions
-   🎨 Add success animations
-   🎨 Refine typography and spacing
-   🎨 Test and fix any UX issues

## Decision Framework

**When deciding whether to implement a UX feature, ask:**

1. **Does it build trust?** (IMEI, warranty) → P0
2. **Does it improve speed?** (lazy loading, optimization) → P0
3. **Does it improve clarity?** (clear CTAs, validation) → P0
4. **Does it work on mobile?** (responsive, touch-friendly) → P0
5. **Is it just polish?** (animations, effects) → P2-P4

**If the answer is "just polish", defer to Week 7-8 or post-MVP.**

## Success Metrics

**Core UX Success (Week 6):**

-   ✅ Page load < 2s
-   ✅ All trust signals visible
-   ✅ All forms have validation
-   ✅ Mobile navigation works
-   ✅ Touch targets 44x44px+

**Polish UX Success (Week 8):**

-   🎨 Hover effects on buttons
-   🎨 Smooth page transitions
-   🎨 Success animations
-   🎨 Premium typography
-   🎨 Generous whitespace

## Conclusion

**Focus on Core UX first.** Trust, speed, and clarity are non-negotiable. Polish is nice to have but not critical for MVP success. If timeline pressure increases, cut polish features without hesitation.

**Remember:** A fast, clear, trustworthy site with basic design beats a slow, confusing, beautiful site every time.
