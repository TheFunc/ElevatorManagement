<?php $__env->startSection('title', '视频预览'); ?>
<?php $__env->startSection('page-title', '视频预览'); ?>

<?php $__env->startSection('content'); ?>
<div class="card">
    <div class="flex flex-wrap justify-between items-center mb-6 gap-4">
        <h3 class="text-xl font-semibold text-gray-800">视频管理</h3>
        <div class="flex flex-wrap gap-3">
            <form action="<?php echo e(route('video.preview')); ?>" method="GET" class="flex gap-3">
                <input type="text" name="keyword" value="<?php echo e(request('keyword')); ?>" placeholder="搜索视频组名..." class="px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 w-64">
                <select name="videoType" class="px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
                    <option value="">全部类型</option>
                    <?php $__currentLoopData = $videoTypes; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $type): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <option value="<?php echo e($type->type); ?>" <?php echo e(request('videoType') == $type->type ? 'selected' : ''); ?>><?php echo e($type->type); ?></option>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </select>
                <button type="submit" class="bg-blue-500 hover:bg-blue-600 text-white px-4 py-2 rounded-lg">
                    <i class="ri-search-line"></i> 搜索
                </button>
                <a href="<?php echo e(route('video.preview')); ?>" class="bg-gray-500 hover:bg-gray-600 text-white px-4 py-2 rounded-lg">
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
                    <th class="text-left py-3 px-4 font-medium text-gray-600">视频组名</th>
                    <th class="text-left py-3 px-4 font-medium text-gray-600">视频类型</th>
                    <th class="text-left py-3 px-4 font-medium text-gray-600">上传时间</th>
                    <th class="text-left py-3 px-4 font-medium text-gray-600">操作</th>
                </tr>
            </thead>
            <tbody>
                <?php $__currentLoopData = $groupedVideos; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $video): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <tr class="border-b hover:bg-gray-50">
                    <td class="py-3 px-4">
                        <img src="<?php echo e(asset($video->coverPath)); ?>" class="w-24 h-14 object-cover rounded-lg border">
                    </td>
                    <td class="py-3 px-4 font-medium"><?php echo e($video->videoGroup); ?></td>
                    <td class="py-3 px-4">
                        <span class="bg-blue-100 text-blue-700 px-2 py-1 rounded text-sm"><?php echo e($video->videoType); ?></span>
                    </td>
                    <td class="py-3 px-4 text-gray-500"><?php echo e($video->created_at->format('Y-m-d H:i')); ?></td>
                    <td class="py-3 px-4">
                        <div class="flex gap-2">
                            <a href="<?php echo e(route('video.group', $video->videoGroup)); ?>" class="bg-blue-500 hover:bg-blue-600 text-white px-3 py-1 rounded text-sm">
                                <i class="ri-eye-line"></i> 查看
                            </a>
                            <button onclick="showDeleteModal('<?php echo e(route('video.delete', $video->id)); ?>')" class="bg-red-500 hover:bg-red-600 text-white px-3 py-1 rounded text-sm">
                                <i class="ri-delete-bin-line"></i> 删除
                            </button>
                        </div>
                    </td>
                </tr>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>

                <?php if($groupedVideos->isEmpty()): ?>
                <tr>
                    <td colspan="5" class="py-8 text-center text-gray-500">
                        <i class="ri-inbox-line text-4xl mb-2 block"></i>
                        暂无视频数据
                    </td>
                </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.elevator', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH D:\else\order\ElevatorManagement\ElevatorManagement\resources\views/video/preview.blade.php ENDPATH**/ ?>