<!DOCTYPE html>
<html lang="zh-CN">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>电梯管理系统 - @yield('title')</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://cdn.jsdelivr.net/npm/remixicon@4.2.0/fonts/remixicon.css" rel="stylesheet">
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        primary: '#165DFF',
                        secondary: '#4080FF',
                        light: '#E8F3FF',
                        dark: '#0E42D2',
                        sidebar: '#F5F7FA'
                    },
                }
            }
        }
    </script>
    <style type="text/tailwindcss">
        @layer utilities {
            .content-auto {
                content-visibility: auto;
            }
            .menu-item-active {
                @apply bg-primary text-white rounded-lg shadow-md;
            }
            .menu-item {
                @apply px-4 py-3 flex items-center gap-3 cursor-pointer hover:bg-light rounded-lg transition-all duration-200;
            }
            .card {
                @apply bg-white rounded-xl shadow-sm p-6 border border-gray-100;
            }
        }
    </style>
</head>
<body class="bg-gray-50 min-h-screen">
    <div class="flex h-screen overflow-hidden">
        <!-- 左侧菜单 -->
        <aside class="w-64 bg-sidebar border-r border-gray-200 flex flex-col">
            <!-- Logo区域 -->
            <div class="h-16 flex items-center px-5 border-b border-gray-200">
                <i class="ri-building-4-line text-primary text-2xl"></i>
                <h1 class="ml-3 text-lg font-bold text-gray-800">电梯管理系统</h1>
            </div>
            
            <!-- 菜单导航 -->
            <nav class="flex-1 p-4 overflow-y-auto">
                <div class="space-y-1">
                    <div class="text-xs font-semibold text-gray-500 uppercase mb-3 px-2">系统管理</div>
                    
                    <a href="{{ route('elevator.ledger') }}" class="menu-item @if(Route::currentRouteName() == 'elevator.ledger') menu-item-active @endif">
                        <i class="ri-file-list-3-line text-lg"></i>
                        <span>电梯台账</span>
                    </a>
                    
                    <a href="{{ route('elevator.maintenance') }}" class="menu-item @if(Route::currentRouteName() == 'elevator.maintenance') menu-item-active @endif">
                        <i class="ri-tools-line text-lg"></i>
                        <span>维保记录</span>
                    </a>
                    
                    <a href="{{ route('elevator.warning') }}" class="menu-item @if(Route::currentRouteName() == 'elevator.warning') menu-item-active @endif">
                        <i class="ri-alarm-warning-line text-lg"></i>
                        <span>年检预警</span>
                    </a>
                    
                    <div class="text-xs font-semibold text-gray-500 uppercase mb-3 mt-6 px-2">资料管理</div>
                    
                    <a href="{{ route('data.device') }}" class="menu-item @if(Route::currentRouteName() == 'data.device') menu-item-active @endif">
                        <i class="ri-database-2-line text-lg"></i>
                        <span>设备基础录入信息</span>
                    </a>
                    
                    <a href="{{ route('data.prepare') }}" class="menu-item @if(Route::currentRouteName() == 'data.prepare') menu-item-active @endif">
                        <i class="ri-upload-cloud-line text-lg"></i>
                        <span>准备资料上传</span>
                    </a>
                    
                    <a href="{{ route('data.maintenance') }}" class="menu-item @if(Route::currentRouteName() == 'data.maintenance') menu-item-active @endif">
                        <i class="ri-file-settings-line text-lg"></i>
                        <span>维保资料上传</span>
                    </a>
                    
                    <a href="{{ route('data.inspection') }}" class="menu-item @if(Route::currentRouteName() == 'data.inspection') menu-item-active @endif">
                        <i class="ri-search-eye-line text-lg"></i>
                        <span>日常巡检资料上传</span>
                    </a>
                    
                    <a href="{{ route('data.fault') }}" class="menu-item @if(Route::currentRouteName() == 'data.fault') menu-item-active @endif">
                        <i class="ri-error-warning-line text-lg"></i>
                        <span>故障记录资料上传</span>
                    </a>
                    
                    <a href="{{ route('data.repair') }}" class="menu-item @if(Route::currentRouteName() == 'data.repair') menu-item-active @endif">
                        <i class="ri-wrench-line text-lg"></i>
                        <span>维修记录资料上传</span>
                    </a>
                    
                    <a href="{{ route('data.accident') }}" class="menu-item @if(Route::currentRouteName() == 'data.accident') menu-item-active @endif">
                        <i class="ri-alert-line text-lg"></i>
                        <span>事故记录资料上传</span>
                    </a>
                    
                    <a href="{{ route('data.rescue') }}" class="menu-item @if(Route::currentRouteName() == 'data.rescue') menu-item-active @endif">
                        <i class="ri-lifebuoy-line text-lg"></i>
                        <span>救援演练资料上传</span>
                    </a>
                    
                    <a href="{{ route('data.query') }}" class="menu-item @if(Route::currentRouteName() == 'data.query') menu-item-active @endif">
                        <i class="ri-search-line text-lg"></i>
                        <span>资料查询</span>
                    </a>
                </div>
            </nav>
        </aside>
        
        <!-- 主内容区域 -->
        <div class="flex-1 flex flex-col overflow-hidden">
            <!-- 顶部导航栏 -->
            <header class="h-16 bg-white border-b border-gray-200 flex items-center justify-between px-6 shadow-sm">
                <div class="flex items-center">
                    <h2 class="text-lg font-medium text-gray-700">@yield('page-title')</h2>
                </div>
                
                <div class="flex items-center space-x-4">
                    <button class="flex items-center gap-2 px-3 py-2 text-gray-600 hover:text-primary hover:bg-light rounded-lg transition-all">
                        <i class="ri-user-line"></i>
                        <span>用户设置</span>
                    </button>
                    
                    <button class="flex items-center gap-2 px-3 py-2 text-gray-600 hover:text-primary hover:bg-light rounded-lg transition-all">
                        <i class="ri-login-box-line"></i>
                        <span>用户登录</span>
                    </button>
                    
                    <button class="flex items-center gap-2 px-3 py-2 text-gray-600 hover:text-primary hover:bg-light rounded-lg transition-all">
                        <i class="ri-user-settings-line"></i>
                        <span>个人中心</span>
                    </button>
                    
                    <div class="w-10 h-10 rounded-full bg-primary flex items-center justify-center text-white font-medium">
                        A
                    </div>
                </div>
            </header>
            
            <!-- 内容区域 -->
            <main class="flex-1 overflow-auto p-6 bg-gray-50">
                @yield('content')
            </main>
        </div>
    </div>
</body>
</html>