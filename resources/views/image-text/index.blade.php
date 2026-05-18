@extends('layouts.elevator')

@section('title', '图文管理')

@section('content')
<div class="min-h-screen bg-gray-50">
    <!-- 顶部导航栏 -->
    <div class="bg-white shadow-sm border-b">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-4">
            <div class="flex justify-between items-center">
                <div>
                    <h1 class="text-2xl font-bold text-gray-900">图文管理</h1>
                    <p class="mt-1 text-sm text-gray-500">创建和管理电梯相关的图文信息</p>
                </div>
                <a href="{{ route('image-text.create') }}" 
                   class="inline-flex items-center px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition-colors">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                    </svg>
                    新建图文
                </a>
            </div>
        </div>
    </div>

    <!-- 搜索和筛选区域 -->
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-6">
        @if(session('success'))
            <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-4">
                {{ session('success') }}
            </div>
        @endif

        @if(session('info'))
            <div class="bg-blue-100 border border-blue-400 text-blue-700 px-4 py-3 rounded mb-4">
                {{ session('info') }}
            </div>
        @endif

        <form method="GET" action="{{ route('image-text.index') }}" class="mb-6">
            <div class="flex gap-4 flex-wrap">
                <div class="flex-1 min-w-[300px]">
                    <input type="text" 
                           name="keyword" 
                           value="{{ request('keyword') }}"
                           placeholder="搜索标题或描述..." 
                           class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                </div>
                <select name="is_template" 
                        class="px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                    <option value="">全部类型</option>
                    <option value="1" {{ request('is_template') == '1' ? 'selected' : '' }}>模板</option>
                    <option value="0" {{ request('is_template') == '0' ? 'selected' : '' }}>普通图文</option>
                </select>
                <button type="submit" 
                        class="px-6 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors">
                    搜索
                </button>
                @if(request('keyword') || request('is_template'))
                <a href="{{ route('image-text.index') }}" 
                   class="px-6 py-2 bg-gray-200 text-gray-700 rounded-lg hover:bg-gray-300 transition-colors">
                    重置
                </a>
                @endif
            </div>
        </form>

        <!-- 图文列表网格 -->
        @if($imageTexts->count() > 0)
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6">
            @foreach($imageTexts as $item)
            <div class="bg-white rounded-lg shadow-md overflow-hidden hover:shadow-xl transition-shadow duration-300">
                <!-- 缩略图 -->
                <div class="relative h-48 bg-gray-100">
                    @if($item->thumbnail)
                    <img src="{{ asset($item->thumbnail) }}" 
                         alt="{{ $item->title }}" 
                         class="w-full h-full object-cover">
                    @else
                    <div class="w-full h-full flex items-center justify-center text-gray-400">
                        <svg class="w-16 h-16" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                                  d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                        </svg>
                    </div>
                    @endif
                    
                    @if($item->is_template)
                    <span class="absolute top-2 right-2 px-2 py-1 bg-purple-500 text-white text-xs rounded">模板</span>
                    @endif
                </div>

                <!-- 内容区域 -->
                <div class="p-4">
                    <h3 class="text-lg font-semibold text-gray-900 mb-2 truncate" title="{{ $item->title }}">
                        {{ $item->title }}
                    </h3>
                    
                    @if($item->description)
                    <p class="text-sm text-gray-600 mb-3 line-clamp-2">{{ Str::limit($item->description, 80) }}</p>
                    @endif

                    <div class="flex items-center justify-between text-xs text-gray-500 mb-3">
                        <span>{{ $item->created_at->format('Y-m-d') }}</span>
                        @if($item->creator)
                        <span>{{ $item->creator->name }}</span>
                        @endif
                    </div>

                    <!-- 操作按钮 -->
                    <div class="flex gap-2">
                        <a href="{{ route('image-text.show', $item->id) }}" 
                           target="_blank"
                           class="flex-1 px-3 py-2 bg-blue-50 text-blue-600 rounded hover:bg-blue-100 transition-colors text-center text-sm font-medium">
                            查看
                        </a>
                        <a href="{{ route('image-text.edit', $item->id) }}" 
                           class="flex-1 px-3 py-2 bg-green-50 text-green-600 rounded hover:bg-green-100 transition-colors text-center text-sm font-medium">
                            编辑
                        </a>
                        <button onclick="confirmDelete({{ $item->id }})" 
                                class="flex-1 px-3 py-2 bg-red-50 text-red-600 rounded hover:bg-red-100 transition-colors text-sm font-medium">
                            删除
                        </button>
                    </div>
                </div>
            </div>
            @endforeach
        </div>

        <!-- 分页 -->
        <div class="mt-8">
            {{ $imageTexts->links() }}
        </div>
        @else
        <!-- 空状态 -->
        <div class="text-center py-12">
            <svg class="mx-auto h-24 w-24 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                      d="M9 13h6m-3-3v6m5 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
            </svg>
            <h3 class="mt-4 text-lg font-medium text-gray-900">暂无图文</h3>
            <p class="mt-2 text-sm text-gray-500">点击"新建图文"开始创建您的第一个图文内容</p>
            <div class="mt-6">
                <a href="{{ route('image-text.create') }}" 
                   class="inline-flex items-center px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-blue-600 hover:bg-blue-700">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                    </svg>
                    新建图文
                </a>
            </div>
        </div>
        @endif
    </div>
</div>

<!-- 删除确认模态框 -->
<div id="deleteModal" class="hidden fixed inset-0 bg-black bg-opacity-50 z-50 flex items-center justify-center">
    <div class="bg-white rounded-lg p-6 max-w-md w-full mx-4">
        <h3 class="text-lg font-semibold text-gray-900 mb-4">确认删除</h3>
        <p class="text-gray-600 mb-6">您确定要删除这个图文吗？此操作不可恢复。</p>
        <div class="flex gap-4 justify-end">
            <button onclick="closeDeleteModal()" 
                    class="px-4 py-2 bg-gray-200 text-gray-700 rounded hover:bg-gray-300 transition-colors">
                取消
            </button>
            <form id="deleteForm" method="POST" style="display: inline;">
                @csrf
                @method('DELETE')
                <button type="submit" 
                        class="px-4 py-2 bg-red-600 text-white rounded hover:bg-red-700 transition-colors">
                    确认删除
                </button>
            </form>
        </div>
    </div>
</div>

<script>
function confirmDelete(id) {
    document.getElementById('deleteForm').action = `/image-text/${id}`;
    document.getElementById('deleteModal').classList.remove('hidden');
}

function closeDeleteModal() {
    document.getElementById('deleteModal').classList.add('hidden');
}
</script>
@endsection