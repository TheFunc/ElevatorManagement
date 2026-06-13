<?php $__env->startSection('title', '图文预览'); ?>
<?php $__env->startSection('page-title', '图文预览'); ?>

<?php $__env->startSection('content'); ?>
<div class="card">
    <div class="flex flex-wrap justify-between items-center mb-6 gap-4">
        <h3 class="text-xl font-semibold text-gray-800">图片管理</h3>
        <div class="flex flex-wrap gap-3">
            <form action="<?php echo e(route('image-text.preview')); ?>" method="GET" class="flex gap-3">
                <input type="text" name="keyword" value="<?php echo e(request('keyword')); ?>" placeholder="搜索图片组名..." class="px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 w-64">
                <select name="imageType" class="px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
                    <option value="">全部类型</option>
                    <?php $__currentLoopData = $imageTypes; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $type): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <option value="<?php echo e($type->type); ?>" <?php echo e(request('imageType') == $type->type ? 'selected' : ''); ?>><?php echo e($type->type); ?></option>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </select>
                <button type="submit" class="bg-blue-500 hover:bg-blue-600 text-white px-4 py-2 rounded-lg">
                    <i class="ri-search-line"></i> 搜索
                </button>
                <a href="<?php echo e(route('image-text.preview')); ?>" class="bg-gray-500 hover:bg-gray-600 text-white px-4 py-2 rounded-lg">
                    重置
                </a>
            </form>
        </div>
    </div>

    <?php if(session('success')): ?>
        <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-4">
            <?php echo e(session('success')); ?>

        </div>
    <?php endif; ?>

    <div class="overflow-x-auto">
        <table class="w-full">
            <thead>
                <tr class="bg-gray-50 border-b">
                    <th class="text-left py-3 px-4 font-medium text-gray-600">封面</th>
                    <th class="text-left py-3 px-4 font-medium text-gray-600">图片组名</th>
                    <th class="text-left py-3 px-4 font-medium text-gray-600">图片类型</th>
                    <th class="text-left py-3 px-4 font-medium text-gray-600">上传时间</th>
                    <th class="text-left py-3 px-4 font-medium text-gray-600">操作</th>
                </tr>
            </thead>
            <tbody>
                <?php $__currentLoopData = $groupedImages; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $image): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <tr class="border-b hover:bg-gray-50">
                    <td class="py-3 px-4">
                        <img src="<?php echo e(asset($image->coverPath)); ?>" class="w-24 h-14 object-cover rounded-lg border">
                    </td>
                    <td class="py-3 px-4 font-medium"><?php echo e($image->imageGroup); ?></td>
                    <td class="py-3 px-4">
                        <span class="bg-blue-100 text-blue-700 px-2 py-1 rounded text-sm"><?php echo e($image->imageType); ?></span>
                    </td>
                    <td class="py-3 px-4 text-gray-500"><?php echo e($image->created_at->format('Y-m-d H:i')); ?></td>
                    <td class="py-3 px-4">
                        <div class="flex gap-2">
                            <a href="<?php echo e(route('image-text.group', $image->imageGroup)); ?>" class="bg-blue-500 hover:bg-blue-600 text-white px-3 py-1 rounded text-sm">
                                <i class="ri-eye-line"></i> 查看
                            </a>
                            <button onclick="showDeleteModal('<?php echo e(route('image-group.delete', $image->id)); ?>')" class="bg-red-500 hover:bg-red-600 text-white px-3 py-1 rounded text-sm">
                                <i class="ri-delete-bin-line"></i> 删除
                            </button>
                        </div>
                    </td>
                </tr>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>

                <?php if($groupedImages->isEmpty()): ?>
                <tr>
                    <td colspan="5" class="py-8 text-center text-gray-500">
                        <i class="ri-inbox-line text-4xl mb-2 block"></i>
                        暂无图片数据
                    </td>
                </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>

<!-- 删除确认模态框 -->
<div id="deleteModal" class="fixed inset-0 bg-black bg-opacity-50 hidden items-center justify-center z-50">
    <div class="bg-white rounded-lg w-full max-w-sm mx-4 p-6">
        <div class="text-center">
            <i class="ri-error-warning-line text-5xl text-red-500 mb-4"></i>
            <h3 class="text-lg font-semibold text-gray-800 mb-2">确认删除</h3>
            <p class="text-gray-500 mb-6">确定要删除此图片组吗？该分组下的所有图片都将被删除，此操作不可恢复。</p>
            
            <form id="deleteForm" method="POST">
                <?php echo csrf_field(); ?>
                <div class="flex justify-center gap-3">
                    <button type="button" onclick="closeDeleteModal()" class="px-4 py-2 border border-gray-300 rounded-lg text-gray-700 hover:bg-gray-50">
                        取消
                    </button>
                    <button type="submit" class="px-4 py-2 bg-red-500 text-white rounded-lg hover:bg-red-600">
                        确认删除
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
function showDeleteModal(deleteUrl) {
    document.getElementById('deleteForm').action = deleteUrl;
    document.getElementById('deleteModal').classList.remove('hidden');
    document.getElementById('deleteModal').classList.add('flex');
}

function closeDeleteModal() {
    document.getElementById('deleteModal').classList.add('hidden');
    document.getElementById('deleteModal').classList.remove('flex');
}
</script>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.elevator', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH D:\else\order\ElevatorManagement\ElevatorManagement\resources\views/image-text/preview.blade.php ENDPATH**/ ?>