# 视频 Range 请求测试脚本
# 在 PowerShell 中运行此脚本进行测试

Write-Host "========================================" -ForegroundColor Cyan
Write-Host "  视频流 Range 请求支持 - 测试脚本" -ForegroundColor Cyan
Write-Host "========================================" -ForegroundColor Cyan
Write-Host ""

$baseUrl = "http://127.0.0.1:8000"

Write-Host "1. 检查后端服务是否运行..." -ForegroundColor Yellow
try {
    $response = Invoke-WebRequest -Uri "$baseUrl/api/v1/video/types" -Method Get -TimeoutSec 5
    if ($response.StatusCode -eq 200) {
        Write-Host "   ✓ 后端服务正常运行" -ForegroundColor Green
    }
} catch {
    Write-Host "   ✗ 后端服务未运行，请先启动: php artisan serve" -ForegroundColor Red
    exit 1
}

Write-Host ""
Write-Host "2. 获取视频列表..." -ForegroundColor Yellow
try {
    $videoResponse = Invoke-WebRequest -Uri "$baseUrl/api/v1/video/list" -Method Get
    $videos = $videoResponse.Content | ConvertFrom-Json
    
    if ($videos.data.Count -gt 0) {
        Write-Host "   ✓ 找到 $($videos.data.Count) 个视频" -ForegroundColor Green
        $firstVideo = $videos.data[0]
        Write-Host "   第一个视频: $($firstVideo.description)" -ForegroundColor Gray
        Write-Host "   视频路径: $($firstVideo.videoPath)" -ForegroundColor Gray
    } else {
        Write-Host "   ⚠ 没有找到视频数据" -ForegroundColor Yellow
        exit 1
    }
} catch {
    Write-Host "   ✗ 获取视频列表失败: $_" -ForegroundColor Red
    exit 1
}

Write-Host ""
Write-Host "3. 测试视频流接口（无 Range 请求）..." -ForegroundColor Yellow
try {
    $videoPath = [System.Web.HttpUtility]::UrlEncode($firstVideo.videoPath)
    $streamUrl = "$baseUrl/api/v1/video/stream/$videoPath"
    
    $headers = @{
        'Accept' = 'video/*'
    }
    
    $streamResponse = Invoke-WebRequest -Uri $streamUrl -Method Get -Headers $headers -TimeoutSec 10
    
    Write-Host "   状态码: $($streamResponse.StatusCode)" -ForegroundColor Gray
    Write-Host "   Content-Type: $($streamResponse.Headers['Content-Type'])" -ForegroundColor Gray
    Write-Host "   Accept-Ranges: $($streamResponse.Headers['Accept-Ranges'])" -ForegroundColor Gray
    
    if ($streamResponse.Headers['Accept-Ranges'] -eq 'bytes') {
        Write-Host "   ✓ Accept-Ranges 头正确" -ForegroundColor Green
    } else {
        Write-Host "   ✗ Accept-Ranges 头缺失或错误" -ForegroundColor Red
    }
} catch {
    Write-Host "   ✗ 视频流请求失败: $_" -ForegroundColor Red
}

Write-Host ""
Write-Host "4. 测试 Range 请求..." -ForegroundColor Yellow
try {
    $rangeHeaders = @{
        'Range' = 'bytes=0-1023'
        'Accept' = 'video/*'
    }
    
    $rangeResponse = Invoke-WebRequest -Uri $streamUrl -Method Get -Headers $rangeHeaders -TimeoutSec 10
    
    Write-Host "   状态码: $($rangeResponse.StatusCode)" -ForegroundColor Gray
    Write-Host "   Content-Range: $($rangeResponse.Headers['Content-Range'])" -ForegroundColor Gray
    
    if ($rangeResponse.StatusCode -eq 206) {
        Write-Host "   ✓ 返回 206 Partial Content" -ForegroundColor Green
    } else {
        Write-Host "   ✗ 期望 206，实际 $($rangeResponse.StatusCode)" -ForegroundColor Red
    }
    
    if ($rangeResponse.Headers['Content-Range']) {
        Write-Host "   ✓ Content-Range 头存在" -ForegroundColor Green
    } else {
        Write-Host "   ✗ Content-Range 头缺失" -ForegroundColor Red
    }
} catch {
    Write-Host "   ✗ Range 请求失败: $_" -ForegroundColor Red
}

Write-Host ""
Write-Host "========================================" -ForegroundColor Cyan
Write-Host "  测试完成！" -ForegroundColor Cyan
Write-Host "========================================" -ForegroundColor Cyan
Write-Host ""
Write-Host "下一步：" -ForegroundColor Yellow
Write-Host "1. 打开浏览器访问: http://127.0.0.1:5174/" -ForegroundColor White
Write-Host "2. 点击任意视频进入播放页面" -ForegroundColor White
Write-Host "3. 按 F12 打开开发者工具 -> Network 标签" -ForegroundColor White
Write-Host "4. 尝试拖动进度条，观察网络请求" -ForegroundColor White
Write-Host "5. 应该看到 206 状态码和 Content-Range 响应头" -ForegroundColor White
Write-Host ""
