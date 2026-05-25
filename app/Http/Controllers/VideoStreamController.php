<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;
use Symfony\Component\HttpFoundation\StreamedResponse;

class VideoStreamController extends Controller
{
    /**
     * 视频流式传输 - 支持 HTTP Range 请求
     * 
     * @param string $path 视频文件路径（URL编码）
     * @return StreamedResponse
     */
    public function stream(Request $request, string $path)
    {
        try {
            // URL解码文件路径
            $filePath = urldecode($path);
            
            Log::info('视频流请求', [
                'original_path' => $path,
                'decoded_path' => $filePath,
                'range' => $request->header('Range')
            ]);
            
            // 防御性清理：移除所有 ../ 和 ..\ 序列，防止路径穿越攻击
            $filePath = str_replace(['../', '..\\', '..'], '', $filePath);
            
            // 如果路径以 storage/ 或 storage\ 开头，移除它（因为storage_path已经包含了storage目录）
            if (strpos($filePath, 'storage/') === 0 || strpos($filePath, 'storage\\') === 0) {
                $filePath = substr($filePath, 8); // 移除 'storage/'
                Log::info('移除storage前缀', ['new_path' => $filePath]);
            }
            
            // 分割路径逐一过滤，确保只保留合法部分
            $parts = preg_split('#[/\\\\]#', $filePath, -1, PREG_SPLIT_NO_EMPTY);
            $parts = array_filter($parts, function ($p) {
                return $p !== '.' && $p !== '..' && $p !== '';
            });
            $filePath = implode(DIRECTORY_SEPARATOR, $parts);
            
            // 构建完整的存储路径
            $fullPath = storage_path('app/public/' . $filePath);
            
            Log::info('完整文件路径', ['full_path' => $fullPath]);
            
            // 安全检查：使用 realpath 解析真实路径，确保不会穿越到 storage/app/public 之外
            $allowedBase = realpath(storage_path('app/public'));
            if ($allowedBase === false) {
                Log::error('存储基础目录不存在', ['base' => storage_path('app/public')]);
                return response()->json(['message' => '视频文件不存在'], 404);
            }
            
            // 检查文件是否存在
            if (!file_exists($fullPath)) {
                Log::error('视频文件不存在', ['path' => $fullPath]);
                
                return response()->json([
                    'message' => '视频文件不存在',
                    'tried_path' => $fullPath,
                    'original_path' => $path
                ], 404);
            }
            
            // realpath 安全校验：确认解析后的真实路径在允许的目录内
            $realPath = realpath($fullPath);
            if ($realPath === false || strpos($realPath . DIRECTORY_SEPARATOR, $allowedBase . DIRECTORY_SEPARATOR) !== 0) {
                Log::warning('路径穿越攻击拦截', [
                    'requested' => $fullPath,
                    'realpath' => $realPath,
                    'allowed_base' => $allowedBase
                ]);
                return response()->json(['message' => '视频文件不存在'], 404);
            }
            
            // 获取文件大小
            $fileSize = filesize($fullPath);
            
            // 检测MIME类型
            $mimeType = mime_content_type($fullPath);
            if (!$mimeType || strpos($mimeType, 'video') === false) {
                $mimeType = 'video/mp4'; // 默认视频类型
            }
            
            Log::info('文件信息', [
                'size' => $fileSize,
                'mime_type' => $mimeType
            ]);
            
            // 处理 Range 请求
            $range = $request->header('Range');
            
            if ($range) {
                Log::info('处理Range请求', ['range' => $range]);
                
                // 解析 Range 头 (格式: bytes=start-end)
                if (preg_match('/bytes=(\d+)-(\d*)/', $range, $matches)) {
                    $start = intval($matches[1]);
                    $end = !empty($matches[2]) ? intval($matches[2]) : $fileSize - 1;
                    
                    // 验证范围
                    if ($start >= $fileSize || $end >= $fileSize || $start > $end) {
                        Log::warning('无效的Range请求', [
                            'start' => $start,
                            'end' => $end,
                            'file_size' => $fileSize
                        ]);
                        return response()->json([
                            'message' => '请求的范围无效'
                        ], 416);
                    }
                    
                    // 计算实际要传输的长度
                    $length = $end - $start + 1;
                    
                    Log::info('Range响应', [
                        'start' => $start,
                        'end' => $end,
                        'length' => $length
                    ]);
                    
                    // 创建流式响应
                    $response = new StreamedResponse(function () use ($fullPath, $start, $length) {
                        $handle = fopen($fullPath, 'rb');
                        fseek($handle, $start);
                        
                        $bufferSize = 8192; // 8KB 缓冲区
                        $bytesRemaining = $length;
                        
                        while ($bytesRemaining > 0 && !feof($handle)) {
                            $chunkSize = min($bufferSize, $bytesRemaining);
                            echo fread($handle, $chunkSize);
                            flush();
                            $bytesRemaining -= $chunkSize;
                        }
                        
                        fclose($handle);
                    }, 206);
                    
                    // 设置响应头
                    $response->headers->set('Content-Type', $mimeType);
                    $response->headers->set('Content-Length', $length);
                    $response->headers->set('Content-Range', "bytes {$start}-{$end}/{$fileSize}");
                    $response->headers->set('Accept-Ranges', 'bytes');
                    $response->headers->set('Cache-Control', 'public, max-age=3600');
                    
                    return $response;
                }
            }
            
            // 没有 Range 请求，返回完整文件
            Log::info('返回完整文件');
            
            $response = new StreamedResponse(function () use ($fullPath) {
                $handle = fopen($fullPath, 'rb');
                $bufferSize = 8192;
                
                while (!feof($handle)) {
                    echo fread($handle, $bufferSize);
                    flush();
                }
                
                fclose($handle);
            }, 200);
            
            $response->headers->set('Content-Type', $mimeType);
            $response->headers->set('Content-Length', $fileSize);
            $response->headers->set('Accept-Ranges', 'bytes');
            $response->headers->set('Cache-Control', 'public, max-age=3600');
            
            return $response;
            
        } catch (\Exception $e) {
            Log::error('视频流传输错误', [
                'message' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            
            return response()->json([
                'message' => '视频加载失败: ' . $e->getMessage()
            ], 500);
        }
    }
}