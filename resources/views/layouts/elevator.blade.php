<!DOCTYPE html>
<html lang="zh-CN">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
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
                    screens: {
                        'xs': '360px',
                        'sm': '640px',
                        'md': '768px',
                        'lg': '1024px',
                        'xl': '1280px',
                    }
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
                @apply bg-primary text-white rounded-xl shadow-md shadow-primary/20;
            }
            .menu-item {
                @apply px-4 py-3.5 flex items-center gap-3 cursor-pointer hover:bg-light rounded-xl transition-all duration-200 active:scale-[0.98];
            }
            .card {
                @apply bg-white rounded-2xl shadow-sm shadow-gray-200/50 p-5 md:p-6 border border-gray-100;
            }
            .sidebar-overlay {
                @apply fixed inset-0 bg-black/40 backdrop-blur-sm z-40 transition-opacity duration-300 md:hidden;
            }
            .table-responsive {
                @apply overflow-x-auto -mx-5 px-5 md:-mx-6 md:px-6;
            }
            .btn-group-responsive {
                @apply flex flex-wrap gap-2.5;
            }
            
            /* 移动端按钮优化 */
            .btn-mobile {
                @apply h-12 px-5 rounded-xl font-medium transition-all duration-200 active:scale-[0.97] shadow-sm;
            }
            
            .btn-primary {
                @apply btn-mobile bg-primary text-white hover:bg-dark shadow-primary/20 active:shadow-md;
            }
            
            .btn-secondary {
                @apply btn-mobile bg-gray-100 text-gray-700 hover:bg-gray-200 active:bg-gray-300;
            }
            
            .btn-success {
                @apply btn-mobile bg-green-500 text-white hover:bg-green-600 shadow-green-500/20;
            }
            
            .btn-danger {
                @apply btn-mobile bg-red-500 text-white hover:bg-red-600 shadow-red-500/20;
            }
            
            /* 移动端表单优化 */
            .form-input {
                @apply h-12 w-full px-4 rounded-xl border border-gray-200 bg-gray-50 focus:bg-white focus:border-primary focus:ring-4 focus:ring-primary/10 outline-none transition-all duration-200 text-base;
            }
            
            .form-select {
                @apply h-12 w-full px-4 rounded-xl border border-gray-200 bg-gray-50 focus:bg-white focus:border-primary focus:ring-4 focus:ring-primary/10 outline-none transition-all duration-200 text-base appearance-none bg-no-repeat bg-right pr-10;
                background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' fill='none' viewBox='0 0 24 24' stroke='%239CA3AF'%3E%3Cpath stroke-linecap='round' stroke-linejoin='round' stroke-width='2' d='M19 9l-7 7-7-7'%3E%3C/path%3E%3C/svg%3E");
                background-size: 1.25rem;
                background-position: right 0.75rem center;
            }
            
            .form-textarea {
                @apply w-full px-4 py-3 rounded-xl border border-gray-200 bg-gray-50 focus:bg-white focus:border-primary focus:ring-4 focus:ring-primary/10 outline-none transition-all duration-200 text-base resize-vertical min-h-[120px];
            }
            
            .form-label {
                @apply block text-sm font-medium text-gray-700 mb-2.5;
            }
            
            /* 移动端复选框优化 */
            .form-checkbox {
                @apply w-5 h-5 rounded-md border-2 border-gray-300 text-primary focus:ring-primary focus:ring-offset-2 cursor-pointer transition-all duration-200;
            }
            
            /* 移动端开关组件 */
            .form-toggle {
                @apply relative w-12 h-6 rounded-full bg-gray-200 cursor-pointer transition-colors duration-300 peer-checked:bg-primary;
            }
            .form-toggle::after {
                content: '';
                @apply absolute left-0.5 top-0.5 w-5 h-5 bg-white rounded-full shadow-md transition-transform duration-300;
            }
            .peer:checked + .form-toggle::after {
                transform: translateX(24px);
            }
            
            /* 移动端表格优化 */
            .table-mobile {
                @apply w-full;
            }
            .table-mobile th {
                @apply px-4 py-3.5 text-left text-sm font-semibold text-gray-600 bg-gray-50 first:rounded-tl-xl last:rounded-tr-xl;
            }
            .table-mobile td {
                @apply px-4 py-3.5 text-gray-700 border-b border-gray-100;
            }
            .table-mobile tr {
                @apply hover:bg-gray-50/50 transition-colors;
            }
            .table-mobile tr:last-child td {
                @apply border-b-0;
            }
        }
    </style>
        <meta name="csrf-token" content="{{ csrf_token() }}">
        <script src="{{ asset('js/common.js') }}"></script>
    </head>
<body class="bg-gray-50 min-h-screen text-[15px] antialiased">

<!-- 退出确认弹窗 -->
<div id="logoutModal" class="fixed inset-0 bg-black/40 backdrop-blur-sm flex items-center justify-center z-50 hidden">
    <div class="bg-white rounded-2xl shadow-2xl max-w-md w-full mx-4 animate-fade-in scale-95">
        <div class="p-6 border-b border-gray-100">
            <div class="flex items-center gap-4">
                <div class="w-14 h-14 rounded-2xl bg-primary/10 flex items-center justify-center">
                    <i class="ri-logout-box-line text-primary text-2xl"></i>
                </div>
                <div>
                    <h3 class="text-lg font-semibold text-gray-800">退出登录</h3>
                    <p class="text-sm text-gray-500">确认要退出当前账号吗？</p>
                </div>
            </div>
        </div>
        <div class="p-6">
            <p class="text-gray-600 mb-6">您确定要退出电梯管理系统吗？退出后需要重新登录才能继续使用。</p>
            <div class="flex flex-col sm:flex-row gap-3">
                <button onclick="hideLogoutModal()" class="flex-1 btn-secondary">
                    取消
                </button>
                <form action="{{ route('logout') }}" method="POST" class="flex-1">
                    @csrf
                    <button type="submit" class="w-full btn-primary">
                        确认退出
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- 侧边栏遮罩 -->
<div id="sidebarOverlay" class="sidebar-overlay opacity-0 pointer-events-none" onclick="toggleSidebar()"></div>

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

// 移动端侧边栏控制
function toggleSidebar() {
    const sidebar = document.getElementById('sidebar');
    const overlay = document.getElementById('sidebarOverlay');
    
    if (sidebar.classList.contains('-translate-x-full')) {
        sidebar.classList.remove('-translate-x-full');
        overlay.classList.remove('opacity-0', 'pointer-events-none');
        overlay.classList.add('opacity-100');
    } else {
        sidebar.classList.add('-translate-x-full');
        overlay.classList.add('opacity-0', 'pointer-events-none');
        overlay.classList.remove('opacity-100');
    }
}

// 窗口尺寸变化时自动处理侧边栏
window.addEventListener('resize', function() {
    if (window.innerWidth >= 768) {
        document.getElementById('sidebar').classList.remove('-translate-x-full');
        document.getElementById('sidebarOverlay').classList.add('opacity-0', 'pointer-events-none');
    } else {
        document.getElementById('sidebar').classList.add('-translate-x-full');
    }
});

// 初始化侧边栏状态
document.addEventListener('DOMContentLoaded', function() {
    if (window.innerWidth < 768) {
        document.getElementById('sidebar').classList.add('-translate-x-full');
    }
    
    // 移动端点击反馈
    document.querySelectorAll('button, a, .menu-item').forEach(el => {
        el.addEventListener('touchstart', function() {
            this.classList.add('scale-[0.98]');
        }, { passive: true });
        el.addEventListener('touchend', function() {
            this.classList.remove('scale-[0.98]');
        }, { passive: true });
    });
});
</script>
    <div class="flex h-screen overflow-hidden">
        <!-- 左侧菜单 -->
        <aside id="sidebar" class="fixed md:relative z-50 w-64 bg-sidebar border-r border-gray-200 flex flex-col h-full transition-transform duration-300 ease-out shadow-xl md:shadow-none">
            <!-- Logo区域 -->
            <div class="h-16 flex items-center px-5 border-b border-gray-200">
                <i class="ri-building-4-line text-primary text-2xl"></i>
                <h1 class="ml-3 text-lg font-bold text-gray-800">电梯管理系统</h1>
                <button onclick="toggleSidebar()" class="md:hidden ml-auto text-gray-500 hover:text-primary p-2 rounded-xl hover:bg-gray-100 transition-colors">
                    <i class="ri-close-line text-xl"></i>
                </button>
            </div>
            
            <!-- 菜单导航 -->
            <nav class="flex-1 p-4 overflow-y-auto">
                <div class="space-y-1.5">
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
        <div class="flex-1 flex flex-col overflow-hidden w-full">
            <!-- 顶部导航栏 -->
            <header class="h-16 bg-white border-b border-gray-200 flex items-center justify-between px-4 md:px-6 shadow-sm">
                <div class="flex items-center">
                    <button onclick="toggleSidebar()" class="md:hidden mr-3 text-gray-500 hover:text-primary p-2.5 rounded-xl hover:bg-gray-100 transition-colors">
                        <i class="ri-menu-line text-xl"></i>
                    </button>
                    <h2 class="text-base md:text-lg font-medium text-gray-700 truncate max-w-[200px] sm:max-w-none">@yield('page-title')</h2>
                </div>
                
                <div class="flex items-center gap-2 md:space-x-4">
                    @auth
                        <span class="hidden md:inline text-gray-600">
                            <i class="ri-user-line mr-1"></i>欢迎您，<span class="font-medium text-primary">{{ Auth::user()->name }}</span>
                        </span>
                        
                        <a href="{{ route('user.profile') }}" class="hidden md:flex items-center gap-2 px-3 py-2 text-gray-600 hover:text-primary hover:bg-light rounded-xl transition-all">
                            <i class="ri-user-settings-line"></i>
                            <span>个人中心</span>
                        </a>
                        
                        <button type="button" onclick="showLogoutModal()" class="flex items-center gap-1 md:gap-2 px-2 md:px-3 py-2 text-gray-600 hover:text-primary hover:bg-light rounded-xl transition-all">
                            <i class="ri-logout-box-line"></i>
                            <span class="hidden sm:inline">退出登录</span>
                        </button>
                        
                        <div class="w-10 h-10 rounded-full bg-primary flex items-center justify-center text-white font-medium flex-shrink-0">
                            {{ strtoupper(substr(Auth::user()->name, 0, 1)) }}
                        </div>
                    @else
                        <a href="{{ route('login') }}" class="flex items-center gap-2 px-3 py-2 text-gray-600 hover:text-primary hover:bg-light rounded-xl transition-all">
                            <i class="ri-login-box-line"></i>
                            <span>用户登录</span>
                        </a>
                    @endauth
                </div>
            </header>
            
            <!-- 内容区域 -->
            <main class="flex-1 overflow-auto p-4 md:p-6 bg-gray-50">
                @yield('content')
            </main>
        </div>
    </div>
</body>
</html>