<?php $__env->startSection('title', '电梯台账'); ?>
<?php $__env->startSection('page-title', '电梯台账'); ?>

<?php $__env->startSection('content'); ?>
<!-- 使用flex布局 -->
<div class="flex flex-col lg:flex-row gap-6 mb-6">
    <!-- 电梯列表 -->
    <div id="elevatorListContainer" class="flex-1 min-w-0 card transition-all duration-300">
        <div class="flex justify-between items-center mb-4">
            <h3 class="text-xl font-semibold text-gray-800">电梯设备列表</h3>
            <div class="flex gap-3">
                <?php if(auth()->guard()->check()): ?>
                <?php if(Auth::user()->role == 1): ?>
                <a href="<?php echo e(route('campus.index')); ?>" class="px-4 py-2 bg-secondary text-white rounded-lg hover:bg-primary transition-colors">
                    <i class="ri-building-line mr-1"></i>校区管理
                </a>
                <a href="<?php echo e(route('data.device')); ?>" class="px-4 py-2 bg-primary text-white rounded-lg hover:bg-dark transition-colors">
                    <i class="ri-add-line mr-1"></i>添加电梯
                </a>
                <?php endif; ?>
                <?php endif; ?>
            </div>
        </div>

        <!-- 搜索栏 -->
        <form action="" method="GET" class="mb-6">
            <div class="flex gap-3 flex-wrap">
                <input type="text" name="keyword" value="<?php echo e(request('keyword')); ?>" placeholder="搜索电梯编号、名称、位置..." class="flex-1 min-w-[200px] px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary focus:border-primary outline-none">
                <select name="status" class="px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary focus:border-primary outline-none">
                    <option value="">全部状态</option>
                    <option value="1" <?php echo e(request('status') == '1' ? 'selected' : ''); ?>>在用</option>
                    <option value="0" <?php echo e(request('status') == '0' ? 'selected' : ''); ?>>停用</option>
                    <option value="2" <?php echo e(request('status') == '2' ? 'selected' : ''); ?>>报废</option>
                </select>
                <button type="submit" class="px-6 py-2 bg-gray-700 text-white rounded-lg hover:bg-gray-800 transition-colors">
                    <i class="ri-search-line mr-1"></i>查询
                </button>
                <?php if(request('keyword') || request('status') != ''): ?>
                <a href="<?php echo e(route('elevator.ledger')); ?>" class="px-6 py-2 bg-gray-200 text-gray-700 rounded-lg hover:bg-gray-300 transition-colors">
                    重置
                </a>
                <?php endif; ?>
            </div>
        </form>
        
        <!-- 年检状态提示条 -->
        <?php
            $now = \Carbon\Carbon::now();
            $overdueCount = 0;   // 已逾期
            $nearCount = 0;      // 临近期 (<= 30天)
            $near2Count = 0;     // 临期2个月 (>30天且<=60天)
            $okCount = 0;        // 未临期 (>60天)
            $noneCount = 0;      // 未设置

            foreach($devices as $device) {
                $checkDate = isset($checkNumbers[$device->number]) ? \Carbon\Carbon::parse($checkNumbers[$device->number]) : null;
                $daysDiff = $checkDate ? $now->diffInDays($checkDate, false) : null;

                if (!$checkDate) {
                    $noneCount++;
                } elseif ($daysDiff < 0) {
                    $overdueCount++;
                } elseif ($daysDiff <= 30) {
                    $nearCount++;
                } elseif ($daysDiff <= 60) {
                    $near2Count++;
                } else {
                    $okCount++;
                }
            }
            $totalCheckCount = $devices->count();
        ?>
        <div class="flex flex-wrap gap-3 mb-4 p-4 bg-gray-50 rounded-lg border border-gray-100">
            <span class="text-base text-gray-600 font-medium mr-1 self-center">年检状态：</span>
            <?php if($overdueCount > 0): ?>
            <span class="text-sm text-gray-500 self-center">已超过年检日期：</span>
            <span class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-full text-sm font-semibold bg-red-100 text-red-800">
                <i class="ri-alarm-warning-line"></i>已逾期 <span class="text-lg"><?php echo e($overdueCount); ?></span>
            </span>
            <?php endif; ?>
            <?php if($nearCount > 0): ?>
            <span class="text-sm text-gray-500 self-center">距年检日 ≤ 30天：</span>
            <span class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-full text-sm font-semibold bg-yellow-100 text-yellow-800">
                <i class="ri-time-line"></i>临近期 <span class="text-lg"><?php echo e($nearCount); ?></span>
            </span>
            <?php endif; ?>
            <?php if($near2Count > 0): ?>
            <span class="text-sm text-gray-500 self-center">距年检日 ≤ 60天：</span>
            <span class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-full text-sm font-semibold bg-orange-100 text-orange-800">
                <i class="ri-calendar-line"></i>临期2个月 <span class="text-lg"><?php echo e($near2Count); ?></span>
            </span>
            <?php endif; ?>
            <span class="text-sm text-gray-500 self-center">距年检日 > 60天：</span>
            <span class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-full text-sm font-semibold bg-green-100 text-green-800">
                <i class="ri-checkbox-circle-line"></i>未临期 <span class="text-lg"><?php echo e($okCount); ?></span>
            </span>
            <?php if($noneCount > 0): ?>
            <span class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-full text-sm font-medium bg-gray-100 text-gray-600">
                <i class="ri-question-line"></i>未设置 <span class="text-lg"><?php echo e($noneCount); ?></span>
            </span>
            <?php endif; ?>
            <span class="text-sm text-gray-400 self-center ml-auto">共 <?php echo e($totalCheckCount); ?> 台设备</span>
        </div>

        <!-- PC端表格 仅在桌面显示 -->
        <div class="hidden md:block">
            <!-- 顶部滚动条容器 -->
            <div class="overflow-x-auto mb-1" id="topScrollbar" style="scrollbar-width: none; -ms-overflow-style: none;">
                <div id="topScrollbarInner" style="height: 1px;"></div>
            </div>
            
            <!-- 隐藏顶部滚动条的Webkit样式 -->
            <style>
                #topScrollbar::-webkit-scrollbar {
                    display: none;
                }
            </style>
            
            <!-- 表格容器 -->
            <div class="overflow-x-auto" id="tableContainer">
                <table class="w-full table-fixed" id="deviceTable">
                    <thead>
                        <tr class="bg-gray-50">
                            <th class="px-3 py-3 text-left text-sm font-medium text-gray-600 cursor-pointer hover:bg-gray-100 w-[120px]" onclick="sortTable('number')">
                                电梯编号 <i class="ri-arrow-up-down-line ml-1"></i>
                            </th>
                            <th class="px-3 py-3 text-left text-sm font-medium text-gray-600 cursor-pointer hover:bg-gray-100 w-[150px]" onclick="sortTable('name')">
                                设备名称 <i class="ri-arrow-up-down-line ml-1"></i>
                            </th>
                            <th class="px-3 py-3 text-left text-sm font-medium text-gray-600 w-[100px]">楼号</th>
                            <th class="px-3 py-3 text-left text-sm font-medium text-gray-600 w-[120px]">型号</th>
                            <th class="px-3 py-3 text-left text-sm font-medium text-gray-600 w-[100px]">校区</th>
                            <th class="px-3 py-3 text-left text-sm font-medium text-gray-600 w-[180px]">位置</th>
                            <th class="px-3 py-3 text-left text-sm font-medium text-gray-600 w-[130px]">年检</th>
                            <th class="px-3 py-3 text-left text-sm font-medium text-gray-600 w-[70px]">状态</th>
                            <th class="px-3 py-3 text-left text-sm font-medium text-gray-600 w-[100px]">操作</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                            $now = \Carbon\Carbon::now();
                        ?>
                        <?php $__currentLoopData = $devices; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $device): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <?php
                            $checkDate = isset($checkNumbers[$device->number]) ? \Carbon\Carbon::parse($checkNumbers[$device->number]) : null;
                            $daysDiff = $checkDate ? $now->diffInDays($checkDate, false) : null;
                        ?>
                        <tr class="border-b border-gray-100 hover:bg-gray-50">
                            <td class="px-3 py-3 text-gray-800 font-medium whitespace-nowrap truncate" title="<?php echo e($device->number); ?>"><?php echo e($device->number); ?></td>
                            <td class="px-3 py-3 text-gray-600 whitespace-nowrap truncate" title="<?php echo e($device->name ?? '-'); ?>"><?php echo e($device->name ?? '-'); ?></td>
                            <td class="px-3 py-3 text-gray-600 whitespace-nowrap truncate" title="<?php echo e($device->building ?? '-'); ?>"><?php echo e($device->building ?? '-'); ?></td>
                            <td class="px-3 py-3 text-gray-600 whitespace-nowrap truncate" title="<?php echo e($device->Model ?? '-'); ?>"><?php echo e($device->Model ?? '-'); ?></td>
                            <td class="px-3 py-3 text-gray-600 whitespace-nowrap truncate" title="<?php echo e($device->Campus ?? '-'); ?>"><?php echo e($device->Campus ?? '-'); ?></td>
                            <td class="px-3 py-3 text-gray-600 whitespace-nowrap truncate" title="<?php echo e($device->Position); ?>"><?php echo e($device->Position); ?></td>
                            <td class="px-3 py-3 whitespace-nowrap">
                                <?php if($checkDate): ?>
                                    <div class="text-xs text-gray-500"><?php echo e($checkDate->format('Y-m-d')); ?></div>
                                    <?php if($daysDiff < 0): ?>
                                        <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-800">已逾期</span>
                                    <?php elseif($daysDiff <= 30): ?>
                                        <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium bg-yellow-100 text-yellow-800">临近期</span>
                                    <?php elseif($daysDiff <= 60): ?>
                                        <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium bg-orange-100 text-orange-800">临期2个月</span>
                                    <?php else: ?>
                                        <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">未临期</span>
                                    <?php endif; ?>
                                <?php else: ?>
                                    <span class="text-gray-400 text-xs">未设置</span>
                                <?php endif; ?>
                            </td>
                            <td class="px-3 py-3">
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium whitespace-nowrap
                                    <?php echo e($device->status == 1 ? 'bg-green-100 text-green-800' : ($device->status == 0 ? 'bg-red-100 text-red-800' : 'bg-gray-200 text-gray-700')); ?>">
                                    <?php echo e($device->status == 1 ? '在用' : ($device->status == 0 ? '停用' : '报废')); ?>

                                </span>
                            </td>
                            <td class="px-3 py-3">
                                <a href="<?php echo e(route('device.show', $device->id)); ?>" class="text-primary hover:text-dark font-medium whitespace-nowrap">
                                    查看详情
                                </a>
                            </td>
                        </tr>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        
                        <?php if($devices->isEmpty()): ?>
                        <tr>
                            <td colspan="9" class="px-4 py-8 text-center text-gray-500">
                                暂无电梯数据，请点击"添加电梯"录入设备信息
                            </td>
                        </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
        
        <!-- 手机端卡片流布局 仅在移动端显示 -->
        <div class="md:hidden space-y-3">
            <?php $__currentLoopData = $devices; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $device): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
            <div class="bg-white rounded-xl border border-gray-100 shadow-sm overflow-hidden">
                <div class="flex p-4 gap-4">
                    <!-- 左侧图片 -->
                    <div class="flex-shrink-0 w-20 h-20 bg-gray-100 rounded-lg flex items-center justify-center">
                        <i class="ri-building-4-line text-3xl text-gray-400"></i>
                    </div>
                    
                    <!-- 右侧信息 -->
                    <div class="flex-1 min-w-0">
                        <div class="flex items-center justify-between mb-1">
                            <h4 class="font-semibold text-gray-800 truncate"><?php echo e($device->number); ?></h4>
                            <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium 
                                <?php echo e($device->status == 1 ? 'bg-green-100 text-green-800' : ($device->status == 0 ? 'bg-red-100 text-red-800' : 'bg-gray-200 text-gray-700')); ?>">
                                <?php echo e($device->status == 1 ? '在用' : ($device->status == 0 ? '停用' : '报废')); ?>

                            </span>
                        </div>
                        <p class="text-sm text-gray-600 mb-0.5"><?php echo e($device->name ?? '-'); ?></p>
                        <?php if($device->building): ?>
                        <p class="text-xs text-gray-500 mb-0.5"><i class="ri-building-line mr-1"></i><?php echo e($device->building); ?></p>
                        <?php endif; ?>
                        <p class="text-xs text-gray-500 mb-0.5"><?php echo e($device->Model ?? '-'); ?></p>
                        <p class="text-xs text-gray-500 mb-0.5"><i class="ri-map-pin-line mr-1"></i><?php echo e($device->Campus ?? '-'); ?></p>
                        <p class="text-xs text-gray-500 truncate"><?php echo e($device->Position); ?></p>
                    </div>
                </div>
                
                <!-- 操作按钮 -->
                <div class="border-t border-gray-100 px-4 py-3">
                    <a href="<?php echo e(route('device.show', $device->id)); ?>" class="w-full flex items-center justify-center gap-1.5 py-2.5 bg-primary/10 text-primary rounded-lg hover:bg-primary/20 transition-colors">
                        <i class="ri-eye-line"></i>
                        <span>查看详情</span>
                    </a>
                </div>
            </div>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            
            <?php if($devices->isEmpty()): ?>
            <div class="py-12 text-center text-gray-500">
                <i class="ri-inbox-line text-4xl text-gray-300 mb-3"></i>
                <p>暂无电梯数据，请点击"添加电梯"录入设备信息</p>
            </div>
            <?php endif; ?>
        </div>
    </div>
    
</div>

<script>
function sortTable(field) {
    var url = new URL(window.location.href);
    var currentSort = url.searchParams.get('sort');
    var currentOrder = url.searchParams.get('order');
    
    var newOrder = 'asc';
    if (currentSort === field && currentOrder === 'asc') {
        newOrder = 'desc';
    }
    
    url.searchParams.set('sort', field);
    url.searchParams.set('order', newOrder);
    
    // 保留其他查询参数
    if (document.querySelector('input[name="keyword"]').value) {
        url.searchParams.set('keyword', document.querySelector('input[name="keyword"]').value);
    }
    if (document.querySelector('select[name="status"]').value !== '') {
        url.searchParams.set('status', document.querySelector('select[name="status"]').value);
    }
    
    window.location.href = url.toString();
}

// 鼠标滚轮横向滚动表格
document.addEventListener('DOMContentLoaded', function() {
    // 鼠标滚轮横向滚动表格
    var tableContainer = document.getElementById('tableContainer');
    var topScrollbar = document.getElementById('topScrollbar');
    var topScrollbarInner = document.getElementById('topScrollbarInner');
    
    if (!tableContainer || !topScrollbar || !topScrollbarInner) return;
    
    // 设置顶部滚动条内部容器的宽度与表格一致
    function syncScrollbarWidth() {
        if (tableContainer && topScrollbarInner) {
            topScrollbarInner.style.width = tableContainer.scrollWidth + 'px';
        }
    }
    
    // 初始化时同步宽度
    syncScrollbarWidth();
    
    // 窗口大小改变时重新同步
    window.addEventListener('resize', syncScrollbarWidth);
    
    // 双向同步滚动
    var isSyncing = false;
    
    // 表格滚动时同步顶部滚动条
    tableContainer.addEventListener('scroll', function() {
        if (!isSyncing) {
            isSyncing = true;
            topScrollbar.scrollLeft = this.scrollLeft;
            requestAnimationFrame(function() {
                isSyncing = false;
            });
        }
    });
    
    // 顶部滚动条滚动时同步表格
    topScrollbar.addEventListener('scroll', function() {
        if (!isSyncing) {
            isSyncing = true;
            tableContainer.scrollLeft = this.scrollLeft;
            requestAnimationFrame(function() {
                isSyncing = false;
            });
        }
    });
    
    // 鼠标滚轮横向滚动表格
    tableContainer.addEventListener('wheel', function(e) {
        // 检查是否有横向滚动条
        if (this.scrollWidth > this.clientWidth) {
            // 阻止默认的垂直滚动行为
            e.preventDefault();
            // 将垂直滚动转换为横向滚动
            this.scrollLeft += e.deltaY;
        }
    }, { passive: false });
});
</script>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.elevator', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH D:\else\order\ElevatorManagement\ElevatorManagement\resources\views/elevator/ledger.blade.php ENDPATH**/ ?>