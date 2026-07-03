<!DOCTYPE html>
<html lang="zh-CN">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
    <title>电梯资料管理 - <?php echo $__env->yieldContent('title'); ?></title>
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
        }
    </style>
    
    <!-- PC 端样式 -->
    <style media="(min-width: 768px)">
        /* PC端保持原有样式完全不变 */
        .table-responsive { overflow: visible; }
        .form-input { height: 42px; }
        .form-select { height: 42px; }
        .btn-mobile { height: 40px; padding: 0 16px; }
        .card { padding: 24px; }
        table th, table td { padding: 12px 16px; }
        
        /* 自定义滚动条样式 - 应用于所有横向滚动容器 */
        .overflow-x-auto::-webkit-scrollbar {
            height: 8px;
        }
        
        .overflow-x-auto::-webkit-scrollbar-track {
            background: #f1f5f9;
            border-radius: 4px;
        }
        
        .overflow-x-auto::-webkit-scrollbar-thumb {
            background: #cbd5e1;
            border-radius: 4px;
            transition: background 0.3s ease;
        }
        
        .overflow-x-auto::-webkit-scrollbar-thumb:hover {
            background: #94a3b8;
        }
        
        /* Firefox 滚动条样式 */
        .overflow-x-auto {
            scrollbar-width: thin;
            scrollbar-color: #cbd5e1 #f1f5f9;
        }
    </style>
    
    <!-- 手机端独立样式 -->
    <style media="(max-width: 767px)">
        /* ========== 手机端完全独立布局 ========== */
        
        /* 全局间距 */
        * {
            -webkit-tap-highlight-color: transparent;
        }
        
        body {
            font-size: 15px;
            line-height: 1.6;
        }
        
        /* 内容区域边距 */
        main {
            padding: 12px !important;
            gap: 16px;
        }
        
        /* 卡片样式 */
        .card {
            border-radius: 16px;
            padding: 16px !important;
            margin-bottom: 16px;
            box-shadow: 0 1px 3px rgba(0,0,0,0.05);
        }
        
        /* 按钮优化 */
        .btn-mobile {
            height: 48px;
            width: 100%;
            border-radius: 12px;
            font-size: 15px;
            font-weight: 500;
        }
        
        .btn-group-responsive {
            flex-direction: column;
            gap: 12px;
            width: 100%;
        }
        
        .btn-group-responsive button,
        .btn-group-responsive a {
            width: 100% !important;
        }
        
        /* 表单控件 */
        .form-input,
        .form-select {
            height: 50px !important;
            border-radius: 12px;
            font-size: 16px;
            padding: 0 16px;
            margin-bottom: 16px;
            width: 100%;
        }
        
        .form-textarea {
            min-height: 120px;
            border-radius: 12px;
            font-size: 16px;
            padding: 12px 16px;
            margin-bottom: 16px;
            width: 100%;
        }
        
        .form-label {
            margin-bottom: 8px;
            font-size: 14px;
            font-weight: 500;
        }
        
        .form-checkbox {
            width: 22px;
            height: 22px;
            border-radius: 6px;
        }
        
        /* 搜索栏 */
        .search-bar {
            flex-direction: column;
            gap: 12px;
        }
        
        .search-bar input,
        .search-bar select {
            width: 100% !important;
            min-width: 100% !important;
        }
        
        .search-bar button {
            width: 100%;
        }
        
        /* 表格适配 */
        .table-responsive {
            overflow-x: auto;
            margin: 0 -16px;
            padding: 0 16px;
            -webkit-overflow-scrolling: touch;
        }
        
        .table-mobile {
            min-width: 700px;
            border-spacing: 0;
        }
        
        .table-mobile th {
            padding: 14px 12px;
            font-size: 13px;
            font-weight: 600;
            white-space: nowrap;
        }
        
        .table-mobile td {
            padding: 14px 12px;
            font-size: 14px;
            line-height: 1.5;
        }
        
        .table-mobile tr {
            border-bottom: 1px solid #f3f4f6;
        }
        
        /* 统计卡片网格 */
        .grid-cols-1.md\:grid-cols-4,
        .grid-cols-1.lg\:grid-cols-3,
        .grid-cols-1.md\:grid-cols-2 {
            grid-template-columns: 1fr !important;
            gap: 12px !important;
        }
        
        /* 操作按钮组 */
        .action-buttons {
            flex-direction: column;
            gap: 8px;
            width: 100%;
        }
        
        .action-buttons a,
        .action-buttons button {
            width: 100%;
            justify-content: center;
            padding: 10px 16px;
        }
        
        /* 水平布局转垂直 */
        .flex-row.md\:flex-row {
            flex-direction: column !important;
            gap: 12px;
        }
        
        .flex.items-center.justify-between {
            flex-direction: column;
            align-items: flex-start;
            gap: 12px;
        }
        
        .flex.items-center.justify-between > div {
            width: 100%;
        }
        
        /* 间距调整 */
        .space-y-1 > * + * {
            margin-top: 12px;
        }
        
        .gap-3 {
            gap: 12px;
        }
        
        .gap-4 {
            gap: 16px;
        }
        
        .gap-6 {
            gap: 20px;
        }
        
        /* 文字优化 */
        h1, h2, h3 {
            line-height: 1.4;
        }
        
        /* 弹窗 */
        .modal-content {
            margin: 16px;
            border-radius: 20px;
        }
        
        /* 侧边栏 */
        #sidebar {
            width: 280px;
        }
    </style>

        <meta name="csrf-token" content="<?php echo e(csrf_token()); ?>">
        <script src="<?php echo e(asset('js/common.js')); ?>"></script>
    </head>
<body class="bg-gray-50 min-h-screen antialiased">

<!-- 退出确认弹窗 -->
<div id="logoutModal" class="fixed inset-0 bg-black/40 backdrop-blur-sm flex items-center justify-center z-50 hidden">
    <div class="bg-white rounded-2xl shadow-2xl max-w-md w-full mx-4 animate-fade-in scale-95 modal-content">
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
            <p class="text-gray-600 mb-6">您确定要退出电梯资料管理吗？退出后需要重新登录才能继续使用。</p>
            <div class="flex flex-col sm:flex-row gap-3">
                <button onclick="hideLogoutModal()" class="flex-1 px-5 py-3 bg-gray-100 text-gray-700 rounded-xl hover:bg-gray-200 active:bg-gray-300 transition-all">
                    取消
                </button>
                <form action="<?php echo e(route('logout')); ?>" method="POST" class="flex-1">
                    <?php echo csrf_field(); ?>
                    <button type="submit" class="w-full px-5 py-3 bg-primary text-white rounded-xl hover:bg-dark transition-all">
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
            this.style.transform = 'scale(0.98)';
        }, { passive: true });
        el.addEventListener('touchend', function() {
            this.style.transform = '';
        }, { passive: true });
        el.addEventListener('touchcancel', function() {
            this.style.transform = '';
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
                <h1 class="ml-3 text-lg font-bold text-gray-800">电梯资料管理</h1>
                <button onclick="toggleSidebar()" class="md:hidden ml-auto text-gray-500 hover:text-primary p-2 rounded-xl hover:bg-gray-100 transition-colors">
                    <i class="ri-close-line text-xl"></i>
                </button>
            </div>
            
            <!-- 菜单导航 -->
            <nav class="flex-1 p-4 overflow-y-auto">
                <div class="space-y-1.5">
                    <?php if(auth()->guard()->check()): ?>
                        <?php if(Auth::user()->role == 1): ?>
                        <div class="text-xs font-semibold text-gray-500 uppercase mb-3 px-2">用户管理</div>
                        
                        <a href="<?php echo e(route('user.index')); ?>" class="menu-item <?php if(Route::currentRouteName() == 'user.index' || Route::currentRouteName() == 'user.create'): ?> menu-item-active <?php endif; ?>">
                            <i class="ri-user-settings-line text-lg"></i>
                            <span>用户管理</span>
                        </a>
                        
                        <div class="h-px bg-gray-200 my-4"></div>
                        <?php endif; ?>
                    <?php endif; ?>

                    <div class="text-xs font-semibold text-gray-500 uppercase mb-3 px-2">信息列表显示</div>
                    
                    <a href="<?php echo e(route('elevator.ledger')); ?>" class="menu-item <?php if(Route::currentRouteName() == 'elevator.ledger'): ?> menu-item-active <?php endif; ?>">
                        <i class="ri-file-list-3-line text-lg"></i>
                        <span>电梯信息列表</span>
                    </a>
                    
                    <a href="<?php echo e(route('repair.orders')); ?>" class="menu-item <?php if(Route::currentRouteName() == 'elevator.maintenance'): ?> menu-item-active <?php endif; ?>">
                        <i class="ri-tools-line text-lg"></i>
                        <span>维保单列表</span>
                    </a>
                    
                    <!-- <a href="<?php echo e(route('elevator.warning')); ?>" class="menu-item <?php if(Route::currentRouteName() == 'elevator.warning'): ?> menu-item-active <?php endif; ?>">
                        <i class="ri-alarm-warning-line text-lg"></i>
                        <span>年检预警</span>
                    </a> -->
                    
                    <div class="text-xs font-semibold text-gray-500 uppercase mb-3 mt-6 px-2">资料管理</div>
                    
                    <?php if(auth()->guard()->check()): ?>
                    <?php if(Auth::user()->role == 1): ?>
                    <a href="<?php echo e(route('data.device')); ?>" class="menu-item <?php if(Route::currentRouteName() == 'data.device'): ?> menu-item-active <?php endif; ?>">
                        <i class="ri-database-2-line text-lg"></i>
                        <span>电梯基础录入</span>
                    </a>
                    <?php endif; ?>
                    <?php endif; ?>
                    
                    <a href="<?php echo e(route('data.upload')); ?>" class="menu-item <?php if(Route::currentRouteName() == 'data.upload'): ?> menu-item-active <?php endif; ?>">
                        <i class="ri-upload-cloud-line text-lg"></i>
                        <span>资料上传</span>
                    </a>
                    
                    <a href="<?php echo e(route('repair.orders')); ?>" class="menu-item <?php if(Route::currentRouteName() == 'repair.orders' || Route::currentRouteName() == 'repair.upload'): ?> menu-item-active <?php endif; ?>">
                        <i class="ri-file-list-2-line text-lg"></i>
                        <span>维保单上传</span>
                    </a>

                    <a href="<?php echo e(route('data.query')); ?>" class="menu-item <?php if(Route::currentRouteName() == 'data.query'): ?> menu-item-active <?php endif; ?>">
                        <i class="ri-search-line text-lg"></i>
                        <span>资料查询</span>
                    </a>
                    
                    <?php if(auth()->guard()->check()): ?>
                    <?php if(Auth::user()->role == 1): ?>
                    <div class="text-xs font-semibold text-gray-500 uppercase mb-3 mt-6 px-2">视频管理</div>
                    
                    <a href="<?php echo e(route('video.index')); ?>" class="menu-item <?php if(Route::currentRouteName() == 'video.index'): ?> menu-item-active <?php endif; ?>">
                        <i class="ri-video-line text-lg"></i>
                        <span>视频类型管理</span>
                    </a>
                    
                    <a href="<?php echo e(route('video.preview')); ?>" class="menu-item <?php if(Route::currentRouteName() == 'video.preview'): ?> menu-item-active <?php endif; ?>">
                        <i class="ri-eye-line text-lg"></i>
                        <span>视频预览</span>
                    </a>
                    
                    <a href="<?php echo e(route('video.create')); ?>" class="menu-item <?php if(Route::currentRouteName() == 'video.create'): ?> menu-item-active <?php endif; ?>">
                        <i class="ri-add-circle-line text-lg"></i>
                        <span>新增视频</span>
                    </a>
                    
                    <div class="text-xs font-semibold text-gray-500 uppercase mb-3 mt-6 px-2">图文管理</div>
                    
                    <a href="<?php echo e(route('image-text.types')); ?>" class="menu-item <?php if(Route::currentRouteName() == 'image-text.types'): ?> menu-item-active <?php endif; ?>">
                        <i class="ri-layout-grid-line text-lg"></i>
                        <span>图文类型管理</span>
                    </a>
                    
                    <a href="<?php echo e(route('image-text.preview')); ?>" class="menu-item <?php if(Route::currentRouteName() == 'image-text.preview'): ?> menu-item-active <?php endif; ?>">
                        <i class="ri-eye-line text-lg"></i>
                        <span>图文预览</span>
                    </a>
                    
                    <a href="<?php echo e(route('image-text.create')); ?>" class="menu-item <?php if(Route::currentRouteName() == 'image-text.create'): ?> menu-item-active <?php endif; ?>">
                        <i class="ri-add-circle-line text-lg"></i>
                        <span>新增图文</span>
                    </a>
                    
                    <div class="text-xs font-semibold text-gray-500 uppercase mb-3 mt-6 px-2">文本管理</div>
                    
                    <a href="<?php echo e(route('text-management.types')); ?>" class="menu-item <?php if(Route::currentRouteName() == 'text-management.types'): ?> menu-item-active <?php endif; ?>">
                        <i class="ri-file-list-3-line text-lg"></i>
                        <span>文本类型管理</span>
                    </a>
                    
                    <a href="<?php echo e(route('text-management.preview')); ?>" class="menu-item <?php if(Route::currentRouteName() == 'text-management.preview'): ?> menu-item-active <?php endif; ?>">
                        <i class="ri-eye-line text-lg"></i>
                        <span>文本预览</span>
                    </a>
                    
                    <a href="<?php echo e(route('text-management.create')); ?>" class="menu-item <?php if(Route::currentRouteName() == 'text-management.create'): ?> menu-item-active <?php endif; ?>">
                        <i class="ri-add-circle-line text-lg"></i>
                        <span>新增文本</span>
                    </a>
                    <?php endif; ?>
                    <?php endif; ?>
                    
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
                    <h2 class="text-base md:text-lg font-medium text-gray-700 truncate max-w-[200px] sm:max-w-none"><?php echo $__env->yieldContent('page-title'); ?></h2>
                </div>
                
                <div class="flex items-center gap-2 md:space-x-4">
                    <?php if(auth()->guard()->check()): ?>
                        <span class="hidden md:inline text-gray-600">
                            <i class="ri-user-line mr-1"></i>欢迎您，<span class="font-medium text-primary"><?php echo e(Auth::user()->name); ?></span>
                        </span>
                        
                        <a href="<?php echo e(route('user.profile')); ?>" class="hidden md:flex items-center gap-2 px-3 py-2 text-gray-600 hover:text-primary hover:bg-light rounded-xl transition-all">
                            <i class="ri-user-settings-line"></i>
                            <span>个人中心</span>
                        </a>
                        
                        <button type="button" onclick="showLogoutModal()" class="flex items-center gap-1 md:gap-2 px-2 md:px-3 py-2 text-gray-600 hover:text-primary hover:bg-light rounded-xl transition-all">
                            <i class="ri-logout-box-line"></i>
                            <span class="hidden sm:inline">退出登录</span>
                        </button>
                        
                        <div class="w-10 h-10 rounded-full bg-primary flex items-center justify-center text-white font-medium flex-shrink-0">
                            <?php echo e(strtoupper(mb_substr(Auth::user()->name, 0, 1, 'UTF-8'))); ?>

                        </div>
                    <?php else: ?>
                        <a href="<?php echo e(route('login')); ?>" class="flex items-center gap-2 px-3 py-2 text-gray-600 hover:text-primary hover:bg-light rounded-xl transition-all">
                            <i class="ri-login-box-line"></i>
                            <span>用户登录</span>
                        </a>
                    <?php endif; ?>
                </div>
            </header>
            
            <!-- 内容区域 -->
            <main class="flex-1 overflow-auto p-4 md:p-6 bg-gray-50">
                <?php echo $__env->yieldContent('content'); ?>
            </main>
        </div>
    </div>
</body>
</html><?php /**PATH D:\else\order\ElevatorManagement\ElevatorManagement\resources\views/layouts/elevator.blade.php ENDPATH**/ ?>