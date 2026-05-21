@extends('layouts.elevator')

@section('title', '文本预览')
@section('page-title', '文本预览')

@section('content')
<div class="card">
    <div class="flex flex-wrap justify-between items-center mb-6 gap-4">
        <h3 class="text-xl font-semibold text-gray-800">文本管理</h3>
        <div class="flex flex-wrap gap-3">
            <form action="{{ route('text-management.preview') }}" method="GET" class="flex gap-3">
                <input type="text" name="keyword" value="{{ request('keyword') }}" placeholder="搜索文本分组..." class="px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 w-64">
                <select name="textType" class="px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
                    <option value="">全部类型</option>
                    @foreach($textTypes as $type)
                        <option value="{{ $type->type }}" {{ request('textType') == $type->type ? 'selected' : '' }}>{{ $type->type }}</option>
                    @endforeach
                </select>
                <button type="submit" class="bg-blue-500 hover:bg-blue-600 text-white px-4 py-2 rounded-lg">
                    <i class="ri-search-line"></i> 搜索
                </button>
                <a href="{{ route('text-management.preview') }}" class="bg-gray-500 hover:bg-gray-600 text-white px-4 py-2 rounded-lg">
                    重置
                </a>
            </form>
            <a href="{{ route('text-management.create') }}" class="bg-green-500 hover:bg-green-600 text-white px-4 py-2 rounded-lg flex items-center gap-2">
                <i class="ri-add-line"></i> 添加文本
            </a>
        </div>
    </div>

    @if(session('success'))
        <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-4">
            {{ session('success') }}
        </div>
    @endif

    <div class="overflow-x-auto">
        <table class="w-full">
            <thead>
                <tr class="bg-gray-50 border-b">
                    <th class="text-left py-3 px-4 font-medium text-gray-600">文本类型</th>
                    <th class="text-left py-3 px-4 font-medium text-gray-600">内容摘要</th>
                    <th class="text-left py-3 px-4 font-medium text-gray-600">字数</th>
                    <th class="text-left py-3 px-4 font-medium text-gray-600">上传时间</th>
                    <th class="text-left py-3 px-4 font-medium text-gray-600">操作</th>
                </tr>
            </thead>
            <tbody>
                @foreach($texts as $text)
                <tr class="border-b hover:bg-gray-50">
                    <td class="py-3 px-4">
                        <span class="bg-blue-100 text-blue-700 px-2 py-1 rounded text-sm">{{ $text->TextType }}</span>
                    </td>
                    <td class="py-3 px-4 text-gray-600 max-w-md truncate">
                        {{ Str::limit(strip_tags($text->TextContent), 100) }}
                    </td>
                    <td class="py-3 px-4 text-gray-500">
                        {{ mb_strlen($text->TextContent ?? '') }}
                    </td>
                    <td class="py-3 px-4 text-gray-500">{{ $text->created_at->format('Y-m-d H:i') }}</td>
                    <td class="py-3 px-4">
                        <div class="flex gap-2">
                            <a href="{{ route('text-info.edit', $text->id) }}" class="bg-indigo-500 hover:bg-indigo-600 text-white px-3 py-1 rounded text-sm">
                                <i class="ri-edit-line"></i> 编辑
                            </a>
                            <button onclick="showDeleteModal('{{ route('text-info.delete', $text->id) }}')" class="bg-red-500 hover:bg-red-600 text-white px-3 py-1 rounded text-sm">
                                <i class="ri-delete-bin-line"></i> 删除
                            </button>
                        </div>
                    </td>
                </tr>
                @endforeach

                @if($texts->isEmpty())
                <tr>
                    <td colspan="5" class="py-8 text-center text-gray-500">
                        <i class="ri-inbox-line text-4xl mb-2 block"></i>
                        暂无文本数据
                    </td>
                </tr>
                @endif
            </tbody>
        </table>
    </div>

    <!-- 分页 -->
    @if($texts->hasPages())
    <div class="mt-6">
        {{ $texts->links() }}
    </div>
    @endif
</div>

<!-- 删除确认模态框 -->
<div id="deleteModal" class="fixed inset-0 bg-black bg-opacity-50 hidden items-center justify-center z-50">
    <div class="bg-white rounded-lg w-full max-w-sm mx-4 p-6">
        <div class="text-center">
            <i class="ri-error-warning-line text-5xl text-red-500 mb-4"></i>
            <h3 class="text-lg font-semibold text-gray-800 mb-2">确认删除</h3>
            <p class="text-gray-500 mb-6">确定要删除此文本吗？此操作不可恢复。</p>
            
            <form id="deleteForm" method="POST">
                @csrf
                <div class="flex justify-center gap-3">
                    <button type="button" onclick="closeDeleteModal()" class="px-4 py-2 border border-gray-300 rounded-lg text-gray-700 hover:bg-gray-50">
                        取消
                    </button>
                    <button type="submit" class="px-4 py-2 bg-red-500 text-white rounded-lg hover:bg-red-600">
                        确认删除
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
function showDeleteModal(url) {
    document.getElementById('deleteForm').action = url;
    document.getElementById('deleteModal').classList.remove('hidden');
    document.getElementById('deleteModal').classList.add('flex');
}

function closeDeleteModal() {
    document.getElementById('deleteModal').classList.add('hidden');
    document.getElementById('deleteModal').classList.remove('flex');
}
</script>
@endsection
