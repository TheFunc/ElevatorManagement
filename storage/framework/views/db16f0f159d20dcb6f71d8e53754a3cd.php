<?php $__env->startSection('title', '添加文本'); ?>
<?php $__env->startSection('page-title', '添加文本'); ?>

<?php $__env->startSection('content'); ?>
<div class="card max-w-4xl mx-auto">
    <div class="mb-6">
        <h3 class="text-xl font-semibold text-gray-800">添加新文本</h3>
        <p class="text-gray-500 mt-1">填写以下信息以添加新的文本内容</p>
    </div>

    <?php if($errors->any()): ?>
        <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-4">
            <ul class="list-disc list-inside">
                <?php $__currentLoopData = $errors->all(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $error): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <li><?php echo e($error); ?></li>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </ul>
        </div>
    <?php endif; ?>

    <form action="<?php echo e(route('text-info.store')); ?>" method="POST">
        <?php echo csrf_field(); ?>
        
        <!-- 隐藏字段：TextGroup 默认为 null -->
        <input type="hidden" name="TextGroup" value="null">
        
        <!-- 文本类型 -->
        <div class="mb-6">
            <label class="block text-sm font-medium text-gray-700 mb-2">
                文本类型 <span class="text-red-500">*</span>
            </label>
            <select name="TextType" required class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
                <option value="">请选择文本类型</option>
                <?php $__currentLoopData = $textTypes; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $type): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <option value="<?php echo e($type->type); ?>"><?php echo e($type->type); ?></option>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </select>
            <p class="text-sm text-gray-500 mt-1">如果没有合适的类型，请先在"文本类型管理"中添加</p>
        </div>

        <!-- 文本内容 -->
        <div class="mb-6">
            <label class="block text-sm font-medium text-gray-700 mb-2">
                文本内容
            </label>
            <textarea name="TextContent" rows="20" 
                      class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 font-mono text-sm"
                      placeholder="在此输入文本内容..."><?php echo e(old('TextContent')); ?></textarea>
            <p class="text-sm text-gray-500 mt-1">支持长文本内容</p>
        </div>

        <!-- 操作按钮 -->
        <div class="flex justify-end gap-3 pt-4 border-t">
            <a href="<?php echo e(route('text-management.preview')); ?>" class="px-6 py-2 border border-gray-300 rounded-lg text-gray-700 hover:bg-gray-50">
                取消
            </a>
            <button type="submit" class="px-6 py-2 bg-blue-500 text-white rounded-lg hover:bg-blue-600">
                <i class="ri-save-line"></i> 保存
            </button>
        </div>
    </form>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.elevator', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH D:\else\order\ElevatorManagement\ElevatorManagement\resources\views/text-management/create.blade.php ENDPATH**/ ?>