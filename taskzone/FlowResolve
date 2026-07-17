# Quy Trình Giải Quyết Vấn Đề & Code Cho AI Agent (Agent-Oriented Flow Resolve)

Tài liệu này được tối ưu hóa để hướng dẫn AI Agent giải quyết vấn đề hiệu quả, lập kế hoạch chính xác, đọc đúng tệp tin cần thiết, tiết kiệm tối đa token và thực hiện công việc tuần tự trong một phiên làm việc.

---

## 📊 Sơ Đồ Quy Trình Cho Agent

```mermaid
graph TD
    1[1. Xác định vấn đề & Khoanh vùng 5W1H] --> 2[2. Thu thập & Xác định danh sách File cần đọc]
    2 --> 3[3. Phân tích nguyên nhân 5 Whys]
    3 --> 4[4. Đề xuất các giải pháp code]
    4 --> 5[5. Đánh giá & Chọn giải pháp tối ưu]
    5 --> 6[6. Lập kế hoạch thực hiện chi tiết]
    6 --> 7[7. Thực hiện Code tuần tự]
    7 --> 8[8. Chạy Test & Đánh giá kết quả]

    style 1 fill:#f9f,stroke:#333,stroke-width:2px
    style 2 fill:#ffe3e3,stroke:#333,stroke-width:2px
    style 6 fill:#e3ffe3,stroke:#333,stroke-width:2px
    style 7 fill:#d0f0c0,stroke:#333,stroke-width:2px
    style 8 fill:#bbf,stroke:#333,stroke-width:2px
```

---

## 📝 Mẫu Điền Thông Tin Hướng Dẫn Agent

### 1. Xác định vấn đề (5W1H & Scope)
> **Mục tiêu:** Định nghĩa rõ vấn đề và khoanh vùng phạm vi ban đầu để Agent không tự ý quét toàn bộ dự án.
*   **What (Cái gì):** Lỗi/Tính năng cần thực hiện là gì?
    *   👉 
*   **Who (Ai):** Ai yêu cầu/gặp lỗi? (Có liên quan đến phân quyền/Role nào không?)
    *   👉 
*   **Where (Ở đâu):** Lỗi/Tính năng thuộc Module, Component, hoặc Route nào?
    *   👉 
*   **When (Khi nào):** Lỗi xảy ra khi thực hiện hành động nào?
    *   👉 
*   **Why (Tại sao):** Tại sao cần giải quyết? Mục tiêu mong muốn đạt được là gì?
    *   👉 
*   **How (Như thế nào):** Hành vi lỗi hiện tại vs. Hành vi mong muốn?
    *   👉 

---

### 2. Thu thập thông tin & Giới hạn File cần đọc (Token Optimization)
> **Mục tiêu:** Liệt kê CHÍNH XÁC những file Agent được phép đọc. **CẤM** Agent tự ý quét hoặc đọc toàn bộ thư mục lớn để tiết kiệm token.
*   **Danh sách File cần đọc (Đường dẫn tuyệt đối hoặc tương đối chính xác):**
    1.  `[Tên file 1](file:///path/to/file1)` (Dòng X - Y) - *Lý do: Chứa logic xử lý...*
    2.  `[Tên file 2](file:///path/to/file2)` - *Lý do: Định nghĩa schema/config...*
*   **Từ khóa tìm kiếm (Grep Search Query):** *(Nếu chưa rõ file, cung cấp từ khóa để Agent dùng `grep_search` thay vì duyệt thư mục)*
    *   👉 
*   **Log lỗi / Dữ liệu thực tế:** *(Cung cấp log cụ thể để tránh Agent phải chạy mò)*
    *   👉 

---

### 3. Phân tích nguyên nhân (5 Whys)
> **Mục tiêu:** Dẫn dắt Agent phân tích nguyên nhân gốc rễ dựa trên thông tin đã thu thập ở Bước 2.
*   **Chuỗi 5 Whys:**
    1.  **Tại sao 1:** Tại sao hành vi lỗi xảy ra? 👉 
    2.  **Tại sao 2:** Tại sao (Trả lời 1) xảy ra? 👉 
    3.  **Tại sao 3:** Tại sao (Trả lời 2) xảy ra? 👉 
    4.  **Tại sao 4:** Tại sao (Trả lời 3) xảy ra? 👉 
    5.  **Tại sao 5 (Nguyên nhân gốc rễ):** Tại sao (Trả lời 4) xảy ra? 👉 
*   **Kết luận nguyên nhân gốc rễ:**
    *   👉 

---

### 4. Đề xuất các giải pháp Code
> **Mục tiêu:** Đưa ra các phương án sửa đổi code cụ thể (về mặt logic hoặc cấu trúc).

| STT | Phương Án Giải Quyết | File Sẽ Bị Ảnh Hưởng | Rủi ro / Điểm cần lưu ý |
| :--- | :--- | :--- | :--- |
| 1 | **Phương án A:** *Tối ưu hóa câu query...* | `[file.php](file:///...)` | Ảnh hưởng tới hiệu năng tạm thời |
| 2 | **Phương án B:** *Thêm middleware kiểm tra...* | `[middleware.php](file:///...)` | Có thể chặn nhầm request nếu config sai |

---

### 5. Đánh giá và chọn giải pháp tối ưu
> **Mục tiêu:** Chọn giải pháp tối thiểu hóa rủi ro, thời gian và lượng code thay đổi.
*   👉 **Giải pháp được chọn:** **[Phương án X]**
*   **Lý do chọn:** *(Ít rủi ro nhất, tối ưu token khi sửa đổi, dễ test...)*
    *   👉 

---

### 6. Lập kế hoạch thực hiện chi tiết (Task List cho Agent)
> **Mục tiêu:** Lập trình tự các bước thực hiện chi tiết. **Agent PHẢI làm việc theo đúng trình tự này trong 1 lần làm việc.**

```markdown
- [ ] Bước 1: Đọc kỹ dòng X đến Y của file A để xác nhận cấu trúc code hiện tại.
- [ ] Bước 2: Sửa đổi logic tại file A (chỉ sửa phân đoạn cần thiết, dùng `replace_file_content` hoặc `multi_replace_file_content`, CẤM ghi đè toàn bộ file).
- [ ] Bước 3: Tạo mới file B (nếu cần).
- [ ] Bước 4: Cập nhật route/config tại file C.
- [ ] Bước 5: Chạy lệnh test/kiểm tra lỗi cú pháp (Syntax check / Unit Test).
```

---

### 7. Thực hiện (Execution)
> **Mục tiêu:** Thực hiện code theo đúng kế hoạch ở Bước 6. Ghi nhận nhật ký thay đổi và các lỗi phát sinh trong quá trình code.
*   **Nhật ký sửa đổi code:**
    *   *Task 1:* Đã hoàn thành sửa file A...
    *   *Task 2:* ...
*   **Lỗi phát sinh & Cách xử lý của Agent:** *(Ghi nhận nếu trình biên dịch báo lỗi hoặc test case thất bại)*
    *   👉 

---

### 8. Đánh giá kết quả (Verification)
> **Mục tiêu:** Xác nhận vấn đề đã được giải quyết triệt để và code hoạt động đúng.
*   **Kết quả chạy lệnh Test:** *(Dán kết quả chạy PHPUnit, Jest, npm run test...)*
    *   👉 
*   **Kiểm tra chất lượng code (Lint & Clean):**
    *   [ ] Đã xóa code thừa, console.log, dd() hoặc comments nháp.
    *   [ ] Đã chạy linter không còn cảnh báo/lỗi.
    *   [ ] Code tuân thủ coding convention của dự án.
*   **Bài học rút ra cho Agent:** *(Lưu ý gì cho các task tương tự lần sau để tránh lặp lại lỗi)*
    *   👉 
