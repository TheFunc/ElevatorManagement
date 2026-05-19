# 解决 HTTP 413 Content Too Large 错误 - v4.mp4 上传失败

## 🔍 问题诊断

**错误信息**: `✗ v4.mp4 上传失败: HTTP 413 Content Too Large`

**根本原因**: 
- PHP 配置限制：`upload_max_filesize = 2M`，`post_max_size = 8M`
- 视频文件大小超过了这些限制

## 🛠️ 解决方案

### 步骤 1：修改 php.ini 配置文件

**文件位置**: `D:\sofeware\php\phpstudy_pro\Extensions\php\php8.2.9nts\php.ini`

**需要修改的配置项**:

找到以下行（如果不存在则添加）：

```ini
; ============================================
; 文件上传配置
; ============================================

; 允许最大上传文件大小（根据实际需求调整，这里设置为 500MB）
upload_max_filesize = 500M

; POST 请求最大大小（应略大于 upload_max_filesize）
post_max_size = 512M

; 脚本最大执行时间（秒），大文件上传需要更长时间
max_execution_time = 300

; 内存限制
memory_limit = 512M

; 最大输入时间
max_input_time = 300

; 同时上传的最大文件数
max_file_uploads = 20
```

**修改方法**:
1. 用记事本或 VS Code 打开 `php.ini` 文件
2. 搜索 `upload_max_filesize`，将值改为 `500M`
3. 搜索 `post_max_size`，将值改为 `512M`
4. 保存文件

### 步骤 2：重启 PHP 服务

**如果你使用 phpstudy 控制面板**:
1. 打开 phpstudy 控制面板
2. 找到正在运行的 PHP 8.2.9
3. 点击"重启"按钮

**如果你使用命令行 `php artisan serve`**:
1. 在运行服务器的终端窗口按 `Ctrl+C` 停止服务器
2. 重新运行：`php artisan serve`

### 步骤 3：验证配置是否生效

打开 PowerShell，运行：

```powershell
php -r "echo 'upload_max_filesize: ' . ini_get('upload_max_filesize') . PHP_EOL;"
php -r "echo 'post_max_size: ' . ini_get('post_max_size') . PHP_EOL;"
```

**期望输出**:
```
upload_max_filesize: 500M
post_max_size: 512M
```

如果仍然显示 `2M` 和 `8M`，说明配置未生效，请确认：
- 是否修改了正确的 php.ini 文件
- 是否已重启 PHP 服务

### 步骤 4：重新上传视频

1. 刷新浏览器页面（Ctrl+F5 强制刷新）
2. 重新选择 v4.mp4 文件
3. 点击"开始上传"

现在应该可以成功上传了！

## 📊 检查视频文件大小

如果你想查看 v4.mp4 的具体大小，在 PowerShell 中运行：

```powershell
# 替换为实际的文件路径
(Get-Item "C:\path\to\v4.mp4").Length / 1MB
```

## ⚠️ 注意事项

1. **生产环境安全**: 不要将上传限制设置得过大，应根据实际需求调整
   - 如果视频通常在 50MB 以内，设置为 `100M` 即可
   - 如果需要支持更大的文件，再适当增加

2. **磁盘空间**: 确保服务器有足够的磁盘空间存储视频文件

3. **超时设置**: 大文件上传可能需要较长时间，`max_execution_time` 要足够

4. **Nginx/Apache 用户**: 如果你使用的是 Nginx 或 Apache（而非 phpstudy），还需要修改 Web 服务器配置：
   
   **Nginx** (`nginx.conf`):
   ```nginx
   http {
       client_max_body_size 500M;
   }
   ```
   
   **Apache** (`.htaccess`):
   ```apache
   LimitRequestBody 524288000
   ```

5. **清除缓存**: 修改配置后建议清除 Laravel 缓存：
   ```bash
   php artisan cache:clear
   php artisan config:clear
   ```

## 🎯 推荐配置参考

根据常见需求，以下是推荐的配置值：

| 场景 | upload_max_filesize | post_max_size | max_execution_time |
|------|---------------------|---------------|-------------------|
| 小文件（图片、文档） | 10M | 12M | 60 |
| 中等文件（短视频） | 100M | 110M | 180 |
| 大文件（长视频） | 500M | 512M | 300 |
| 超大文件 | 1024M | 1100M | 600 |

## ✅ 验证清单

- [ ] 已修改 php.ini 中的 `upload_max_filesize`
- [ ] 已修改 php.ini 中的 `post_max_size`
- [ ] 已重启 PHP 服务
- [ ] 运行 `php -r` 命令验证配置已生效
- [ ] 刷新浏览器页面
- [ ] 重新上传视频文件
- [ ] 上传成功！

## 🆘 如果问题仍然存在

1. **检查是否有其他配置文件覆盖**:
   ```bash
   php --ini
   ```
   查看 "Loaded Configuration File" 确认加载的是哪个配置文件

2. **检查 Laravel 日志**:
   ```bash
   Get-Content storage\logs\laravel.log -Tail 50
   ```

3. **检查浏览器控制台**:
   - 按 F12 打开开发者工具
   - 切换到 Network 标签
   - 查看上传请求的详细信息

4. **联系技术支持**: 如果以上步骤都无法解决问题，请提供：
   - v4.mp4 的文件大小
   - 修改后的 php.ini 相关配置截图
   - Laravel 日志中的错误信息