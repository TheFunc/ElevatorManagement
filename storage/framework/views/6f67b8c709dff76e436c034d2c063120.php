<?php $__env->startSection('title', '电梯详情'); ?>
<?php $__env->startSection('page-title', '电梯详情'); ?>

<?php $__env->startSection('content'); ?>
<div class="card max-w-2xl mx-auto">
    <div class="flex justify-between items-center mb-6">
        <h3 class="text-xl font-semibold text-gray-800">电梯详细信息</h3>
        <div class="flex gap-3">
            <a href="<?php echo e(route('device.edit', $device->id)); ?>" class="px-4 py-2 bg-primary text-white rounded-lg hover:bg-dark transition-colors">
                <i class="ri-edit-line mr-1"></i>修改信息
            </a>
            <a href="<?php echo e(route('elevator.ledger')); ?>" class="px-4 py-2 bg-gray-200 text-gray-700 rounded-lg hover:bg-gray-300 transition-colors">
                返回列表
            </a>
        </div>
    </div>

    <?php if(session('success')): ?>
        <div class="bg-green-50 border border-green-200 text-green-700 px-4 py-3 rounded-lg mb-6">
            <?php echo e(session('success')); ?>

        </div>
    <?php endif; ?>

    <div class="space-y-6">
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <div class="bg-gray-50 p-4 rounded-lg">
                <p class="text-sm text-gray-500 mb-1">电梯编号</p>
                <p class="text-lg font-semibold text-gray-800"><?php echo e($device->number); ?></p>
            </div>
            <div class="bg-gray-50 p-4 rounded-lg">
                <p class="text-sm text-gray-500 mb-1">设备名称</p>
                <p class="text-lg font-semibold text-gray-800"><?php echo e($device->name ?? '未填写'); ?></p>
            </div>
        </div>
        
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <div class="bg-gray-50 p-4 rounded-lg">
                <p class="text-sm text-gray-500 mb-1">电梯注册编号</p>
                <p class="text-lg font-semibold text-gray-800"><?php echo e($device->register ?? '未填写'); ?></p>
            </div>
            <div class="bg-gray-50 p-4 rounded-lg">
                <p class="text-sm text-gray-500 mb-1">出厂编号</p>
                <p class="text-lg font-semibold text-gray-800"><?php echo e($device->FactorySerial ?? '未填写'); ?></p>
            </div>
        </div>
        
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <div class="bg-gray-50 p-4 rounded-lg">
                <p class="text-sm text-gray-500 mb-1">设备型号</p>
                <p class="text-lg font-semibold text-gray-800"><?php echo e($device->Model ?? '未填写'); ?></p>
            </div>
            <div class="bg-gray-50 p-4 rounded-lg">
                <p class="text-sm text-gray-500 mb-1">制造厂家</p>
                <p class="text-lg font-semibold text-gray-800"><?php echo e($device->Manufacturer ?? '未填写'); ?></p>
            </div>
        </div>
        
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <div class="bg-gray-50 p-4 rounded-lg">
                <p class="text-sm text-gray-500 mb-1">使用状态</p>
                <p class="text-lg font-semibold 
                    <?php echo e($device->status == 1 ? 'text-green-600' : ($device->status == 0 ? 'text-red-600' : 'text-gray-500')); ?>">
                    <?php echo e($device->status == 1 ? '在用' : ($device->status == 0 ? '停用' : '报废')); ?>

                </p>
            </div>
            <div class="bg-gray-50 p-4 rounded-lg">
                <p class="text-sm text-gray-500 mb-1">电梯位置</p>
                <p class="text-lg font-semibold text-gray-800"><?php echo e($device->Position); ?></p>
            </div>
        </div>
        
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <div class="bg-gray-50 p-4 rounded-lg">
                <p class="text-sm text-gray-500 mb-1">校区</p>
                <p class="text-lg font-semibold text-gray-800"><?php echo e($device->Campus); ?></p>
            </div>
            <div class="bg-gray-50 p-4 rounded-lg">
                <p class="text-sm text-gray-500 mb-1">楼号</p>
                <p class="text-lg font-semibold text-gray-800"><?php echo e($device->building); ?></p>
            </div>
        </div>
        
        <div class="bg-gray-50 p-4 rounded-lg">
            <p class="text-sm text-gray-500 mb-1">电梯描述</p>
            <p class="text-gray-800"><?php echo e($device->desc); ?></p>
        </div>
        
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <div class="bg-gray-50 p-4 rounded-lg">
                <p class="text-sm text-gray-500 mb-1">创建时间</p>
                <p class="text-gray-800"><?php echo e($device->created_at->format('Y-m-d H:i')); ?></p>
            </div>
            <div class="bg-gray-50 p-4 rounded-lg">
                <p class="text-sm text-gray-500 mb-1">最后更新</p>
                <p class="text-gray-800"><?php echo e($device->updated_at->format('Y-m-d H:i')); ?></p>
            </div>
        </div>
    </div>
</div>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.elevator', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH D:\else\order\ElevatorManagement\ElevatorManagement\resources\views/elevator/show.blade.php ENDPATH**/ ?>