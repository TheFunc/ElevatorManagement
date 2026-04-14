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

<!-- 退出确认弹窗 -->
<div id="logoutModal" class="fixed inset-0 bg-black/50 flex items-center justify-center z-50 hidden">
    <div class="bg-white rounded-xl shadow-xl max-w-md w-full mx-4 animate-fade-in">
        <div class="p-6 border-b border-gray-100">
            <div class="flex items-center gap-3">
                <div class="w-12 h-12 rounded-full bg-primary/10 flex items-center justify-center">
                    <i class="ri-logout-box-line text-primary text-xl"></i>
                </div>
                <div>
                    <h3 class="text-lg font-semibold text-gray-800">退出登录</h3>
                    <p class="text-sm text-gray-500">确认要退出当前账号吗？</p>
                </div>
            </div>
        </div>
        <div class="p-6">
            <p class="text-gray-600 mb-4">您确定要退出电梯管理系统吗？退出后需要重新登录才能继续使用。</p>
            <div class="flex gap-3">
                <button onclick="hideLogoutModal()" class="flex-1 px-4 py-3 bg-gray-100 text-gray-700 rounded-lg hover:bg-gray-200 transition-colors">
                    取消
                </button>
                <form action="{{ route('logout') }}" method="POST" class="flex-1">
                    @csrf
                    <button type="submit" class="w-full px-4 py-3 bg-primary text-white rounded-lg hover:bg-dark transition-colors">
                        确认退出
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
function showLogoutModal() {
    document.getElementById('logoutModal').classList.remove('hidden');
}
function hideLogoutModal() {
    document.getElementById('logoutModal').classList.add('hidden');
}
// 点击背景关闭弹窗
document.getElementById('logoutModal').addEventListener('click', function(e) {
    if (e.target === this) {
        hideLogoutModal();
    }
});
</script>
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

                    @auth
                        @if(Auth::user()->role == 1)
                        <a href="{{ route('user.index') }}" class="menu-item @if(Route::currentRouteName() == 'user.index' || Route::currentRouteName() == 'user.create') menu-item-active @endif">
                            <i class="ri-user-settings-line text-lg"></i>
                            <span>用户管理</span>
                        </a>
                        @endif
                    @endauth
                    
                    <div class="text-xs font-semibold text-gray-500 uppercase mb-3 mt-6 px-2">资料管理</div>
                    
                    @auth
                    @if(Auth::user()->role == 1)
                    <a href="{{ route('data.device') }}" class="menu-item @if(Route::currentRouteName() == 'data.device') menu-item-active @endif">
                        <i class="ri-database-2-line text-lg"></i>
                        <span>设备基础录入信息</span>
                    </a>
                    @endif
                    @endauth
                    
                    <a href="{{ route('data.upload') }}" class="menu-item @if(Route::currentRouteName() == 'data.upload') menu-item-active @endif">
                        <i class="ri-upload-cloud-line text-lg"></i>
                        <span>资料上传</span>
                    </a>
                    
                    <a href="{{ route('repair.orders') }}" class="menu-item @if(Route::currentRouteName() == 'repair.orders' || Route::currentRouteName() == 'repair.upload') menu-item-active @endif">
                        <i class="ri-file-list-2-line text-lg"></i>
                        <span>电梯单上传</span>
                    </a>

                    <a href="{{ route('data.query') }}" class="menu-item @if(Route::currentRouteName() == 'data.query') menu-item-active @endif">
                        <i class="ri-search-line text-lg"></i>
                        <span>资料查询</span>
                    </a>
                    
                    @auth
                    @if(Auth::user()->role == 1)
                    <div class="text-xs font-semibold text-gray-500 uppercase mb-3 mt-6 px-2">视频管理</div>
                    
                    <a href="{{ route('video.index') }}" class="menu-item @if(Route::currentRouteName() == 'video.index') menu-item-active @endif">
                        <i class="ri-video-line text-lg"></i>
                        <span>视频管理</span>
                    </a>
                    
                    <a href="{{ route('video.preview') }}" class="menu-item @if(Route::currentRouteName() == 'video.preview') menu-item-active @endif">
                        <i class="ri-eye-line text-lg"></i>
                        <span>视频预览</span>
                    </a>
                    
                    <a href="{{ route('video.create') }}" class="menu-item @if(Route::currentRouteName() == 'video.create') menu-item-active @endif">
                        <i class="ri-add-circle-line text-lg"></i>
                        <span>增加视频</span>
                    </a>
                    @endif
                    @endauth
                    
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
                    @auth
                        <span class="text-gray-600">
                            <i class="ri-user-line mr-1"></i>欢迎您，<span class="font-medium text-primary">{{ Auth::user()->name }}</span>
                        </span>
                        
                        <a href="{{ route('user.profile') }}" class="flex items-center gap-2 px-3 py-2 text-gray-600 hover:text-primary hover:bg-light rounded-lg transition-all">
                            <i class="ri-user-settings-line"></i>
                            <span>个人中心</span>
                        </a>
                        
                        <button type="button" onclick="showLogoutModal()" class="flex items-center gap-2 px-3 py-2 text-gray-600 hover:text-primary hover:bg-light rounded-lg transition-all">
                            <i class="ri-logout-box-line"></i>
                            <span>退出登录</span>
                        </button>
                        
                        <div class="w-10 h-10 rounded-full bg-primary flex items-center justify-center text-white font-medium">
                            {{ strtoupper(substr(Auth::user()->name, 0, 1)) }}
                        </div>
                    @else
                        <a href="{{ route('login') }}" class="flex items-center gap-2 px-3 py-2 text-gray-600 hover:text-primary hover:bg-light rounded-lg transition-all">
                            <i class="ri-login-box-line"></i>
                            <span>用户登录</span>
                        </a>
                    @endauth
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