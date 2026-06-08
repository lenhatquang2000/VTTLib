# Network Logos Slider - Logic Documentation

## Mô tả tính năng
Slider tự động trượt sang trái liên tục trên endpoint `http://localhost:8000/`. Khi trượt gần hết danh sách items, nó tự động nhân bản items lên 3 lần để có thêm nội dung để tiếp tục trượt.

## Cấu trúc & Logic

### Biến toàn cục
```javascript
let sliderPosition = 0;          // Vị trí hiện tại của slider (px)
let autoSlideInterval;           // ID của setInterval auto-slide
let itemsPerPage = 1;            // Số item hiển thị trên màn hình
let totalItems = 0;              // Tổng số items hiện tại
let originalItemCount = 0;       // Số lượng items gốc ban đầu
```

### Hàm chính

#### 1. `duplicateItems()`
- Lấy `originalItemCount` items gốc từ đầu danh sách
- Nhân bản mỗi item 3 lần và thêm vào cuối slider
- Cập nhật `totalItems` = tổng items hiện tại
- In log: `[Slider] Items duplicated. Original: X, Current total: Y`

#### 2. `initNetworkSlider()`
- Gọi lần đầu khi page load
- Lưu lại `originalItemCount` (số items gốc)
- Tính `itemsPerPage` dựa trên responsive breakpoints:
  - Mobile (< 768px): 2 items
  - Desktop (≥ 768px): 4 items
- Nhân bản items ban đầu lên 3 lần (chỉ lần đầu)
- Khởi động auto-slide

#### 3. `startAutoSlide()`
- Chạy auto-slide mỗi 4 giây
- Tính `shift` = itemWidth + gap (16px)
- Tăng `sliderPosition` += shift (trượt sang phải)
- **Kiểm tra trigger**: `if (currentIndex >= totalItems - 4)`
  - Khi index ≥ (totalItems - 4), gọi `duplicateItems()` để nhân bản lại
- Áp dụng CSS transform: `translateX(-${sliderPosition}px)`

#### 4. `nextSlide()` (Manual button)
- Trượt sang phải 1 item khi bấm nút next
- Tương tự `startAutoSlide()`, kiểm tra trigger nhân bản
- Reset auto-slide timer

#### 5. `prevSlide()` (Manual button)
- Trượt sang trái 1 item khi bấm nút prev
- Reset vị trí nếu < 0
- Reset auto-slide timer

## Luồng hoạt động

```
Page Load
  ↓
initNetworkSlider()
  ├─ Lưu originalItemCount = 3 (ví dụ)
  ├─ Thiết lập itemsPerPage = 4 (desktop)
  ├─ duplicateItems() → Items từ 3 → 12 (nhân 3 lần)
  └─ startAutoSlide()
      ↓
  Mỗi 4 giây:
    sliderPosition += shift
    currentIndex = 10? Nếu 10 >= (12-4=8) → duplicateItems()
    Items từ 12 → 21
    Tiếp tục slide...
```

## Ví dụ cụ thể

**Ban đầu**: 3 library logos
1. Khởi tạo → Nhân bản 3 lần → 12 logos
2. Slide tự động: 0 → 1 → 2 → 3 → 4 → 5 → 6 → 7 → **8 (trigger!)**
3. Trigger nhân bản → 12 + (3 × 3) = 21 logos
4. Tiếp tục slide: 9 → 10 → ... → 17 → **18 (trigger!)**
5. Trigger nhân bản → 21 + (3 × 3) = 30 logos
6. Vòng lặp vô hạn...

## Responsive Behavior

| Breakpoint | Items Per View | Trigger Index |
|-----------|---|---|
| Mobile (<768px) | 2 | totalItems - 4 |
| Desktop (≥768px) | 4 | totalItems - 4 |

Ví dụ: Desktop với 12 logos → Trigger tại index 8

## Performance Notes

- ✅ Sử dụng CSS `transform` thay vì `left` → Smooth 60fps animation
- ✅ `transition-transform duration-500` = 500ms smooth slide
- ✅ Nhân bản `cloneNode(true)` = Copy toàn bộ DOM node + event listeners
- ✅ Auto-slide interval = 4 giây / lần, điều chỉnh được dễ dàng

## Customization

Để thay đổi tốc độ slide:
```javascript
// Từ: }, 4000);
// Thành: }, 6000); // 6 giây
```

Để thay đổi số lần nhân bản:
```javascript
// Từ 3 lần:
itemsToDuplicate.forEach(...) // 3 lần
// Thành 5 lần (sửa 3 forEach thành 5)
```

Để thay đổi trigger threshold:
```javascript
// Từ: if (currentIndex >= totalItems - 4)
// Thành: if (currentIndex >= totalItems - 6) // Trigger sớm hơn
```

## Testing

Mở DevTools Console để xem logs:
```
[Slider] Items duplicated. Original: 3, Current total: 12
[Slider] Reached index 8 (threshold: 8), duplicating items again...
```

---

**File được sửa**: `resources/views/site/pages/home.blade.php` (dòng 866-930)
