@extends('layouts.elevator')

@section('title', '资料查询')
@section('page-title', '资料查询')

@section('content')
<div class="card">
    <div class="flex flex-wrap items-center justify-between mb-6">
        <h3 class="text-xl font-semibold text-gray-800">资料管理查询</h3>
        <!-- 类型图例 -->
        <div class="flex flex-wrap gap-3">
            @foreach($fileTypes as $key => $type)
            <div class="flex items-center">
                <span class="w-6 h-6 flex items-center justify-center rounded bg-{{ $type['color'] }}-100 text-{{ $type['color'] }}-600">
                    <i class="{{ $type['icon'] }}" style="font-size: 14px;"></i>
                </span>
                <span class="ml-1.5 text-sm text-gray-600">{{ $type['name'] }}</span>
            </div>
            @endforeach
        </div>
    </div>
    
    <!-- 搜索和过滤区域 -->
    <form action="" method="GET" class="mb-6">
        <div class="flex flex-wrap gap-3 items-end">
            <div class="flex-1 min-w-[200px]">
                <label class="block text-sm text-gray-600 mb-1">关键词搜索</label>
                <input type="text" name="keyword" value="{{ request('keyword') }}" placeholder="搜索文件标题..." class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary focus:border-primary outline-none">
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
    
    <!-- 资料表格 -->
    <div class="overflow-x-auto">
        <table class="w-full">
            <thead>
                <tr class="bg-gray-50">
                    <th class="px-4 py-3 text-left text-sm font-medium text-gray-600">类型</th>
                    <th class="px-4 py-3 text-left text-sm font-medium text-gray-600">文件标题</th>
                    <!-- 描述列已隐藏 -->
                    <th class="px-4 py-3 text-left text-sm font-medium text-gray-600">
                        <a href="{{ route('data.query', array_merge(request()->all(), ['sort' => 'created_at', 'order' => request('order', 'desc') == 'desc' ? 'asc' : 'desc'])) }}" class="inline-flex items-center gap-1 hover:text-primary transition-colors group">
                            上传时间
                            @php
                                $currentOrder = request('order', 'desc');
                            @endphp
                            <span class="inline-flex flex-col text-[10px] leading-none opacity-40 group-hover:opacity-100 transition-opacity">
                                <i class="ri-arrow-up-s-line {{ $currentOrder == 'asc' ? 'text-primary opacity-100' : '' }}" style="margin-bottom: -2px;"></i>
                                <i class="ri-arrow-down-s-line {{ $currentOrder == 'desc' ? 'text-primary opacity-100' : '' }}" style="margin-top: -2px;"></i>
                            </span>
                        </a>
                    </th>
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
                    <!-- 描述列已隐藏 -->
                    <td class="px-4 py-3 text-gray-600">
                        @php
                            $_descData = json_decode($file->desc, true);
                        @endphp
                        {{ is_array($_descData) && isset($_descData['event_time']) ? \Carbon\Carbon::parse($_descData['event_time'])->format('Y-m-d') : $file->created_at->format('Y-m-d') }}
                    </td>
                    <td class="px-4 py-3">
                        {{-- <a href="{{ route('file.show', $file->id) }}" class="text-primary hover:text-dark font-medium mr-3">查看详情</a> --}}
                        <a href="{{ route('file.download', $file->id) }}" class="text-green-600 hover:text-green-800 font-medium mr-3">
                            <i class="ri-download-line mr-1"></i>下载
                        </a>
                        <button type="button" class="text-red-600 hover:text-red-800 font-medium" onclick="showDeleteConfirm({{ $file->id }})">
                            <i class="ri-delete-bin-line mr-1"></i>删除
                        </button>
                    </td>
                </tr>
                @endforeach
                
                @if($files->isEmpty())
                <tr>
                    <td colspan="4" class="px-4 py-8 text-center text-gray-500">
                        暂无符合条件的资料文件
                    </td>
                </tr>
                @endif
            </tbody>
</table>
    </div>
    
    <!-- 分页 -->
    @if($files->hasPages())
    <div class="mt-6 flex justify-center">
        <div class="flex items-center gap-1.5">
            {{-- 上一页 --}}
            @if($files->onFirstPage())
                <span class="px-3 py-2 text-sm text-gray-400 bg-gray-50 rounded-xl cursor-not-allowed">
                    <i class="ri-arrow-left-s-line"></i>
                </span>
            @else
                <a href="{{ $files->previousPageUrl() }}" class="px-3 py-2 text-sm text-gray-700 bg-white border border-gray-200 rounded-xl hover:bg-light hover:border-primary hover:text-primary transition-all duration-200">
                    <i class="ri-arrow-left-s-line"></i>
                </a>
            @endif
            
            {{-- 页码 --}}
            @foreach($files->getUrlRange(1, $files->lastPage()) as $page => $url)
                @if($page == $files->currentPage())
                    <span class="px-4 py-2 text-sm font-medium text-white bg-primary rounded-xl shadow-sm shadow-primary/20">{{ $page }}</span>
                @else
                    <a href="{{ $url }}" class="px-4 py-2 text-sm text-gray-700 bg-white border border-gray-200 rounded-xl hover:bg-light hover:border-primary hover:text-primary transition-all duration-200">{{ $page }}</a>
                @endif
            @endforeach
            
            {{-- 下一页 --}}
            @if($files->hasMorePages())
                <a href="{{ $files->nextPageUrl() }}" class="px-3 py-2 text-sm text-gray-700 bg-white border border-gray-200 rounded-xl hover:bg-light hover:border-primary hover:text-primary transition-all duration-200">
                    <i class="ri-arrow-right-s-line"></i>
                </a>
            @else
                <span class="px-3 py-2 text-sm text-gray-400 bg-gray-50 rounded-xl cursor-not-allowed">
                    <i class="ri-arrow-right-s-line"></i>
                </span>
            @endif
        </div>
    </div>
    @endif
</div>

<!-- 自定义确认对话框 -->
<div id="deleteModal" class="fixed inset-0 bg-black/50 z-50 hidden items-center justify-center">
    <div class="bg-white rounded-xl shadow-xl p-6 max-w-md w-11/12 transform transition-all">
        <div class="flex items-center mb-4">
            <div class="w-12 h-12 rounded-full bg-red-100 flex items-center justify-center mr-4">
                <i class="ri-error-warning-line text-2xl text-red-600"></i>
            </div>
            <div>
                <h3 class="text-lg font-semibold text-gray-800">确认删除</h3>
                <p class="text-gray-500 text-sm">此操作将永久删除文件</p>
            </div>
        </div>
        
        <p class="text-gray-600 mb-6">确定要删除这个文件吗？删除后将无法恢复，同时会删除服务器上的物理文件。</p>
        
        <div class="flex gap-3 justify-end">
            <button type="button" onclick="closeDeleteModal()" class="px-5 py-2 bg-gray-200 text-gray-700 rounded-lg hover:bg-gray-300 transition-colors">
                取消
            </button>
            <form id="deleteForm" method="POST" style="display: inline;">
                @csrf
                <button type="submit" class="px-5 py-2 bg-red-600 text-white rounded-lg hover:bg-red-700 transition-colors">
                    <i class="ri-delete-bin-line mr-1"></i>确认删除
                </button>
            </form>
        </div>
    </div>
</div>

<script>
function showDeleteConfirm(id) {
    const modal = document.getElementById('deleteModal');
    const form = document.getElementById('deleteForm');
    
    form.action = `/file/${id}/delete`;
    modal.classList.remove('hidden');
    modal.classList.add('flex');
}

function closeDeleteModal() {
    const modal = document.getElementById('deleteModal');
    modal.classList.add('hidden');
    modal.classList.remove('flex');
}

// 点击背景关闭
document.getElementById('deleteModal').addEventListener('click', function(e) {
    if (e.target === this) {
        closeDeleteModal();
    }
});
</script>
@endsection
