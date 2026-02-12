# Student Management System (SMS)

Hệ thống quản lý học sinh trường học toàn diện được xây dựng trên nền tảng **Laravel 11**. Dự án cung cấp giải pháp quản lý phân quyền chặt chẽ giữa Quản trị viên (Admin), Giáo viên (Teacher) và Học sinh (Student), tích hợp các tính năng hiện đại như nhập liệu từ Excel và đăng nhập qua Google.

## 🚀 Tính Năng Chính

### 1. Quản Trị Viên (Admin)
- **Dashboard:** Thống kê tổng quan số lượng học sinh, giáo viên, môn học theo thời gian thực.
- **Quản lý Học sinh & Giáo viên:**
  - Thêm, sửa, xóa, xem chi tiết hồ sơ.
  - **Đặc biệt:** Hỗ trợ Import danh sách học sinh hàng loạt từ file Excel (`.xlsx`, `.xls`).
  - Phân công giáo viên chủ nhiệm và giáo viên bộ môn.
- **Quản lý Học vụ:**
  - CRUD Môn học (Subjects) và nhập danh sách môn từ Excel.
  - Quản lý Lớp học (Classes), Khối (Grades) và Ban chuyên môn (Subject Streams).
  - Phân lớp và gán học sinh vào lớp học.
- **Cài đặt hệ thống:** Quản lý thông tin tài khoản và bảo mật.

### 2. Giáo Viên (Teacher)
- **Quản lý Lớp chủ nhiệm:** Xem danh sách học sinh, thông tin chi tiết từng học sinh.
- **Phân công chuyên môn:** Gán hoặc thay đổi môn học cho học sinh.
- **Thông báo (Announcements):**
  - Đăng thông báo mới cho lớp học.
  - Hệ thống tự động **gửi Email** nội dung thông báo đến toàn bộ học sinh trong lớp.
- **Hồ sơ:** Cập nhật thông tin cá nhân và mật khẩu.

### 3. Học Sinh (Student)
- **Dashboard cá nhân:** Xem thông tin tổng quan.
- **Hồ sơ:** Tra cứu thông tin cá nhân, người giám hộ và lịch sử học tập.
- **Môn học:** Xem danh sách các môn học đã đăng ký.

---

## 🛠 Công Nghệ Sử Dụng

- **Backend:** Laravel Framework 11.x
- **Database:** MySQL
- **Frontend:** Blade Templates, Bootstrap 5, Custom CSS/JS.
- **Thư viện nổi bật:**
  - `laravel/socialite`: Tích hợp đăng nhập Google OAuth.
  - `phpoffice/phpspreadsheet`: Xử lý nhập/xuất file Excel.
  - `sweetalert2`: Hiển thị thông báo UI/UX thân thiện.

---

## ⚙️ Yêu Cầu Hệ Thống

- PHP >= 8.2
- Composer
- MySQL / MariaDB
- Node.js & NPM (để build assets)

---
