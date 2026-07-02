<?php $__env->startSection('title', '校区管理'); ?>
<?php $__env->startSection('page-title', '校区管理'); ?>

<?php $__env->startSection('content'); ?>
<div class="card">
    <div class="flex justify-between items-center mb-6">
        <h3 class="text-xl font-semibold text-gray-800">校区信息管理</h3>
        <a href="<?php echo e(route('elevator.ledger')); ?>" class="px-4 py-2 bg-gray-200 text-gray-700 rounded-lg hover:bg-gray-300 transition-colors">
            返回台账
        </a>
    </div>

    <?php if(session('success')): ?>
        <div class="bg-green-50 border border-green-200 text-green-700 px-4 py-3 rounded-lg mb-6">
            <?php echo e(session('success')); ?>

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

    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        <!-- 添加校区表单 -->
        <div class="card border border-gray-200">
            <h4 class="text-lg font-medium text-gray-800 mb-4">添加新校区</h4>
            <form action="<?php echo e(route('campus.store')); ?>" method="POST" class="space-y-4">
                <?php echo csrf_field(); ?>
                
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">校区名称 <span class="text-red-500">*</span></label>
                    <input type="text" name="Campus" value="<?php echo e(old('Campus')); ?>" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary focus:border-primary outline-none" placeholder="例如：东校区、西校区">
                </div>
                
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">校区描述 <span class="text-red-500">*</span></label>
                    <textarea name="description" rows="3" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary focus:border-primary outline-none" placeholder="校区位置、范围等描述信息"><?php echo e(old('description')); ?></textarea>
                </div>
                
                <button type="submit" class="px-5 py-2 bg-primary text-white rounded-lg hover:bg-dark transition-colors">
                    <i class="ri-add-line mr-1"></i>添加校区
                </button>
            </form>
        </div>

        <!-- 校区列表 -->
        <div class="card border border-gray-200">
            <h4 class="text-lg font-medium text-gray-800 mb-4">已添加校区列表</h4>
            
            <div class="overflow-x-auto">
                <table class="w-full">
                    <thead>
                        <tr class="bg-gray-50">
                            <th class="px-4 py-3 text-left text-sm font-medium text-gray-600">校区名称</th>
                            <th class="px-4 py-3 text-left text-sm font-medium text-gray-600">描述</th>
                            <th class="px-4 py-3 text-left text-sm font-medium text-gray-600">操作</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php $__currentLoopData = $campuses; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $campus): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <tr class="border-b border-gray-100 hover:bg-gray-50">
                            <td class="px-4 py-3 text-gray-800 font-medium"><?php echo e($campus->Campus); ?></td>
                            <td class="px-4 py-3 text-gray-600"><?php echo e(Str::limit($campus->description, 30)); ?></td>
                            <td class="px-4 py-3">
                                <form action="<?php echo e(route('campus.delete', $campus->id)); ?>" method="POST" class="inline" onsubmit="return confirm('确定要删除这个校区吗？')">
                                    <?php echo csrf_field(); ?>
                                    <button type="submit" class="text-red-600 hover:text-red-800 font-medium">
                                        <i class="ri-delete-bin-line mr-1"></i>删除
                                    </button>
                                </form>
                            </td>
                        </tr>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        
                        <?php if($campuses->isEmpty()): ?>
                        <tr>
                            <td colspan="3" class="px-4 py-8 text-center text-gray-500">
                                暂无校区数据，请在左侧表单添加
                            </td>
                        </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.elevator', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH D:\else\order\ElevatorManagement\ElevatorManagement\resources\views/campus/index.blade.php ENDPATH**/ ?>