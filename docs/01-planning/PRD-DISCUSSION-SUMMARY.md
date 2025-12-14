# TỔNG HỢP THẢO LUẬN PRD - DỰ ÁN TACT

**Ngày:** 2025-12-14  
**User:** TomiSakae  
**Trạng thái:** Đã hoàn thành Step 1-3, chuẩn bị tiếp tục Step 4-11

---

## 🎯 TỔNG QUAN DỰ ÁN ĐÃ CHỐT

### **Tên dự án:** Tact - Website Quản lý Cửa hàng Điện thoại O2O

### **Mô tả ngắn gọn:**

Web application (KHÔNG phải hệ thống phức tạp) quản lý cửa hàng điện thoại với mô hình O2O, cho phép:

-   Khách hàng: Mua sắm online
-   Nhân viên: Quản lý toàn bộ hoạt động + Bán hàng tại quầy (POS)

### **Tech Stack:**

-   Laravel 12
-   Tailwind CSS 4
-   DaisyUI 5
-   MySQL (12 bảng - CỐ ĐỊNH, không thêm bảng)
-   Timeline: 8 buổi học (đồ án môn học)

### **Context quan trọng:**

-   Đây là ĐỒ ÁN MÔN HỌC, không phải enterprise system
-   12 bảng database ĐÃ CỐ ĐỊNH, không được thêm bảng mới
-   Timeline 8 buổi → Phải chọn features hợp lý

---

## 📋 CÁC QUYẾT ĐỊNH QUAN TRỌNG ĐÃ THẢO LUẬN

### **1. POS là gì?**

-   **KHÔNG phải** thiết bị phần cứng
-   **LÀ** một trang web đặc biệt trong back-end (`/admin/pos`)
-   Dành cho nhân viên Sales bán hàng tại quầy
-   Giao diện tối ưu: Tìm sản phẩm nhanh, tìm khách, áp dụng voucher/điểm, thanh toán ngay

### **2. IMEI Tracking**

-   **CÓ** làm IMEI tracking
-   **KHÔNG** cần bảng riêng
-   Lưu trong `order_items.imei_list` (TEXT hoặc JSON)
-   Ví dụ: `"123456789012345,123456789012346"` cho 2 máy

### **3. Voucher & Điểm tích lũy**

-   **Voucher (Promotions):**

    -   Fixed: Giảm số tiền cố định (VD: GIAM100K)
    -   Percent: Giảm % (VD: GIAM10)
    -   Có điều kiện: min_order, max_discount, usage_limit
    -   Dùng được cả online và POS

-   **Điểm tích lũy:**
    -   Tích: 100.000đ = 1 điểm (tự động khi order completed)
    -   Quy đổi: 1 điểm = 1.000đ giảm giá
    -   Dùng được cả online và POS
    -   Có thể kết hợp voucher + điểm

### **4. Timeline & Cancel Order**

-   **CÓ** làm timeline trạng thái đơn hàng:

    -   ✓ Đã đặt hàng (pending)
    -   ✓ Đã xác nhận (confirmed)
    -   🚚 Đang giao hàng (shipping)
    -   ✓ Hoàn thành (completed)
    -   ❌ Đã hủy (cancelled)

-   **CÓ** nút "Hủy đơn":
    -   Chỉ cho đơn pending
    -   Khách tự hủy được
    -   Nhân viên cũng hủy được (với lý do)

### **5. Quản lý kho**

-   **Option A - Đơn giản (ĐÃ CHỌN):**

    -   Nhập hàng (form nhập từ NCC)
    -   Xuất kho TỰ ĐỘNG khi bán hàng
    -   Lịch sử nhập/xuất
    -   **Safety Features:**
        -   Cảnh báo tồn kho thấp (< 5 cái)
        -   Sản phẩm chậm bán (> 30 ngày)
        -   Giá trị tồn kho (tổng tiền theo giá vốn)
        -   Xác nhận giao dịch giá trị cao (> 50M)

-   **KHÔNG làm:**
    -   Xuất kho thủ công phức tạp
    -   Kiểm kho định kỳ
    -   Multi-location inventory

### **6. Bảo hành**

-   **Option A - Thông tin only (ĐÃ CHỌN):**

    -   Hiển thị thời hạn bảo hành trên sản phẩm
    -   Tính ngày hết hạn (created_at + warranty_months)
    -   Hiển thị IMEI + bảo hành trong đơn hàng
    -   Khách tự check trạng thái bảo hành
    -   Hiển thị hotline/email liên hệ

-   **KHÔNG làm:**
    -   Module quản lý yêu cầu bảo hành
    -   Tạo phiếu bảo hành
    -   Theo dõi sửa chữa
    -   (Vì cần thêm bảng, mất 2-3 buổi)

---

## 🗄️ DATABASE (12 BẢNG - ĐÃ CỐ ĐỊNH)

```
1. roles - 4 quyền: Admin, Manager, Sales, Warehouse
2. users - Nhân viên
3. customers - Khách hàng (có google_id, password, points)
4. categories - Danh mục sản phẩm
5. brands - Thương hiệu
6. suppliers - Nhà cung cấp
7. products - Sản phẩm (có SKU, quantity, warranty_months, status)
8. product_specs - Thông số kỹ thuật
9. stock_movements - Nhập/xuất kho (type: in/out)
10. promotions - Voucher/Khuyến mãi
11. orders - Đơn hàng (source: web/store, order_status, payment_status)
12. order_items - Chi tiết đơn hàng (có imei_list)
```

**Triggers tự động:**

-   `update_stock`: Tự động ±quantity khi nhập/xuất kho
-   `add_points`: Tự động tích điểm khi đơn completed (100k = 1 điểm)

---

## 👥 NGƯỜI DÙNG & PHÂN QUYỀN

### **1. Khách hàng (Customer)**

-   Xem sản phẩm, đặt hàng online
-   Đăng ký/Đăng nhập (Email/Password + Google OAuth)
-   **Bắt buộc set password nếu login Google lần đầu**
-   Xem lịch sử đơn hàng với timeline
-   Hủy đơn hàng (nếu pending)
-   Sử dụng voucher và điểm tích lũy
-   Xem điểm tích lũy hiện có

### **2. Sales (Nhân viên bán hàng)**

-   Bán hàng tại quầy qua POS interface
-   Xử lý đơn hàng online (duyệt, giao, hoàn thành)
-   Áp dụng voucher/điểm cho khách
-   Xem sản phẩm, khách hàng

### **3. Warehouse (Nhân viên kho)**

-   Nhập hàng từ nhà cung cấp
-   Xem lịch sử nhập/xuất kho
-   Xem cảnh báo tồn kho
-   Xem giá trị tồn kho

### **4. Manager (Quản lý)**

-   Quản lý sản phẩm, đơn hàng, khách hàng
-   Xem báo cáo, thống kê
-   Quản lý voucher/khuyến mãi
-   Tất cả trừ quản lý users

### **5. Admin**

-   Toàn quyền quản trị hệ thống
-   Quản lý nhân viên, phân quyền
-   Quản lý toàn bộ modules

---

## 🎨 FRONT-END (KHÁCH HÀNG) - 8 TRANG

### **1. Trang chủ (Home)**

-   Banner slider
-   Sản phẩm nổi bật
-   Danh mục, thương hiệu

### **2. Danh sách sản phẩm (Product List)**

-   Grid 3-4 cột, responsive
-   Filter: Giá, hãng, danh mục
-   Sort: Giá, mới, bán chạy
-   Phân trang

### **3. Chi tiết sản phẩm (Product Detail)**

-   Ảnh lớn + thumbnails
-   Tên, giá, SKU, tồn kho
-   Thông số kỹ thuật đầy đủ
-   **Bảo hành: 12 tháng chính hãng**
-   Nút "Thêm giỏ hàng"

### **4. Giỏ hàng (Cart)**

-   Danh sách sản phẩm
-   Tăng/giảm số lượng, xóa
-   Tổng tiền
-   Nút "Thanh toán"

### **5. Thanh toán (Checkout)**

-   Form thông tin giao hàng
-   **Nhập mã voucher** (áp dụng)
-   **Sử dụng điểm tích lũy** (1 điểm = 1.000đ)
-   Chọn phương thức thanh toán (COD/Chuyển khoản)
-   Hiển thị: Tạm tính, Giảm giá (voucher), Giảm giá (điểm), Tổng cộng
-   Xác nhận đặt hàng

### **6. Lịch sử đơn hàng (Order History)**

-   Danh sách đơn hàng
-   Filter theo trạng thái
-   **Timeline trạng thái đơn hàng** (visual)
-   **Nút "Hủy đơn"** (chỉ cho đơn pending)
-   Chi tiết đơn:
    -   Thông tin khách hàng
    -   Danh sách sản phẩm
    -   **IMEI của từng máy**
    -   **Thông tin bảo hành** (từ ngày, đến ngày, còn X ngày)
    -   Địa chỉ giao hàng
    -   Tổng tiền (có breakdown: subtotal, voucher, điểm, total)

### **7. Tài khoản (My Account)**

-   Thông tin cá nhân
-   **Điểm tích lũy hiện có**
-   Đổi mật khẩu

### **8. Đăng ký/Đăng nhập (Auth)**

-   Form đăng ký thường
-   Form đăng nhập thường
-   **Đăng nhập Google OAuth**
-   **Bắt buộc set password nếu login Google lần đầu**

---

## 🖥️ BACK-END (QUẢN LÝ)

### **Dashboard**

-   Cards thống kê: Doanh thu, đơn hàng, sản phẩm sắp hết, khách mới
-   Biểu đồ doanh thu (Chart.js)
-   **Cảnh báo tồn kho:**
    -   🔴 Sắp hết (< 5 cái)
    -   🟡 Tồn kho thấp (5-10 cái)
    -   🟢 Tồn kho tốt (> 10 cái)
-   **Sản phẩm chậm bán** (> 30 ngày không bán)
-   **Giá trị tồn kho** (tổng tiền theo giá vốn)
-   Đơn hàng cần xử lý

### **CRUD Modules (12 modules)**

Pattern chung: List View (DataTables) + Create/Edit Form + Validation

1. Roles
2. Users
3. Customers
4. Categories
5. Brands
6. Suppliers
7. Products (có upload ảnh, SKU unique, warranty_months)
8. Product Specs
9. Promotions (voucher)
10. Orders
11. Stock Movements
12. Reports

### **Quản lý đơn hàng (Orders Management)**

-   List: Mã đơn, khách, tiền, trạng thái, nguồn (Web/Store)
-   Detail: Info khách, sản phẩm (có IMEI), địa chỉ, timeline
-   **Actions:**
    -   Duyệt (pending → confirmed)
    -   Giao hàng (confirmed → shipping) - Nhập mã vận đơn
    -   Hoàn thành (shipping → completed)
    -   Hủy (→ cancelled)

### **Quản lý kho (Inventory Management)**

**1. Nhập hàng (Stock In):**

-   Chọn nhà cung cấp
-   Nhập mã phiếu nhập
-   Chọn sản phẩm + số lượng (nhiều sản phẩm)
-   Ghi chú
-   **Xác nhận giao dịch giá trị cao** (> 50M)
-   Lưu → Tạo stock_movements (type='in')
-   Trigger tự động tăng products.quantity

**2. Xuất kho tự động:**

-   Khi bán hàng (POS/online order completed)
-   Tự động tạo stock_movements (type='out')
-   Trigger tự động giảm products.quantity
-   Lưu IMEI vào order_items.imei_list

**3. Lịch sử nhập/xuất:**

-   Danh sách tất cả giao dịch
-   Filter: Loại, ngày, sản phẩm
-   Hiển thị: Ngày, loại, sản phẩm, SL, NCC, nhân viên, IMEI

**4. Cảnh báo & Báo cáo:**

-   Cảnh báo tồn kho thấp (Dashboard)
-   Sản phẩm chậm bán (Dashboard)
-   Giá trị tồn kho (Dashboard)
-   Top sản phẩm giá trị cao

### **🏪 POS Interface (Point of Sale)**

**URL:** `/admin/pos` hoặc `/sales/pos`

**Layout:**

```
┌─────────────────────────────────────────────────────┐
│  🏪 POS - Bán hàng tại quầy                        │
├─────────────────────────────────────────────────────┤
│  KHÁCH HÀNG: [Tìm SĐT] → Nguyễn Văn A - 500 điểm  │
│  [Hoặc tạo khách mới nhanh]                        │
├─────────────────────────────────────────────────────┤
│  TÌM SẢN PHẨM | GIỎ HÀNG                           │
│  [Tìm/Quét]   | - iPhone 15 Pro x1: 25M            │
│               | - Ốp lưng x1: 50K                   │
│               | Tạm tính: 25.050.000đ              │
├─────────────────────────────────────────────────────┤
│  💳 VOUCHER: [GIAM100K] → Giảm 100.000đ           │
│  ⭐ ĐIỂM: [250 điểm] → Giảm 250.000đ              │
│  TỔNG: 24.700.000đ                                 │
│  Thanh toán: ○ Tiền mặt ○ Thẻ ○ CK                │
│  [HOÀN TẤT & IN HÓA ĐƠN]                          │
└─────────────────────────────────────────────────────┘
```

**Quy trình:**

1. Tìm khách (SĐT) hoặc tạo mới
2. Tìm/quét sản phẩm → Thêm giỏ
3. Áp dụng voucher (optional)
4. Sử dụng điểm (optional)
5. Chọn thanh toán
6. Hoàn tất:
    - Tạo order: source='store', status='completed', payment_status='paid'
    - Lưu IMEI
    - Tự động xuất kho (trigger)
    - Trừ điểm đã dùng
    - Tích điểm mới (trigger)
    - In hóa đơn (optional)

### **Báo cáo & Thống kê (Reports)**

1. Báo cáo doanh thu (theo ngày/tuần/tháng, biểu đồ)
2. Báo cáo sản phẩm (top bán chạy, sắp hết, chậm bán, giá trị tồn kho)
3. Báo cáo khách hàng (mới, top khách, điểm tích lũy)
4. Báo cáo nhân viên (doanh số, số đơn)

---

## 🎁 HỆ THỐNG VOUCHER & ĐIỂM

### **Voucher (Promotions)**

-   **Loại:**
    -   Fixed: Giảm số tiền (VD: GIAM100K = -100.000đ)
    -   Percent: Giảm % (VD: GIAM10 = -10%)
-   **Điều kiện:**
    -   min_order: Đơn tối thiểu
    -   max_discount: Giảm tối đa
    -   start_date, end_date
    -   usage_limit: Giới hạn số lần dùng
-   **Sử dụng:** Online (khách nhập) + POS (nhân viên nhập)

### **Điểm tích lũy (Loyalty Points)**

-   **Tích:** 100.000đ = 1 điểm (tự động khi order completed)
-   **Quy đổi:** 1 điểm = 1.000đ giảm giá
-   **Ví dụ:**
    ```
    Đơn: 25.000.000đ → Tích 250 điểm
    Lần sau: Dùng 250 điểm = -250.000đ
    ```
-   **Kết hợp:** Có thể dùng cả voucher + điểm

### **Thứ tự áp dụng:**

1. Tính subtotal
2. Trừ voucher
3. Trừ điểm
4. = Tổng cuối

---

## 🎯 TÍNH NĂNG ĐẶC BIỆT

### **1. O2O (Online-to-Offline)**

-   Khách đặt online → `source='web'`, `status='pending'`
-   Nhân viên bán tại quầy → `source='store'`, `status='completed'`
-   Tích điểm thống nhất cả 2 kênh

### **2. IMEI Tracking**

-   Lưu trong `order_items.imei_list` (TEXT/JSON)
-   Không cần bảng riêng
-   Hiển thị trong chi tiết đơn hàng
-   Track được máy nào bán cho khách nào

### **3. Timeline & Cancel Order**

-   Timeline visual: pending → confirmed → shipping → completed
-   Khách tự hủy đơn pending
-   Nhân viên hủy với lý do

### **4. Safety Features (Quản lý kho)**

-   Cảnh báo tồn kho thấp (< 5)
-   Sản phẩm chậm bán (> 30 ngày)
-   Giá trị tồn kho
-   Xác nhận giao dịch cao (> 50M)

### **5. Triggers tự động**

-   Auto update stock khi nhập/xuất
-   Auto tích điểm khi order completed

### **6. Warranty Info**

-   Hiển thị thời hạn trên sản phẩm
-   Tính ngày hết hạn
-   Hiển thị IMEI + bảo hành trong đơn
-   Không quản lý yêu cầu bảo hành (để tiết kiệm thời gian)

---

## ✅ SCOPE CUỐI CÙNG - CHECKLIST

### **Must-Have (Bắt buộc - 8 buổi):**

-   ✅ Front-end: 8 trang
-   ✅ Back-end: CRUD 12 modules
-   ✅ POS Interface
-   ✅ Quản lý đơn hàng (duyệt, giao, hoàn thành, hủy)
-   ✅ Quản lý kho (nhập, lịch sử, cảnh báo)
-   ✅ Voucher system
-   ✅ Loyalty points (tích + sử dụng)
-   ✅ IMEI tracking (order_items.imei_list)
-   ✅ Google OAuth + Password
-   ✅ Timeline đơn hàng
-   ✅ Cancel order
-   ✅ Dashboard với charts
-   ✅ Báo cáo cơ bản
-   ✅ 4 roles phân quyền
-   ✅ Warranty info display

### **Nice-to-Have (Nếu còn thời gian):**

-   ⚠️ Export báo cáo Excel/PDF
-   ⚠️ Email notifications
-   ⚠️ In hóa đơn

### **Out of Scope (KHÔNG làm):**

-   ❌ Mobile app riêng
-   ❌ Hardware POS terminals
-   ❌ Multi-location inventory
-   ❌ Warranty claim management (cần thêm bảng)
-   ❌ Xuất kho thủ công phức tạp
-   ❌ Kiểm kho định kỳ
-   ❌ Payment gateway integration
-   ❌ Advanced analytics/BI

---

## 📊 SUCCESS CRITERIA ĐÃ ĐỊNH NGHĨA

### **User Success:**

-   Checkout < 5 phút
-   Tìm sản phẩm < 2 phút
-   IMEI hiển thị rõ ràng
-   Voucher/điểm không lỗi
-   Timeline trực quan
-   CSAT: 4.5/5, NPS > 50

### **Business Success:**

-   3 tháng: 99% uptime, 95% inventory accuracy
-   6 tháng: 20% BOPIS, 30% repeat rate, <1% shrinkage
-   12 tháng: 20% revenue growth, 8-10x inventory turnover

### **Technical Success:**

-   Page load < 2s
-   POS response < 1s
-   99% uptime
-   Zero data loss
-   Laravel best practices

---

## 🚀 IMPLEMENTATION TIMELINE (8 BUỔI)

**Week 1-2:** Setup (Database, Auth, Google OAuth)  
**Week 3-4:** Core CRUD (12 modules)  
**Week 5-6:** Advanced (POS, Voucher, Points, IMEI)  
**Week 7-8:** Polish (Dashboard, Reports, Timeline, Demo)

---

## 📝 GHI CHÚ QUAN TRỌNG CHO AI TIẾP TỤC

1. **12 bảng CỐ ĐỊNH** - Không được thêm bảng mới
2. **POS là trang web** - Không phải hardware
3. **IMEI trong order_items.imei_list** - Không cần bảng riêng
4. **Voucher + Điểm kết hợp được** - Thứ tự: subtotal → voucher → điểm → total
5. **Timeline + Cancel** - Đã chốt làm
6. **Bảo hành chỉ hiển thị** - Không quản lý yêu cầu bảo hành
7. **Quản lý kho đơn giản** - Có safety features, không có kiểm kho phức tạp
8. **Đồ án 8 buổi** - Phải realistic về scope

---

## 📄 FILES LIÊN QUAN

-   **PRD chính:** `docs/prd.md` (đã có Executive Summary, Classification, Success Criteria)
-   **Database:** `database/db.sql` (12 bảng + 2 triggers)
-   **Product Brief:** `docs/analysis/product-brief-Tact-2025-12-14.md`
-   **Research:** `docs/analysis/research/*.md` (4 files)
-   **Brainstorming:** `docs/analysis/brainstorming-session-2025-12-14.md`

---

## 🔄 TRẠNG THÁI WORKFLOW

**Đã hoàn thành:**

-   ✅ Step 1: Initialization
-   ✅ Step 2: Discovery & Classification
-   ✅ Step 3: Success Criteria

**Cần làm tiếp:**

-   ⏳ Step 4: User Journeys
-   ⏳ Step 5: Features & Requirements
-   ⏳ Step 6: Technical Architecture
-   ⏳ Step 7: Data Models
-   ⏳ Step 8: API Specifications
-   ⏳ Step 9: UI/UX Guidelines
-   ⏳ Step 10: Implementation Plan
-   ⏳ Step 11: Risks & Mitigation

---

**LƯU Ý:** File này là tổng hợp TẤT CẢ những gì đã thảo luận. Đọc kỹ trước khi tiếp tục workflow PRD!
