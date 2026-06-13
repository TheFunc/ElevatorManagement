<?php $__env->startSection('title', '用户管理'); ?>
<?php $__env->startSection('page-title', '用户管理'); ?>

<?php $__env->startSection('content'); ?>
<div class="card">
    <div class="flex justify-between items-center mb-6">
        <h3 class="text-xl font-semibold text-gray-800">系统用户管理</h3>
        <div class="flex gap-3 items-center">
            <div class="flex gap-2">
                <a href="<?php echo e(route('user.index', ['search' => $search, 'sort' => 'id', 'order' => 'asc'])); ?>" class="px-4 py-2 bg-gray-100 text-gray-700 rounded-lg hover:bg-gray-200 transition-colors <?php echo e($sort == 'id' ? 'bg-primary text-white hover:bg-dark' : ''); ?>">
                    <i class="ri-sort-number-asc mr-1"></i>按ID排序
                </a>
                <a href="<?php echo e(route('user.index', ['search' => $search, 'sort' => 'role', 'order' => 'desc'])); ?>" class="px-4 py-2 bg-gray-100 text-gray-700 rounded-lg hover:bg-gray-200 transition-colors <?php echo e($sort == 'role' ? 'bg-primary text-white hover:bg-dark' : ''); ?>">
                    <i class="ri-group-line mr-1"></i>按角色排序
                </a>
            </div>
            <form action="<?php echo e(route('user.index')); ?>" method="GET" class="flex gap-2">
                <input type="text" name="search" value="<?php echo e($search); ?>" placeholder="搜索用户名" class="px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary focus:border-primary outline-none w-48">
                <button type="submit" class="px-4 py-2 bg-primary text-white rounded-lg hover:bg-dark transition-colors">
                    <i class="ri-search-line"></i>
                </button>
                <?php if($search): ?>
                    <a href="<?php echo e(route('user.index')); ?>" class="px-4 py-2 bg-gray-200 text-gray-700 rounded-lg hover:bg-gray-300 transition-colors">
                        清除
                    </a>
                <?php endif; ?>
            </form>
            <a href="<?php echo e(route('user.create')); ?>" class="px-4 py-2 bg-primary text-white rounded-lg hover:bg-dark transition-colors">
                <i class="ri-add-line mr-1"></i>添加新用户
            </a>
        </div>
    </div>

    <?php if(session('success')): ?>
        <div class="bg-green-50 border border-green-200 text-green-700 px-4 py-3 rounded-lg mb-6">
            <?php echo e(session('success')); ?>

        </div>
    <?php endif; ?>

    <div class="overflow-x-auto">
        <table class="w-full">
            <thead>
                <tr class="bg-gray-50">
                    <th class="px-4 py-3 text-left text-sm font-medium text-gray-600">
                        <a href="<?php echo e(route('user.index', ['search' => $search, 'sort' => 'id', 'order' => $sort == 'id' && $order == 'asc' ? 'desc' : 'asc'])); ?>" class="hover:text-primary flex items-center gap-1">
                            ID
                            <?php if($sort == 'id'): ?>
                                <i class="ri-arrow-<?php echo e($order == 'asc' ? 'up' : 'down'); ?>-line"></i>
                            <?php endif; ?>
                        </a>
                    </th>
                    <th class="px-4 py-3 text-left text-sm font-medium text-gray-600">用户名</th>
                    <th class="px-4 py-3 text-left text-sm font-medium text-gray-600">
                        <a href="<?php echo e(route('user.index', ['search' => $search, 'sort' => 'role', 'order' => $sort == 'role' && $order == 'asc' ? 'desc' : 'asc'])); ?>" class="hover:text-primary flex items-center gap-1">
                            用户角色
                            <?php if($sort == 'role'): ?>
                                <i class="ri-arrow-down-line"></i>
                            <?php endif; ?>
                        </a>
                    </th>
                    <th class="px-4 py-3 text-left text-sm font-medium text-gray-600">创建时间</th>
                    <th class="px-4 py-3 text-left text-sm font-medium text-gray-600">登录时间</th>
                    <th class="px-4 py-3 text-left text-sm font-medium text-gray-600">操作</th>
                </tr>
            </thead>
            <tbody>
                <?php $__currentLoopData = $users; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $user): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <tr class="border-b border-gray-100 hover:bg-gray-50">
                    <td class="px-4 py-3 text-gray-600"><?php echo e($user->id); ?></td>
                    <td class="px-4 py-3 font-medium text-gray-800"><?php echo e($user->name); ?></td>
                    <td class="px-4 py-3">
                        <?php if($user->role == 1): ?>
                            <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-primary/10 text-primary">
                                <i class="ri-admin-line mr-1"></i>总监
                            </span>
                        <?php else: ?>
                            <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-gray-100 text-gray-600">
                                <i class="ri-user-line mr-1"></i>电梯管理员
                            </span>
                        <?php endif; ?>
                    </td>
                    <td class="px-4 py-3 text-gray-600"><?php echo e($user->created_at->format('Y-m-d')); ?></td>
                    <td class="px-4 py-3 text-gray-500 text-sm">
                        <?php if($user->last_login_at): ?>
                            <?php echo e($user->last_login_at->format('Y-m-d H:i')); ?>

                        <?php else: ?>
                            从未登录
                        <?php endif; ?>
                    </td>
                    <td class="px-4 py-3">
                        <div class="flex gap-2">
                            <button onclick="showPasswordModal(<?php echo e($user->id); ?>, '<?php echo e($user->name); ?>')" class="px-3 py-1.5 bg-primary/10 text-primary rounded hover:bg-primary/20 transition-colors text-sm">
                                <i class="ri-lock-password-line mr-1"></i>修改密码
                            </button>
                            
                            <?php if($user->name != 'admin'): ?>
                            <form action="<?php echo e(route('user.delete', $user->id)); ?>" method="POST" onsubmit="return confirm('确定要删除此用户吗？')" class="inline">
                                <?php echo csrf_field(); ?>
                                <button type="submit" class="px-3 py-1.5 bg-red-100 text-red-600 rounded hover:bg-red-200 transition-colors text-sm">
                                    <i class="ri-delete-bin-line mr-1"></i>删除
                                </button>
                            </form>
                            <?php else: ?>
                            <span class="px-3 py-1.5 bg-gray-100 text-gray-400 rounded text-sm cursor-not-allowed" title="超级管理员账号受保护，不允许删除">
                                <i class="ri-shield-check-line mr-1"></i>受保护
                            </span>
                            <?php endif; ?>
                        </div>
                    </td>
                </tr>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </tbody>
        </table>
    </div>

    <div class="mt-6 text-sm text-gray-500">
        共 <?php echo e($users->count()); ?> 个用户，其中总监 <?php echo e($users->where('role', 1)->count()); ?> 名
    </div>
</div>

<!-- 修改密码弹窗 -->
<div id="passwordModal" class="fixed inset-0 bg-black/50 flex items-center justify-center z-50 hidden">
    <div class="bg-white rounded-xl shadow-xl max-w-md w-full mx-4">
        <div class="p-6 border-b border-gray-100">
            <div class="flex items-center gap-3">
                <div class="w-12 h-12 rounded-full bg-primary/10 flex items-center justify-center">
                    <i class="ri-lock-password-line text-primary text-xl"></i>
                </div>
                <div>
                    <h3 class="text-lg font-semibold text-gray-800">修改用户密码</h3>
                    <p id="modalUserName" class="text-sm text-gray-500"></p>
                </div>
            </div>
        </div>
        <form id="passwordForm" method="POST">
            <?php echo csrf_field(); ?>
            <div class="p-6">
                <div class="space-y-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">新密码</label>
                        <input type="password" name="password" class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary focus:border-primary outline-none" placeholder="请输入新密码">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">确认密码</label>
                        <input type="password" name="password_confirmation" class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary focus:border-primary outline-none" placeholder="请再次输入密码">
                    </div>
                </div>
                <div class="flex gap-3 mt-6">
                    <button type="button" onclick="hidePasswordModal()" class="flex-1 px-4 py-3 bg-gray-100 text-gray-700 rounded-lg hover:bg-gray-200 transition-colors">
                        取消
                    </button>
                    <button type="submit" class="flex-1 px-4 py-3 bg-primary text-white rounded-lg hover:bg-dark transition-colors">
                        确认修改
                    </button>
                </div>
            </div>
        </form>
    </div>
</div>

<script>
function showPasswordModal(userId, userName) {
    document.getElementById('modalUserName').textContent = '正在修改用户：' + userName;
    document.getElementById('passwordForm').action = '/users/' + userId + '/password';
    document.getElementById('passwordModal').classList.remove('hidden');
}

function hidePasswordModal() {
    document.getElementById('passwordModal').classList.add('hidden');
    document.getElementById('passwordForm').reset();
}

// 点击背景关闭弹窗
document.getElementById('passwordModal').addEventListener('click', function(e) {
    if (e.target === this) {
        hidePasswordModal();
    }
});
</script>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.elevator', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH D:\else\order\ElevatorManagement\ElevatorManagement\resources\views/user/index.blade.php ENDPATH**/ ?>