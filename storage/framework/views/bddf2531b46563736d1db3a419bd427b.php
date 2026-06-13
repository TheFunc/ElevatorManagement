<?php $__env->startSection('title', '文件详情'); ?>
<?php $__env->startSection('page-title', '文件详情'); ?>

<?php $__env->startSection('content'); ?>
<div class="card max-w-2xl mx-auto">
    <div class="flex justify-between items-center mb-6">
        <h3 class="text-xl font-semibold text-gray-800">文件详细信息</h3>
        <div class="flex gap-3">
            <?php
                $fileExtension = strtolower(pathinfo($file->path, PATHINFO_EXTENSION));
            ?>
            <?php if(in_array($fileExtension, ['pdf', 'ppt', 'pptx'])): ?>
            <button onclick="openPreviewModal()" class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors">
                <i class="ri-eye-line mr-1"></i>在线预览
            </button>
            <?php endif; ?>
            <a href="<?php echo e(route('file.download', $file->id)); ?>" class="px-4 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700 transition-colors">
                <i class="ri-download-line mr-1"></i>下载文件
            </a>
            <a href="<?php echo e(route('data.query')); ?>" class="px-4 py-2 bg-gray-200 text-gray-700 rounded-lg hover:bg-gray-300 transition-colors">
                返回列表
            </a>
        </div>
    </div>

    <!-- 文件类型标签 -->
    <div class="mb-6">
        <span class="inline-flex items-center px-4 py-2 rounded-lg text-base font-medium bg-<?php echo e($fileTypes[$file->type]['color']); ?>-100 text-<?php echo e($fileTypes[$file->type]['color']); ?>-700">
            <i class="<?php echo e($fileTypes[$file->type]['icon']); ?> mr-2 text-lg"></i>
            <?php echo e($fileTypes[$file->type]['name']); ?>

        </span>
    </div>

    <div class="space-y-6">
        <div class="bg-gray-50 p-5 rounded-lg">
            <p class="text-sm text-gray-500 mb-2">文件标题</p>
            <p class="text-xl font-semibold text-gray-800"><?php echo e($file->title); ?></p>
        </div>
        
        <div class="bg-gray-50 p-5 rounded-lg">
            <p class="text-sm text-gray-500 mb-2">文件描述</p>
            <p class="text-gray-800"><?php echo e($file->desc ?? '暂无描述'); ?></p>
        </div>
        
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <div class="bg-gray-50 p-5 rounded-lg">
                <p class="text-sm text-gray-500 mb-2">文件类型</p>
                <p class="text-gray-800 font-medium"><?php echo e($fileTypes[$file->type]['name']); ?></p>
            </div>
            <div class="bg-gray-50 p-5 rounded-lg">
                <p class="text-sm text-gray-500 mb-2">文件路径</p>
                <p class="text-gray-600 text-sm font-mono"><?php echo e($file->path); ?></p>
            </div>
        </div>
        
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <div class="bg-gray-50 p-5 rounded-lg">
                <p class="text-sm text-gray-500 mb-2">上传时间</p>
                <p class="text-gray-800"><?php echo e($file->created_at->format('Y-m-d H:i:s')); ?></p>
            </div>
            <div class="bg-gray-50 p-5 rounded-lg">
                <p class="text-sm text-gray-500 mb-2">最后更新</p>
                <p class="text-gray-800"><?php echo e($file->updated_at->format('Y-m-d H:i:s')); ?></p>
            </div>
        </div>
    </div>
</div>

<!-- 文件预览模态框 -->
<div id="previewModal" class="fixed inset-0 bg-black/70 z-50 hidden flex-col">
    <!-- 模态框头部 -->
    <div class="bg-white shadow-lg px-6 py-4 flex justify-between items-center">
        <h3 class="text-lg font-semibold text-gray-800">
            <i class="ri-file-text-line mr-2"></i><?php echo e($file->title); ?>

        </h3>
        <button onclick="closePreviewModal()" class="text-gray-500 hover:text-gray-700 text-2xl">
            <i class="ri-close-line"></i>
        </button>
    </div>
    
    <!-- 预览内容区域 -->
    <div class="flex-1 overflow-auto p-4">
        <div id="previewContainer" class="bg-white rounded-lg shadow-xl mx-auto max-w-6xl h-full">
            <?php if($fileExtension === 'pdf'): ?>
            <iframe src="<?php echo e(asset('storage/' . $file->path)); ?>" 
                    class="w-full h-full min-h-[70vh]" 
                    frameborder="0">
            </iframe>
            <?php elseif(in_array($fileExtension, ['ppt', 'pptx'])): ?>
            <div class="flex items-center justify-center h-full p-10">
                <div class="text-center">
                    <i class="ri-file-ppt-line text-6xl text-orange-500 mb-4"></i>
                    <p class="text-gray-600 mb-4">PPT文件在线预览</p>
                    <iframe src="https://view.officeapps.live.com/op/embed.aspx?src=<?php echo e(urlencode(asset('storage/' . $file->path))); ?>" 
                            class="w-[900px] h-[600px] rounded-lg shadow-lg" 
                            frameborder="0">
                    </iframe>
                </div>
            </div>
            <?php endif; ?>
        </div>
    </div>
</div>

<script>
function openPreviewModal() {
    const modal = document.getElementById('previewModal');
    modal.classList.remove('hidden');
    modal.classList.add('flex');
    document.body.style.overflow = 'hidden';
}

function closePreviewModal() {
    const modal = document.getElementById('previewModal');
    modal.classList.add('hidden');
    modal.classList.remove('flex');
    document.body.style.overflow = '';
}

// ESC键关闭预览
document.addEventListener('keydown', function(e) {
    if (e.key === 'Escape') {
        closePreviewModal();
    }
});

// 点击背景关闭
document.getElementById('previewModal').addEventListener('click', function(e) {
    if (e.target === this) {
        closePreviewModal();
    }
});
</script>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.elevator', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH D:\else\order\ElevatorManagement\ElevatorManagement\resources\views/file/show.blade.php ENDPATH**/ ?>