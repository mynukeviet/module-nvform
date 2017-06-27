Module cho phép tạo form khảo sát (Tương tự công cụ tạo form của google) hoạt động trên NukeViet với các trường dữ liệu phổ biến, giúp việc khảo sát và thu thập dữ liệu an toàn, tiện dụng.

# Giấy phép
Module được phát hành theo giấy phép GNU/GPL v2 hoặc các phiên bản cao hơn.

Xem [LICENSE.txt](LICENSE.txt) để biết thêm thông tin.

# Cài đặt
- Tải về mã nguồn
- Đăng nhập quản trị tối cao. Truy cập menu Mở rộng / Cài dặt gói ứng dụng
- Hoặc có thể cài đặt từ store (Cho phiên bản ổn định)

## Sử dụng chức năng xuất báo cáo ra file

Để sử dụng chức năng này, cần cài đặt thư viện PHPExcel và mPDF, bằng một trong hai cách dưới đây
### Cài đặt qua composer (khuyến khích sử dụng)
(Hãy bỏ qua phương án này nếu bạn không hiểu composer là gì)
```
composer require phpoffice/phpexcel
composer require mpdf/mpdf
```
### Cài đặt từ mã nguồn
- Tải về phiên bản mới nhất của PHPExcel tại https://github.com/PHPOffice/PHPExcel/releases
- Giải nén, copy thư mục `Classes` vào thư mục `includes` (của NukeViet)
- Đổi tên thư mục `Classes` vừa copy sang thành `class`

# Tài trợ
Công ty cổ phần phát triển nguồn mở Việt Nam (VINADES.,JSC)

Phòng 1706 - Tòa nhà CT2 Nàng Hương, 583 Nguyễn Trãi, Hà Nội.

Điện thoại: +84-4-85872007, Fax: +84-4-35500914, Email: contact@vinades.vn

Website: www.vinades.vn | www.nukeviet.vn | www.webnhanh.vn | www.toasoandientu.vn
