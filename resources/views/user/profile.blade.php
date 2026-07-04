@extends('layouts.elevator')

@section('title', '个人中心')
@section('page-title', '个人中心')

@section('content')
<div class="card max-w-2xl mx-auto">
    <div class="flex justify-between items-center mb-6">
        <h3 class="text-xl font-semibold text-gray-800">个人信息</h3>
    </div>

    @if(session('success'))
        <div class="bg-green-50 border border-green-200 text-green-700 px-4 py-3 rounded-lg mb-6">
            {{ session('success') }}
        </div>
    @endif

    <!-- 用户基本信息 -->
    <div class="bg-gray-50 p-6 rounded-lg mb-6">
        <div class="flex items-center gap-6">
            <div class="w-20 h-20 rounded-full bg-primary flex items-center justify-center text-white text-3xl font-bold">
                {{ strtoupper(mb_substr(Auth::user()->name, 0, 1, 'UTF-8')) }}
            </div>
            <div>
                <h3 class="text-2xl font-bold text-gray-800">{{ Auth::user()->name }}</h3>
                <p class="mt-2">
                    <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-blue-100 text-blue-800">
                        {{ Auth::user()->role == 1 ? '总监' : '电梯管理员' }}
                    </span>
                </p>
            </div>
        </div>
    </div>

    <!-- 账号信息 -->
    <div class="space-y-4">
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <div class="bg-gray-50 p-4 rounded-lg">
                <p class="text-sm text-gray-500 mb-1">用户ID</p>
                <p class="text-lg font-semibold text-gray-800">{{ Auth::user()->id }}</p>
            </div>
            <div class="bg-gray-50 p-4 rounded-lg">
                <p class="text-sm text-gray-500 mb-1">用户名</p>
                <p class="text-lg font-semibold text-gray-800">{{ Auth::user()->name }}</p>
            </div>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <div class="bg-gray-50 p-4 rounded-lg">
                <p class="text-sm text-gray-500 mb-1">用户角色</p>
                <p class="text-lg font-semibold text-gray-800">{{ Auth::user()->role == 1 ? '总监' : '电梯管理员' }}</p>
            </div>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <div class="bg-gray-50 p-4 rounded-lg">
                <p class="text-sm text-gray-500 mb-1">注册时间</p>
                <p class="text-lg font-semibold text-gray-800">{{ Auth::user()->created_at->format('Y-m-d H:i') }}</p>
            </div>
            <div class="bg-gray-50 p-4 rounded-lg">
                <p class="text-sm text-gray-500 mb-1">最后登录</p>
                <p class="text-lg font-semibold text-gray-800">{{ Auth::user()->updated_at->format('Y-m-d H:i') }}</p>
            </div>
        </div>
    </div>

    <!-- 可操作内容 -->
    <div class="mt-8">
        <h4 class="text-lg font-semibold text-gray-800 mb-4">快捷操作</h4>
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <a href="{{ route('elevator.ledger') }}" class="p-4 border border-gray-200 rounded-lg hover:border-primary hover:bg-light transition-all flex items-center gap-3">
                <i class="ri-building-4-line text-2xl text-primary"></i>
                <div>
                    <p class="font-medium text-gray-800">电梯台账</p>
                    <p class="text-sm text-gray-500">查看管理所有电梯设备</p>
                </div>
            </a>

            <a href="{{ route('data.upload') }}" class="p-4 border border-gray-200 rounded-lg hover:border-primary hover:bg-light transition-all flex items-center gap-3">
                <i class="ri-upload-cloud-line text-2xl text-green-600"></i>
                <div>
                    <p class="font-medium text-gray-800">上传资料</p>
                    <p class="text-sm text-gray-500">上传各类电梯管理资料</p>
                </div>
            </a>

            <a href="{{ route('data.query') }}" class="p-4 border border-gray-200 rounded-lg hover:border-primary hover:bg-light transition-all flex items-center gap-3">
                <i class="ri-search-line text-2xl text-blue-600"></i>
                <div>
                    <p class="font-medium text-gray-800">资料查询</p>
                    <p class="text-sm text-gray-500">查询和下载已上传资料</p>
                </div>
            </a>

        </div>
    </div>

    <!-- 密码修改区域 -->
    @if(Auth::user()->role == 1)
    <div class="mt-8 border-t border-gray-200 pt-6">
        <h4 class="text-lg font-semibold text-gray-800 mb-4">安全设置</h4>
        <div class="bg-gray-50 p-4 rounded-lg">
            <div class="flex justify-between items-center">
                <div>
                    <p class="font-medium text-gray-800">修改登录密码</p>
                    <p class="text-sm text-gray-500">定期修改密码保证账号安全</p>
                </div>
                <a href="{{ route('user.index') }}" class="px-4 py-2 bg-primary text-white rounded-lg hover:bg-dark transition-colors">
                    修改密码
                </a>
            </div>
        </div>
    </div>
    @endif
</div>
@endsection