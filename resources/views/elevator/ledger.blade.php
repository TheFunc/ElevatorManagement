@extends('layouts.elevator')

@section('title', '电梯台账')
@section('page-title', '电梯台账')

@section('content')
<!-- 统计卡片 -->
<div class="grid grid-cols-1 md:grid-cols-4 gap-4 mb-6">
    <div class="card p-4">
        <div class="flex items-center">
            <div class="bg-primary/10 p-3 rounded-lg">
                <i class="ri-building-4-line text-2xl text-primary"></i>
            </div>
            <div class="ml-4">
                <p class="text-gray-500 text-sm">电梯总数</p>
                <h3 class="text-2xl font-bold text-gray-800">{{ $devices->count() }}</h3>
            </div>
        </div>
    </div>
    
    <div class="card p-4">
        <div class="flex items-center">
            <div class="bg-green-100 p-3 rounded-lg">
                <i class="ri-file-list-3-line text-2xl text-green-600"></i>
            </div>
            <div class="ml-4">
                <p class="text-gray-500 text-sm">资料总数</p>
                <h3 class="text-2xl font-bold text-gray-800">{{ array_sum($fileStats) }}</h3>
            </div>
        </div>
    </div>
    
    <div class="card p-4">
        <div class="flex items-center">
            <div class="bg-blue-100 p-3 rounded-lg">
                <i class="ri-tools-line text-2xl text-blue-600"></i>
            </div>
            <div class="ml-4">
                <p class="text-gray-500 text-sm">维保资料</p>
                <h3 class="text-2xl font-bold text-gray-800">{{ $fileStats['maintenance'] }}</h3>
            </div>
        </div>
    </div>
    
    <div class="card p-4">
        <div class="flex items-center">
            <div class="bg-orange-100 p-3 rounded-lg">
                <i class="ri-alarm-warning-line text-2xl text-orange-600"></i>
            </div>
            <div class="ml-4">
                <p class="text-gray-500 text-sm">故障记录</p>
                <h3 class="text-2xl font-bold text-gray-800">{{ $fileStats['fault'] }}</h3>
            </div>
        </div>
    </div>
</div>

<div class="grid grid-cols-1 lg:grid-cols-3 gap-6 mb-6">
    <!-- 电梯列表 -->
    <div class="lg:col-span-2 card">
        <div class="flex justify-between items-center mb-4">
            <h3 class="text-xl font-semibold text-gray-800">电梯设备列表</h3>
            <div class="flex gap-3">
                @auth
                @if(Auth::user()->role == 1)
                <a href="{{ route('campus.index') }}" class="px-4 py-2 bg-secondary text-white rounded-lg hover:bg-primary transition-colors">
                    <i class="ri-building-line mr-1"></i>校区管理
                </a>
                <a href="{{ route('data.device') }}" class="px-4 py-2 bg-primary text-white rounded-lg hover:bg-dark transition-colors">
                    <i class="ri-add-line mr-1"></i>添加电梯
                </a>
                @endif
                @endauth
            </div>
        </div>

        <!-- 搜索栏 -->
        <form action="" method="GET" class="mb-6">
            <div class="flex gap-3 flex-wrap">
                <input type="text" name="keyword" value="{{ request('keyword') }}" placeholder="搜索电梯编号、名称、位置..." class="flex-1 min-w-[200px] px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary focus:border-primary outline-none">
                <select name="status" class="px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary focus:border-primary outline-none">
                    <option value="">全部状态</option>
                    <option value="1" {{ request('status') == '1' ? 'selected' : '' }}>在用</option>
                    <option value="0" {{ request('status') == '0' ? 'selected' : '' }}>停用</option>
                    <option value="2" {{ request('status') == '2' ? 'selected' : '' }}>废用</option>
                </select>
                <button type="submit" class="px-6 py-2 bg-gray-700 text-white rounded-lg hover:bg-gray-800 transition-colors">
                    <i class="ri-search-line mr-1"></i>查询
                </button>
                @if(request('keyword') || request('status') != '')
                <a href="{{ route('elevator.ledger') }}" class="px-6 py-2 bg-gray-200 text-gray-700 rounded-lg hover:bg-gray-300 transition-colors">
                    重置
                </a>
                @endif
            </div>
        </form>
        
        <!-- PC端表格 仅在桌面显示 -->
        <div class="hidden md:block overflow-x-auto">
            <table class="w-full">
                <thead>
                    <tr class="bg-gray-50">
                        <th class="px-4 py-3 text-left text-sm font-medium text-gray-600 cursor-pointer hover:bg-gray-100" onclick="sortTable('number')">
                            电梯编号 <i class="ri-arrow-up-down-line ml-1"></i>
                        </th>
                        <th class="px-4 py-3 text-left text-sm font-medium text-gray-600 cursor-pointer hover:bg-gray-100" onclick="sortTable('name')">
                            设备名称 <i class="ri-arrow-up-down-line ml-1"></i>
                        </th>
                        <th class="px-4 py-3 text-left text-sm font-medium text-gray-600">型号</th>
                        <th class="px-4 py-3 text-left text-sm font-medium text-gray-600">校区</th>
                        <th class="px-4 py-3 text-left text-sm font-medium text-gray-600">位置</th>
                        <th class="px-4 py-3 text-left text-sm font-medium text-gray-600">状态</th>
                        <th class="px-4 py-3 text-left text-sm font-medium text-gray-600">操作</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($devices as $device)
                    <tr class="border-b border-gray-100 hover:bg-gray-50">
                        <td class="px-4 py-3 text-gray-800 font-medium">{{ $device->number }}</td>
                        <td class="px-4 py-3 text-gray-600">{{ $device->name ?? '-' }}</td>
                        <td class="px-4 py-3 text-gray-600">{{ $device->Model ?? '-' }}</td>
                        <td class="px-4 py-3 text-gray-600">{{ $device->Campus ?? '-' }}</td>
                        <td class="px-4 py-3 text-gray-600">{{ $device->Position }}</td>
                        <td class="px-4 py-3">
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium 
                                {{ $device->status == 1 ? 'bg-green-100 text-green-800' : ($device->status == 0 ? 'bg-red-100 text-red-800' : 'bg-gray-200 text-gray-700') }}">
                                {{ $device->status == 1 ? '在用' : ($device->status == 0 ? '停用' : '废用') }}
                            </span>
                        </td>
                        <td class="px-4 py-3">
                            <a href="{{ route('device.show', $device->id) }}" class="text-primary hover:text-dark font-medium">
                                查看详情
                            </a>
                        </td>
                    </tr>
                    @endforeach
                    
                    @if($devices->isEmpty())
                    <tr>
                        <td colspan="7" class="px-4 py-8 text-center text-gray-500">
                            暂无电梯数据，请点击"添加电梯"录入设备信息
                        </td>
                    </tr>
                    @endif
                </tbody>
            </table>
        </div>
        
        <!-- 手机端卡片流布局 仅在移动端显示 -->
        <div class="md:hidden space-y-3">
            @foreach($devices as $device)
            <div class="bg-white rounded-xl border border-gray-100 shadow-sm overflow-hidden">
                <div class="flex p-4 gap-4">
                    <!-- 左侧图片 -->
                    <div class="flex-shrink-0 w-20 h-20 bg-gray-100 rounded-lg flex items-center justify-center">
                        <i class="ri-building-4-line text-3xl text-gray-400"></i>
                    </div>
                    
                    <!-- 右侧信息 -->
                    <div class="flex-1 min-w-0">
                        <div class="flex items-center justify-between mb-1">
                            <h4 class="font-semibold text-gray-800 truncate">{{ $device->number }}</h4>
                            <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium 
                                {{ $device->status == 1 ? 'bg-green-100 text-green-800' : ($device->status == 0 ? 'bg-red-100 text-red-800' : 'bg-gray-200 text-gray-700') }}">
                                {{ $device->status == 1 ? '在用' : ($device->status == 0 ? '停用' : '废用') }}
                            </span>
                        </div>
                        <p class="text-sm text-gray-600 mb-0.5">{{ $device->name ?? '-' }}</p>
                        <p class="text-xs text-gray-500 mb-0.5">{{ $device->Model ?? '-' }}</p>
                        <p class="text-xs text-gray-500 mb-0.5"><i class="ri-map-pin-line mr-1"></i>{{ $device->Campus ?? '-' }}</p>
                        <p class="text-xs text-gray-500 truncate">{{ $device->Position }}</p>
                    </div>
                </div>
                
                <!-- 操作按钮 -->
                <div class="border-t border-gray-100 px-4 py-3">
                    <a href="{{ route('device.show', $device->id) }}" class="w-full flex items-center justify-center gap-1.5 py-2.5 bg-primary/10 text-primary rounded-lg hover:bg-primary/20 transition-colors">
                        <i class="ri-eye-line"></i>
                        <span>查看详情</span>
                    </a>
                </div>
            </div>
            @endforeach
            
            @if($devices->isEmpty())
            <div class="py-12 text-center text-gray-500">
                <i class="ri-inbox-line text-4xl text-gray-300 mb-3"></i>
                <p>暂无电梯数据，请点击"添加电梯"录入设备信息</p>
            </div>
            @endif
        </div>
    </div>
    
    <!-- 资料统计饼图 -->
    <div class="card">
        <h3 class="text-xl font-semibold text-gray-800 mb-4">资料统计</h3>
        <div class="h-64 flex items-center justify-center p-4">
            <canvas id="fileChart"></canvas>
        </div>
        <div class="mt-4 space-y-2">
            <div class="flex items-center text-sm">
                <span class="w-3 h-3 rounded-full bg-blue-500 mr-2"></span>
                <span class="text-gray-600">准备资料: {{ $fileStats['prepare'] }}</span>
            </div>
            <div class="flex items-center text-sm">
                <span class="w-3 h-3 rounded-full bg-green-500 mr-2"></span>
                <span class="text-gray-600">维保资料: {{ $fileStats['maintenance'] }}</span>
            </div>
            <div class="flex items-center text-sm">
                <span class="w-3 h-3 rounded-full bg-yellow-500 mr-2"></span>
                <span class="text-gray-600">巡检资料: {{ $fileStats['inspection'] }}</span>
            </div>
            <div class="flex items-center text-sm">
                <span class="w-3 h-3 rounded-full bg-red-500 mr-2"></span>
                <span class="text-gray-600">故障记录: {{ $fileStats['fault'] }}</span>
            </div>
            <div class="flex items-center text-sm">
                <span class="w-3 h-3 rounded-full bg-purple-500 mr-2"></span>
                <span class="text-gray-600">维修记录: {{ $fileStats['repair'] }}</span>
            </div>
            <div class="flex items-center text-sm">
                <span class="w-3 h-3 rounded-full bg-orange-500 mr-2"></span>
                <span class="text-gray-600">事故记录: {{ $fileStats['accident'] }}</span>
            </div>
            <div class="flex items-center text-sm">
                <span class="w-3 h-3 rounded-full bg-teal-500 mr-2"></span>
                <span class="text-gray-600">救援演练: {{ $fileStats['rescue'] }}</span>
            </div>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
// 饼状图
const ctx = document.getElementById('fileChart').getContext('2d');
new Chart(ctx, {
    type: 'pie',
    data: {
        labels: ['准备资料', '维保资料', '巡检资料', '故障记录', '维修记录', '事故记录', '救援演练'],
        datasets: [{
            data: [
                {{ $fileStats['prepare'] }},
                {{ $fileStats['maintenance'] }},
                {{ $fileStats['inspection'] }},
                {{ $fileStats['fault'] }},
                {{ $fileStats['repair'] }},
                {{ $fileStats['accident'] }},
                {{ $fileStats['rescue'] }}
            ],
            backgroundColor: [
                '#3B82F6', '#10B981', '#EAB308', '#EF4444', '#8B5CF6', '#F97316', '#14B8A6'
            ],
            borderWidth: 0,
            hoverOffset: 0
        }]
    },
    options: {
        responsive: true,
        maintainAspectRatio: false,
        cutout: 0,
        plugins: {
            legend: {
                display: false
            },
            tooltip: {
                backgroundColor: 'rgba(0,0,0,0.8)',
                padding: 12,
                titleFont: {
                    size: 14
                },
                bodyFont: {
                    size: 13
                }
            }
        },
        animation: {
            animateRotate: false,
            animateScale: false,
            duration: 0
        },
        interaction: {
            mode: 'nearest',
            intersect: true
        }
    }
});
</script>

<script>
function sortTable(field) {
    const url = new URL(window.location.href);
    const currentSort = url.searchParams.get('sort');
    const currentOrder = url.searchParams.get('order');
    
    let newOrder = 'asc';
    if (currentSort === field && currentOrder === 'asc') {
        newOrder = 'desc';
    }
    
    url.searchParams.set('sort', field);
    url.searchParams.set('order', newOrder);
    
    // 保留其他查询参数
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
