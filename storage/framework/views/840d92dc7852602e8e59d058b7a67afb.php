<?php $__env->startSection('title', '视频组详情'); ?>
<?php $__env->startSection('page-title', '视频组详情 - ' . $group); ?>

<?php $__env->startSection('content'); ?>
<div class="card">
    <div class="flex justify-between items-center mb-6">
        <div>
            <h3 class="text-xl font-semibold text-gray-800">视频组: <?php echo e($group); ?></h3>
            <p class="text-gray-500 mt-1">共 <?php echo e($videos->count()); ?> 个视频文件</p>
        </div>
        <a href="<?php echo e(route('video.preview')); ?>" class="bg-gray-500 hover:bg-gray-600 text-white px-4 py-2 rounded-lg">
            <i class="ri-arrow-left-line"></i> 返回列表
        </a>
    </div>

    <div class="overflow-x-auto">
        <table class="w-full">
            <thead>
                <tr class="bg-gray-50 border-b">
                    <th class="text-left py-3 px-4 font-medium text-gray-600">封面</th>
                    <th class="text-left py-3 px-4 font-medium text-gray-600">视频文件名</th>
                    <th class="text-left py-3 px-4 font-medium text-gray-600">视频类型</th>
                    <th class="text-left py-3 px-4 font-medium text-gray-600">上传时间</th>
                    <th class="text-left py-3 px-4 font-medium text-gray-600">操作</th>
                </tr>
            </thead>
            <tbody>
                <?php $__currentLoopData = $videos; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $video): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <tr class="border-b hover:bg-gray-50">
                    <td class="py-3 px-4">
                        <img src="<?php echo e(asset($video->coverPath)); ?>" class="w-24 h-14 object-cover rounded-lg border">
                    </td>
                    <td class="py-3 px-4 font-medium">
                        <?php echo e(basename($video->videoPath)); ?>

                    </td>
                    <td class="py-3 px-4">
                        <span class="bg-blue-100 text-blue-700 px-2 py-1 rounded text-sm"><?php echo e($video->videoType); ?></span>
                    </td>
                    <td class="py-3 px-4 text-gray-500"><?php echo e($video->created_at->format('Y-m-d H:i')); ?></td>
                    <td class="py-3 px-4">
                        <div class="flex gap-2">
                            <button onclick="playVideo('<?php echo e(asset($video->videoPath)); ?>', '<?php echo e(basename($video->videoPath)); ?>')" class="bg-green-500 hover:bg-green-600 text-white px-3 py-1 rounded text-sm">
                                <i class="ri-play-circle-line"></i> 播放
                            </button>
                            <button onclick="showDeleteModal('<?php echo e(route('video.delete.single', $video->id)); ?>')" class="bg-red-500 hover:bg-red-600 text-white px-3 py-1 rounded text-sm">
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

<!-- 视频播放弹窗 -->
<div id="videoModal" class="fixed inset-0 bg-black bg-opacity-75 hidden items-center justify-center z-50">
    <div class="bg-white rounded-lg w-full max-w-4xl mx-4 p-6">
        <div class="flex justify-between items-center mb-4">
            <h3 id="videoTitle" class="text-lg font-semibold text-gray-800">视频播放</h3>
            <button onclick="closeVideoModal()" class="text-gray-400 hover:text-gray-600">
                <i class="ri-close-line text-xl"></i>
            </button>
        </div>
        <div class="bg-black rounded-lg overflow-hidden">
            <video id="videoPlayer" class="w-full" controls>
                <source src="" type="video/mp4">
                您的浏览器不支持视频播放
            </video>
        </div>
    </div>
</div>

<script>
function playVideo(videoUrl, videoName) {
    document.getElementById('videoTitle').textContent = videoName;
    document.getElementById('videoPlayer').src = videoUrl;
    document.getElementById('videoModal').classList.remove('hidden');
    document.getElementById('videoModal').classList.add('flex');
    document.getElementById('videoPlayer').play();
}

function closeVideoModal() {
    document.getElementById('videoPlayer').pause();
    document.getElementById('videoPlayer').src = '';
    document.getElementById('videoModal').classList.add('hidden');
    document.getElementById('videoModal').classList.remove('flex');
}
</script>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.elevator', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH D:\else\order\ElevatorManagement\ElevatorManagement\resources\views/video/group.blade.php ENDPATH**/ ?>