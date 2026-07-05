<?php $__env->startSection('title', '图片组详情'); ?>
<?php $__env->startSection('page-title', '图片组详情 - ' . $group); ?>

<?php $__env->startSection('content'); ?>
<div class="card">
    <div class="flex justify-between items-center mb-6">
        <div>
            <h3 class="text-xl font-semibold text-gray-800">图片组: <?php echo e($group); ?></h3>
            <p class="text-gray-500 mt-1">共 <?php echo e($images->count()); ?> 个图片文件</p>
        </div>
        <a href="<?php echo e(route('image-text.preview')); ?>" class="bg-gray-500 hover:bg-gray-600 text-white px-4 py-2 rounded-lg">
            <i class="ri-arrow-left-line"></i> 返回列表
        </a>
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
                    <th class="text-left py-3 px-4 font-medium text-gray-600">图片文件名</th>
                    <th class="text-left py-3 px-4 font-medium text-gray-600">图片类型</th>
                    <th class="text-left py-3 px-4 font-medium text-gray-600">描述</th>
                    <th class="text-left py-3 px-4 font-medium text-gray-600">上传时间</th>
                    <th class="text-left py-3 px-4 font-medium text-gray-600">操作</th>
                </tr>
            </thead>
            <tbody>
                <?php $__currentLoopData = $images; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $image): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <tr class="border-b hover:bg-gray-50">
                    <td class="py-3 px-4">
                        <img src="<?php echo e(asset($image->coverPath)); ?>" class="w-24 h-14 object-cover rounded-lg border">
                    </td>
                    <td class="py-3 px-4 font-medium">
                        <?php echo e(basename($image->imagePath)); ?>

                    </td>
                    <td class="py-3 px-4">
                        <span class="bg-blue-100 text-blue-700 px-2 py-1 rounded text-sm"><?php echo e($image->imageType); ?></span>
                    </td>
                    <td class="py-3 px-4 text-gray-500 max-w-xs truncate">
                        <?php echo e($image->description ?? '-'); ?>

                    </td>
                    <td class="py-3 px-4 text-gray-500"><?php echo e($image->created_at->format('Y-m-d H:i')); ?></td>
                    <td class="py-3 px-4">
                        <div class="flex gap-2">
                            <button onclick="previewImage('<?php echo e(asset($image->imagePath)); ?>', '<?php echo e(basename($image->imagePath)); ?>')" class="bg-green-500 hover:bg-green-600 text-white px-3 py-1 rounded text-sm">
                                <i class="ri-eye-line"></i> 预览
                            </button>
                            <button onclick="showDeleteModal('<?php echo e(route('image-single.delete', $image->id)); ?>')" class="bg-red-500 hover:bg-red-600 text-white px-3 py-1 rounded text-sm">
                                <i class="ri-delete-bin-line"></i> 删除
                            </button>
                        </div>
                    </td>
                </tr>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </tbody>
        </table>
    </div>
</div>

<!-- 图片预览弹窗 -->
<div id="imageModal" class="fixed inset-0 bg-black bg-opacity-75 hidden items-center justify-center z-50">
    <div class="bg-white rounded-lg w-full max-w-4xl mx-4 p-6">
        <div class="flex justify-between items-center mb-4">
            <h3 id="imageTitle" class="text-lg font-semibold text-gray-800">图片预览</h3>
            <button onclick="closeImageModal()" class="text-gray-400 hover:text-gray-600">
                <i class="ri-close-line text-xl"></i>
            </button>
        </div>
        <div class="bg-gray-100 rounded-lg overflow-hidden flex items-center justify-center" style="min-height: 400px;">
            <img id="imagePreview" src="" class="max-w-full max-h-[70vh] object-contain">
        </div>
    </div>
</div>

<!-- 删除确认模态框 -->
<div id="deleteModal" class="fixed inset-0 bg-black bg-opacity-50 hidden items-center justify-center z-50">
    <div class="bg-white rounded-lg w-full max-w-sm mx-4 p-6">
        <div class="text-center">
            <i class="ri-error-warning-line text-5xl text-red-500 mb-4"></i>
            <h3 class="text-lg font-semibold text-gray-800 mb-2">确认删除</h3>
            <p class="text-gray-500 mb-6">确定要删除此图片吗？此操作不可恢复。</p>
            
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
function previewImage(imageUrl, imageName) {
    document.getElementById('imageTitle').textContent = imageName;
    document.getElementById('imagePreview').src = imageUrl;
    document.getElementById('imageModal').classList.remove('hidden');
    document.getElementById('imageModal').classList.add('flex');
}

function closeImageModal() {
    document.getElementById('imagePreview').src = '';
    document.getElementById('imageModal').classList.add('hidden');
    document.getElementById('imageModal').classList.remove('flex');
}

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

<?php echo $__env->make('layouts.elevator', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH D:\else\order\ElevatorManagement\ElevatorManagement\resources\views/image-text/group.blade.php ENDPATH**/ ?>