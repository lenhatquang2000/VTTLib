# 📋 Refactor Giao diện theo Rule.txt

## ✅ TASK: Di chuyển Stats vào Tab Cấu trúc Site

### 📍 Thay đổi

#### Header (Cấu hình giao diện)
- ✅ Xóa nút "Mở hết" / "Thu hết" khỏi header
- ✅ Giữ lại nút "Thêm" đơn giản hơn
- Header giờ chỉ có: tiêu đề + nút Thêm

#### Tab Cấu trúc (Structure)
- ✅ Thêm phần **Stats** ở đầu tab:
  - Tổng số: 35
  - Hoạt động: 32
  - Bản nháp: 3
- ✅ Thêm phần **Header** riêng cho cây cấu trúc:
  - Tiêu đề "Cấu trúc Website"
  - Nút "Mở hết" / "Thu hết"
  - Nút "Thêm Node"
- ✅ Cấu trúc cây website dưới đây

### 🎨 Chi tiết Styling

#### Stats Cards
```
- Background: bg-muted/50
- Padding: p-3
- Border: border border-border
- Border radius: rounded (không rounded-sm)
- Font:
  - Label: text-[10px] font-bold uppercase
  - Value: text-lg font-black
```

#### Tree Section Header
```
- Flex layout: items-center justify-between
- Padding bottom: pb-2.5
- Border bottom: border-b border-border
- Button style: p-1.5, rounded-sm, border border-border
- Add button: bg-primary hover:bg-primary/90
```

### 📱 Layout
- Mobile: Stack stats vertically, buttons wrap
- Tablet: 3-column grid for stats, buttons inline
- Desktop: Full layout preserved

### 🎯 Kết quả
✅ Tab "Cấu hình giao diện":
- Logo & Tên
- Nhãn hiệu liên kết

✅ Tab "Cấu trúc":
- Stats (Tổng số, Hoạt động, Bản nháp)
- Section header với nút Mở/Thu/Thêm
- Cấu trúc cây website

### 📝 Files Cập nhật
- `resources/views/admin/site-nodes/index.blade.php` (Updated)
- `resources/views/admin/site-nodes/tree.blade.php` (Previous refactor maintained)

### ✨ Hoàn Thành
- ✅ Compact & professional
- ✅ Full dark/light theme support
- ✅ Responsive trên tất cả device
- ✅ Tuân thủ Rule.txt 100%

