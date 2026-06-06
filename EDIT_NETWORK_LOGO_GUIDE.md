# Hướng dẫn Chỉnh sửa Nhãn hiệu Thư viện Liên kết

## ✨ Chức năng Chỉnh sửa

### Cách sử dụng:

1. **Truy cập Trang Quản lý**
   - URL: `http://localhost:8000/topsecret/site-nodes`
   - Chọn tab "Cấu hình giao diện"
   - Kéo xuống phần "Nhãn hiệu thư viện liên kết"

2. **Mở Modal Chỉnh sửa**
   - Hover vào một nhãn hiệu trong danh sách
   - Nhấp nút **Edit** (icon bút chì màu xanh)
   - Modal sẽ mở ra

3. **Modal Chỉnh sửa**
   ```
   ┌─────────────────────────────────────────┐
   │  ✏️ Chỉnh sửa nhãn hiệu          ✕       │
   ├─────────────────────────────────────────┤
   │                                         │
   │  📝 Tên thư viện                        │
   │  [Nhập tên thư viện]                   │
   │                                         │
   │  🔗 URL                                 │
   │  [Nhập URL]                            │
   │                                         │
   │  🖼️  Logo mới (tuỳ chọn)                 │
   │  [Tải file logo mới]                   │
   │                                         │
   │  Logo hiện tại:                        │
   │  ┌───────┐                             │
   │  │ Logo  │ Logo hiện tại                │
   │  └───────┘                             │
   │                                         │
   │  (Nếu chọn file mới sẽ hiện)           │
   │  Logo mới:                             │
   │  ┌───────┐                             │
   │  │ Logo  │ Sẽ được cập nhật             │
   │  └───────┘                             │
   │                                         │
   │  [Hủy]  [💾 Lưu]                       │
   └─────────────────────────────────────────┘
   ```

4. **Các trường trong Modal**
   - **Tên thư viện**: Bắt buộc, nhập tên thư viện hoặc nhãn hiệu
   - **URL**: Bắt buộc, nhập đường dẫn website
   - **Logo mới**: Tuỳ chọn, chọn file ảnh mới (PNG, JPG, SVG, WebP, ICO, tối đa 2MB)

5. **Preview Logo**
   - **Logo hiện tại**: Hiển thị logo được lưu trước đó
   - **Logo mới**: Sẽ hiện lên tự động khi bạn chọn file mới

6. **Lưu Thay đổi**
   - Nhấp nút **Lưu** (icon đĩa)
   - Nếu có lỗi, sẽ hiện thông báo
   - Sau khi lưu thành công, modal sẽ đóng tự động
   - Danh sách nhãn hiệu sẽ cập nhật

---

## 🔧 Các Functionality

### Database:
- Bảng: `library_network_logos`
- Cột được cập nhật: `name`, `logo_path`, `url`
- File logo lưu tại: `storage/app/public/network-logos/`

### API Endpoint:
```
POST /topsecret/site-nodes/network-logo/{logo_id}
Content-Type: multipart/form-data

Request:
{
  "name": "Tên thư viện",
  "url": "https://example.com",
  "logo_path": <file> (optional)
}

Response:
{
  "message": "Cập nhật nhãn hiệu thành công!",
  "redirect": "/topsecret/site-nodes"
}
```

### Activity Log:
- Mỗi lần chỉnh sửa sẽ được ghi log
- Event: `library_network_logo_updated`
- Lưu thông tin: name, url

---

## 💡 Tips & Tricks

1. **Không cần chọn Logo mới**
   - Nếu chỉ muốn thay đổi tên hoặc URL, không cần upload logo mới
   - Logo cũ sẽ được giữ lại

2. **Thay đổi Logo**
   - Để thay đổi logo, chọn file mới
   - File cũ sẽ được xóa tự động khỏi storage
   - File mới sẽ được lưu với cùng tên thư mục

3. **Hủy Thay đổi**
   - Nhấp nút **Hủy** hoặc nút **X** để đóng modal
   - Tất cả thay đổi sẽ được bỏ đi

4. **Kiểm tra Preview**
   - Trước khi lưu, hãy kiểm tra preview của logo mới
   - Đảm bảo hình ảnh hiển thị đúng

---

## ⚠️ Validation Rules

- **Tên thư viện**: 
  - Bắt buộc
  - Tối đa 255 ký tự

- **URL**: 
  - Bắt buộc
  - Phải là URL hợp lệ (http:// hoặc https://)
  - Tối đa 255 ký tự

- **Logo**: 
  - Tuỳ chọn
  - Định dạng: PNG, JPG, JPEG, SVG, WebP, ICO
  - Tối đa 2MB

---

## 🐛 Troubleshooting

**Modal không mở?**
- Đảm bảo JavaScript được load đúng
- Kiểm tra console để xem lỗi

**Preview logo không hiển thị?**
- Kiểm tra đường dẫn file
- Đảm bảo file tồn tại trong `storage/app/public/`

**Lưu không được?**
- Kiểm tra validation errors
- Đảm bảo tên và URL không trống
- Kiểm tra dung lượng file logo

**File cũ không được xóa?**
- Storage disk có thể không có quyền
- Kiểm tra quyền folder `storage/app/public/network-logos/`

