# DỰ ÁN: ORLIS — NỀN TẢNG THƯƠNG MẠI ĐIỆN TỬ THỜI TRANG CAO CẤP

---

## 1. TỔNG QUAN DỰ ÁN

Orlis là nền tảng thương mại điện tử chuyên về thời trang cao cấp (luxury fashion), được phát triển
theo mô hình full-stack với Laravel 13. Hệ thống phục vụ đồng thời nhiều nhóm người dùng khác nhau,
bao gồm khách hàng mua sắm, quản trị viên, nhân viên nội bộ, nhân viên giao hàng và nhà cung cấp.

---

## 2. CÔNG NGHỆ SỬ DỤNG (TECH STACK)

### Backend
- Ngôn ngữ lập trình : PHP 8.3+
- Framework         : Laravel 13.x (MVC Pattern)
- Xác thực          : Laravel Sanctum (API token), Laravel Socialite (OAuth2)
- Cơ sở dữ liệu     : MySQL 8.x

### Frontend
- Template engine   : Blade (server-side rendering)
- Build tool        : Vite 8.x
- CSS               : Vanilla CSS (Admin Panel) + Tailwind CSS 4.x (Client)
- JavaScript        : Vanilla JS ES2022+

### Tích hợp bên ngoài
- Cổng thanh toán   : VNPay
- Đăng nhập MXH     : Google OAuth2, Facebook OAuth2
- Email             : SMTP Gmail

---

## 3. CHỨC NĂNG CHÍNH

### 3.1 Giao diện khách hàng (Client)

- Duyệt sản phẩm theo danh mục, tìm kiếm và lọc theo màu sắc, kích thước, khoảng giá.
- Xem chi tiết sản phẩm: gallery ảnh, chọn biến thể (size/màu), hướng dẫn chọn size.
- Quản lý giỏ hàng và áp mã giảm giá (coupon).
- Thanh toán COD hoặc qua VNPay.
- Đăng ký, đăng nhập, đăng nhập bằng Google hoặc Facebook.
- Theo dõi lịch sử đơn hàng và trạng thái giao hàng.
- Danh sách yêu thích (wishlist).
- Đặt lịch hẹn tư vấn tại showroom.
- Gửi yêu cầu hỗ trợ qua hệ thống ticket (chatbox).
- Đọc bài viết tạp chí thời trang.
- Chương trình tích điểm và nâng hạng thành viên.

### 3.2 Trang quản trị (Admin Panel)

- Dashboard thống kê doanh thu, đơn hàng, khách hàng.
- Quản lý đơn hàng: xem danh sách, lọc đa chiều, xác nhận, chuyển trạng thái.
- Tạo đơn hàng trực tiếp (POS/Telesales): tìm khách hàng và sản phẩm, tính tiền tự động.
- Quản lý sản phẩm và biến thể (màu sắc × kích thước).
- Quản lý kho hàng theo từng showroom: nhập kho, điều chỉnh, điều chuyển hàng.
- Lập phiếu nhập hàng (Purchase Order) gửi nhà cung cấp.
- Quản lý lịch hẹn: phân công nhân viên, cập nhật trạng thái.
- Quản lý cửa hàng (showroom): địa chỉ, hotline, giờ hoạt động.
- Quản lý khách hàng: phân hạng thành viên, khóa/mở khóa tài khoản.
- Quản lý nhân sự nội bộ theo 8 vai trò.
- Xử lý yêu cầu hỗ trợ từ khách hàng qua hệ thống ticket.
- Quản lý banner quảng cáo, bài viết tạp chí, mã giảm giá.
- Cấu hình phương thức vận chuyển và cài đặt chung hệ thống.

### 3.3 Cổng dành riêng cho từng vai trò

- Shipper      : Xem đơn hàng được phân công, cập nhật trạng thái giao hàng.
- Nhân viên kho: Quản lý nhập/xuất kho, xác nhận phiếu nhập hàng.
- Nhà cung cấp : Xem và xác nhận Purchase Order.
- Nhân viên    : Xem đơn hàng, quản lý khách hàng, lịch hẹn.

---

## 4. PHÂN QUYỀN HỆ THỐNG

Hệ thống sử dụng phân quyền theo vai trò (Role-Based Access Control) với 8 loại vai trò:

  admin            — Quản trị viên toàn quyền
  manager          — Quản lý (toàn quyền, trừ quản lý nhân sự)
  staff            — Nhân viên cửa hàng
  customer_service — Chăm sóc khách hàng
  editor           — Biên tập viên nội dung
  warehouse_staff  — Nhân viên kho
  shipper          — Nhân viên giao hàng
  supplier         — Nhà cung cấp

---

## 5. HƯỚNG DẪN CÀI ĐẶT VÀ CHẠY THỬ

### Yêu cầu hệ thống

- PHP 8.3 trở lên
- Composer 2.x
- Node.js 18 trở lên
- MySQL 8.x

### Các bước thực hiện

Bước 1: Tải mã nguồn về máy.

    git clone https://github.com/MR-WlND/Orlis.git
    cd orlis

Bước 2: Cài đặt các thư viện.

    composer install
    npm install

Bước 3: Tạo file cấu hình môi trường.

    cp .env.example .env
    php artisan key:generate

Bước 4: Tạo database trong MySQL, sau đó cập nhật thông tin kết nối vào file .env:

    DB_CONNECTION=mysql
    DB_HOST=127.0.0.1
    DB_PORT=3306
    DB_DATABASE=orlis
    DB_USERNAME=root
    DB_PASSWORD=

Bước 5: Chạy migration để tạo các bảng trong database.

    php artisan migrate

Bước 6: (Tuỳ chọn) Nạp dữ liệu mẫu.

    php artisan db:seed

Bước 7: Tạo liên kết thư mục lưu file upload.

    php artisan storage:link

Bước 8: Build tài nguyên frontend và khởi động ứng dụng.

    npm run build
    php artisan serve

Ứng dụng sẽ chạy tại địa chỉ: http://127.0.0.1:8000

Để chạy ở chế độ phát triển (tự động reload):

    composer run dev

### Đăng nhập trang quản trị

    Địa chỉ  : http://127.0.0.1:8000/login/admin
    Email    : admin@orlis.com
    Mật khẩu : (mật khẩu được tạo khi seed dữ liệu)

---

## 6. CẤU TRÚC THƯ MỤC CHÍNH

    app/
      Http/Controllers/
        Admin/      — 19 controller quản trị
        Client/     — Controller giao diện khách hàng
        Auth/       — Xác thực và đăng nhập mạng xã hội
        Shipper/    — Cổng shipper
        Warehouse/  — Cổng kho hàng
        Supplier/   — Cổng nhà cung cấp
      Models/       — 29 Eloquent Models
      Services/     — Business logic tách biệt

    resources/
      views/
        admin/      — Giao diện trang quản trị
        client/     — Giao diện trang khách hàng
        layouts/    — Layout chung
      css/
        admin.css   — CSS tổng hợp Admin Panel
        admin/      — Các module CSS con

    database/
      migrations/   — 60 file migration
      seeders/      — Dữ liệu mẫu

    routes/
      web.php       — Toàn bộ định tuyến ứng dụng

---

Phiên bản 1.0.0 — Laravel 13 / PHP 8.3 / MySQL 8
