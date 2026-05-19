@extends('layouts.elevator')

@section('title', '创建用户')
@section('page-title', '创建新用户')

@section('content')
<div class="card max-w-lg mx-auto">
    <h3 class="text-xl font-semibold text-gray-800 mb-6">添加系统用户</h3>

    @if ($errors->any())
        <div class="bg-red-50 border border-red-200 text-red-700 px-4 py-3 rounded-lg mb-6">
            <ul class="space-y-1">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form action="{{ route('user.store') }}" method="POST" class="space-y-5">
        @csrf
        
        <div>
            <label class="block text-sm font-medium text-gray-700 mb-2">用户名 <span class="text-red-500">*</span></label>
            <input type="text" name="name" value="{{ old('name') }}" 
                class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary focus:border-primary outline-none"
                placeholder="请输入登录用户名">
        </div>

        <div>
            <label class="block text-sm font-medium text-gray-700 mb-2">登录密码 <span class="text-red-500">*</span></label>
            <input type="password" name="password" 
                class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary focus:border-primary outline-none"
                placeholder="请设置登录密码">
        </div>

        <div>
            <label class="block text-sm font-medium text-gray-700 mb-2">用户角色 <span class="text-red-500">*</span></label>
            <div class="flex gap-4">
                <label class="flex items-center cursor-pointer">
                    <input type="radio" name="role" value="0" {{ old('role') == 0 ? 'checked' : '' }} class="w-4 h-4 text-primary">
                    <span class="ml-2 text-gray-700">电梯管理员</span>
                </label>
                <label class="flex items-center cursor-pointer">
                    <input type="radio" name="role" value="1" {{ old('role') == 1 ? 'checked' : '' }} class="w-4 h-4 text-primary">
                    <span class="ml-2 text-gray-700">总监</span>
                </label>
            </div>
        </div>

        <div class="flex gap-3 pt-4">
            <a href="{{ route('user.index') }}" class="flex-1 px-5 py-3 bg-gray-200 text-gray-700 rounded-lg hover:bg-gray-300 transition-colors text-center">
                返回
            </a>
            <button type="submit" class="flex-1 px-5 py-3 bg-primary text-white rounded-lg hover:bg-dark transition-colors">
                创建用户
            </button>
        </div>
    </form>
</div>
@endsection