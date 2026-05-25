# 视频流接口测试脚本
Write-Host "========================================" -ForegroundColor Cyan
Write-Host "  视频流接口测试" -ForegroundColor Cyan
Write-Host "========================================" -ForegroundColor Cyan
Write-Host ""

$baseUrl = "http://127.0.0.1:8000"

Write-Host "1. 检查后端服务..." -ForegroundColor Yellow
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
Write-Host "2. 获取第一个视频信息..." -ForegroundColor Yellow
try {
    $videoResponse = Invoke-WebRequest -Uri "$baseUrl/api/v1/video/list" -Method Get
    $videos = $videoResponse.Content | ConvertFrom-Json
    
    if ($videos.data.Count -gt 0) {
        $firstVideo = $videos.data[0]
        Write-Host "   ✓ 找到视频: $($firstVideo.description)" -ForegroundColor Green
        Write-Host "   视频路径: $($firstVideo.videoPath)" -ForegroundColor Gray
        
        # 构建流式传输URL
        $encodedPath = [System.Web.HttpUtility]::UrlEncode($firstVideo.videoPath)
        $streamUrl = "$baseUrl/api/v1/video/stream/$encodedPath"
        Write-Host "   流式URL: $streamUrl" -ForegroundColor Gray
    } else {
        Write-Host "   ✗ 没有找到视频数据" -ForegroundColor Yellow
        exit 1
    }
} catch {
    Write-Host "   ✗ 获取视频列表失败: $_" -ForegroundColor Red
    exit 1
}

Write-Host ""
Write-Host "3. 测试视频流接口（完整请求）..." -ForegroundColor Yellow
try {
    $headers = @{
        'Accept' = 'video/*'
    }
    
    $streamResponse = Invoke-WebRequest -Uri $streamUrl -Method Get -Headers $headers -TimeoutSec 10
    
    Write-Host "   状态码: $($streamResponse.StatusCode)" -ForegroundColor Gray
    Write-Host "   Content-Type: $($streamResponse.Headers['Content-Type'])" -ForegroundColor Gray
    Write-Host "   Accept-Ranges: $($streamResponse.Headers['Accept-Ranges'])" -ForegroundColor Gray
    Write-Host "   Content-Length: $($streamResponse.Headers['Content-Length'])" -ForegroundColor Gray
    
    if ($streamResponse.StatusCode -eq 200) {
        Write-Host "   ✓ 完整文件请求成功" -ForegroundColor Green
    } else {
        Write-Host "   ✗ 期望 200，实际 $($streamResponse.StatusCode)" -ForegroundColor Red
    }
} catch {
    Write-Host "   ✗ 请求失败: $_" -ForegroundColor Red
    Write-Host "   错误详情: $($_.Exception.Message)" -ForegroundColor Red
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
        Write-Host "   ✓ Range请求成功 (206 Partial Content)" -ForegroundColor Green
    } else {
        Write-Host "   ✗ 期望 206，实际 $($rangeResponse.StatusCode)" -ForegroundColor Red
    }
} catch {
    Write-Host "   ✗ Range请求失败: $_" -ForegroundColor Red
    Write-Host "   错误详情: $($_.Exception.Message)" -ForegroundColor Red
}

Write-Host ""
Write-Host "5. 查看后端日志..." -ForegroundColor Yellow
$logFile = "storage/logs/laravel.log"
if (Test-Path $logFile) {
    $lastLines = Get-Content $logFile -Tail 30
    Write-Host "最近日志:" -ForegroundColor Gray
    foreach ($line in $lastLines) {
        if ($line -match '\[error\]|\[warning\]') {
            Write-Host "   $line" -ForegroundColor Red
        } elseif ($line -match '\[info\]') {
            Write-Host "   $line" -ForegroundColor Green
        } else {
            Write-Host "   $line" -ForegroundColor Gray
        }
    }
} else {
    Write-Host "   日志文件不存在" -ForegroundColor Yellow
}

Write-Host ""
Write-Host "========================================" -ForegroundColor Cyan
Write-Host "  测试完成！" -ForegroundColor Cyan
Write-Host "========================================" -ForegroundColor Cyan
