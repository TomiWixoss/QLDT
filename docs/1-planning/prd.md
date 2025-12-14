---
stepsCompleted: [1, 2, 3, 4, 5, 6, 7, 8, 9, 10, 11]
inputDocuments:
    - "docs/0-discovery/product-brief-Tact-2025-12-14.md"
    - "docs/0-discovery/brainstorming-session-2025-12-14.md"
    - "docs/0-discovery/research/inventory-management-phone-retail-2025-12-14.md"
    - "docs/0-discovery/research/market-vietnam-mobile-phone-retail-2025-12-14.md"
    - "docs/0-discovery/research/o2o-model-retail-2025-12-14.md"
    - "docs/0-discovery/research/pos-system-phone-retail-2025-12-14.md"
documentCounts:
    briefs: 1
    research: 4
    brainstorming: 1
    projectDocs: 0
workflowType: "prd"
lastStep: 11
project_name: "Tact"
user_name: "TomiSakae"
date: "2025-12-14"
completed: true
completedDate: "2025-12-14"
---

# Product Requirements Document - Tact

**Author:** TomiSakae
**Date:** 2025-12-14

## Executive Summary

**Tact** là website quản lý cửa hàng điện thoại với mô hình O2O (Online-to-Offline), được xây dựng trên Laravel 12, Tailwind CSS 4, và DaisyUI 5. Hệ thống tích hợp liền mạch giữa bán hàng online và offline, cung cấp công cụ quản lý toàn diện cho cửa hàng điện thoại bán lẻ tại Việt Nam.

### Vấn đề giải quyết

Các cửa hàng điện thoại tại Việt Nam đang đối mặt với:

-   **Trải nghiệm O2O không liền mạch**: 65% khách hàng có hành vi ROPO (Research Online, Purchase Offline) nhưng thông tin giá và tồn kho không đồng bộ
-   **Quản lý tồn kho phức tạp**: Điện thoại là high-value items ($200-$2,000) cần tracking chặt chẽ, IMEI-level tracking, và cảnh báo dead stock
-   **Quy trình bán hàng chậm**: Thiếu công cụ POS tối ưu, không có hệ thống voucher/điểm tích lũy thống nhất
-   **Thiếu minh bạch**: 85% khách hàng lo ngại hàng giả, cần tracking IMEI và thông tin bảo hành rõ ràng

### Giải pháp

Tact cung cấp:

-   **O2O Integration**: Khách đặt online hoặc nhân viên bán tại quầy (POS), tất cả thống nhất trong một hệ thống
-   **IMEI Tracking**: Lưu IMEI trong order_items để track từng máy cụ thể
-   **Voucher & Loyalty Points**: Hệ thống giảm giá và tích điểm tự động, sử dụng được cả online và offline
-   **Smart Inventory**: Cảnh báo tồn kho thấp, sản phẩm chậm bán, giá trị tồn kho, triggers tự động
-   **Timeline & Cancel**: Khách hàng xem timeline đơn hàng trực quan và tự hủy đơn pending
-   **Warranty Info**: Hiển thị thông tin bảo hành, IMEI, ngày hết hạn trong đơn hàng

### Người dùng mục tiêu

1. **Khách hàng**: Xem sản phẩm, đặt hàng, dùng voucher/điểm, theo dõi đơn hàng với timeline
2. **Sales**: Bán hàng tại quầy qua POS interface, áp dụng voucher/điểm
3. **Warehouse**: Nhập hàng, xem cảnh báo tồn kho
4. **Manager**: Quản lý toàn bộ, xem báo cáo
5. **Admin**: Quản trị hệ thống, phân quyền

### What Makes This Special

-   **O2O-First Design**: Được thiết kế từ đầu cho O2O model, không phải POS thêm e-commerce hay ngược lại. Hệ thống hiểu rõ hành vi khách hàng Việt Nam với 65% ROPO behavior.

-   **Phone Retail Specialized**: IMEI tracking built-in, warranty information display, inventory alerts đặc thù cho high-value items. Không phải generic e-commerce được customize.

-   **Modern Tech Stack với Academic Timeline**: Laravel 12 + Tailwind CSS 4 + DaisyUI 5 tạo ra UI/UX hiện đại, responsive. Dù là đồ án 8 buổi nhưng có architecture rõ ràng, có thể scale và commercialize.

-   **Vietnamese Market Focus**: Google OAuth integration, timeline UX trực quan, loyalty points system phù hợp với thị trường Việt Nam. Hiểu rõ pain points của cả khách hàng và chủ cửa hàng.

-   **Safety Features for High-Value Items**: Cảnh báo tồn kho, dead stock alerts, inventory value tracking, transaction confirmations - thể hiện hiểu biết về đặc thù ngành bán lẻ điện thoại.

## Project Classification

**Technical Type:** Web Application

**Domain:** Retail / E-commerce (Phone Retail)

**Complexity:** Medium

**Project Context:** Greenfield - New project

### Classification Rationale

Dự án được phân loại dựa trên các signals:

-   **Web Application**: Laravel-based web application với responsive design, không phải mobile app hay desktop application
-   **Retail/E-commerce Domain**: Bán lẻ điện thoại với đặc thù O2O model, inventory management, POS system
-   **Medium Complexity**:
    -   12 bảng database với relationships phức tạp
    -   4 roles với phân quyền chi tiết
    -   O2O integration (online + offline channels)
    -   IMEI tracking cho high-value items
    -   Voucher + Loyalty points system
    -   POS interface với real-time features
    -   Timeline 8 buổi học nhưng scope đầy đủ

### Domain-Specific Considerations

**Phone Retail Characteristics:**

-   High-value items ($200-$2,000) require careful inventory tracking
-   Fast product lifecycle (6-12 months) demands dead stock management
-   IMEI-level tracking is industry standard
-   Customer trust is critical (85% worry about counterfeit products)

**Vietnamese Market Insights:**

-   65% ROPO behavior (Research Online, Purchase Offline)
-   Google OAuth preferred for quick registration
-   Visual timeline for order tracking improves trust
-   Loyalty points system drives retention

## Success Criteria

### User Success

**Khách hàng (Customer):**

-   Checkout online hoàn thành trong < 5 phút
-   Tìm thấy sản phẩm cần mua trong < 2 phút
-   Nhận được thông tin IMEI và bảo hành rõ ràng trên hóa đơn
-   Sử dụng voucher/điểm thành công không lỗi
-   Xem được timeline đơn hàng trực quan
-   Tự hủy đơn pending được ngay lập tức
-   CSAT: 4.5/5 stars
-   NPS: > 50

**Nhân viên Sales:**

-   Hoàn thành giao dịch POS trong < 5 phút (vs 10-15 phút hiện tại)
-   Tìm sản phẩm/khách hàng trong < 10 giây
-   Áp dụng voucher/điểm không lỗi, tự động tính toán
-   Không cần ghi tay IMEI (scan/nhập tự động)
-   100% giao dịch có IMEI được record

**Nhân viên Warehouse:**

-   Nhập hàng hoàn thành trong < 10 phút
-   Nhận cảnh báo tồn kho thấp tự động
-   Inventory accuracy: 95%+ (vs 80-85% hiện tại)
-   Không cần kiểm kho thủ công hàng ngày

**Manager:**

-   Xem báo cáo real-time không cần chờ
-   Phát hiện sản phẩm chậm bán trong < 1 phút
-   Quyết định nhập hàng dựa trên data rõ ràng

### Business Success

**3 tháng đầu (MVP Launch):**

-   System uptime: 99%+
-   100% staff trained và sử dụng thành thạo
-   Inventory accuracy: 95%+
-   Zero critical bugs blocking operations
-   50+ đơn hàng/ngày được xử lý thành công

**6 tháng (Optimization):**

-   BOPIS adoption: 20% online orders
-   Repeat customer rate: 30%
-   Shrinkage rate: < 1% (vs 2-3% industry)
-   Stockout rate: < 5% cho flagship phones
-   Dead stock: < 5% inventory value
-   Customer satisfaction: 4.5/5 stars

**12 tháng (Growth):**

-   Revenue growth: 20% YoY
-   Inventory turnover: 8-10x/năm
-   Days Sales of Inventory: 40-50 ngày
-   Loyalty program enrollment: 60% customers
-   Operating cost reduction: 15% through automation

### Technical Success

**Performance:**

-   Page load time: < 2 giây
-   POS response time: < 1 giây
-   Database query time: < 100ms
-   Concurrent users: 50+ without degradation

**Reliability:**

-   System uptime: 99%+
-   Data backup: Daily automated
-   Recovery time: < 5 phút if system down
-   Zero data loss incidents

**Security:**

-   100% transactions encrypted
-   Password hashing (bcrypt)
-   SQL injection prevention (Eloquent ORM)
-   XSS prevention (Blade escaping)
-   CSRF protection enabled

**Code Quality:**

-   Laravel best practices followed
-   Responsive design (mobile, tablet, desktop)
-   Browser compatibility: Chrome, Firefox, Safari, Edge
-   Accessibility: Basic WCAG compliance

### Measurable Outcomes

**Week 1-2 (Setup):**

-   ✅ Database migrated successfully
-   ✅ All 12 tables with relationships working
-   ✅ Triggers functioning correctly
-   ✅ Authentication working (email + Google OAuth)

**Week 3-4 (Core Features):**

-   ✅ CRUD operations for all 12 modules
-   ✅ Products with images uploaded
-   ✅ Orders created and processed
-   ✅ Stock movements recorded

**Week 5-6 (Advanced Features):**

-   ✅ POS interface functional
-   ✅ Voucher system working
-   ✅ Loyalty points calculating correctly
-   ✅ IMEI tracking implemented

**Week 7-8 (Polish & Demo):**

-   ✅ Dashboard with charts
-   ✅ Reports generated
-   ✅ Timeline UI implemented
-   ✅ All features tested and working
-   ✅ Demo data populated
-   ✅ Presentation ready

## Product Scope

### MVP - Minimum Viable Product (8 buổi)

**Must-Have Features:**

**Front-end (Khách hàng):**

-   Trang chủ với banner, sản phẩm nổi bật
-   Danh sách sản phẩm (filter, sort, pagination)
-   Chi tiết sản phẩm (specs, warranty, IMEI info)
-   Giỏ hàng (add, remove, update quantity)
-   Checkout (voucher, points, payment method)
-   Lịch sử đơn hàng (timeline, cancel, detail)
-   Tài khoản (profile, points, password)
-   Auth (register, login, Google OAuth)

**Back-end (Quản lý):**

-   Dashboard (stats, charts, alerts)
-   CRUD 12 modules (roles, users, customers, categories, brands, suppliers, products, product_specs, stock_movements, promotions, orders, order_items)
-   POS Interface (bán hàng tại quầy)
-   Quản lý đơn hàng (approve, ship, complete, cancel)
-   Quản lý kho (stock in, history, alerts)
-   Voucher management
-   Loyalty points (auto calculate)
-   Reports (revenue, products, inventory)
-   4 roles authorization

**Technical:**

-   Laravel 12 + Tailwind CSS 4 + DaisyUI 5
-   MySQL database (12 tables)
-   2 triggers (stock update, points)
-   Google OAuth (Socialite)
-   Responsive design
-   IMEI tracking (order_items.imei_list)

**Safety Features:**

-   Stock alerts (< 5 items)
-   Dead stock alerts (> 30 days)
-   Inventory value display
-   High-value transaction confirmation

### Growth Features (Post-MVP)

**Nếu còn thời gian hoặc sau đồ án:**

**Enhanced Reporting:**

-   Export Excel/PDF
-   Advanced analytics
-   Predictive forecasting
-   Custom date ranges

**Notifications:**

-   Email notifications (order status)
-   SMS notifications
-   Push notifications
-   Low stock email alerts

**Advanced Inventory:**

-   Multi-location inventory
-   Stock transfers between stores
-   Cycle counting tools
-   Barcode printing

**Customer Features:**

-   Product reviews & ratings
-   Wishlist
-   Product comparison
-   Live chat support

**Warranty Management:**

-   Warranty claim module
-   Repair tracking
-   Warranty expiry alerts

### Vision (Future)

**Nếu commercialize sau này:**

**Mobile App:**

-   Native iOS/Android app
-   Mobile POS for sales staff
-   Customer mobile app

**Advanced O2O:**

-   Reserve online, try in-store
-   Real-time inventory sync
-   Curbside pickup
-   In-store navigation (AR)

**AI/ML Features:**

-   Demand forecasting
-   Dynamic pricing
-   Personalized recommendations
-   Customer segmentation

**Integrations:**

-   Payment gateways (VNPay, MoMo, ZaloPay)
-   Shipping providers (GHN, GHTK)
-   Accounting software (QuickBooks, Xero)
-   Marketing automation

**Trade-in & Repair:**

-   Device valuation tool
-   IMEI blacklist check
-   Repair services tracking
-   Spare parts inventory

**Multi-Store:**

-   Franchise management
-   Centralized reporting
-   Stock transfers
-   Performance comparison

## User Journeys

### Journey 1: Minh - Khách hàng Gen Z mua iPhone đầu tiên

Minh, sinh viên năm 3, vừa nhận được tiền thưởng từ internship và quyết định mua chiếc iPhone 15 đầu tiên. Anh lo lắng về hàng giả (85% khách Việt Nam có nỗi lo này) và muốn chắc chắn mình mua đúng máy chính hãng với giá tốt. Tối thứ 6, sau giờ học, Minh lên Google tìm "mua iPhone 15 Hà Nội" và tìm thấy website Tact.

Trên Tact, Minh thấy iPhone 15 Pro với thông tin chi tiết: giá 25.000.000đ, còn 8 máy, bảo hành 12 tháng chính hãng. Anh dùng filter để so sánh với iPhone 15 thường, đọc specs đầy đủ. Điều khiến Minh yên tâm là hệ thống hiển thị rõ "Mỗi máy có IMEI riêng, được ghi trên hóa đơn" và "Đổi trả trong 7 ngày nếu phát hiện vấn đề". Anh thêm vào giỏ hàng, nhập mã voucher GIAM100K (giảm 100K), và sử dụng 50 điểm tích lũy từ lần mua ốp lưng trước đó (= -50.000đ).

Breakthrough moment: Sau khi đặt hàng, Minh nhận được email với **timeline trực quan** - thấy đơn hàng đang ở trạng thái "Đã xác nhận". Khi nhận máy, nhân viên mở hộp trước mặt Minh, check IMEI trên hộp khớp với máy. Hóa đơn VAT ghi rõ IMEI: 123456789012345, bảo hành đến 14/12/2026. Minh về nhà vào checkcoverage.apple.com nhập IMEI, thấy hiện "iPhone 15 Pro 128GB, bảo hành hợp lệ" - khớp với hóa đơn. Anh cảm thấy tin tưởng hoàn toàn và giới thiệu Tact cho 3 bạn cùng lớp. Hai tháng sau, Minh quay lại mua AirPods và tích thêm 250 điểm.

### Journey 2: Lan - Nhân viên Sales bán hàng tại quầy trong giờ cao điểm

Chị Lan, 28 tuổi, làm sales tại cửa hàng Tact chi nhánh Cầu Giấy. Sáng thứ 7, cửa hàng đông khách, chị vừa xử lý xong 1 khách mua Galaxy S24 thì có thêm 2 khách nữa đang chờ. Trước đây, với hệ thống cũ, chị phải ghi tay IMEI, tính toán voucher thủ công, mất 10-15 phút/giao dịch. Khách chờ lâu, có người bỏ đi.

Hôm nay, với **POS interface** của Tact, chị Lan mở `/admin/pos`, tìm khách bằng SĐT (0912345678) → Hệ thống hiện "Nguyễn Văn A - 500 điểm". Chị gõ "iPhone 15 Pro" vào ô tìm kiếm → Chọn từ dropdown → Tự động thêm vào giỏ: 25.000.000đ. Khách hỏi "Em có voucher gì không?", chị nhập GIAM100K → Hệ thống tự động trừ 100.000đ. Khách muốn dùng 250 điểm → Chị tick vào ô "Sử dụng điểm" → Tự động trừ thêm 250.000đ. Tổng: 24.650.000đ.

Climax moment: Khách đồng ý mua, chị Lan mở hộp iPhone, nhìn IMEI trên khay sim và gõ vào ô "IMEI": 123456789012345, chọn "Tiền mặt", click "Hoàn tất" → **Chỉ mất 4 phút**! Hệ thống tự động:

-   Tạo order (source='store', status='completed')
-   Lưu IMEI vào order_items.imei_list
-   Xuất kho (trigger tự động giảm quantity)
-   Trừ 250 điểm đã dùng
-   Tích 246 điểm mới (24.650.000 / 100.000)
-   In hóa đơn với IMEI rõ ràng

Chị Lan xử lý được 3 khách trong 15 phút thay vì chỉ 1 khách. Cuối tháng, doanh số của chị tăng 30%, được thưởng. Quan trọng hơn, chị không còn stress trong giờ cao điểm.

### Journey 3: Anh Tuấn - Nhân viên kho nhập hàng 500 máy từ nhà cung cấp

Anh Tuấn, 35 tuổi, quản lý kho Tact. Thứ 2 đầu tháng, anh nhận được lô hàng 500 máy iPhone 15 từ nhà cung cấp FPT, trị giá 12 tỷ đồng. Với hệ thống cũ, anh phải nhập thủ công vào Excel, dễ sai sót, và không có cảnh báo gì khi tồn kho thấp.

Sáng nay, anh Tuấn đăng nhập Tact với role Warehouse, vào module **Quản lý kho** → Chọn "Nhập hàng". Anh điền:

-   Nhà cung cấp: FPT
-   Mã phiếu nhập: PN-2025-001
-   Sản phẩm: iPhone 15 Pro (128GB) - Số lượng: 300
-   Sản phẩm: iPhone 15 (128GB) - Số lượng: 200
-   Ghi chú: "Lô hàng tháng 12, đã kiểm tra đầy đủ"

Khi click "Lưu", hệ thống phát hiện tổng giá trị > 50M → Hiện popup **"Xác nhận giao dịch giá trị cao: 12.000.000.000đ"**. Anh Tuấn xác nhận → Hệ thống:

-   Tạo stock_movements (type='in')
-   Trigger tự động tăng products.quantity (+300 và +200)
-   Gửi notification cho Manager

Breakthrough: Một tuần sau, anh Tuấn mở Dashboard, thấy cảnh báo 🔴 **"Sắp hết: Galaxy S24 - Còn 3 máy"**. Anh lập tức liên hệ NCC đặt thêm hàng, tránh được tình trạng hết hàng mất khách. Cuối tháng, báo cáo **Inventory Accuracy: 97%** (trước đây chỉ 82%). Anh Tuấn không còn phải kiểm kho thủ công hàng ngày, tiết kiệm 2 giờ/ngày.

### Journey 4: Chị Hương - Manager phát hiện sản phẩm chậm bán và điều chỉnh chiến lược

Chị Hương, 40 tuổi, quản lý cửa hàng Tact. Cuối tháng 11, chị lo lắng vì doanh thu giảm nhẹ so với tháng trước. Chị không biết nguyên nhân: Sản phẩm nào bán chậm? Tồn kho có vấn đề gì không?

Sáng thứ 2, chị Hương đăng nhập Tact với role Manager, mở **Dashboard**. Chị thấy:

-   📊 Biểu đồ doanh thu: Tháng 11 giảm 8% so với tháng 10
-   🟡 Cảnh báo: **"Sản phẩm chậm bán: Galaxy A54 - 35 ngày không bán"**
-   💰 Giá trị tồn kho: 450.000.000đ (15 máy Galaxy A54 @ 30M/máy)

Chị Hương click vào "Sản phẩm chậm bán" → Thấy Galaxy A54 có 15 máy tồn kho, nhập từ 40 ngày trước, chưa bán được máy nào. Chị nhận ra: Giá 9.500.000đ quá cao so với thị trường (đối thủ bán 8.900.000đ).

Climax moment: Chị Hương quyết định:

1. Vào module **Promotions** → Tạo voucher "GALADAY" giảm 10% cho Galaxy A54
2. Giảm giá sản phẩm xuống 8.800.000đ
3. Giao cho Sales team chạy campaign

Hai tuần sau, 12/15 máy Galaxy A54 đã bán hết. Dashboard hiện:

-   ✅ Doanh thu tháng 12 tăng 15% so với tháng 11
-   🟢 Tồn kho tốt: Tất cả sản phẩm < 20 ngày
-   📈 Inventory turnover: 9x/năm (target: 8-10x)

Chị Hương cảm thấy tự tin hơn trong việc ra quyết định kinh doanh nhờ có data real-time. Chị không còn phải đoán mò hay chờ báo cáo cuối tháng.

### Journey 5: Anh Nam - Admin setup hệ thống và phân quyền cho nhân viên mới

Anh Nam, IT Manager, vừa được giao nhiệm vụ triển khai Tact cho cửa hàng. Tuần đầu tiên, anh cần:

-   Setup 4 roles: Admin, Manager, Sales, Warehouse
-   Tạo tài khoản cho 12 nhân viên
-   Phân quyền đúng cho từng người
-   Đảm bảo security (password, CSRF, SQL injection prevention)

Anh Nam đăng nhập với role Admin, vào module **Users Management**:

1. Tạo user mới: Email, Password, Chọn Role
2. Phân quyền:
    - Sales: Chỉ xem sản phẩm, khách hàng, bán hàng POS
    - Warehouse: Chỉ nhập kho, xem lịch sử
    - Manager: Xem tất cả trừ quản lý users
    - Admin: Full quyền

Breakthrough: Một nhân viên Sales mới (chị Mai) cố gắng vào module "Users Management" → Hệ thống hiện **"403 Forbidden - Bạn không có quyền truy cập"**. Anh Nam kiểm tra log, thấy Laravel middleware đã block đúng. Security hoạt động tốt!

Một tháng sau, anh Nam chạy báo cáo:

-   ✅ 100% nhân viên đã được training và sử dụng thành thạo
-   ✅ System uptime: 99.8%
-   ✅ Zero security incidents
-   ✅ 50+ đơn hàng/ngày được xử lý thành công

Anh Nam yên tâm về hệ thống và tập trung vào việc tối ưu hóa performance.

### Journey Requirements Summary

Các journeys trên đã reveal ra những **capabilities** cần thiết cho từng user type:

**Customer Journey Requirements:**

-   Product catalog với filter, sort, specs đầy đủ
-   Shopping cart với voucher + loyalty points integration
-   Checkout flow với timeline trực quan
-   Order history với IMEI tracking và warranty info
-   Google OAuth + Password authentication
-   Email notifications với order status

**Sales Journey Requirements:**

-   POS interface tối ưu (< 5 phút/giao dịch)
-   Quick customer lookup by phone number
-   Product search với autocomplete
-   Real-time voucher validation và points calculation
-   IMEI input field với validation
-   Auto stock update via database triggers
-   Auto points calculation via database triggers
-   Invoice generation với IMEI display

**Warehouse Journey Requirements:**

-   Stock in module với multi-product support
-   High-value transaction confirmation (> 50M threshold)
-   Auto stock update via database triggers
-   Dashboard alerts: Low stock (< 5 items), Dead stock (> 30 days)
-   Inventory value tracking by cost price
-   Stock movement history với filtering
-   Manager notifications for critical events

**Manager Journey Requirements:**

-   Dashboard với real-time charts (Chart.js)
-   Multi-level alerts: Stock levels, dead stock, revenue trends
-   Inventory reports: Value, turnover, aging analysis
-   Revenue reports với time-based comparison
-   Product performance analytics
-   Promotion management CRUD
-   Data-driven decision support tools

**Admin Journey Requirements:**

-   User management CRUD với role assignment
-   Role-based access control (4 roles: Admin, Manager, Sales, Warehouse)
-   Laravel middleware authorization
-   Security features: CSRF protection, bcrypt password hashing, SQL injection prevention
-   System monitoring dashboard
-   Audit logs for security events
-   Access control testing và validation

## Web Application Specific Requirements

### Architecture Overview

**Tact** is built as a **traditional Multi-Page Application (MPA)** with progressive enhancement through AJAX, leveraging Laravel 12's server-side rendering capabilities combined with modern frontend tooling.

**Architecture Pattern:**

-   **MPA Core**: Laravel Blade templates with server-side routing
-   **Progressive Enhancement**: Axios-powered AJAX for dynamic features
-   **Build System**: Vite 7 with Laravel Vite Plugin for asset compilation
-   **Styling**: Tailwind CSS 4 + DaisyUI 5 component library

This hybrid approach provides:

-   SEO-friendly server-rendered pages for customer-facing content
-   Fast, interactive experiences for admin features (POS, dashboard)
-   Simple deployment without complex SPA infrastructure
-   Suitable for 8-week academic timeline

### Browser Compatibility Matrix

**Supported Browsers:**

| Browser       | Minimum Version   | Notes                      |
| ------------- | ----------------- | -------------------------- |
| Chrome        | Latest 2 versions | Primary development target |
| Firefox       | Latest 2 versions | Full support               |
| Safari        | Latest 2 versions | iOS Safari included        |
| Edge          | Latest 2 versions | Chromium-based             |
| Mobile Chrome | Latest 2 versions | Android support            |
| Mobile Safari | Latest 2 versions | iOS support                |

**Not Supported:**

-   Internet Explorer 11 (EOL)
-   Legacy Edge (pre-Chromium)
-   Browsers older than 2 versions

**Technical Justification:**

-   Tailwind CSS 4 and Vite 7 target modern browsers
-   PHP 8.2 features used on server-side
-   No polyfills needed for modern browser features
-   Reduces development complexity for academic timeline

### Responsive Design Requirements

**Breakpoint Strategy:**

```css
/* Tailwind CSS 4 default breakpoints */
sm: 640px   /* Small tablets, large phones */
md: 768px   /* Tablets */
lg: 1024px  /* Laptops, small desktops */
xl: 1280px  /* Desktops */
2xl: 1536px /* Large desktops */
```

**Device-Specific Layouts:**

**Mobile (< 640px):**

-   Customer pages: Single column, touch-optimized
-   Admin pages: Simplified navigation, essential features only
-   POS: Not optimized (desktop/tablet only)

**Tablet (640px - 1024px):**

-   Customer pages: 2-column grid for products
-   Admin pages: Collapsible sidebar navigation
-   POS: Functional with landscape orientation

**Desktop (> 1024px):**

-   Customer pages: 3-4 column grid for products
-   Admin pages: Full sidebar, multi-column layouts
-   POS: Optimized split-screen (products | cart)
-   Dashboard: Multi-widget layout with charts

**Responsive Components:**

-   Navigation: Hamburger menu (mobile) → Full navbar (desktop)
-   Tables: Horizontal scroll (mobile) → Full table (desktop)
-   Forms: Stacked (mobile) → Multi-column (desktop)
-   Images: Responsive with srcset for optimization

### Performance Targets

**Page Load Performance:**

| Metric                         | Target  | Measurement          |
| ------------------------------ | ------- | -------------------- |
| First Contentful Paint (FCP)   | < 1.5s  | Lighthouse           |
| Largest Contentful Paint (LCP) | < 2.5s  | Lighthouse           |
| Time to Interactive (TTI)      | < 3.5s  | Lighthouse           |
| Cumulative Layout Shift (CLS)  | < 0.1   | Lighthouse           |
| First Input Delay (FID)        | < 100ms | Real User Monitoring |

**Application Performance:**

| Feature          | Target        | Notes                   |
| ---------------- | ------------- | ----------------------- |
| POS Transaction  | < 1s response | Critical for sales flow |
| Product Search   | < 500ms       | AJAX autocomplete       |
| Cart Operations  | < 300ms       | Add/remove items        |
| Dashboard Load   | < 2s          | With charts and data    |
| Image Loading    | Lazy load     | Below fold images       |
| Database Queries | < 100ms       | Eloquent optimization   |

**Optimization Strategies:**

-   **Asset Optimization**: Vite code splitting, tree shaking
-   **Image Optimization**: WebP format, responsive images, lazy loading
-   **Database**: Eager loading, query optimization, indexing
-   **Caching**: Laravel cache for products, categories, static data
-   **CDN**: Optional for production (not required for MVP)

### SEO Strategy

**SEO Requirements:**

**Customer-Facing Pages (SEO Critical):**

-   Homepage: Meta tags, structured data (Organization)
-   Product List: Meta tags, pagination SEO
-   Product Detail: Rich snippets (Product schema), Open Graph
-   Category Pages: Meta tags, breadcrumbs

**Admin Pages (No SEO):**

-   Behind authentication
-   robots.txt disallow /admin/\*
-   No indexing needed

**Technical SEO Implementation:**

**Meta Tags (All Pages):**

```html
<title>{{page_title}} | Tact</title>
<meta name="description" content="{{page_description}}" />
<meta name="keywords" content="{{page_keywords}}" />
<link rel="canonical" href="{{canonical_url}}" />
```

**Open Graph (Product Pages):**

```html
<meta property="og:title" content="{{product_name}}" />
<meta property="og:description" content="{{product_description}}" />
<meta property="og:image" content="{{product_image}}" />
<meta property="og:type" content="product" />
<meta property="og:price:amount" content="{{price}}" />
<meta property="og:price:currency" content="VND" />
```

**Structured Data (Product Schema):**

```json
{
    "@context": "https://schema.org/",
    "@type": "Product",
    "name": "{{product_name}}",
    "image": "{{product_image}}",
    "description": "{{product_description}}",
    "sku": "{{product_sku}}",
    "brand": {
        "@type": "Brand",
        "name": "{{brand_name}}"
    },
    "offers": {
        "@type": "Offer",
        "price": "{{price}}",
        "priceCurrency": "VND",
        "availability": "{{availability}}"
    }
}
```

**Sitemap.xml:**

-   Auto-generated via Laravel package
-   Include: Homepage, categories, products, static pages
-   Update frequency: Daily for products, weekly for categories

**robots.txt:**

```
User-agent: *
Allow: /
Disallow: /admin/
Disallow: /api/
Sitemap: https://tact.vn/sitemap.xml
```

**SEO Priority Level:** Medium

-   Local business focus (not heavy organic traffic dependency)
-   Basic SEO sufficient for MVP
-   Can enhance post-launch if needed

### Real-Time Features & Data Refresh Strategy

**Approach:** Polling-based updates (no WebSocket/Pusher for MVP)

**Dashboard Real-Time Updates:**

-   **Method**: AJAX polling every 30 seconds
-   **Data**: New orders count, low stock alerts, revenue updates
-   **Implementation**: JavaScript setInterval + Axios
-   **Fallback**: Manual refresh button

**POS Inventory Sync:**

-   **Method**: Database-level consistency (no real-time sync needed)
-   **Approach**: Optimistic locking for concurrent transactions
-   **Conflict Resolution**: Last-write-wins with quantity validation
-   **User Feedback**: Error message if stock insufficient

**Order Status Updates:**

-   **Method**: Page refresh or manual check
-   **Customer View**: Timeline shows current status on page load
-   **Admin View**: Order list refreshes on navigation

**Notifications:**

-   **Method**: In-app notifications (no push notifications)
-   **Display**: Badge count on navbar, notification dropdown
-   **Persistence**: Database-stored, marked as read

**Future Enhancement (Post-MVP):**

-   Laravel Echo + Pusher for true real-time
-   WebSocket for POS multi-terminal sync
-   Push notifications for mobile

**Rationale for Polling:**

-   Simpler implementation for 8-week timeline
-   No additional infrastructure (Pusher, Redis)
-   Sufficient for single-store operation
-   Easy to upgrade to WebSocket later

### Accessibility Level

**Target:** WCAG 2.1 Level A (Basic Accessibility)

**Level A Requirements (Must Have):**

**Keyboard Navigation:**

-   All interactive elements accessible via Tab key
-   Logical tab order (top to bottom, left to right)
-   Skip to main content link
-   Escape key closes modals/dropdowns

**Semantic HTML:**

-   Proper heading hierarchy (h1 → h2 → h3)
-   `<nav>`, `<main>`, `<article>`, `<aside>` tags
-   `<button>` for actions, `<a>` for links
-   `<label>` for all form inputs

**Alternative Text:**

-   All images have descriptive alt text
-   Decorative images: alt=""
-   Product images: alt="{{product_name}} - {{brand}}"

**Form Accessibility:**

-   Labels associated with inputs (for/id)
-   Error messages linked to fields (aria-describedby)
-   Required fields marked (required attribute + visual indicator)
-   Clear focus indicators on form fields

**Color Contrast:**

-   Text: Minimum 4.5:1 contrast ratio
-   Large text (18pt+): Minimum 3:1 contrast ratio
-   DaisyUI default themes meet basic contrast requirements

**DaisyUI Accessibility Features (Built-in):**

-   Semantic component structure
-   ARIA attributes on interactive components
-   Focus states on buttons, inputs, links
-   Keyboard-accessible dropdowns and modals

**Not Implemented (Level AA/AAA):**

-   Screen reader optimization
-   High contrast mode
-   Text resizing up to 200%
-   Advanced ARIA landmarks
-   Comprehensive keyboard shortcuts

**Rationale:**

-   8-week academic timeline limits scope
-   Level A provides basic usability
-   DaisyUI provides foundation for future enhancement
-   Can upgrade to Level AA post-launch if needed

**Testing Approach:**

-   Manual keyboard navigation testing
-   Lighthouse accessibility audit (target: 80+ score)
-   Basic screen reader testing (optional)

### Implementation Considerations

**Development Workflow:**

-   **Local Development**: Laravel Sail (Docker) or native PHP 8.2
-   **Asset Compilation**: `npm run dev` (Vite HMR)
-   **Database**: MySQL 8.0+ or MariaDB 10.3+
-   **Version Control**: Git with feature branch workflow

**Deployment Requirements:**

-   **Server**: PHP 8.2, Nginx/Apache, MySQL
-   **Node.js**: For asset compilation (build step)
-   **Storage**: Local filesystem (no S3 for MVP)
-   **SSL**: Required for production (Let's Encrypt)

**Security Considerations:**

-   **CSRF Protection**: Laravel built-in (enabled by default)
-   **XSS Prevention**: Blade escaping ({{ }} syntax)
-   **SQL Injection**: Eloquent ORM (parameterized queries)
-   **Password Hashing**: bcrypt (Laravel default)
-   **Session Security**: HTTP-only cookies, secure flag in production

**Third-Party Dependencies:**

-   **Minimal External APIs**: Google OAuth only
-   **No Payment Gateway**: COD and bank transfer (manual)
-   **No Shipping API**: Manual shipping management
-   **No Email Service**: Laravel Mail with SMTP (optional)

**Scalability Considerations (Post-MVP):**

-   **Caching Layer**: Redis for session and cache
-   **Queue System**: Laravel Queue for async jobs
-   **CDN**: CloudFlare for static assets
-   **Database**: Read replicas for reporting
-   **Load Balancer**: Multiple app servers if needed

**Monitoring & Logging:**

-   **Application Logs**: Laravel Log (storage/logs)
-   **Error Tracking**: Laravel Telescope (development)
-   **Performance**: Laravel Debugbar (development)
-   **Production**: Basic error logging (no APM for MVP)

## Project Scoping & Development Strategy

### MVP Strategy & Philosophy

**Approach:** Problem-Solving MVP with Experience Focus

**Tact** follows a pragmatic MVP strategy designed for an **8-week academic timeline** while maintaining production-ready quality. The approach balances:

-   **Core Problem Solving**: Address the critical pain points of O2O phone retail (inventory tracking, POS efficiency, customer trust)
-   **Complete User Experience**: Deliver end-to-end journeys for all 5 user types (Customer, Sales, Warehouse, Manager, Admin)
-   **Technical Foundation**: Build on proven Laravel 12 stack with modern frontend (Tailwind CSS 4 + DaisyUI 5)

**Resource Requirements:**

-   **Team Size**: 1-2 developers (academic project)
-   **Timeline**: 8 weeks (buổi học)
-   **Technical Skills**: PHP/Laravel, MySQL, Tailwind CSS, basic JavaScript
-   **Infrastructure**: Local development (Laravel Sail) or shared hosting

### MVP Boundaries (Week 1-8)

**Scope Definition:**
The MVP includes ALL essential features documented in the "Product Scope" section, specifically:

**Must-Have (Bắt buộc):**

-   ✅ 8 customer-facing pages (Home, Products, Detail, Cart, Checkout, Orders, Account, Auth)
-   ✅ 12 CRUD modules for admin (matching 12 database tables)
-   ✅ POS Interface for in-store sales
-   ✅ Order management workflow (approve, ship, complete, cancel)
-   ✅ Inventory management (stock in, alerts, history)
-   ✅ Voucher + Loyalty points system
-   ✅ IMEI tracking (order_items.imei_list)
-   ✅ Google OAuth + Password authentication
-   ✅ Timeline UI for order tracking
-   ✅ Dashboard with charts and alerts
-   ✅ Basic reports (revenue, products, inventory)
-   ✅ 4-role authorization (Admin, Manager, Sales, Warehouse)

**Explicitly Out of Scope for MVP:**

-   ❌ Mobile app (web responsive only)
-   ❌ Hardware POS terminals (web-based POS)
-   ❌ Payment gateway integration (COD/bank transfer manual)
-   ❌ Shipping API integration (manual tracking)
-   ❌ Email notifications (optional)
-   ❌ Warranty claim management module
-   ❌ Multi-location inventory
-   ❌ Real-time WebSocket features (polling only)

### Post-MVP Roadmap

**Phase 2: Growth Features (Post-Academic Project)**
If commercialized or extended beyond academic scope:

-   Enhanced reporting (Excel/PDF export)
-   Email/SMS notifications
-   Advanced inventory features
-   Customer reviews & ratings
-   Warranty claim module

**Phase 3: Expansion (Future Vision)**
Long-term commercialization features:

-   Native mobile apps (iOS/Android)
-   Payment gateway integrations (VNPay, MoMo, ZaloPay)
-   AI/ML features (demand forecasting, recommendations)
-   Multi-store franchise management
-   Trade-in & repair services

### Risk Mitigation Strategy

**Technical Risks:**

| Risk                                       | Mitigation                                                    |
| ------------------------------------------ | ------------------------------------------------------------- |
| Database complexity (12 tables + triggers) | Use Laravel migrations, test triggers early (Week 1-2)        |
| POS performance (< 1s requirement)         | Optimize queries, use eager loading, test with realistic data |
| Google OAuth integration issues            | Implement email/password first, OAuth as enhancement          |
| Timeline pressure (8 weeks)                | Fixed scope (12 tables), no feature creep, weekly milestones  |

**Market Risks:**

| Risk                                  | Mitigation                                                    |
| ------------------------------------- | ------------------------------------------------------------- |
| Feature completeness for demo         | Prioritize core journeys, use seed data for demo              |
| User acceptance (academic evaluation) | Focus on UX polish, clear documentation, working demo         |
| Real-world applicability              | Design with actual phone retail insights, realistic scenarios |

**Resource Risks:**

| Risk                    | Mitigation                                                        |
| ----------------------- | ----------------------------------------------------------------- |
| Solo developer timeline | Use Laravel scaffolding, DaisyUI components, minimize custom code |
| Technical blockers      | Leverage Laravel community, Stack Overflow, AI assistants         |
| Scope creep             | Fixed 12-table constraint, clear MVP boundaries                   |

### Success Criteria Alignment

**MVP Success = Meeting Academic Requirements + Demonstrable Value**

**Academic Success (Week 8):**

-   ✅ All 12 database tables implemented with relationships
-   ✅ CRUD operations functional for all modules
-   ✅ Core user journeys demonstrable (Customer purchase, POS sale, Inventory management)
-   ✅ Clean, responsive UI with Tailwind CSS + DaisyUI
-   ✅ Working demo with realistic data
-   ✅ Documentation complete (ERD, user manual, technical docs)

**Product Success (Beyond Academic):**

-   ✅ Meets User Success criteria from "Success Criteria" section
-   ✅ Achieves 3-month Business Success targets (if deployed)
-   ✅ Technical Success metrics (performance, security, reliability)

**Validation Approach:**

-   **Week 4 Checkpoint**: Core CRUD + Auth working
-   **Week 6 Checkpoint**: POS + Inventory + Orders working
-   **Week 8 Demo**: Full end-to-end scenarios with polished UI

### Development Timeline (8 Weeks)

**Week 1-2: Foundation**

-   Database setup (12 tables + 2 triggers)
-   Authentication (Email/Password + Google OAuth)
-   Basic CRUD scaffolding
-   Admin layout with DaisyUI

**Week 3-4: Core Features**

-   Complete CRUD for all 12 modules
-   Product management with images
-   Order creation and basic workflow
-   Stock movements recording

**Week 5-6: Advanced Features**

-   POS interface implementation
-   Voucher system
-   Loyalty points (triggers)
-   IMEI tracking
-   Order timeline UI

**Week 7-8: Polish & Demo**

-   Dashboard with Chart.js
-   Reports generation
-   Responsive design refinement
-   Demo data seeding
-   Testing and bug fixes
-   Documentation and presentation

**Milestone Deliverables:**

-   Week 2: Database + Auth demo
-   Week 4: CRUD modules demo
-   Week 6: POS + Orders demo
-   Week 8: Final presentation with full system demo

## Functional Requirements

### 1. Customer Account Management

-   FR1: Customers can register using email and password
-   FR2: Customers can register using Google OAuth
-   FR3: Customers who register via Google OAuth must set a password on first login
-   FR4: Customers can log in using email/password or Google OAuth
-   FR5: Customers can view their profile information
-   FR6: Customers can update their profile information
-   FR7: Customers can change their password
-   FR8: Customers can view their current loyalty points balance

### 2. Product Discovery & Browsing

-   FR9: Customers can view homepage with featured products and banners
-   FR10: Customers can browse products by category
-   FR11: Customers can browse products by brand
-   FR12: Customers can filter products by price range
-   FR13: Customers can filter products by brand
-   FR14: Customers can filter products by category
-   FR15: Customers can sort products by price (low to high, high to low)
-   FR16: Customers can sort products by newest arrivals
-   FR17: Customers can sort products by best sellers
-   FR18: Customers can search products by name or SKU
-   FR19: Customers can view product details including specs, price, stock availability, warranty info, and IMEI tracking notice
-   FR20: Customers can view product images with thumbnails
-   FR21: Customers can view product technical specifications

### 3. Shopping Cart & Checkout

-   FR22: Customers can add products to shopping cart
-   FR23: Customers can update product quantities in cart
-   FR24: Customers can remove products from cart
-   FR25: Customers can view cart summary with subtotal
-   FR26: Customers can proceed to checkout from cart
-   FR27: Customers can enter shipping information during checkout
-   FR28: Customers can apply voucher codes during checkout
-   FR29: Customers can use loyalty points for discount during checkout
-   FR30: Customers can select payment method (COD or Bank Transfer)
-   FR31: Customers can view order summary with price breakdown (subtotal, voucher discount, points discount, total)
-   FR32: Customers can confirm and place orders

### 4. Order Management (Customer)

-   FR33: Customers can view list of their orders
-   FR34: Customers can filter orders by status
-   FR35: Customers can view order details including products, IMEI numbers, warranty information, shipping address, and price breakdown
-   FR36: Customers can view order status timeline (pending → confirmed → shipping → completed → cancelled)
-   FR37: Customers can cancel orders that are in pending status
-   FR38: Customers can view IMEI numbers for purchased devices
-   FR39: Customers can view warranty expiration dates for purchased devices

### 5. Loyalty & Promotions (Customer)

-   FR40: Customers automatically earn loyalty points when orders are completed (100,000 VND = 1 point)
-   FR41: Customers can redeem loyalty points for discounts (1 point = 1,000 VND)
-   FR42: Customers can view available vouchers
-   FR43: Customers can apply vouchers to orders (both online and POS)

### 6. Point of Sale (POS) - Sales Staff

-   FR44: Sales staff can access POS interface
-   FR45: Sales staff can search customers by phone number
-   FR46: Sales staff can create new customer records quickly
-   FR47: Sales staff can search products by name or SKU
-   FR48: Sales staff can add products to POS cart
-   FR49: Sales staff can update quantities in POS cart
-   FR50: Sales staff can remove products from POS cart
-   FR51: Sales staff can apply voucher codes to POS transactions
-   FR52: Sales staff can apply customer loyalty points to POS transactions
-   FR53: Sales staff can enter IMEI numbers for devices
-   FR54: Sales staff can select payment method (Cash, Card, Bank Transfer)
-   FR55: Sales staff can complete POS transactions
-   FR56: System automatically creates completed orders for POS transactions
-   FR57: System automatically records IMEI numbers in order items
-   FR58: System automatically updates inventory when POS transactions complete
-   FR59: System automatically deducts used loyalty points
-   FR60: System automatically awards new loyalty points for POS transactions
-   FR61: Sales staff can print invoices with IMEI numbers

### 7. Order Management (Admin/Manager/Sales)

-   FR62: Staff can view list of all orders
-   FR63: Staff can filter orders by status
-   FR64: Staff can filter orders by source (web or store)
-   FR65: Staff can view order details
-   FR66: Staff can approve pending orders (change status to confirmed)
-   FR67: Staff can mark orders as shipping and enter tracking numbers
-   FR68: Staff can mark orders as completed
-   FR69: Staff can cancel orders with reason
-   FR70: Staff can view order timeline history

### 8. Inventory Management - Stock In (Warehouse)

-   FR71: Warehouse staff can create stock-in transactions
-   FR72: Warehouse staff can select supplier for stock-in
-   FR73: Warehouse staff can enter stock-in reference number
-   FR74: Warehouse staff can add multiple products to stock-in transaction
-   FR75: Warehouse staff can specify quantities for each product
-   FR76: Warehouse staff can add notes to stock-in transactions
-   FR77: System prompts confirmation for high-value transactions (> 50M VND)
-   FR78: System automatically creates stock movement records (type='in')
-   FR79: System automatically updates product quantities via database trigger

### 9. Inventory Management - Monitoring (Warehouse/Manager)

-   FR80: Staff can view stock movement history
-   FR81: Staff can filter stock movements by type (in/out)
-   FR82: Staff can filter stock movements by date range
-   FR83: Staff can filter stock movements by product
-   FR84: Staff can view low stock alerts (< 5 items)
-   FR85: Staff can view dead stock alerts (> 30 days without sales)
-   FR86: Staff can view total inventory value by cost price
-   FR87: Dashboard displays color-coded stock level indicators (red: < 5, yellow: 5-10, green: > 10)

### 10. Product Management (Admin/Manager)

-   FR88: Staff can create new products
-   FR89: Staff can upload product images
-   FR90: Staff can assign products to categories
-   FR91: Staff can assign products to brands
-   FR92: Staff can set product SKU (unique)
-   FR93: Staff can set product prices
-   FR94: Staff can set warranty period in months
-   FR95: Staff can set product status (active/inactive)
-   FR96: Staff can update product information
-   FR97: Staff can delete products
-   FR98: Staff can view product list with pagination
-   FR99: Staff can add product technical specifications
-   FR100: Staff can update product technical specifications
-   FR101: Staff can delete product technical specifications

### 11. Promotion Management (Admin/Manager)

-   FR102: Staff can create vouchers with fixed discount amount
-   FR103: Staff can create vouchers with percentage discount
-   FR104: Staff can set minimum order value for vouchers
-   FR105: Staff can set maximum discount amount for percentage vouchers
-   FR106: Staff can set voucher validity period (start/end dates)
-   FR107: Staff can set usage limit for vouchers
-   FR108: Staff can set voucher codes
-   FR109: Staff can update voucher information
-   FR110: Staff can deactivate vouchers
-   FR111: System validates voucher eligibility during checkout/POS
-   FR112: System tracks voucher usage count

### 12. Dashboard & Reporting (Manager/Admin)

-   FR113: Staff can view dashboard with key metrics (revenue, orders, products, customers)
-   FR114: Staff can view revenue charts by time period
-   FR115: Staff can view stock alerts on dashboard
-   FR116: Staff can view dead stock alerts on dashboard
-   FR117: Staff can view inventory value on dashboard
-   FR118: Staff can generate revenue reports by date range
-   FR119: Staff can generate product performance reports (top sellers, slow movers)
-   FR120: Staff can generate inventory reports (stock levels, value, turnover)
-   FR121: Staff can generate customer reports (new customers, top customers, loyalty points)

### 13. User & Role Management (Admin)

-   FR122: Admin can create staff user accounts
-   FR123: Admin can assign roles to staff (Admin, Manager, Sales, Warehouse)
-   FR124: Admin can update staff information
-   FR125: Admin can deactivate staff accounts
-   FR126: System enforces role-based access control
-   FR127: Sales role can only access POS, orders, products, customers
-   FR128: Warehouse role can only access inventory management
-   FR129: Manager role can access all modules except user management
-   FR130: Admin role has full system access

### 14. Customer Management (Admin/Manager/Sales)

-   FR131: Staff can view customer list
-   FR132: Staff can view customer details including order history and loyalty points
-   FR133: Staff can search customers by name, email, or phone
-   FR134: Staff can view customer loyalty points balance
-   FR135: Staff can view customer order history

### 15. Master Data Management (Admin/Manager)

-   FR136: Staff can create, update, and delete categories
-   FR137: Staff can create, update, and delete brands
-   FR138: Staff can create, update, and delete suppliers
-   FR139: Staff can view lists of categories, brands, and suppliers

## Non-Functional Requirements

### Performance Requirements

**Response Time:**

-   NFR1: Customer-facing pages load within 2 seconds (First Contentful Paint < 1.5s, Largest Contentful Paint < 2.5s)
-   NFR2: POS transactions complete within 1 second from click to confirmation
-   NFR3: Product search returns results within 500ms
-   NFR4: Shopping cart operations (add/remove/update) complete within 300ms
-   NFR5: Dashboard loads with charts and data within 2 seconds
-   NFR6: Database queries execute within 100ms average

**Throughput:**

-   NFR7: System supports 50+ concurrent users without performance degradation
-   NFR8: POS interface handles 3 transactions per minute per sales staff
-   NFR9: System processes 50+ orders per day without performance issues

**Resource Efficiency:**

-   NFR10: Page sizes optimized (< 2MB including images)
-   NFR11: Images lazy-loaded below the fold
-   NFR12: Vite code splitting reduces initial bundle size

### Security Requirements

**Authentication & Authorization:**

-   NFR13: All passwords hashed using bcrypt with minimum 10 rounds
-   NFR14: Session cookies are HTTP-only and secure (HTTPS only in production)
-   NFR15: CSRF protection enabled for all state-changing requests
-   NFR16: Role-based access control enforced at middleware level
-   NFR17: Failed login attempts logged for security monitoring

**Data Protection:**

-   NFR18: All database connections use encrypted channels (SSL/TLS)
-   NFR19: Sensitive data (passwords, payment info) never logged in plain text
-   NFR20: IMEI numbers stored securely with access audit trail
-   NFR21: Customer personal information (email, phone, address) protected per GDPR principles

**Input Validation:**

-   NFR22: All user inputs validated and sanitized server-side
-   NFR23: SQL injection prevented through Eloquent ORM parameterized queries
-   NFR24: XSS attacks prevented through Blade template escaping
-   NFR25: File uploads restricted to allowed types (images only) with size limits (< 5MB)

**Compliance:**

-   NFR26: System maintains audit logs for financial transactions (orders, payments)
-   NFR27: Customer data deletion capability (GDPR right to be forgotten)
-   NFR28: VAT invoice generation complies with Vietnamese tax regulations

### Reliability Requirements

**Availability:**

-   NFR29: System uptime target of 99%+ (< 7.2 hours downtime per month)
-   NFR30: Planned maintenance windows communicated 24 hours in advance
-   NFR31: Critical bugs (blocking operations) resolved within 4 hours

**Data Integrity:**

-   NFR32: Zero data loss for completed transactions
-   NFR33: Database backups performed daily with 30-day retention
-   NFR34: Database triggers ensure inventory consistency (stock movements auto-update quantities)
-   NFR35: Loyalty points calculations are atomic (no partial updates)

**Error Handling:**

-   NFR36: All errors logged with context (user, action, timestamp, stack trace)
-   NFR37: User-friendly error messages displayed (no technical details exposed)
-   NFR38: System gracefully handles database connection failures with retry logic
-   NFR39: Failed transactions rolled back completely (no partial state)

**Recovery:**

-   NFR40: Database restore time < 1 hour from backup
-   NFR41: System recovery time < 5 minutes after crash
-   NFR42: Transaction logs enable point-in-time recovery

### Usability Requirements

**Responsive Design:**

-   NFR43: All customer-facing pages fully functional on mobile (320px width minimum)
-   NFR44: Admin pages functional on tablets (768px width minimum)
-   NFR45: POS interface optimized for desktop/laptop (1024px+ width)
-   NFR46: Touch targets minimum 44x44px for mobile interactions

**Browser Compatibility:**

-   NFR47: Full support for Chrome, Firefox, Safari, Edge (latest 2 versions)
-   NFR48: Graceful degradation for older browsers (no crashes, basic functionality)
-   NFR49: Mobile browser support for iOS Safari and Chrome Mobile

**Accessibility:**

-   NFR50: WCAG 2.1 Level A compliance (keyboard navigation, alt text, semantic HTML)
-   NFR51: All interactive elements accessible via keyboard (Tab, Enter, Escape)
-   NFR52: Form inputs have associated labels (for/id attributes)
-   NFR53: Color contrast ratio minimum 4.5:1 for text
-   NFR54: Focus indicators visible on all interactive elements
-   NFR55: Lighthouse accessibility score 80+ target

**User Experience:**

-   NFR56: Consistent UI patterns across all pages (DaisyUI components)
-   NFR57: Loading indicators displayed for operations > 500ms
-   NFR58: Success/error feedback provided for all user actions
-   NFR59: Form validation errors displayed inline with clear messages
-   NFR60: Confirmation prompts for destructive actions (delete, cancel order)

### Maintainability Requirements

**Code Quality:**

-   NFR61: Laravel best practices followed (PSR-12 coding standards)
-   NFR62: Code organized following MVC pattern (Models, Views, Controllers)
-   NFR63: Database migrations version-controlled for schema changes
-   NFR64: Environment-specific configuration via .env files (no hardcoded values)

**Documentation:**

-   NFR65: Database ERD documented with relationships
-   NFR66: API endpoints documented (if any)
-   NFR67: User manual provided for admin features
-   NFR68: Setup instructions documented (installation, configuration, deployment)

**Testing:**

-   NFR69: Critical user journeys manually tested before demo
-   NFR70: Database triggers tested with realistic data
-   NFR71: Role-based access control tested for all roles
-   NFR72: Cross-browser testing performed on target browsers

### Scalability Requirements (Future)

**Note:** These are NOT required for MVP (8-week academic project) but documented for future commercialization:

-   NFR73: System architecture supports horizontal scaling (multiple app servers)
-   NFR74: Database supports read replicas for reporting queries
-   NFR75: Caching layer (Redis) can be added without code changes
-   NFR76: Queue system (Laravel Queue) can handle async jobs for notifications
