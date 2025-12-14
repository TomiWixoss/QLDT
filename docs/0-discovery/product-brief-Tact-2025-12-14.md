---
stepsCompleted: [1, 2, 3, 4, 5]
inputDocuments:
    - "docs/0-discovery/brainstorming-session-2025-12-14.md"
    - "docs/0-discovery/research/market-vietnam-mobile-phone-retail-2025-12-14.md"
    - "docs/0-discovery/research/o2o-model-retail-2025-12-14.md"
    - "docs/0-discovery/research/inventory-management-phone-retail-2025-12-14.md"
    - "docs/0-discovery/research/pos-system-phone-retail-2025-12-14.md"
workflowType: "product-brief"
lastStep: 5
project_name: "Tact"
user_name: "TomiSakae"
date: "2025-12-14"
---

# Product Brief: Tact

**Date:** 2025-12-14
**Author:** TomiSakae

---

## Executive Summary

**Tact** là hệ thống quản lý cửa hàng điện thoại O2O (Online-to-Offline) được thiết kế để giải quyết các thách thức trong vận hành cửa hàng bán lẻ điện thoại di động tại Việt Nam. Hệ thống tích hợp liền mạch giữa bán hàng online và offline, cung cấp công cụ quản lý toàn diện từ inventory, POS, đến CRM với focus đặc biệt vào IMEI tracking và trải nghiệm khách hàng.

Tact được xây dựng trên nền tảng Laravel 12, Tailwind CSS 4, và DaisyUI 5, tối ưu hóa cho cả khách hàng (front-end) và nhân viên quản lý (back-end) với 4 cấp độ phân quyền (Admin, Manager, Sales, Warehouse).

---

## Core Vision

### Problem Statement

**Các cửa hàng điện thoại tại Việt Nam đang đối mặt với nhiều thách thức:**

1. **Trải nghiệm O2O không liền mạch (65% khách hàng có hành vi ROPO)**

    - Thông tin giá, tồn kho không đồng bộ giữa online và offline
    - Không thể đặt online và nhận tại cửa hàng gần nhất
    - Lịch sử mua hàng không được lưu trữ xuyên suốt các kênh

2. **Quản lý tồn kho phức tạp**

    - Điện thoại là high-value items ($200-$2,000) cần tracking chặt chẽ
    - IMEI-level tracking bắt buộc nhưng nhiều hệ thống không hỗ trợ tốt
    - Quản lý nhiều cửa hàng với stock levels khác nhau
    - Risk cao về shrinkage (theft, errors) và dead stock

3. **Quy trình bán hàng chậm và phức tạp**

    - Khách hàng phải điền nhiều giấy tờ, thời gian chờ đợi lâu
    - POS không tối ưu cho phone retail (thiếu IMEI tracking, warranty management)
    - Không có công cụ hỗ trợ trade-in và repair tracking

4. **Thiếu minh bạch và tin cậy**
    - 85% khách hàng lo ngại hàng giả, hàng nhái
    - Giá cả không minh bạch, khuyến mãi phức tạp
    - Chính sách bảo hành, đổi trả không rõ ràng

### Problem Impact

**Đối với chủ cửa hàng:**

-   Mất doanh thu do stockouts hoặc overstocking
-   Chi phí vận hành cao do quy trình thủ công
-   Khó cạnh tranh với các chuỗi lớn (TGDĐ, FPT Shop)
-   Rủi ro cao về shrinkage và dead stock

**Đối với nhân viên:**

-   Quy trình bán hàng phức tạp, tốn thời gian
-   Khó quản lý inventory chính xác
-   Thiếu công cụ hỗ trợ tư vấn khách hàng

**Đối với khách hàng:**

-   Trải nghiệm mua sắm không mượt mà
-   Lo ngại về chất lượng sản phẩm và dịch vụ
-   Không có sự liên kết giữa online và offline

### Why Existing Solutions Fall Short

**Các hệ thống POS truyền thống (Square, Shopify POS):**

-   ❌ Không có IMEI tracking built-in (critical cho phone retail)
-   ❌ Thiếu warranty management và trade-in support
-   ❌ Không tối ưu cho O2O model

**Các giải pháp phone retail chuyên biệt (CellSmart, Cellivo):**

-   ⚠️ Tích hợp e-commerce hạn chế
-   ⚠️ Không focus vào O2O experience
-   ⚠️ UI/UX chưa hiện đại

**Các chuỗi lớn (TGDĐ, FPT Shop):**

-   💰 Hệ thống enterprise quá phức tạp và đắt đỏ cho SME
-   🔒 Không phù hợp cho cửa hàng độc lập
-   📊 Thiếu flexibility để customize

### Proposed Solution

**Tact** là hệ thống quản lý cửa hàng điện thoại O2O toàn diện với:

**1. O2O Integration Liền Mạch**

-   Real-time sync giữa online và offline (giá, tồn kho, khuyến mãi)
-   BOPIS (Buy Online, Pick Up In Store) support
-   Unified customer profile xuyên suốt các kênh
-   Mobile-first approach

**2. IMEI-Based Inventory Management**

-   Track từng máy cụ thể từ warehouse đến customer
-   IMEI scanning tại mọi transaction
-   Warranty management tự động theo IMEI
-   Anti-theft protection với IMEI blacklist check

**3. POS Tối Ưu Cho Phone Retail**

-   IMEI tracking built-in
-   Warranty activation tự động
-   Trade-in management với device valuation
-   Repair services tracking
-   Fast checkout (< 5 phút)

**4. Quản Lý Đa Cửa Hàng**

-   Multi-location inventory visibility
-   Smart stock transfers
-   Centralized reporting và analytics
-   Role-based access (Admin, Manager, Sales, Warehouse)

**5. Customer Experience Focus**

-   Minh bạch về giá và sản phẩm
-   Loyalty program tích hợp
-   Purchase history tracking
-   Personalized recommendations

### Key Differentiators

**1. O2O-First Design (Unique Advantage)**

-   Không phải là POS truyền thống thêm e-commerce
-   Không phải là e-commerce thêm POS
-   Được thiết kế từ đầu cho O2O model với 65% khách hàng ROPO

**2. Phone Retail Specialized**

-   IMEI tracking là core feature, không phải add-on
-   Warranty, trade-in, repair management built-in
-   Product specs database comprehensive
-   Accessories cross-selling optimization

**3. Modern Tech Stack**

-   Laravel 12 + Tailwind CSS 4 + DaisyUI 5
-   Mobile-responsive, fast, beautiful UI
-   Easy to customize và extend
-   Cost-effective cho SME

**4. Vietnamese Market Focus**

-   Hiểu rõ hành vi khách hàng Việt Nam (ROPO, price sensitivity)
-   Tích hợp payment gateways Việt Nam (VNPay, MoMo, ZaloPay)
-   Vietnamese language first
-   Phù hợp với quy mô và ngân sách cửa hàng Việt Nam

**5. Academic Project → Production Ready**

-   Timeline 8 buổi học nhưng có roadmap rõ ràng
-   Focus vào core features trước, nice-to-have sau
-   Có thể scale và commercialize sau này
-   Learning-by-doing với real-world problem

---

## Target Users

### Primary Users

**1. Nhân Viên Bán Hàng (Sales Staff) - "Minh"**

**Profile:**

-   **Vai trò**: Nhân viên bán hàng tại cửa hàng điện thoại
-   **Tuổi**: 24 tuổi, làm việc 2 năm trong ngành
-   **Môi trường**: Làm việc tại cửa hàng 8-10 giờ/ngày, tiếp xúc 20-30 khách/ngày
-   **Mục tiêu**: Bán được nhiều điện thoại, đạt target doanh số, nhận hoa hồng cao

**Problem Experience:**

-   Phải nhập thông tin khách hàng thủ công nhiều lần → Mất 5-10 phút/đơn
-   Không biết tồn kho chính xác → Phải gọi kho hoặc check nhiều nơi
-   Khách hàng hỏi về specs, warranty → Phải tra cứu nhiều nguồn
-   POS hiện tại không có IMEI tracking → Phải ghi tay vào sổ
-   Quy trình trade-in phức tạp → Mất thời gian định giá và check máy cũ

**Success Vision:**

-   Checkout nhanh < 5 phút với IMEI tự động
-   Check tồn kho real-time trên mobile
-   Tra cứu specs và warranty ngay trên POS
-   Trade-in process đơn giản với tool định giá tự động
-   Tư vấn khách hàng tốt hơn nhờ có purchase history

**2. Quản Lý Cửa Hàng (Store Manager) - "Lan"**

**Profile:**

-   **Vai trò**: Quản lý 1 cửa hàng với 5-8 nhân viên
-   **Tuổi**: 32 tuổi, 6 năm kinh nghiệm quản lý
-   **Môi trường**: Chịu trách nhiệm doanh thu, inventory, nhân sự
-   **Mục tiêu**: Tối ưu doanh thu, giảm shrinkage, quản lý team hiệu quả

**Problem Experience:**

-   Không có visibility về inventory real-time → Phải kiểm kho thủ công
-   Khó track performance của từng nhân viên → Không biết ai bán tốt
-   Stockouts hoặc overstocking → Mất doanh thu hoặc vốn bị ứ đọng
-   Báo cáo doanh thu phải làm thủ công → Mất nhiều giờ cuối ngày
-   Không biết sản phẩm nào bán chạy → Khó quyết định nhập hàng

**Success Vision:**

-   Dashboard real-time với doanh thu, tồn kho, performance nhân viên
-   Automated reports gửi email hàng ngày
-   Low stock alerts để nhập hàng kịp thời
-   Phân tích sản phẩm bán chạy để optimize inventory
-   Quản lý nhân viên với sales tracking và commission tự động

**3. Khách Hàng (End Customer) - "Hùng"**

**Profile:**

-   **Vai trò**: Khách hàng mua điện thoại
-   **Tuổi**: 28 tuổi, Millennial, tech-savvy
-   **Môi trường**: Làm việc văn phòng, thu nhập 15-20 triệu/tháng
-   **Mục tiêu**: Mua điện thoại chính hãng với giá tốt, trải nghiệm mượt mà

**Problem Experience:**

-   Research online nhưng giá và tồn kho không đồng bộ với cửa hàng
-   Đến cửa hàng thì hết hàng hoặc giá khác với online
-   Phải điền nhiều giấy tờ khi mua → Mất thời gian
-   Lo ngại hàng giả, không biết cách check IMEI
-   Không có lịch sử mua hàng khi cần bảo hành

**Success Vision:**

-   Check giá và tồn kho online chính xác real-time
-   Đặt online, nhận tại cửa hàng gần nhất (BOPIS)
-   Checkout nhanh < 5 phút
-   Nhận SMS/email xác nhận với IMEI và warranty info
-   Tra cứu lịch sử mua hàng và warranty status online

### Secondary Users

**4. Admin/Chủ Cửa Hàng (Business Owner) - "Anh Tuấn"**

**Profile:**

-   **Vai trò**: Chủ chuỗi 3 cửa hàng điện thoại
-   **Tuổi**: 40 tuổi, 10 năm kinh doanh
-   **Môi trường**: Quản lý toàn bộ operations, chiến lược kinh doanh
-   **Mục tiêu**: Tăng trưởng doanh thu, mở rộng chuỗi, tối ưu lợi nhuận

**Problem Experience:**

-   Không có visibility về toàn bộ chuỗi → Phải đi từng cửa hàng
-   Khó so sánh performance giữa các cửa hàng
-   Inventory không đồng bộ → Một cửa hàng thừa, một thiếu
-   Không có insights để quyết định chiến lược
-   Chi phí vận hành cao do quy trình thủ công

**Success Vision:**

-   Dashboard tổng quan toàn chuỗi (doanh thu, inventory, performance)
-   So sánh performance giữa các cửa hàng
-   Stock transfer dễ dàng giữa các cửa hàng
-   Predictive analytics để forecast demand
-   Giảm chi phí vận hành 20-30% nhờ automation

**5. Nhân Viên Kho (Warehouse Staff) - "Nam"**

**Profile:**

-   **Vai trò**: Quản lý kho, nhập xuất hàng
-   **Tuổi**: 26 tuổi, 3 năm kinh nghiệm
-   **Môi trường**: Làm việc tại kho, xử lý 50-100 giao dịch nhập xuất/ngày
-   **Mục tiêu**: Nhập xuất chính xác, không sai sót, nhanh chóng

**Problem Experience:**

-   Phải ghi IMEI thủ công khi nhập hàng → Dễ sai sót
-   Không biết cửa hàng nào cần hàng gấp → Ưu tiên sai
-   Kiểm kho mất nhiều thời gian → Phải đếm từng máy
-   Không có cảnh báo hàng sắp hết → Stockouts
-   Transfer hàng giữa cửa hàng phức tạp → Nhiều giấy tờ

**Success Vision:**

-   Scan IMEI tự động khi nhập hàng
-   Nhận alerts từ cửa hàng khi cần stock transfer
-   Cycle counting với barcode scanner
-   Automated reorder points
-   Stock transfer workflow đơn giản với tracking

### User Journey

**Journey 1: Khách Hàng Mua Điện Thoại (ROPO Model)**

**Discovery (Online):**

1. Hùng search "iPhone 15 Pro giá tốt" trên Google
2. Tìm thấy website Tact, xem specs và giá
3. So sánh với TGDĐ, FPT Shop
4. Đọc reviews từ khách hàng khác
5. Check tồn kho tại cửa hàng gần nhất → **Có sẵn!**

**Decision:** 6. Quyết định đến cửa hàng để trải nghiệm trực tiếp 7. Đặt trước online để reserve (optional)

**Purchase (Offline):** 8. Đến cửa hàng, Minh (sales) chào đón 9. Trải nghiệm iPhone 15 Pro thực tế 10. Minh tra cứu purchase history của Hùng → Khách hàng thân thiết 11. Offer trade-in iPhone 12 cũ → Định giá tự động: 8 triệu 12. Hùng đồng ý, checkout nhanh < 5 phút: - Scan IMEI iPhone 15 Pro mới - Scan IMEI iPhone 12 cũ (check blacklist ✓) - Thanh toán: 22 triệu - 8 triệu = 14 triệu - Tích điểm loyalty: +140 điểm 13. Nhận SMS xác nhận với IMEI và warranty info 14. Warranty tự động activate 12 tháng

**Post-Purchase:** 15. Hùng nhận email survey sau 1 tuần 16. Chia sẻ trải nghiệm tích cực trên Facebook 17. Sau 6 tháng, nhận offer accessories (case, sạc) qua app 18. Khi cần bảo hành, tra cứu warranty status online

**Journey 2: Manager Quản Lý Hàng Ngày**

**Morning (8:00 AM):**

1. Lan mở app Tact trên điện thoại
2. Xem dashboard: Doanh thu hôm qua, tồn kho hiện tại
3. Nhận alert: "iPhone 15 Pro 256GB còn 2 máy" → Tạo purchase order

**During Day:** 4. Theo dõi sales real-time trên dashboard 5. Nhận notification: Minh vừa bán được iPhone 15 Pro Max → +1 điểm KPI 6. Check performance team: Minh leading với 5 máy/ngày

**Afternoon:** 7. Khách hàng yêu cầu màu Blue không có → Check stock cửa hàng khác 8. Cửa hàng 2 có sẵn → Tạo stock transfer request 9. Nam (warehouse) nhận request, chuẩn bị giao hàng

**Evening (6:00 PM):** 10. Xem báo cáo doanh thu tự động: 50 triệu hôm nay 11. Top sản phẩm: iPhone 15 Pro (8 máy), Galaxy S24 (5 máy) 12. Nhận email báo cáo chi tiết gửi về 13. Plan cho ngày mai: Nhập thêm iPhone 15 Pro

**Journey 3: Admin Quản Lý Toàn Chuỗi**

**Weekly Review (Monday Morning):**

1. Anh Tuấn login vào admin dashboard
2. Xem performance 3 cửa hàng:
    - Store 1: 300 triệu/tuần (↑ 15%)
    - Store 2: 250 triệu/tuần (↓ 5%)
    - Store 3: 200 triệu/tuần (→ 0%)
3. Phân tích: Store 2 giảm do thiếu iPhone 15 Pro
4. Quyết định: Transfer 10 máy từ Store 1 sang Store 2

**Monthly Planning:** 5. Xem báo cáo tháng: Doanh thu 3 tỷ, lợi nhuận 15% 6. Top products: iPhone chiếm 60%, Samsung 30%, Xiaomi 10% 7. Inventory turnover: 8x/năm → Tốt 8. Shrinkage rate: 0.5% → Excellent (nhờ IMEI tracking) 9. Plan Q1 năm sau: Mở thêm 2 cửa hàng mới

---

## Success Metrics

### User Success Metrics

**1. Sales Staff Success (Minh)**

**Efficiency Metrics:**

-   **Checkout Time**: < 5 phút per transaction (vs 10-15 phút hiện tại)
    -   Measurement: Average time from product selection to receipt printing
    -   Target: 80% transactions < 5 phút trong tháng đầu
-   **IMEI Accuracy**: 100% IMEI được scan và record chính xác
    -   Measurement: % transactions có IMEI recorded vs total transactions
    -   Target: 100% compliance sau 2 tuần training
-   **Stock Lookup Speed**: < 10 giây để check tồn kho multi-location
    -   Measurement: Average time to get stock availability results
    -   Target: Real-time response < 10 giây

**Productivity Metrics:**

-   **Sales per Hour**: Tăng 30% nhờ quy trình nhanh hơn
    -   Baseline: 2-3 transactions/hour
    -   Target: 3-4 transactions/hour
-   **Customer Satisfaction**: NPS score từ customers served
    -   Target: NPS > 50 (industry benchmark: 30-40)

**2. Store Manager Success (Lan)**

**Inventory Management:**

-   **Inventory Accuracy**: 95%+ (vs 80-85% hiện tại)
    -   Measurement: Physical count vs system count
    -   Target: 95% accuracy trong cycle counts
-   **Shrinkage Rate**: < 1% (vs 2-3% industry average)
    -   Measurement: (Recorded inventory - Actual inventory) / Recorded inventory
    -   Target: < 1% annually
-   **Stockout Rate**: < 5% cho A items (fast-moving products)
    -   Measurement: % SKUs out of stock / Total SKUs
    -   Target: < 5% for flagship phones

**Operational Efficiency:**

-   **Report Generation Time**: < 5 phút (vs 30-60 phút thủ công)
    -   Measurement: Time to generate daily/weekly reports
    -   Target: Automated reports delivered via email
-   **Stock Transfer Time**: < 2 giờ from request to fulfillment
    -   Measurement: Time from transfer request to goods received
    -   Target: Same-day transfers within city

**Team Performance:**

-   **Sales per Employee**: Track và improve individual performance
    -   Measurement: Revenue per employee per day/week/month
    -   Target: 15% increase in 6 months

**3. Customer Success (Hùng)**

**Experience Metrics:**

-   **Purchase Completion Rate**: 90%+ (cart to checkout)
    -   Measurement: % customers who complete purchase after adding to cart
    -   Target: 90% online, 95% in-store
-   **BOPIS Adoption**: 20% online orders choose pickup
    -   Measurement: % online orders with pickup option selected
    -   Target: 20% trong 3 tháng đầu
-   **Checkout Satisfaction**: 4.5/5 stars
    -   Measurement: Post-purchase survey rating
    -   Target: 4.5/5 average rating

**Trust & Transparency:**

-   **IMEI Verification Rate**: 100% customers receive IMEI on receipt
    -   Measurement: % receipts with IMEI printed
    -   Target: 100% compliance
-   **Warranty Activation Rate**: 100% automatic activation
    -   Measurement: % sales with warranty auto-activated
    -   Target: 100% for eligible products

**Loyalty & Retention:**

-   **Repeat Purchase Rate**: 30% customers return within 12 months
    -   Measurement: % customers with 2+ purchases
    -   Target: 30% repeat rate (industry: 20-25%)
-   **Loyalty Program Enrollment**: 60% customers join
    -   Measurement: % customers enrolled in loyalty program
    -   Target: 60% enrollment rate

**4. Business Owner Success (Anh Tuấn)**

**Financial Performance:**

-   **Revenue Growth**: 20% YoY increase
    -   Measurement: Total revenue vs previous year
    -   Target: 20% growth in year 1
-   **Gross Margin**: Maintain 18-20% (industry benchmark)
    -   Measurement: (Revenue - COGS) / Revenue
    -   Target: 18-20% gross margin
-   **Operating Cost Reduction**: 15% reduction
    -   Measurement: Operating expenses vs revenue
    -   Target: 15% reduction through automation

**Inventory Optimization:**

-   **Inventory Turnover**: 8-10x per year
    -   Measurement: COGS / Average Inventory Value
    -   Target: 8-10x (industry benchmark: 6-12x)
-   **Days Sales of Inventory (DSI)**: 40-50 days
    -   Measurement: (Average Inventory / COGS) × 365
    -   Target: 40-50 days (industry: 30-60 days)
-   **Dead Stock Percentage**: < 5%
    -   Measurement: Dead stock value / Total inventory value
    -   Target: < 5%

**Multi-Location Efficiency:**

-   **Stock Transfer Optimization**: 30% reduction in inter-store transfers
    -   Measurement: Number of transfers vs sales volume
    -   Target: Optimize stock allocation to reduce unnecessary transfers

---

### Business Objectives

**Phase 1: MVP Launch (Tháng 1-3)**

**Primary Objectives:**

1. **System Stability**: 99% uptime
    - Critical: POS must be reliable for daily operations
    - Target: < 1% downtime, < 5 phút recovery time
2. **User Adoption**: 100% staff trained và sử dụng system
    - Target: All staff comfortable với core features trong 2 tuần
3. **Data Migration**: 100% existing data migrated accurately
    - Products, customers, inventory, IMEI records
4. **Core Features Functional**: IMEI tracking, POS, inventory, basic reporting
    - All critical workflows working smoothly

**Success Criteria:**

-   ✅ Zero critical bugs blocking daily operations
-   ✅ Staff can complete all core tasks without manual workarounds
-   ✅ Inventory accuracy > 90% (improving to 95% in Phase 2)

**Phase 2: Optimization (Tháng 4-6)**

**Primary Objectives:**

1. **O2O Integration**: Website + POS fully synced
    - Real-time inventory sync
    - BOPIS functional
    - Unified customer profiles
2. **Performance Optimization**: Inventory accuracy 95%+, shrinkage < 1%
3. **Advanced Features**: Warranty management, trade-in, loyalty program
4. **User Satisfaction**: NPS > 50

**Success Criteria:**

-   ✅ 20% online orders choose BOPIS
-   ✅ Inventory accuracy 95%+
-   ✅ Customer satisfaction 4.5/5 stars

**Phase 3: Scale & Growth (Tháng 7-12)**

**Primary Objectives:**

1. **Business Growth**: 20% revenue increase
2. **Market Expansion**: Ready to onboard new stores
3. **Competitive Advantage**: Differentiated O2O experience
4. **Data-Driven Decisions**: Predictive analytics functional

**Success Criteria:**

-   ✅ 20% revenue growth YoY
-   ✅ 30% repeat customer rate
-   ✅ System can scale to 5+ stores without performance degradation

---

### Key Performance Indicators (KPIs)

**Tier 1 KPIs (Critical - Track Daily)**

**Operational KPIs:**

1. **System Uptime**: 99%+
    - Measurement: % time system is operational
    - Alert: < 99% triggers immediate investigation
2. **Transaction Success Rate**: 99%+
    - Measurement: % transactions completed successfully
    - Alert: < 99% indicates system issues
3. **IMEI Tracking Compliance**: 100%
    - Measurement: % transactions with IMEI recorded
    - Alert: < 100% indicates training or process issues

**Sales KPIs:** 4. **Daily Revenue**: Track vs target

-   Measurement: Total revenue per day
-   Target: Based on historical data + 20% growth

5. **Average Transaction Value**: 15-20 triệu VNĐ
    - Measurement: Total revenue / Number of transactions
    - Target: Increase through upselling accessories

**Inventory KPIs:** 6. **Stock Accuracy**: 95%+

-   Measurement: Daily cycle counts for A items
-   Alert: < 95% triggers full audit

7. **Stockout Rate**: < 5% for A items
    - Measurement: % SKUs out of stock
    - Alert: Stockout of flagship phones triggers immediate action

**Tier 2 KPIs (Important - Track Weekly)**

**Customer Experience:** 8. **Customer Satisfaction (CSAT)**: 4.5/5

-   Measurement: Post-purchase survey
-   Target: 4.5/5 average rating

9. **Net Promoter Score (NPS)**: > 50
    - Measurement: "How likely to recommend?" (0-10 scale)
    - Target: NPS > 50 (Promoters - Detractors)
10. **BOPIS Adoption Rate**: 20%
    - Measurement: % online orders with pickup
    - Target: 20% trong 3 tháng

**Operational Efficiency:** 11. **Average Checkout Time**: < 5 phút - Measurement: Time from product selection to receipt - Target: 80% transactions < 5 phút 12. **Inventory Turnover**: 8-10x/year - Measurement: COGS / Average Inventory - Target: 8-10x annually

**Tier 3 KPIs (Strategic - Track Monthly)**

**Business Growth:** 13. **Revenue Growth**: 20% YoY - Measurement: Monthly revenue vs same month last year - Target: 20% growth 14. **Gross Margin**: 18-20% - Measurement: (Revenue - COGS) / Revenue - Target: Maintain 18-20% 15. **Customer Lifetime Value (CLV)**: Increase 25% - Measurement: Average revenue per customer over 12 months - Target: 25% increase through loyalty program

**Market Position:** 16. **Market Share**: Track vs competitors - Measurement: Estimated market share in target area - Target: Gain 2-3% market share in year 1 17. **Repeat Purchase Rate**: 30% - Measurement: % customers with 2+ purchases - Target: 30% repeat rate

**Innovation & Learning:** 18. **Feature Adoption Rate**: 80% - Measurement: % users actively using new features - Target: 80% adoption within 1 month of launch 19. **Staff Training Completion**: 100% - Measurement: % staff completed training modules - Target: 100% completion within 2 weeks

---

## MVP Scope

### Core Features (Must-Have for MVP)

**Phase 1: Foundation (Buổi 1-2) - Authentication & Database**

**1. Authentication System**

-   ✅ **User Registration & Login**: Email/password authentication
-   ✅ **Google OAuth Integration**: Social login với Google
-   ✅ **Password Management**: Set password lần đầu cho Google users
-   ✅ **Role-Based Access Control**: 4 roles (Admin, Manager, Sales, Warehouse)
-   ✅ **Session Management**: Secure session handling
-   **Rationale**: Foundation cho toàn bộ system, security critical

**2. Database Setup**

-   ✅ **12 Tables Migration**: All tables với relationships
-   ✅ **Seeders**: Master data (roles, categories, brands)
-   ✅ **Foreign Keys & Indexes**: Performance optimization
-   **Rationale**: Data structure là backbone của system

**Phase 2: Core CRUD (Buổi 3-4) - Essential Modules**

**3. Product Management (Critical)**

-   ✅ **Products CRUD**: Create, Read, Update, Delete products
-   ✅ **IMEI Tracking**: Scan và record IMEI per product
-   ✅ **Product Specs**: Store technical specifications
-   ✅ **Image Upload**: Product images (1 main image MVP)
-   ✅ **SKU/Barcode**: Unique identifiers
-   ✅ **Soft Delete**: Status-based (active/inactive)
-   **Rationale**: Core inventory, IMEI tracking là differentiator

**4. Categories & Brands CRUD**

-   ✅ **Categories Management**: Điện thoại, Phụ kiện, etc.
-   ✅ **Brands Management**: Apple, Samsung, Xiaomi, etc.
-   **Rationale**: Product organization essential

**5. Suppliers CRUD**

-   ✅ **Supplier Management**: Tên, MST, Contact info
-   **Rationale**: Needed for stock movements

**6. Customers CRUD**

-   ✅ **Customer Profiles**: Name, email, phone, address
-   ✅ **Google ID Support**: Link Google accounts
-   ✅ **Points System**: Basic loyalty points tracking
-   **Rationale**: Customer data cho orders và loyalty

**Phase 3: POS & Orders (Buổi 5-6) - Transaction Core**

**7. POS System (Critical)**

-   ✅ **Product Search**: Quick search by name, SKU, barcode
-   ✅ **IMEI Scanning**: Scan IMEI khi bán
-   ✅ **Cart Management**: Add, remove, update quantity
-   ✅ **Customer Lookup**: Find customer by phone
-   ✅ **Quick Customer Create**: Tạo customer nhanh nếu chưa có
-   ✅ **Payment Processing**: Cash, card, transfer
-   ✅ **Receipt Generation**: Print receipt với IMEI
-   ✅ **Order Status**: Completed immediately for POS
-   **Rationale**: Core business operation, must work flawlessly

**8. Order Management**

-   ✅ **Orders CRUD**: Create, view, update orders
-   ✅ **Order Items**: Link products với IMEI
-   ✅ **Order Source**: Web vs Store (POS)
-   ✅ **Payment Status**: Unpaid, Paid
-   ✅ **Order Status**: Pending, Confirmed, Completed, Cancelled
-   ✅ **Basic Order Flow**: Pending → Confirmed → Completed
-   **Rationale**: Track all transactions, support both online và offline

**9. Stock Movements (Basic)**

-   ✅ **Stock In**: Nhập hàng từ supplier
-   ✅ **Stock Out**: Xuất hàng (auto khi order)
-   ✅ **IMEI Recording**: Track IMEI per movement
-   ✅ **Quantity Update**: Auto ± product quantity
-   **Rationale**: Inventory accuracy critical

**Phase 4: Admin Dashboard & Reports (Buổi 7) - Management Tools**

**10. Admin Dashboard**

-   ✅ **Summary Cards**: Doanh thu, đơn hàng, sản phẩm sắp hết, khách mới
-   ✅ **Revenue Chart**: Bar chart doanh thu theo ngày/tháng (Chart.js)
-   ✅ **Recent Orders**: List đơn hàng mới nhất
-   ✅ **Low Stock Alerts**: Sản phẩm < 5 quantity
-   **Rationale**: Visibility cho managers

**11. Basic Reports**

-   ✅ **Sales Report**: Doanh thu theo ngày/tháng
-   ✅ **Product Report**: Top bán chạy, sắp hết hàng
-   ✅ **Inventory Report**: Tồn kho hiện tại
-   **Rationale**: Decision-making data

**12. User Management (Admin only)**

-   ✅ **Users CRUD**: Manage staff accounts
-   ✅ **Role Assignment**: Assign roles to users
-   ✅ **Status Management**: Active/Inactive users
-   **Rationale**: Team management

**Phase 5: Customer Front-End (Buổi 8) - Basic E-Commerce**

**13. Customer Website (Basic)**

-   ✅ **Home Page**: Banner, featured products, categories
-   ✅ **Product List**: Grid view với filter (category, brand, price)
-   ✅ **Product Detail**: Specs, price, stock availability, add to cart
-   ✅ **Shopping Cart**: View cart, update quantity, remove items
-   ✅ **Checkout**: Basic checkout form (name, phone, address, payment method)
-   ✅ **Order Confirmation**: Thank you page với order details
-   ✅ **My Account**: View profile, order history
-   **Rationale**: Basic O2O presence, customers can browse và order

**14. Responsive UI**

-   ✅ **Tailwind CSS 4 + DaisyUI 5**: Modern, mobile-responsive
-   ✅ **Mobile-First Design**: Works on all devices
-   **Rationale**: User experience critical

---

### Out of Scope for MVP (Future Enhancements)

**Deferred to Post-MVP (After 8 buổi):**

**1. Advanced O2O Features**

-   ❌ **BOPIS (Buy Online, Pick Up In Store)**: Requires complex inventory reservation
-   ❌ **Real-Time Inventory Sync**: MVP will have basic sync, not real-time
-   ❌ **Stock Check at Multiple Locations**: MVP single location focus
-   ❌ **Reserve Online**: Requires reservation system
-   **Rationale**: Complex, can add after MVP validation

**2. Advanced Inventory Management**

-   ❌ **Multi-Location Inventory**: MVP single location
-   ❌ **Stock Transfers**: Between stores
-   ❌ **Automated Reorder Points**: AI-driven forecasting
-   ❌ **Cycle Counting Tools**: Advanced audit features
-   **Rationale**: Complexity, can scale later

**3. Advanced POS Features**

-   ❌ **Trade-In Management**: Device valuation, IMEI blacklist check
-   ❌ **Repair Tracking**: Repair tickets, spare parts
-   ❌ **Financing Integration**: EMI calculator, financer management
-   ❌ **Mobile POS**: Tablet-based selling
-   **Rationale**: Nice-to-have, not essential for MVP

**4. Advanced Customer Features**

-   ❌ **Loyalty Program**: Advanced tiers, rewards redemption
-   ❌ **Personalized Recommendations**: AI-powered suggestions
-   ❌ **Wishlist**: Save products for later
-   ❌ **Product Reviews**: Customer reviews và ratings
-   ❌ **Live Chat**: Customer support chat
-   **Rationale**: Engagement features, add after core works

**5. Advanced Warranty Management**

-   ❌ **Warranty Activation**: Auto-activation on sale
-   ❌ **Warranty Claims**: Claim submission và tracking
-   ❌ **Warranty Expiry Alerts**: Notifications before expiry
-   **Rationale**: Can track manually in MVP, automate later

**6. Advanced Promotions**

-   ❌ **Promotion Engine**: Complex discount rules
-   ❌ **Coupon Codes**: Validation và usage limits
-   ❌ **Bundle Deals**: Product bundles
-   **Rationale**: Manual discounts sufficient for MVP

**7. Advanced Analytics**

-   ❌ **Predictive Analytics**: Demand forecasting
-   ❌ **Customer Segmentation**: AI-powered segments
-   ❌ **A/B Testing**: Feature testing
-   ❌ **Heatmaps**: User behavior tracking
-   **Rationale**: Data-driven features, need data first

**8. Integration & Automation**

-   ❌ **Accounting Integration**: QuickBooks, Xero
-   ❌ **Shipping Integration**: GHN, GHTK, Viettel Post
-   ❌ **Payment Gateway**: VNPay, MoMo, ZaloPay (MVP: manual payment)
-   ❌ **Email Marketing**: Automated campaigns
-   ❌ **SMS Notifications**: Order updates via SMS
-   **Rationale**: Integrations add complexity, manual process OK for MVP

**9. Advanced Security**

-   ❌ **Two-Factor Authentication (2FA)**: Extra security layer
-   ❌ **Audit Logs**: Detailed activity tracking
-   ❌ **Data Encryption**: Advanced encryption
-   **Rationale**: Basic security sufficient for MVP, enhance later

**10. Mobile Apps**

-   ❌ **iOS App**: Native mobile app
-   ❌ **Android App**: Native mobile app
-   **Rationale**: Responsive web sufficient for MVP

---

### MVP Success Criteria

**Technical Success Criteria:**

1. **System Stability**

    - ✅ 99% uptime during business hours
    - ✅ < 2 seconds page load time
    - ✅ Zero critical bugs blocking daily operations
    - ✅ All 12 CRUD modules functional

2. **IMEI Tracking Compliance**

    - ✅ 100% transactions have IMEI recorded
    - ✅ IMEI scanning works reliably
    - ✅ IMEI printed on all receipts

3. **Data Integrity**
    - ✅ Inventory accuracy > 90% (target 95% post-MVP)
    - ✅ Zero data loss incidents
    - ✅ Successful data migration from existing system (if applicable)

**User Adoption Criteria:**

4. **Staff Adoption**

    - ✅ 100% staff trained within 2 weeks
    - ✅ Staff can complete all core tasks without manual workarounds
    - ✅ < 5 support tickets per week after training period

5. **Customer Adoption**
    - ✅ 50+ online orders in first month
    - ✅ 80%+ checkout completion rate
    - ✅ Customer satisfaction 4.0/5 stars minimum

**Business Validation Criteria:**

6. **Operational Efficiency**

    - ✅ Checkout time < 5 phút (vs 10-15 phút manual)
    - ✅ Report generation < 5 phút (vs 30-60 phút manual)
    - ✅ Zero stockouts of flagship phones (A items)

7. **Business Impact**
    - ✅ Maintain current revenue (no drop due to system change)
    - ✅ 10% reduction in operational time spent on manual tasks
    - ✅ Positive ROI projection within 6 months

**Go/No-Go Decision Points:**

**After 1 Month:**

-   ✅ System stable với < 5 critical bugs
-   ✅ Staff comfortable using system
-   ✅ Inventory accuracy > 90%
-   **Decision**: Continue to Phase 2 optimization OR fix critical issues

**After 3 Months:**

-   ✅ Customer adoption growing (100+ online orders/month)
-   ✅ Inventory accuracy 95%+
-   ✅ Staff productivity improved 15%+
-   **Decision**: Invest in advanced features OR pivot strategy

---

### Future Vision (Post-MVP Roadmap)

**Version 2.0 (Tháng 4-6): O2O Optimization**

**Focus: Seamless Online-Offline Integration**

1. **BOPIS Implementation**

    - Buy Online, Pick Up In Store
    - Inventory reservation system
    - SMS notifications when order ready
    - Dedicated pickup counter workflow

2. **Real-Time Inventory Sync**

    - WebSocket-based real-time updates
    - Stock visibility across all channels
    - Prevent overselling

3. **Advanced Warranty Management**

    - Auto-activation on sale
    - Warranty claim submission
    - Expiry alerts và reminders

4. **Trade-In System**

    - Device valuation tool
    - IMEI blacklist check
    - Trade-in credit application

5. **Loyalty Program Enhancement**
    - Tiered membership (Silver, Gold, Platinum)
    - Points redemption
    - Exclusive member offers

**Version 3.0 (Tháng 7-12): Multi-Location & Scale**

**Focus: Scale to Multiple Stores**

1. **Multi-Location Inventory**

    - Unified inventory pool
    - Stock transfers between stores
    - Location-based stock allocation

2. **Advanced Analytics**

    - Predictive demand forecasting
    - Customer segmentation
    - Sales performance by location
    - Inventory optimization recommendations

3. **Mobile POS**

    - Tablet-based POS for floor sales
    - Line busting during peak hours
    - Curbside pickup support

4. **Repair Services Module**

    - Repair ticket management
    - Spare parts inventory
    - Repair status tracking
    - Customer notifications

5. **Integration Ecosystem**
    - Payment gateways (VNPay, MoMo, ZaloPay)
    - Shipping partners (GHN, GHTK)
    - Accounting software (QuickBooks, Xero)
    - Email marketing (Mailchimp)

**Version 4.0 (Năm 2+): Platform & Ecosystem**

**Focus: Become Phone Retail Platform**

1. **B2B2C Platform**

    - White-label solution cho cửa hàng nhỏ
    - Centralized inventory management
    - Shared customer database
    - Commission-based revenue model

2. **AI-Powered Features**

    - Personalized product recommendations
    - Dynamic pricing optimization
    - Chatbot customer support
    - Fraud detection

3. **Marketplace Integration**

    - Shopee, Lazada, Tiki integration
    - Unified order management
    - Multi-channel inventory sync

4. **Advanced Customer Experience**

    - AR product visualization
    - Virtual try-on (for accessories)
    - Live shopping events
    - Social commerce integration

5. **Data & Insights Platform**
    - Market intelligence dashboard
    - Competitor pricing tracking
    - Customer behavior analytics
    - Predictive business insights

**Long-Term Vision (3-5 Years):**

**Become the #1 O2O Phone Retail Platform in Vietnam**

-   **Market Position**: Top 3 phone retail management system
-   **Scale**: 100+ stores using Tact platform
-   **Revenue Model**: SaaS subscription + transaction fees
-   **Differentiation**: Best-in-class O2O experience với IMEI tracking
-   **Expansion**: Southeast Asia markets (Thailand, Indonesia, Philippines)

---
