@extends('layouts.elevator')

@section('title', '校区管理')
@section('page-title', '校区管理')

@section('content')
<div class="card">
    <div class="flex justify-between items-center mb-6">
        <h3 class="text-xl font-semibold text-gray-800">校区信息管理</h3>
        <a href="{{ route('elevator.ledger') }}" class="px-4 py-2 bg-gray-200 text-gray-700 rounded-lg hover:bg-gray-300 transition-colors">
            返回台账
        </a>
    </div>

    @if(session('success'))
        <div class="bg-green-50 border border-green-200 text-green-700 px-4 py-3 rounded-lg mb-6">
            {{ session('success') }}
        </div>
    @endif

    @if ($errors->any())
        <div class="bg-red-50 border border-red-200 text-red-700 px-4 py-3 rounded-lg mb-6">
            <ul class="list-disc pl-5">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        <!-- 添加校区表单 -->
        <div class="card border border-gray-200">
            <h4 class="text-lg font-medium text-gray-800 mb-4">添加新校区</h4>
            <form action="{{ route('campus.store') }}" method="POST" class="space-y-4">
                @csrf
                
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">校区名称 <span class="text-red-500">*</span></label>
                    <input type="text" name="Campus" value="{{ old('Campus') }}" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary focus:border-primary outline-none" placeholder="例如：东校区、西校区">
                </div>
                
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">校区描述 <span class="text-red-500">*</span></label>
                    <textarea name="description" rows="3" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary focus:border-primary outline-none" placeholder="校区位置、范围等描述信息">{{ old('description') }}</textarea>
                </div>
                
                <button type="submit" class="px-5 py-2 bg-primary text-white rounded-lg hover:bg-dark transition-colors">
                    <i class="ri-add-line mr-1"></i>添加校区
                </button>
            </form>
        </div>

        <!-- 校区列表 -->
        <div class="card border border-gray-200">
            <h4 class="text-lg font-medium text-gray-800 mb-4">已添加校区列表</h4>
            
            <div class="overflow-x-auto">
                <table class="w-full">
                    <thead>
                        <tr class="bg-gray-50">
                            <th class="px-4 py-3 text-left text-sm font-medium text-gray-600">校区名称</th>
                            <th class="px-4 py-3 text-left text-sm font-medium text-gray-600">描述</th>
                            <th class="px-4 py-3 text-left text-sm font-medium text-gray-600">操作</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($campuses as $campus)
                        <tr class="border-b border-gray-100 hover:bg-gray-50">
                            <td class="px-4 py-3 text-gray-800 font-medium">{{ $campus->Campus }}</td>
                            <td class="px-4 py-3 text-gray-600">{{ Str::limit($campus->description, 30) }}</td>
                            <td class="px-4 py-3">
                                <form action="{{ route('campus.delete', $campus->id) }}" method="POST" class="inline" onsubmit="return confirm('确定要删除这个校区吗？')">
                                    @csrf
                                    <button type="submit" class="text-red-600 hover:text-red-800 font-medium">
                                        <i class="ri-delete-bin-line mr-1"></i>删除
                                    </button>
                                </form>
                            </td>
                        </tr>
                        @endforeach
                        
                        @if($campuses->isEmpty())
                        <tr>
                            <td colspan="3" class="px-4 py-8 text-center text-gray-500">
                                暂无校区数据，请在左侧表单添加
                            </td>
                        </tr>
                        @endif
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection