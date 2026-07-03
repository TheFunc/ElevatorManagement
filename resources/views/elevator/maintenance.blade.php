@extends('layouts.elevator')

@section('title', '维保记录')
@section('page-title', '维保记录')

@section('content')
<div class="card">
    <div class="flex justify-between items-center mb-6">
        <h3 class="text-xl font-semibold text-gray-800">维保记录管理</h3>
        <a href="{{ route('data.upload') }}" class="px-4 py-2 bg-primary text-white rounded-lg hover:bg-dark transition-colors">
            <i class="ri-add-line mr-1"></i>添加维保记录
        </a>
    </div>
    
    <p class="text-gray-600 mb-6">查看和管理电梯维保历史记录</p>
    
    <!-- 搜索框 -->
    <form action="" method="GET" class="mb-6">
        <div class="flex gap-3">
            <input type="text" name="keyword" value="{{ request('keyword') }}" placeholder="搜索维保记录标题..." class="flex-1 px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary focus:border-primary outline-none">
            <button type="submit" class="px-6 py-2 bg-gray-700 text-white rounded-lg hover:bg-gray-800 transition-colors">
                <i class="ri-search-line mr-1"></i>搜索
            </button>
            @if(request('keyword'))
            <a href="{{ route('elevator.maintenance') }}" class="px-6 py-2 bg-gray-300 text-gray-700 rounded-lg hover:bg-gray-400 transition-colors">
                重置
            </a>
            @endif
        </div>
    </form>
    
    <!-- 维保记录表格 -->
    <div class="overflow-x-auto">
        <table class="w-full">
            <thead>
                <tr class="bg-gray-50">
                    <th class="px-4 py-3 text-left text-sm font-medium text-gray-600">文件标题</th>
                    <!-- 描述列已隐藏 -->
                    <th class="px-4 py-3 text-left text-sm font-medium text-gray-600 cursor-pointer hover:bg-gray-100" onclick="sortTable()">
                        上传时间 <i class="ri-arrow-up-down-line ml-1"></i>
                    </th>
                    <th class="px-4 py-3 text-left text-sm font-medium text-gray-600">操作</th>
                </tr>
            </thead>
            <tbody>
                @foreach($files as $file)
                <tr class="border-b border-gray-100 hover:bg-gray-50">
                    <td class="px-4 py-3 text-gray-800 font-medium">{{ $file->title }}</td>
                    <!-- 描述列已隐藏 -->
                    <td class="px-4 py-3 text-gray-600">{{ $file->created_at->format('Y-m-d H:i') }}</td>
                    <td class="px-4 py-3">
                        <a href="{{ route('file.download', $file->id) }}" class="text-green-600 hover:text-green-800 font-medium">
                            <i class="ri-download-line mr-1"></i>下载
                        </a>
                    </td>
                </tr>
                @endforeach
                
                @if($files->isEmpty())
                <tr>
                    <td colspan="3" class="px-4 py-8 text-center text-gray-500">
                        @if(request('keyword'))
                        未找到匹配的维保记录
                        @else
                        暂无维保记录，请点击右上角"添加维保记录"上传资料
                        @endif
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

<script>
function sortTable() {
    const url = new URL(window.location.href);
    const currentOrder = url.searchParams.get('order');
    
    let newOrder = 'asc';
    if (currentOrder === 'asc') {
        newOrder = 'desc';
    }
    
    url.searchParams.set('sort', 'created_at');
    url.searchParams.set('order', newOrder);
    
    // 保留搜索关键词
    if (document.querySelector('input[name="keyword"]').value) {
        url.searchParams.set('keyword', document.querySelector('input[name="keyword"]').value);
    }
    
    window.location.href = url.toString();
}
</script>
@endsection
