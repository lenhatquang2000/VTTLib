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

### Cách 2: Với xác thực (Khuyến khích)

#### GitHub
1. Vào Settings > Webhooks của repository
2. Thêm webhook mới:
   - **Payload URL**: `https://yourdomain.com/webhook/github`
   - **Content type**: `application/json`
   - **Secret**: Tạo secret ngẫu nhiên, ví dụ: `your-secret-key-here`
3. Cập nhật file `.env`:
   ```
   GITHUB_WEBHOOK_SECRET=your-secret-key-here
   ```

#### GitLab
1. Vào Project > Settings > Integrations > Webhooks
2. Thêm webhook mới:
   - **URL**: `https://yourdomain.com/webhook/gitlab`
   - **Secret token**: Tạo secret ngẫu nhiên
3. Cập nhật file `.env`:
   ```
   GITLAB_WEBHOOK_SECRET=your-secret-token
   ```

#### Bitbucket
1. Vào Repository settings > Webhooks
2. Thêm webhook mới:
   - **URL**: `https://yourdomain.com/webhook/bitbucket`
   - **Triggers**: Push events
3. Không cần secret token (hoặc có thể cấu hình tùy chỉnh)

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

### Kiểm tra logs
Xem logs để verify webhook đã được thực thi:
```bash
tail -f storage/logs/laravel.log
```

## Lưu ý quan trọng

1. **HTTPS**: Sử dụng HTTPS cho production để bảo mật
2. **Secret**: Luôn cấu hình secret token nếu có thể
3. **Git credentials**: Đảm bảo server có quyền truy cập Git repository
4. **SSH key**: Nếu dùng SSH, cấu hình SSH key cho deployment user
5. **Permissions**: Đảm bảo file permissions cho git operations
6. **Middleware CSRF**: Webhook không yêu cầu CSRF token (đã loại trừ)

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
