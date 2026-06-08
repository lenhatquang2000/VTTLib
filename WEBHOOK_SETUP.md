# Webhook Setup Guide

Hướng dẫn cài đặt webhook tự động pull git khi có thay đổi.

## Endpoints Webhook

Ứng dụng hỗ trợ 4 endpoint webhook:

### 1. GitHub Webhook
```
POST /webhook/github
```

### 2. GitLab Webhook
```
POST /webhook/gitlab
```

### 3. Bitbucket Webhook
```
POST /webhook/bitbucket
```

### 4. Generic Webhook (cho bất kỳ provider nào)
```
POST /webhook
```

## Cấu hình Webhook

### Cách 1: Không xác thực (không khuyến khích cho production)
Nếu không cấu hình secret, webhook sẽ chấp nhận tất cả request mà không xác thực.

### Cách 2: Cấu hình nhánh (Branch)
Mặc định webhook sẽ pull nhánh `main`. Để thay đổi, cập nhật file `.env`:
```env
WEBHOOK_BRANCH=develop
```

Hoặc bất kỳ nhánh nào bạn muốn pull.

### Cách 3: Với xác thực (Khuyến khích)

#### GitHub
1. Vào Settings > Webhooks của repository
2. Thêm webhook mới:
   - **Payload URL**: `https://yourdomain.com/webhook/github`
   - **Content type**: `application/json`
   - **Secret**: Tạo secret ngẫu nhiên, ví dụ: `your-secret-key-here`
3. Cập nhật file `.env`:
   ```
   GITHUB_WEBHOOK_SECRET=your-secret-key-here
   WEBHOOK_BRANCH=main
   ```

#### GitLab
1. Vào Project > Settings > Integrations > Webhooks
2. Thêm webhook mới:
   - **URL**: `https://yourdomain.com/webhook/gitlab`
   - **Secret token**: Tạo secret ngẫu nhiên
3. Cập nhật file `.env`:
   ```
   GITLAB_WEBHOOK_SECRET=your-secret-token
   WEBHOOK_BRANCH=main
   ```

#### Bitbucket
1. Vào Repository settings > Webhooks
2. Thêm webhook mới:
   - **URL**: `https://yourdomain.com/webhook/bitbucket`
   - **Triggers**: Push events
3. Không cần secret token (hoặc có thể cấu hình tùy chỉnh)

## Logging Webhook

### Log Files

Webhook logs được lưu riêng biệt từ Laravel logs chính:

- **Webhook logs**: `storage/logs/webhook.log` (hoặc `webhook-*.log` cho daily)
- **Laravel logs**: `storage/logs/laravel.log`

### Cấu trúc Log Webhook

Mỗi webhook request được ghi nhật ký với thông tin chi tiết:

#### Request Log
```
[2024-01-15 10:30:45] local.INFO: === Webhook Request Received ===
{
  "timestamp": "2024-01-15 10:30:45",
  "provider": "GitHub",
  "method": "POST",
  "url": "https://yourdomain.com/webhook/github",
  "ip": "192.30.252.0",
  "headers": {
    "x-hub-signature-256": "sha256=...",
    "x-github-delivery": "12345-67890-...",
    "x-github-event": "push"
  },
  "user_agent": "GitHub-Hookshot/...",
  "content_type": "application/json"
}
```

#### Success Log
```
[2024-01-15 10:30:50] local.INFO: === Webhook Success ===
{
  "timestamp": "2024-01-15 10:30:50",
  "provider": "GitHub",
  "message": "Git pull completed successfully",
  "status": "success"
}
```

#### Error Log
```
[2024-01-15 10:30:45] local.ERROR: === Webhook Error ===
{
  "timestamp": "2024-01-15 10:30:45",
  "provider": "GitHub",
  "error": "fatal: not a git repository",
  "trace": "...",
  "status": "error"
}
```

### Xem Logs

#### Linux/Mac
```bash
# Xem logs webhook real-time
tail -f storage/logs/webhook.log

# Xem logs Laravel real-time
tail -f storage/logs/laravel.log

# Xem tất cả logs webhook
cat storage/logs/webhook.log

# Tìm kiếm lỗi trong logs
grep "ERROR" storage/logs/webhook.log

# Xem logs từ một provider cụ thể
grep "GitHub" storage/logs/webhook.log
```

#### Windows PowerShell
```powershell
# Xem logs webhook real-time
Get-Content -Path storage/logs/webhook.log -Wait

# Xem tất cả logs webhook
Get-Content storage/logs/webhook.log

# Tìm kiếm lỗi
Select-String -Path "storage/logs/webhook.log" -Pattern "ERROR"

# Xem logs từ một provider
Select-String -Path "storage/logs/webhook.log" -Pattern "GitHub"
```

#### Windows CMD
```cmd
# Xem logs webhook
type storage\logs\webhook.log

# Tìm kiếm lỗi
findstr "ERROR" storage\logs\webhook.log
```

### Log Rotation

Webhook logs sử dụng daily rotation:
- Logs được lưu tối đa 30 ngày (có thể thay đổi trong `config/logging.php`)
- Các file logs được đặt tên: `webhook-YYYY-MM-DD.log`

### Quy Tắc Log

1. **Chi tiết Request**: Tất cả webhook request đều được ghi lại với headers, IP, user agent
2. **Tracking Provider**: Dễ dàng phân biệt webhook từ GitHub, GitLab, Bitbucket hoặc Generic
3. **Git Command Output**: Output của git pull được ghi vào Laravel logs
4. **Error Tracking**: Tất cả lỗi được ghi nhật ký chi tiết với stack trace
5. **Performance**: Có thể theo dõi thời gian thực thi webhook

## Các lệnh được thực thi sau khi pull

Sau khi git pull thành công, ứng dụng sẽ tự động chạy:

1. **Optimize cache clear**: `php artisan optimize:clear`

Bạn có thể bổ sung các lệnh khác trong method `runPostPullCommands()` của `WebhookController.php`:

### Ví dụ thêm các lệnh:

```php
// Uncomment trong file WebhookController.php để chạy các lệnh này:

// Chạy database migrations
\Artisan::call('migrate', ['--force' => true]);

// Cài đặt composer dependencies
$this->runCommand(['composer', 'install'], base_path());

// Cài đặt npm dependencies
$this->runCommand(['npm', 'install'], base_path());

// Rebuild npm
$this->runCommand(['npm', 'run', 'build'], base_path());
```

## Kiểm tra Webhook

### Test bằng cURL
```bash
# Generic webhook
curl -X POST http://localhost:8000/webhook \
  -H "Content-Type: application/json" \
  -d '{}'

# GitHub webhook
curl -X POST http://localhost:8000/webhook/github \
  -H "Content-Type: application/json" \
  -d '{}'

# GitLab webhook
curl -X POST http://localhost:8000/webhook/gitlab \
  -H "Content-Type: application/json" \
  -d '{}'

# Bitbucket webhook
curl -X POST http://localhost:8000/webhook/bitbucket \
  -H "Content-Type: application/json" \
  -d '{}'
```

### Test qua PowerShell
```powershell
$uri = "http://localhost:8000/webhook"
$headers = @{"Content-Type" = "application/json"}
$body = @{} | ConvertTo-Json

Invoke-WebRequest -Uri $uri -Method Post -Headers $headers -Body $body
```

### Kiểm tra logs
```bash
# Xem logs để verify webhook đã được thực thi
tail -f storage/logs/webhook.log
```

## Lưu ý quan trọng

1. **HTTPS**: Sử dụng HTTPS cho production để bảo mật
2. **Secret**: Luôn cấu hình secret token nếu có thể
3. **Git credentials**: Đảm bảo server có quyền truy cập Git repository
4. **SSH key**: Nếu dùng SSH, cấu hình SSH key cho deployment user
5. **Permissions**: Đảm bảo file permissions cho git operations
6. **Middleware CSRF**: Webhook không yêu cầu CSRF token (đã loại trừ)
7. **Log Monitoring**: Kiểm tra logs thường xuyên để phát hiện lỗi

## Troubleshooting

### Permission Denied
```
fatal: could not read Username for 'https://github.com': Terminal prompts disabled
```

Giải pháp:
- Sử dụng SSH key thay vì HTTPS
- Hoặc cấu hình Git credentials store

### Timeout
Nếu webhook timeout, tăng timeout trong `gitPull()` method:
```php
$process->setTimeout(600); // 10 minutes
```

### Logs không hiển thị
Kiểm tra file permissions:
```bash
chmod -R 775 storage/logs
```

### Xem logs ngoài file
Nếu logs không hiển thị trong file, kiểm tra:
```bash
# Kiểm tra permissions
ls -la storage/logs/

# Kiểm tra disk space
df -h storage/

# Kiểm tra Laravel logs
tail -f storage/logs/laravel.log | grep -i webhook
```

## Cấu hình cho các Platform

### For Docker
Đảm bảo git được cài đặt trong container:
```dockerfile
RUN apt-get update && apt-get install -y git
```

### For Production
Sử dụng deployment user với restricted permissions:
```bash
# Tạo deployment user
sudo useradd -m -s /bin/bash deploy

# Cấu hình git
su - deploy
git config --global user.name "Deploy Bot"
git config --global user.email "deploy@example.com"

# Thêm SSH key
mkdir ~/.ssh
# Copy SSH private key tới ~/.ssh/id_rsa
chmod 600 ~/.ssh/id_rsa
```
