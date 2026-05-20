@extends('layouts.elevator')

@section('title', '编辑文本')
@section('page-title', '编辑文本')

@section('content')
<div class="card max-w-4xl mx-auto">
    <div class="mb-6">
        <h3 class="text-xl font-semibold text-gray-800">编辑文本</h3>
        <p class="text-gray-500 mt-1">修改文本内容和相关信息</p>
    </div>

    @if ($errors->any())
        <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-4">
            <ul class="list-disc list-inside">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form action="{{ route('text-info.update', $textInfo->id) }}" method="POST">
        @csrf
        
        <!-- 隐藏字段：TextGroup 默认为 null -->
        <input type="hidden" name="TextGroup" value="null">
        
        <!-- 文本类型 -->
        <div class="mb-6">
            <label class="block text-sm font-medium text-gray-700 mb-2">
                文本类型 <span class="text-red-500">*</span>
            </label>
            <select name="TextType" required class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
                <option value="">请选择文本类型</option>
                @foreach($textTypes as $type)
                    <option value="{{ $type->type }}" {{ $textInfo->TextType == $type->type ? 'selected' : '' }}>{{ $type->type }}</option>
                @endforeach
            </select>
        </div>

        <!-- 文本内容 -->
        <div class="mb-6">
            <label class="block text-sm font-medium text-gray-700 mb-2">
                文本内容
            </label>
            <textarea name="TextContent" rows="20" 
                      class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 font-mono text-sm"
                      placeholder="在此输入文本内容...">{{ old('TextContent', $textInfo->TextContent) }}</textarea>
        </div>

        <!-- 操作按钮 -->
        <div class="flex justify-end gap-3 pt-4 border-t">
            <a href="{{ route('text-management.preview') }}" class="px-6 py-2 border border-gray-300 rounded-lg text-gray-700 hover:bg-gray-50">
                取消
            </a>
            <button type="submit" class="px-6 py-2 bg-blue-500 text-white rounded-lg hover:bg-blue-600">
                <i class="ri-save-line"></i> 保存修改
            </button>
        </div>
    </form>
</div>
@endsection
