@extends('layouts.elevator')

@section('title', '年检预警')
@section('page-title', '年检预警')

@section('content')
<!-- 今日预警弹窗 -->
@if($todayWarning->count() > 0)
<div id="warningModal" class="fixed inset-0 bg-black/50 z-50 flex items-center justify-center">
    <div class="bg-white rounded-xl shadow-xl p-6 max-w-lg w-11/12 transform transition-all animate-pulse">
        <div class="flex items-center mb-4">
            <div class="w-14 h-14 rounded-full bg-red-100 flex items-center justify-center mr-4 animate-bounce">
                <i class="ri-alarm-warning-line text-3xl text-red-600"></i>
            </div>
            <div>
                <h3 class="text-xl font-semibold text-red-600">年检预警提醒</h3>
                <p class="text-gray-500">今日有 {{ $todayWarning->count() }} 台电梯需要年检</p>
            </div>
        </div>
        
        <div class="bg-red-50 border border-red-200 rounded-lg p-4 mb-6 max-h-60 overflow-y-auto">
            <ul class="space-y-2">
                @foreach($todayWarning as $item)
                <li class="flex items-center">
                    <i class="ri-checkbox-blank-circle-fill text-red-500 text-xs mr-2"></i>
                    <span class="text-red-800 font-medium">{{ $item->inspection_devices }}</span>
                    <span class="text-gray-500 text-sm ml-2">负责人: {{ $item->responsible_person }}</span>
                </li>
                @endforeach
            </ul>
        </div>
        
        <div class="flex gap-3 justify-end">
            <button type="button" onclick="closeWarningModal()" class="px-6 py-2 bg-primary text-white rounded-lg hover:bg-dark transition-colors">
                知道了，查看详情
            </button>
        </div>
    </div>
</div>
@endif

<div class="card">
    <div class="flex justify-between items-center mb-6">
        <h3 class="text-xl font-semibold text-gray-800">电梯年检预警管理</h3>
        @auth
        @if(Auth::user()->role == 1)
        <button type="button" onclick="showAddModal()" class="px-4 py-2 bg-primary text-white rounded-lg hover:bg-dark transition-colors">
            <i class="ri-add-line mr-1"></i>添加年检
        </button>
        @endif
        @endauth
    </div>
    
    <!-- 状态快速筛选按钮 -->
    <div class="flex flex-wrap gap-3 mb-4">
        <a href="{{ route('elevator.warning', request()->except('status')) }}" class="px-4 py-2 rounded-lg transition-colors {{ !request('status') ? 'bg-gray-700 text-white' : 'bg-gray-100 text-gray-600 hover:bg-gray-200' }}">
            全部
        </a>
        <a href="{{ route('elevator.warning', array_merge(request()->all(), ['status' => '0'])) }}" class="px-4 py-2 rounded-lg transition-colors {{ request('status') == '0' ? 'bg-yellow-500 text-white' : 'bg-yellow-100 text-yellow-700 hover:bg-yellow-200' }}">
            <i class="ri-time-line mr-1"></i>未检查
        </a>
        <a href="{{ route('elevator.warning', array_merge(request()->all(), ['status' => '1'])) }}" class="px-4 py-2 rounded-lg transition-colors {{ request('status') == '1' ? 'bg-green-500 text-white' : 'bg-green-100 text-green-700 hover:bg-green-200' }}">
            <i class="ri-checkbox-circle-line mr-1"></i>已检查
        </a>
        <a href="{{ route('elevator.warning', array_merge(request()->all(), ['status' => '2'])) }}" class="px-4 py-2 rounded-lg transition-colors {{ request('status') == '2' ? 'bg-red-500 text-white' : 'bg-red-100 text-red-700 hover:bg-red-200' }}">
            <i class="ri-error-warning-line mr-1"></i>已过期
        </a>
    </div>

    <!-- 搜索过滤栏 -->
    <form action="" method="GET" class="mb-6">
        <div class="flex gap-3 flex-wrap">
            <input type="text" name="keyword" value="{{ request('keyword') }}" placeholder="搜索电梯名称、负责人..." class="flex-1 min-w-[200px] px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary focus:border-primary outline-none">
            <button type="submit" class="px-6 py-2 bg-gray-700 text-white rounded-lg hover:bg-gray-800 transition-colors">
                <i class="ri-search-line mr-1"></i>查询
            </button>
            @if(request('keyword') || request('status') != '')
            <a href="{{ route('elevator.warning') }}" class="px-6 py-2 bg-gray-200 text-gray-700 rounded-lg hover:bg-gray-300 transition-colors">
                重置
            </a>
            @endif
        </div>
    </form>
    
    <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-6">
        <div class="bg-red-50 border border-red-200 p-4 rounded-lg">
            <div class="flex items-center">
                <div class="bg-red-100 p-3 rounded-lg">
                    <i class="ri-alarm-warning-line text-2xl text-red-600"></i>
                </div>
                <div class="ml-4">
                    <p class="text-sm text-gray-500">今日待检</p>
                    <h3 class="text-2xl font-bold text-red-600">{{ $todayWarning->count() }}</h3>
                </div>
            </div>
        </div>
        
        <div class="bg-yellow-50 border border-yellow-200 p-4 rounded-lg">
            <div class="flex items-center">
                <div class="bg-yellow-100 p-3 rounded-lg">
                    <i class="ri-time-line text-2xl text-yellow-600"></i>
                </div>
                <div class="ml-4">
                    <p class="text-sm text-gray-500">待检总数</p>
                    <h3 class="text-2xl font-bold text-yellow-600">{{ $maintenances->where('status', 0)->count() }}</h3>
                </div>
            </div>
        </div>
        
        <div class="bg-green-50 border border-green-200 p-4 rounded-lg">
            <div class="flex items-center">
                <div class="bg-green-100 p-3 rounded-lg">
                    <i class="ri-checkbox-circle-line text-2xl text-green-600"></i>
                </div>
                <div class="ml-4">
                    <p class="text-sm text-gray-500">已完成</p>
                    <h3 class="text-2xl font-bold text-green-600">{{ $maintenances->where('status', 1)->count() }}</h3>
                </div>
            </div>
        </div>
    </div>
    
    <!-- 预警列表 -->
    <div class="overflow-x-auto">
        <table class="w-full">
            <thead>
                <tr class="bg-gray-50">
                    <th class="px-4 py-3 text-left text-sm font-medium text-gray-600">电梯编号</th>
                    <th class="px-4 py-3 text-left text-sm font-medium text-gray-600">电梯名称</th>
                    <th class="px-4 py-3 text-left text-sm font-medium text-gray-600">年检日期</th>
                    <th class="px-4 py-3 text-left text-sm font-medium text-gray-600">负责人</th>
                    <th class="px-4 py-3 text-left text-sm font-medium text-gray-600">联系电话</th>
                    <th class="px-4 py-3 text-left text-sm font-medium text-gray-600">备注</th>
                    <th class="px-4 py-3 text-left text-sm font-medium text-gray-600 cursor-pointer hover:bg-gray-100" onclick="sortTable()">
                        状态 <i class="ri-arrow-up-down-line ml-1"></i>
                    </th>
                    <th class="px-4 py-3 text-left text-sm font-medium text-gray-600">操作</th>
                </tr>
            </thead>
            <tbody>
                @foreach($maintenances as $item)
                <tr class="border-b border-gray-100 hover:bg-gray-50 @if($item->next_inspection_date->isToday() && $item->status == 0) bg-red-50 @endif">
                    <td class="px-4 py-3 text-gray-800 font-medium">{{ $item->inspection_devices }}</td>
                    <td class="px-4 py-3 text-gray-600">
                        @php
                            $device = \App\Models\Device::where('number', $item->inspection_devices)->first();
                        @endphp
                        {{ $device->name ?? '-' }}
                    </td>
<td class="px-4 py-3 text-gray-600">{{ $item->next_inspection_date->format('Y-m-d') }}</td>
                    <td class="px-4 py-3 text-gray-600">{{ $item->responsible_person }}</td>
                    <td class="px-4 py-3 text-gray-600">{{ $item->contact_phone }}</td>
                    <td class="px-4 py-3 text-gray-600">{{ $item->remark }}</td>
                    <td class="px-4 py-3">
                        @if($item->status == 0)
                            <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-yellow-100 text-yellow-700">
                                <i class="ri-time-line mr-1"></i>未检查
                            </span>
                        @elseif($item->status == 1)
                            <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-green-100 text-green-700">
                                <i class="ri-checkbox-circle-line mr-1"></i>已检查
                            </span>
                        @else
                            <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-red-100 text-red-700">
                                <i class="ri-error-warning-line mr-1"></i>已过期
                            </span>
                        @endif
                    </td>
                <td class="px-4 py-3">
                        @auth
                        @if(Auth::user()->role == 1 || Auth::user()->name == $item->responsible_person)
                            <div class="flex gap-2">
                                @if($item->status == 0 || $item->status == 2)
                                <form action="{{ route('maintenance.status', $item->id) }}" method="POST" class="inline">
                                    @csrf
                                    <input type="hidden" name="status" value="1">
                                    <button type="submit" class="px-3 py-1 bg-green-100 text-green-700 rounded-lg hover:bg-green-200 transition-colors text-sm font-medium" onclick="return confirm('确认标记为已检查？')">
                                        <i class="ri-checkbox-circle-line mr-1"></i>标记完成
                                    </button>
                                </form>
                                @else
                                <span class="px-3 py-1 bg-gray-100 text-gray-500 rounded-lg text-sm">无需操作</span>
                                @endif
                                
                                @auth
                                @if(Auth::user()->role == 1)
                                <form action="{{ route('maintenance.delete', $item->id) }}" method="POST" class="inline" onsubmit="return confirm('确定要删除此年检记录吗？此操作不可恢复！')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="px-3 py-1 bg-red-100 text-red-700 rounded-lg hover:bg-red-200 transition-colors text-sm font-medium">
                                        <i class="ri-delete-bin-line mr-1"></i>删除
                                    </button>
                                </form>
                                @endif
                                @endauth
                            </div>
                        @else
                            <span class="px-3 py-1 bg-gray-100 text-gray-500 rounded-lg text-sm">仅负责人或管理员可操作</span>
                        @endif
                        @endauth
                </td>
                </tr>
                @endforeach
                
                @if($maintenances->isEmpty())
                <tr>
                        <td colspan="8" class="px-4 py-8 text-center text-gray-500">
                            暂无年检预警数据
                        </td>
                </tr>
                @endif
            </tbody>
        </table>
    </div>
</div>

<!-- 添加年检弹窗 -->
<div id="addModal" class="fixed inset-0 bg-black/50 z-50 hidden items-center justify-center">
    <div class="bg-white rounded-xl shadow-xl p-6 max-w-md w-11/12 transform transition-all">
        <div class="flex justify-between items-center mb-4">
            <h3 class="text-lg font-semibold text-gray-800">添加年检记录</h3>
            <button type="button" onclick="closeAddModal()" class="text-gray-400 hover:text-gray-600">
                <i class="ri-close-line text-xl"></i>
            </button>
        </div>
        
        <form action="{{ route('maintenance.store') }}" method="POST" class="space-y-4" onsubmit="return validateForm()">
            @csrf
            
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">选择电梯 <span class="text-red-500">*</span></label>
                <select name="inspection_devices" id="inspection_devices" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary focus:border-primary outline-none">
                    <option value="">请选择电梯</option>
                    @foreach($devices as $device)
                    <option value="{{ $device->number }}">{{ $device->number }} - {{ $device->Position }}</option>
                    @endforeach
                </select>
            </div>
            
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">年检日期 <span class="text-red-500">*</span></label>
                <input type="date" name="next_inspection_date" id="next_inspection_date" value="{{ old('next_inspection_date', date('Y-m-d')) }}" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary focus:border-primary outline-none">
            </div>
            
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">负责人 <span class="text-red-500">*</span></label>
                <select name="responsible_person" id="responsible_person" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary focus:border-primary outline-none">
                    <option value="">请选择负责人</option>
                    @foreach($users as $user)
                    <option value="{{ $user->name }}">{{ $user->name }}</option>
                    @endforeach
                </select>
            </div>
            
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">联系电话</label>
                <input type="text" name="contact_phone" id="contact_phone" value="{{ old('contact_phone') }}" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary focus:border-primary outline-none" placeholder="将自动使用用户手机号">
            </div>
            
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">备注</label>
                <textarea name="remark" rows="2" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary focus:border-primary outline-none" placeholder="可选备注信息">{{ old('remark') }}</textarea>
            </div>
            
            <div id="formError" class="hidden bg-red-50 border border-red-200 text-red-700 px-4 py-3 rounded-lg">
                请填写所有必填项
            </div>
            
            <div class="flex gap-3 justify-end">
                <button type="button" onclick="closeAddModal()" class="px-5 py-2 bg-gray-200 text-gray-700 rounded-lg hover:bg-gray-300 transition-colors">
                    取消
                </button>
                <button type="submit" class="px-5 py-2 bg-primary text-white rounded-lg hover:bg-dark transition-colors">
                    添加
                </button>
            </div>
        </form>
    </div>
</div>

<script>
function closeWarningModal() {
    const modal = document.getElementById('warningModal');
    if (modal) {
        modal.classList.add('hidden');
    }
}

// 点击背景关闭
document.getElementById('warningModal')?.addEventListener('click', function(e) {
    if (e.target === this) {
        closeWarningModal();
    }
});

function showAddModal() {
    const modal = document.getElementById('addModal');
    modal.classList.remove('hidden');
    modal.classList.add('flex');
    document.getElementById('formError').classList.add('hidden');
}

function closeAddModal() {
    const modal = document.getElementById('addModal');
    modal.classList.add('hidden');
    modal.classList.remove('flex');
}

// 点击背景关闭添加弹窗
document.getElementById('addModal')?.addEventListener('click', function(e) {
    if (e.target === this) {
        closeAddModal();
    }
});

// 表单验证
function validateForm() {
    const device = document.getElementById('inspection_devices').value;
    const date = document.getElementById('next_inspection_date').value;
    const person = document.getElementById('responsible_person').value;
    
    if (!device || !date || !person) {
        document.getElementById('formError').classList.remove('hidden');
        return false;
    }
    
    return true;
}
</script>

<script>
function sortTable() {
    const url = new URL(window.location.href);
    const currentOrder = url.searchParams.get('order');
    
    let newOrder = 'asc';
    if (currentOrder === 'asc') {
        newOrder = 'desc';
    }
    
    url.searchParams.set('sort', 'status');
    url.searchParams.set('order', newOrder);
    
    // 保留查询参数
    if (document.querySelector('input[name="keyword"]').value) {
        url.searchParams.set('keyword', document.querySelector('input[name="keyword"]').value);
    }
    if (document.querySelector('select[name="status"]').value !== '') {
        url.searchParams.set('status', document.querySelector('select[name="status"]').value);
    }
    
    window.location.href = url.toString();
}
</script>
@endsection
