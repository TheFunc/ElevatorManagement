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
            <a href="{{ route('data.device') }}" class="px-4 py-2 bg-primary text-white rounded-lg hover:bg-dark transition-colors">
                <i class="ri-add-line mr-1"></i>添加电梯
            </a>
        </div>
        
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead>
                    <tr class="bg-gray-50">
                        <th class="px-4 py-3 text-left text-sm font-medium text-gray-600">电梯编号</th>
                        <th class="px-4 py-3 text-left text-sm font-medium text-gray-600">位置</th>
                        <th class="px-4 py-3 text-left text-sm font-medium text-gray-600">描述</th>
                        <th class="px-4 py-3 text-left text-sm font-medium text-gray-600">操作</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($devices as $device)
                    <tr class="border-b border-gray-100 hover:bg-gray-50">
                        <td class="px-4 py-3 text-gray-800">{{ $device->number }}</td>
                        <td class="px-4 py-3 text-gray-600">{{ $device->Position }}</td>
                        <td class="px-4 py-3 text-gray-600">{{ Str::limit($device->desc, 30) }}</td>
                        <td class="px-4 py-3">
                            <a href="{{ route('device.show', $device->id) }}" class="text-primary hover:text-dark font-medium">
                                查看详情
                            </a>
                        </td>
                    </tr>
                    @endforeach
                    
                    @if($devices->isEmpty())
                    <tr>
                        <td colspan="4" class="px-4 py-8 text-center text-gray-500">
                            暂无电梯数据，请点击"添加电梯"录入设备信息
                        </td>
                    </tr>
                    @endif
                </tbody>
            </table>
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
            hoverOffset: 12
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
            animateRotate: true,
            animateScale: true,
            duration: 800,
            easing: 'easeOutQuart'
        },
        interaction: {
            mode: 'nearest',
            intersect: true
        }
    }
});
</script>
@endsection