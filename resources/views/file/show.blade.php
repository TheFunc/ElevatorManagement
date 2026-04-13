@extends('layouts.elevator')

@section('title', '文件详情')
@section('page-title', '文件详情')

@section('content')
<div class="card max-w-2xl mx-auto">
    <div class="flex justify-between items-center mb-6">
        <h3 class="text-xl font-semibold text-gray-800">文件详细信息</h3>
        <div class="flex gap-3">
            <a href="{{ route('file.download', $file->id) }}" class="px-4 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700 transition-colors">
                <i class="ri-download-line mr-1"></i>下载文件
            </a>
            <a href="{{ route('data.query') }}" class="px-4 py-2 bg-gray-200 text-gray-700 rounded-lg hover:bg-gray-300 transition-colors">
                返回列表
            </a>
        </div>
    </div>

    <!-- 文件类型标签 -->
    <div class="mb-6">
        <span class="inline-flex items-center px-4 py-2 rounded-lg text-base font-medium bg-{{ $fileTypes[$file->type]['color'] }}-100 text-{{ $fileTypes[$file->type]['color'] }}-700">
            <i class="{{ $fileTypes[$file->type]['icon'] }} mr-2 text-lg"></i>
            {{ $fileTypes[$file->type]['name'] }}
        </span>
    </div>

    <div class="space-y-6">
        <div class="bg-gray-50 p-5 rounded-lg">
            <p class="text-sm text-gray-500 mb-2">文件标题</p>
            <p class="text-xl font-semibold text-gray-800">{{ $file->title }}</p>
        </div>
        
        <div class="bg-gray-50 p-5 rounded-lg">
            <p class="text-sm text-gray-500 mb-2">文件描述</p>
            <p class="text-gray-800">{{ $file->desc ?? '暂无描述' }}</p>
        </div>
        
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <div class="bg-gray-50 p-5 rounded-lg">
                <p class="text-sm text-gray-500 mb-2">文件类型</p>
                <p class="text-gray-800 font-medium">{{ $fileTypes[$file->type]['name'] }}</p>
            </div>
            <div class="bg-gray-50 p-5 rounded-lg">
                <p class="text-sm text-gray-500 mb-2">文件路径</p>
                <p class="text-gray-600 text-sm font-mono">{{ $file->path }}</p>
            </div>
        </div>
        
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <div class="bg-gray-50 p-5 rounded-lg">
                <p class="text-sm text-gray-500 mb-2">上传时间</p>
                <p class="text-gray-800">{{ $file->created_at->format('Y-m-d H:i:s') }}</p>
            </div>
            <div class="bg-gray-50 p-5 rounded-lg">
                <p class="text-sm text-gray-500 mb-2">最后更新</p>
                <p class="text-gray-800">{{ $file->updated_at->format('Y-m-d H:i:s') }}</p>
            </div>
        </div>
    </div>
</div>
@endsection