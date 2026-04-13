@extends('layouts.elevator')

@section('title', '修改电梯信息')
@section('page-title', '修改电梯信息')

@section('content')
<div class="card max-w-2xl mx-auto">
    <h3 class="text-xl font-semibold text-gray-800 mb-6">修改电梯信息</h3>

    @if ($errors->any())
        <div class="bg-red-50 border border-red-200 text-red-700 px-4 py-3 rounded-lg mb-6">
            <ul class="list-disc pl-5">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form action="{{ route('device.update', $device->id) }}" method="POST" class="space-y-6">
        @csrf
        
        <div>
            <label class="block text-sm font-medium text-gray-700 mb-2">电梯编号 <span class="text-red-500">*</span></label>
            <input type="text" name="number" value="{{ old('number', $device->number) }}" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary focus:border-primary outline-none" placeholder="请输入电梯编号">
        </div>

        <div>
            <label class="block text-sm font-medium text-gray-700 mb-2">电梯位置 <span class="text-red-500">*</span></label>
            <input type="text" name="Position" value="{{ old('Position', $device->Position) }}" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary focus:border-primary outline-none" placeholder="例如：1号楼东侧">
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">校区 <span class="text-red-500">*</span></label>
                <select name="Campus" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary focus:border-primary outline-none">
                    <option value="">请选择校区</option>
                    @foreach($campuses as $campus)
                    <option value="{{ $campus->Campus }}" {{ old('Campus', $device->Campus) == $campus->Campus ? 'selected' : '' }}>{{ $campus->Campus }}</option>
                    @endforeach
                </select>
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">楼号 <span class="text-red-500">*</span></label>
                <input type="text" name="building" value="{{ old('building', $device->building) }}" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary focus:border-primary outline-none" placeholder="例如：1号楼">
            </div>
        </div>

        <div>
            <label class="block text-sm font-medium text-gray-700 mb-2">电梯描述 <span class="text-red-500">*</span></label>
            <textarea name="desc" rows="4" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary focus:border-primary outline-none" placeholder="请输入电梯详细描述信息">{{ old('desc', $device->desc) }}</textarea>
        </div>

        <div class="flex gap-4">
            <button type="submit" class="px-6 py-3 bg-primary text-white rounded-lg hover:bg-dark transition-colors font-medium">
                <i class="ri-save-line mr-2"></i>保存修改
            </button>
            <a href="{{ route('device.show', $device->id) }}" class="px-6 py-3 bg-gray-200 text-gray-700 rounded-lg hover:bg-gray-300 transition-colors font-medium">
                取消
            </a>
        </div>
    </form>
</div>
@endsection