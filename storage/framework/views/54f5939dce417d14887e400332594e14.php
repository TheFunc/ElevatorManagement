<?php $__env->startSection('title', '资料上传'); ?>
<?php $__env->startSection('page-title', '资料上传'); ?>

<?php $__env->startSection('content'); ?>
<div class="card max-w-2xl mx-auto">
    <h3 class="text-xl font-semibold text-gray-800 mb-4">资料上传</h3>
    <p class="text-gray-600 mb-6">统一上传所有类型的电梯管理资料文件</p>

    <?php if(session('success')): ?>
        <div class="bg-green-50 border border-green-200 text-green-700 px-4 py-3 rounded-lg mb-6">
            <?php echo e(session('success')); ?>

        </div>
    <?php endif; ?>

    <?php if(session('error')): ?>
        <div class="bg-red-50 border border-red-200 text-red-700 px-4 py-3 rounded-lg mb-6">
            <?php echo e(session('error')); ?>

        </div>
    <?php endif; ?>

    <?php if($errors->any()): ?>
        <div class="bg-red-50 border border-red-200 text-red-700 px-4 py-3 rounded-lg mb-6">
            <ul class="list-disc pl-5">
                <?php $__currentLoopData = $errors->all(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $error): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <li><?php echo e($error); ?></li>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </ul>
        </div>
    <?php endif; ?>

    <form action="<?php echo e(route('file.upload')); ?>" method="POST" enctype="multipart/form-data" class="space-y-6">
        <?php echo csrf_field(); ?>
        
        <div>
            <label class="block text-sm font-medium text-gray-700 mb-2">资料类型</label>
            <select name="type" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary focus:border-primary outline-none">
                <option value="">请选择资料类型</option>
                <option value="prepare" <?php echo e(old('type') == 'prepare' ? 'selected' : ''); ?>>准用资料</option>
                <option value="maintenance" <?php echo e(old('type') == 'maintenance' ? 'selected' : ''); ?>>维保资料</option>
                <option value="inspection" <?php echo e(old('type') == 'inspection' ? 'selected' : ''); ?>>日常巡检资料</option>
                <option value="fault" <?php echo e(old('type') == 'fault' ? 'selected' : ''); ?>>故障记录资料</option>
                <option value="repair" <?php echo e(old('type') == 'repair' ? 'selected' : ''); ?>>维修记录资料</option>
                <option value="accident" <?php echo e(old('type') == 'accident' ? 'selected' : ''); ?>>事故记录资料</option>
                <option value="rescue" <?php echo e(old('type') == 'rescue' ? 'selected' : ''); ?>>救援演练资料</option>
                <option value="annual_check" <?php echo e(old('type') == 'annual_check' ? 'selected' : ''); ?>>年检资料</option>
            </select>
        </div>

        <div>
            <label class="block text-sm font-medium text-gray-700 mb-2">文件标题</label>
            <input type="text" name="title" value="<?php echo e(old('title')); ?>" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary focus:border-primary outline-none">
        </div>

        <div>
            <label class="block text-sm font-medium text-gray-700 mb-2">文件描述</label>
            <textarea name="description" rows="3" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary focus:border-primary outline-none"><?php echo e(old('description')); ?></textarea>
        </div>

        <div>
            <label class="block text-sm font-medium text-gray-700 mb-2">选择文件</label>
            <div class="border-2 border-dashed border-gray-300 rounded-lg p-8 text-center hover:border-primary transition-colors">
                <input type="file" name="file" id="file" class="hidden" accept=".doc,.docx,.pdf,.xls,.xlsx,.ppt,.pptx,.txt">
                <label for="file" class="cursor-pointer">
                    <i class="ri-upload-cloud-2-line text-4xl text-gray-400 mb-2"></i>
                    <p class="text-gray-600">点击选择文件或拖拽文件到此处</p>
                    <p class="text-sm text-gray-500 mt-1">支持 Word、PDF、Excel、PPT、TXT 格式，最大20MB</p>
                </label>
            </div>
            <div id="file-name" class="mt-2 text-sm text-gray-600"></div>
        </div>

        <div>
            <button type="submit" class="px-6 py-3 bg-primary text-white rounded-lg hover:bg-dark transition-colors font-medium">
                <i class="ri-upload-line mr-2"></i>上传文件
            </button>
        </div>
    </form>
</div>

<script>
document.getElementById('file').addEventListener('change', function(e) {
    if (e.target.files.length > 0) {
        document.getElementById('file-name').textContent = '已选择文件: ' + e.target.files[0].name;
    }
});
</script>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.elevator', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH D:\else\order\ElevatorManagement\ElevatorManagement\resources\views/data/upload.blade.php ENDPATH**/ ?>