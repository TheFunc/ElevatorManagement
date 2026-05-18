@extends('layouts.elevator')

@section('title', '用户管理')
@section('page-title', '用户管理')

@section('content')
<div class="card">
    <div class="flex justify-between items-center mb-6">
        <h3 class="text-xl font-semibold text-gray-800">系统用户管理</h3>
        <div class="flex gap-3 items-center">
            <div class="flex gap-2">
                <a href="{{ route('user.index', ['search' => $search, 'sort' => 'id', 'order' => 'asc']) }}" class="px-4 py-2 bg-gray-100 text-gray-700 rounded-lg hover:bg-gray-200 transition-colors {{ $sort == 'id' ? 'bg-primary text-white hover:bg-dark' : '' }}">
                    <i class="ri-sort-number-asc mr-1"></i>按ID排序
                </a>
                <a href="{{ route('user.index', ['search' => $search, 'sort' => 'role', 'order' => 'desc']) }}" class="px-4 py-2 bg-gray-100 text-gray-700 rounded-lg hover:bg-gray-200 transition-colors {{ $sort == 'role' ? 'bg-primary text-white hover:bg-dark' : '' }}">
                    <i class="ri-group-line mr-1"></i>按角色排序
                </a>
            </div>
            <form action="{{ route('user.index') }}" method="GET" class="flex gap-2">
                <input type="text" name="search" value="{{ $search }}" placeholder="搜索用户名" class="px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary focus:border-primary outline-none w-48">
                <button type="submit" class="px-4 py-2 bg-primary text-white rounded-lg hover:bg-dark transition-colors">
                    <i class="ri-search-line"></i>
                </button>
                @if($search)
                    <a href="{{ route('user.index') }}" class="px-4 py-2 bg-gray-200 text-gray-700 rounded-lg hover:bg-gray-300 transition-colors">
                        清除
                    </a>
                @endif
            </form>
            <a href="{{ route('user.create') }}" class="px-4 py-2 bg-primary text-white rounded-lg hover:bg-dark transition-colors">
                <i class="ri-add-line mr-1"></i>添加新用户
            </a>
        </div>
    </div>

    @if(session('success'))
        <div class="bg-green-50 border border-green-200 text-green-700 px-4 py-3 rounded-lg mb-6">
            {{ session('success') }}
        </div>
    @endif

    <div class="overflow-x-auto">
        <table class="w-full">
            <thead>
                <tr class="bg-gray-50">
                    <th class="px-4 py-3 text-left text-sm font-medium text-gray-600">
                        <a href="{{ route('user.index', ['search' => $search, 'sort' => 'id', 'order' => $sort == 'id' && $order == 'asc' ? 'desc' : 'asc']) }}" class="hover:text-primary flex items-center gap-1">
                            ID
                            @if($sort == 'id')
                                <i class="ri-arrow-{{ $order == 'asc' ? 'up' : 'down' }}-line"></i>
                            @endif
                        </a>
                    </th>
                    <th class="px-4 py-3 text-left text-sm font-medium text-gray-600">用户名</th>
                    <th class="px-4 py-3 text-left text-sm font-medium text-gray-600">
                        <a href="{{ route('user.index', ['search' => $search, 'sort' => 'role', 'order' => $sort == 'role' && $order == 'asc' ? 'desc' : 'asc']) }}" class="hover:text-primary flex items-center gap-1">
                            用户角色
                            @if($sort == 'role')
                                <i class="ri-arrow-down-line"></i>
                            @endif
                        </a>
                    </th>
                    <th class="px-4 py-3 text-left text-sm font-medium text-gray-600">创建时间</th>
                    <th class="px-4 py-3 text-left text-sm font-medium text-gray-600">登录时间</th>
                    <th class="px-4 py-3 text-left text-sm font-medium text-gray-600">操作</th>
                </tr>
            </thead>
            <tbody>
                @foreach($users as $user)
                <tr class="border-b border-gray-100 hover:bg-gray-50">
                    <td class="px-4 py-3 text-gray-600">{{ $user->id }}</td>
                    <td class="px-4 py-3 font-medium text-gray-800">{{ $user->name }}</td>
                    <td class="px-4 py-3">
                        @if($user->role == 1)
                            <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-primary/10 text-primary">
                                <i class="ri-admin-line mr-1"></i>总监
                            </span>
                        @else
                            <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-gray-100 text-gray-600">
                                <i class="ri-user-line mr-1"></i>电梯管理员
                            </span>
                        @endif
                    </td>
                    <td class="px-4 py-3 text-gray-600">{{ $user->created_at->format('Y-m-d') }}</td>
                    <td class="px-4 py-3 text-gray-500 text-sm">
                        @if($user->last_login_at)
                            {{ $user->last_login_at->format('Y-m-d H:i') }}
                        @else
                            从未登录
                        @endif
                    </td>
                    <td class="px-4 py-3">
                        <div class="flex gap-2">
                            <button onclick="showPasswordModal({{ $user->id }}, '{{ $user->name }}')" class="px-3 py-1.5 bg-primary/10 text-primary rounded hover:bg-primary/20 transition-colors text-sm">
                                <i class="ri-lock-password-line mr-1"></i>修改密码
                            </button>
                            
                            @if($user->name != 'admin')
                            <form action="{{ route('user.delete', $user->id) }}" method="POST" onsubmit="return confirm('确定要删除此用户吗？')" class="inline">
                                @csrf
                                <button type="submit" class="px-3 py-1.5 bg-red-100 text-red-600 rounded hover:bg-red-200 transition-colors text-sm">
                                    <i class="ri-delete-bin-line mr-1"></i>删除
                                </button>
                            </form>
                            @else
                            <span class="px-3 py-1.5 bg-gray-100 text-gray-400 rounded text-sm cursor-not-allowed" title="超级管理员账号受保护，不允许删除">
                                <i class="ri-shield-check-line mr-1"></i>受保护
                            </span>
                            @endif
                        </div>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    <div class="mt-6 text-sm text-gray-500">
        共 {{ $users->count() }} 个用户，其中总监 {{ $users->where('role', 1)->count() }} 名
    </div>
</div>

<!-- 修改密码弹窗 -->
<div id="passwordModal" class="fixed inset-0 bg-black/50 flex items-center justify-center z-50 hidden">
    <div class="bg-white rounded-xl shadow-xl max-w-md w-full mx-4">
        <div class="p-6 border-b border-gray-100">
            <div class="flex items-center gap-3">
                <div class="w-12 h-12 rounded-full bg-primary/10 flex items-center justify-center">
                    <i class="ri-lock-password-line text-primary text-xl"></i>
                </div>
                <div>
                    <h3 class="text-lg font-semibold text-gray-800">修改用户密码</h3>
                    <p id="modalUserName" class="text-sm text-gray-500"></p>
                </div>
            </div>
        </div>
        <form id="passwordForm" method="POST">
            @csrf
            <div class="p-6">
                <div class="space-y-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">新密码</label>
                        <input type="password" name="password" class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary focus:border-primary outline-none" placeholder="请输入新密码">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">确认密码</label>
                        <input type="password" name="password_confirmation" class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary focus:border-primary outline-none" placeholder="请再次输入密码">
                    </div>
                </div>
                <div class="flex gap-3 mt-6">
                    <button type="button" onclick="hidePasswordModal()" class="flex-1 px-4 py-3 bg-gray-100 text-gray-700 rounded-lg hover:bg-gray-200 transition-colors">
                        取消
                    </button>
                    <button type="submit" class="flex-1 px-4 py-3 bg-primary text-white rounded-lg hover:bg-dark transition-colors">
                        确认修改
                    </button>
                </div>
            </div>
        </form>
    </div>
</div>

<script>
function showPasswordModal(userId, userName) {
    document.getElementById('modalUserName').textContent = '正在修改用户：' + userName;
    document.getElementById('passwordForm').action = '/users/' + userId + '/password';
    document.getElementById('passwordModal').classList.remove('hidden');
}

function hidePasswordModal() {
    document.getElementById('passwordModal').classList.add('hidden');
    document.getElementById('passwordForm').reset();
}

// 点击背景关闭弹窗
document.getElementById('passwordModal').addEventListener('click', function(e) {
    if (e.target === this) {
        hidePasswordModal();
    }
});
</script>
@endsection
