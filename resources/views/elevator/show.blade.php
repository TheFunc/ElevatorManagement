@extends('layouts.elevator')

@section('title', '电梯详情')
@section('page-title', '电梯详情')

@section('content')
<div class="card max-w-2xl mx-auto">
    <div class="flex justify-between items-center mb-6">
        <h3 class="text-xl font-semibold text-gray-800">电梯详细信息</h3>
        <div class="flex gap-3">
            <a href="{{ route('device.edit', $device->id) }}" class="px-4 py-2 bg-primary text-white rounded-lg hover:bg-dark transition-colors">
                <i class="ri-edit-line mr-1"></i>修改信息
            </a>
            <a href="{{ route('elevator.ledger') }}" class="px-4 py-2 bg-gray-200 text-gray-700 rounded-lg hover:bg-gray-300 transition-colors">
                返回列表
            </a>
        </div>
    </div>

    @if(session('success'))
        <div class="bg-green-50 border border-green-200 text-green-700 px-4 py-3 rounded-lg mb-6">
            {{ session('success') }}
        </div>
    @endif

    <div class="space-y-6">
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <div class="bg-gray-50 p-4 rounded-lg">
                <p class="text-sm text-gray-500 mb-1">设备代码</p>
                <p class="text-lg font-semibold text-gray-800">{{ $device->number }}</p>
            </div>
            <div class="bg-gray-50 p-4 rounded-lg">
                <p class="text-sm text-gray-500 mb-1">设备名称</p>
                <p class="text-lg font-semibold text-gray-800">{{ $device->name ?? '未填写' }}</p>
            </div>
        </div>
        
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <div class="bg-gray-50 p-4 rounded-lg">
                <p class="text-sm text-gray-500 mb-1">电梯注册编号</p>
                <p class="text-lg font-semibold text-gray-800">{{ $device->register ?? '未填写' }}</p>
            </div>
            <div class="bg-gray-50 p-4 rounded-lg">
                <p class="text-sm text-gray-500 mb-1">出厂编号</p>
                <p class="text-lg font-semibold text-gray-800">{{ $device->FactorySerial ?? '未填写' }}</p>
            </div>
        </div>
        
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <div class="bg-gray-50 p-4 rounded-lg">
                <p class="text-sm text-gray-500 mb-1">设备型号</p>
                <p class="text-lg font-semibold text-gray-800">{{ $device->Model ?? '未填写' }}</p>
            </div>
            <div class="bg-gray-50 p-4 rounded-lg">
                <p class="text-sm text-gray-500 mb-1">制造厂家</p>
                <p class="text-lg font-semibold text-gray-800">{{ $device->Manufacturer ?? '未填写' }}</p>
            </div>
        </div>
        
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <div class="bg-gray-50 p-4 rounded-lg">
                <p class="text-sm text-gray-500 mb-1">使用状态</p>
                <p class="text-lg font-semibold 
                    {{ $device->status == 1 ? 'text-green-600' : ($device->status == 0 ? 'text-red-600' : 'text-gray-500') }}">
                    {{ $device->status == 1 ? '在用' : ($device->status == 0 ? '停用' : '报废') }}
                </p>
            </div>
            <div class="bg-gray-50 p-4 rounded-lg">
                <p class="text-sm text-gray-500 mb-1">下次年检时间</p>
                <div id="checkDisplay">
                    <p class="text-lg font-semibold">
                        @if($check && $check->next_check_at)
                            @php
                                $now = \Carbon\Carbon::now();
                                $checkDate = \Carbon\Carbon::parse($check->next_check_at);
                                $daysDiff = $now->diffInDays($checkDate, false);
                            @endphp
                            <span class="{{ $daysDiff < 0 ? 'text-red-600' : ($daysDiff <= 30 ? 'text-yellow-600' : ($daysDiff <= 60 ? 'text-orange-500' : 'text-green-600')) }}">
                                {{ $checkDate->format('Y-m-d') }}
                            </span>
                            @if($daysDiff < 0)
                                <span class="ml-2 inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-800">已逾期</span>
                            @elseif($daysDiff <= 30)
                                <span class="ml-2 inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-yellow-100 text-yellow-800">临近期</span>
                            @elseif($daysDiff <= 60)
                                <span class="ml-2 inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-orange-100 text-orange-800">临期2个月</span>
                            @else
                                <span class="ml-2 inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">未临期</span>
                            @endif
                        @else
                            <span class="text-gray-400">未设置</span>
                        @endif
                        <button onclick="toggleCheckEdit()" class="ml-2 px-2 py-1 text-xs text-primary hover:bg-primary/10 rounded transition-colors">
                            <i class="ri-edit-line"></i>修改
                        </button>
                    </p>
                </div>
                <div id="checkEditForm" style="display:none;">
                    <form action="{{ route('device.check.update', $device->id) }}" method="POST" class="flex items-center gap-2 mt-1">
                        @csrf
                        <input type="date" name="next_check_at" value="{{ $check && $check->next_check_at ? \Carbon\Carbon::parse($check->next_check_at)->format('Y-m-d') : '' }}" class="flex-1 px-3 py-1.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary focus:border-primary outline-none text-sm">
                        <button type="submit" class="px-3 py-1.5 bg-primary text-white rounded-lg hover:bg-dark transition-colors text-sm">
                            保存
                        </button>
                        <button type="button" onclick="toggleCheckEdit()" class="px-3 py-1.5 bg-gray-200 text-gray-700 rounded-lg hover:bg-gray-300 transition-colors text-sm">
                            取消
                        </button>
                    </form>
                </div>
            </div>
            <script>
                function toggleCheckEdit() {
                    var display = document.getElementById('checkDisplay');
                    var form = document.getElementById('checkEditForm');
                    if (display.style.display === 'none') {
                        display.style.display = '';
                        form.style.display = 'none';
                    } else {
                        display.style.display = 'none';
                        form.style.display = '';
                    }
                }
            </script>
        </div>
        
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <div class="bg-gray-50 p-4 rounded-lg">
                <p class="text-sm text-gray-500 mb-1">电梯位置</p>
                <p class="text-lg font-semibold text-gray-800">{{ $device->Position }}</p>
            </div>
        </div>
        
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <div class="bg-gray-50 p-4 rounded-lg">
                <p class="text-sm text-gray-500 mb-1">校区</p>
                <p class="text-lg font-semibold text-gray-800">{{ $device->Campus }}</p>
            </div>
            <div class="bg-gray-50 p-4 rounded-lg">
                <p class="text-sm text-gray-500 mb-1">楼号</p>
                <p class="text-lg font-semibold text-gray-800">{{ $device->building }}</p>
            </div>
        </div>
        
        <div class="bg-gray-50 p-4 rounded-lg">
            <p class="text-sm text-gray-500 mb-1">电梯描述</p>
            <p class="text-gray-800">{{ $device->desc }}</p>
        </div>
        
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <div class="bg-gray-50 p-4 rounded-lg">
                <p class="text-sm text-gray-500 mb-1">创建时间</p>
                <p class="text-gray-800">{{ $device->created_at->format('Y-m-d H:i') }}</p>
            </div>
            <div class="bg-gray-50 p-4 rounded-lg">
                <p class="text-sm text-gray-500 mb-1">最后更新</p>
                <p class="text-gray-800">{{ $device->updated_at->format('Y-m-d H:i') }}</p>
            </div>
        </div>
    </div>
</div>
@endsection