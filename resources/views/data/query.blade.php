@extends('layouts.elevator')

@section('title', '资料查询')
@section('page-title', '资料查询')

@section('content')
<div class="card">
    <h3 class="text-xl font-semibold text-gray-800 mb-6">资料管理查询</h3>
    
    <!-- 搜索和过滤区域 -->
    <form action="" method="GET" class="mb-6">
        <div class="flex flex-wrap gap-3 items-end">
            <div class="flex-1 min-w-[200px]">
                <label class="block text-sm text-gray-600 mb-1">关键词搜索</label>
                <input type="text" name="keyword" value="{{ request('keyword') }}" placeholder="搜索文件标题或描述..." class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary focus:border-primary outline-none">
            </div>
            <div class="w-48">
                <label class="block text-sm text-gray-600 mb-1">文件类型</label>
                <select name="type" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary focus:border-primary outline-none">
                    <option value="">全部类型</option>
                    @foreach($fileTypes as $key => $type)
                    <option value="{{ $key }}" {{ request('type') == $key ? 'selected' : '' }}>{{ $type['name'] }}</option>
                    @endforeach
                </select>
            </div>
            <div>
                <button type="submit" class="px-6 py-2 bg-gray-700 text-white rounded-lg hover:bg-gray-800 transition-colors">
                    <i class="ri-search-line mr-1"></i>查询
                </button>
            </div>
            @if(request('keyword') || request('type'))
            <div>
                <a href="{{ route('data.query') }}" class="px-6 py-2 bg-gray-300 text-gray-700 rounded-lg hover:bg-gray-400 transition-colors inline-block">
                    重置
                </a>
            </div>
            @endif
        </div>
    </form>
    
    <!-- 类型图例 -->
    <div class="flex flex-wrap gap-4 mb-6 p-4 bg-gray-50 rounded-lg">
        @foreach($fileTypes as $key => $type)
        <div class="flex items-center">
            <span class="w-8 h-8 flex items-center justify-center rounded-lg bg-{{ $type['color'] }}-100 text-{{ $type['color'] }}-600">
                <i class="{{ $type['icon'] }}"></i>
            </span>
            <span class="ml-2 text-sm text-gray-700">{{ $type['name'] }}</span>
        </div>
        @endforeach
    </div>
    
    <!-- 资料表格 -->
    <div class="overflow-x-auto">
        <table class="w-full">
            <thead>
                <tr class="bg-gray-50">
                    <th class="px-4 py-3 text-left text-sm font-medium text-gray-600">类型</th>
                    <th class="px-4 py-3 text-left text-sm font-medium text-gray-600">文件标题</th>
                    <th class="px-4 py-3 text-left text-sm font-medium text-gray-600">描述</th>
                    <th class="px-4 py-3 text-left text-sm font-medium text-gray-600">上传时间</th>
                    <th class="px-4 py-3 text-left text-sm font-medium text-gray-600">操作</th>
                </tr>
            </thead>
            <tbody>
                @foreach($files as $file)
                <tr class="border-b border-gray-100 hover:bg-gray-50">
                    <td class="px-4 py-3">
                        <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-{{ $fileTypes[$file->type]['color'] }}-100 text-{{ $fileTypes[$file->type]['color'] }}-700">
                            <i class="{{ $fileTypes[$file->type]['icon'] }} mr-1"></i>
                            {{ $fileTypes[$file->type]['name'] }}
                        </span>
                    </td>
                    <td class="px-4 py-3 text-gray-800 font-medium">{{ $file->title }}</td>
                    <td class="px-4 py-3 text-gray-600">{{ Str::limit($file->desc, 40) }}</td>
                    <td class="px-4 py-3 text-gray-600">{{ $file->created_at->format('Y-m-d H:i') }}</td>
                    <td class="px-4 py-3">
                        <a href="{{ route('file.show', $file->id) }}" class="text-primary hover:text-dark font-medium mr-3">查看详情</a>
                        <a href="{{ route('file.download', $file->id) }}" class="text-green-600 hover:text-green-800 font-medium">
                            <i class="ri-download-line mr-1"></i>下载
                        </a>
                    </td>
                </tr>
                @endforeach
                
                @if($files->isEmpty())
                <tr>
                    <td colspan="5" class="px-4 py-8 text-center text-gray-500">
                        暂无符合条件的资料文件
                    </td>
                </tr>
                @endif
            </tbody>
        </table>
    </div>
    
    <!-- 分页 -->
    @if($files->hasPages())
    <div class="mt-6">
        {{ $files->links() }}
    </div>
    @endif
</div>
@endsection