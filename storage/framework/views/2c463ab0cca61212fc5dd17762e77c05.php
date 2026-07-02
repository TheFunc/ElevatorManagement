<?php $__env->startSection('title', '修改电梯信息'); ?>
<?php $__env->startSection('page-title', '修改电梯信息'); ?>

<?php $__env->startSection('content'); ?>
<div class="card max-w-2xl mx-auto">
    <h3 class="text-xl font-semibold text-gray-800 mb-6">修改电梯信息</h3>

    <?php if($errors->any()): ?>
        <div class="bg-red-50 border border-red-200 text-red-700 px-4 py-3 rounded-lg mb-6">
            <ul class="list-disc pl-5">
                <?php $__currentLoopData = $errors->all(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $error): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <li><?php echo e($error); ?></li>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </ul>
        </div>
    <?php endif; ?>

    <form action="<?php echo e(route('device.update', $device->id)); ?>" method="POST" class="space-y-6">
        <?php echo csrf_field(); ?>
        
        <div>
            <label class="block text-sm font-medium text-gray-700 mb-2">设备代码 <span class="text-red-500">*</span></label>
            <input type="text" name="number" value="<?php echo e(old('number', $device->number)); ?>" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary focus:border-primary outline-none" placeholder="请输入电梯编号">
        </div>

        <div>
            <label class="block text-sm font-medium text-gray-700 mb-2">电梯注册编号</label>
            <input type="text" name="register" value="<?php echo e(old('register', $device->register)); ?>" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary focus:border-primary outline-none" placeholder="请输入电梯注册编号">
        </div>

        <div>
            <label class="block text-sm font-medium text-gray-700 mb-2">出厂（产品）编号</label>
            <input type="text" name="FactorySerial" value="<?php echo e(old('FactorySerial', $device->FactorySerial)); ?>" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary focus:border-primary outline-none" placeholder="请输入出厂编号">
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">设备名称</label>
                <input type="text" name="name" value="<?php echo e(old('name', $device->name)); ?>" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary focus:border-primary outline-none" placeholder="例如：乘客电梯">
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">设备型号</label>
                <input type="text" name="Model" value="<?php echo e(old('Model', $device->Model)); ?>" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary focus:border-primary outline-none" placeholder="请输入设备型号">
            </div>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">制造厂家</label>
                <input type="text" name="Manufacturer" value="<?php echo e(old('Manufacturer', $device->Manufacturer)); ?>" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary focus:border-primary outline-none" placeholder="请输入制造厂家">
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">设备使用状态</label>
                <select name="status" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary focus:border-primary outline-none">
                    <option value="1" <?php echo e(old('status', $device->status) == 1 ? 'selected' : ''); ?>>在用</option>
                    <option value="0" <?php echo e(old('status', $device->status) == 0 ? 'selected' : ''); ?>>停用</option>
                    <option value="2" <?php echo e(old('status', $device->status) == 2 ? 'selected' : ''); ?>>报废</option>
                </select>
            </div>
        </div>

        <div>
            <label class="block text-sm font-medium text-gray-700 mb-2">电梯位置 <span class="text-red-500">*</span></label>
            <input type="text" name="Position" value="<?php echo e(old('Position', $device->Position)); ?>" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary focus:border-primary outline-none" placeholder="例如：1号楼东侧">
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">校区 <span class="text-red-500">*</span></label>
                <select name="Campus" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary focus:border-primary outline-none">
                    <option value="">请选择校区</option>
                    <?php $__currentLoopData = $campuses; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $campus): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <option value="<?php echo e($campus->Campus); ?>" <?php echo e(old('Campus', $device->Campus) == $campus->Campus ? 'selected' : ''); ?>><?php echo e($campus->Campus); ?></option>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </select>
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">楼号 <span class="text-red-500">*</span></label>
                <input type="text" name="building" value="<?php echo e(old('building', $device->building)); ?>" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary focus:border-primary outline-none" placeholder="例如：1号楼">
            </div>
        </div>

        <div>
            <label class="block text-sm font-medium text-gray-700 mb-2">电梯描述 <span class="text-red-500">*</span></label>
            <textarea name="desc" rows="4" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary focus:border-primary outline-none" placeholder="请输入电梯详细描述信息"><?php echo e(old('desc', $device->desc)); ?></textarea>
        </div>

        <div class="flex gap-4">
            <button type="submit" class="px-6 py-3 bg-primary text-white rounded-lg hover:bg-dark transition-colors font-medium">
                <i class="ri-save-line mr-2"></i>保存修改
            </button>
            <a href="<?php echo e(route('device.show', $device->id)); ?>" class="px-6 py-3 bg-gray-200 text-gray-700 rounded-lg hover:bg-gray-300 transition-colors font-medium">
                取消
            </a>
        </div>
    </form>
</div>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.elevator', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH D:\else\order\ElevatorManagement\ElevatorManagement\resources\views/elevator/edit.blade.php ENDPATH**/ ?>