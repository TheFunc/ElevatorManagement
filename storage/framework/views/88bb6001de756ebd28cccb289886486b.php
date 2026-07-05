<?php $__env->startSection('title', '视频管理'); ?>
<?php $__env->startSection('page-title', '视频管理'); ?>

<?php $__env->startSection('content'); ?>
<style>
@media (max-width: 767px) {
    /* 手机端表格深度优化 */
    .video-table-wrapper {
        margin: 0 -16px;
        padding: 0 16px;
        overflow-x: auto;
        -webkit-overflow-scrolling: touch;
    }
    
    .video-table {
        min-width: 650px;
        width: auto;
    }
    
    .video-table th,
    .video-table td {
        padding: 14px 12px !important;
    }
    
    /* 图片列宽度保护 */
    .video-table td:first-child,
    .video-table th:first-child {
        min-width: 60px;
        width: 60px;
    }
    
    /* 图片不变形优化 */
    .video-thumbnail {
        width: 40px !important;
        height: 40px !important;
        object-fit: contain !important;
        border-radius: 6px;
        flex-shrink: 0;
    }
    
    /* 文字自动换行 */
    .video-table td {
        white-space: normal !important;
        word-break: break-word;
        line-height: 1.5;
    }
    
    /* 操作按钮优化 */
    .video-actions {
        display: flex;
        flex-direction: column;
        gap: 6px;
        min-width: 80px;
    }
    
    .video-actions button,
    .video-actions a {
        padding: 6px 8px;
        border-radius: 6px;
        font-size: 13px;
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 4px;
    }
}
</style>

<div class="card">
    <div class="flex justify-between items-center mb-6">
        <h3 class="text-xl font-semibold text-gray-800">视频类型管理</h3>
        <button type="button" onclick="showAddModal()" class="bg-blue-500 hover:bg-blue-600 text-white px-4 py-2 rounded-lg flex items-center gap-2">
            <i class="ri-add-line"></i>
            添加视频类型
        </button>
    </div>

    <?php if(session('success')): ?>
        <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-4">
            <?php echo e(session('success')); ?>

        </div>
    <?php endif; ?>

    <?php if($errors->any()): ?>
        <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-4">
            <ul>
                <?php $__currentLoopData = $errors->all(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $error): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <li><?php echo e($error); ?></li>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </ul>
        </div>
    <?php endif; ?>

    <div class="video-table-wrapper overflow-x-auto">
        <table class="video-table min-w-full bg-white border border-gray-200 rounded-lg">
            <thead>
                <tr class="bg-gray-50">
                    <th class="px-6 py-3 border-b border-gray-200 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">ID</th>
                    <th class="px-6 py-3 border-b border-gray-200 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">视频类型</th>
                    <th class="px-6 py-3 border-b border-gray-200 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">创建时间</th>
                    <th class="px-6 py-3 border-b border-gray-200 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">更新时间</th>
                    <th class="px-6 py-3 border-b border-gray-200 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">操作</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-200">
                <?php $__currentLoopData = $videoTypes; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $videoType): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <tr class="hover:bg-gray-50">
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900"><?php echo e($videoType->id); ?></td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900"><?php echo e($videoType->type); ?></td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500"><?php echo e($videoType->created_at->format('Y-m-d H:i:s')); ?></td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500"><?php echo e($videoType->updated_at->format('Y-m-d H:i:s')); ?></td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium video-actions">
                        <button onclick="editVideoType(<?php echo e($videoType->id); ?>, '<?php echo e($videoType->type); ?>')" class="text-indigo-600 hover:text-indigo-900">
                            <i class="ri-edit-line"></i> 编辑
                        </button>
                        <button onclick="deleteVideoType(<?php echo e($videoType->id); ?>)" class="text-red-600 hover:text-red-900">
                            <i class="ri-delete-bin-line"></i> 删除
                        </button>
                    </td>
                </tr>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>

                <?php if($videoTypes->isEmpty()): ?>
                <tr>
                    <td colspan="5" class="px-6 py-12 text-center text-gray-500">
                        <i class="ri-inbox-line text-4xl mb-2"></i>
                        <p>暂无视频类型数据</p>
                    </td>
                </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>

<!-- 添加/编辑模态框 -->
<div id="typeModal" class="fixed inset-0 bg-black bg-opacity-50 hidden items-center justify-center z-50">
    <div class="bg-white rounded-lg w-full max-w-md mx-4 p-6">
        <div class="flex justify-between items-center mb-4">
            <h3 id="modalTitle" class="text-lg font-semibold text-gray-800">添加视频类型</h3>
            <button onclick="closeModal()" class="text-gray-400 hover:text-gray-600">
                <i class="ri-close-line text-xl"></i>
            </button>
        </div>

        <form id="typeForm" action="<?php echo e(route('video.type.store')); ?>" method="POST">
            <?php echo csrf_field(); ?>
            <input type="hidden" id="typeId" name="id" value="">
            
            <div class="mb-4">
                <label class="block text-sm font-medium text-gray-700 mb-2">视频类型名称</label>
                <input type="text" id="typeName" name="type" value="" required class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500" placeholder="请输入视频类型名称">
            </div>

            <div class="flex justify-end gap-3 mt-6">
                <button type="button" onclick="closeModal()" class="px-4 py-2 border border-gray-300 rounded-lg text-gray-700 hover:bg-gray-50">
                    取消
                </button>
                <button type="submit" class="px-4 py-2 bg-blue-500 text-white rounded-lg hover:bg-blue-600">
                    保存
                </button>
            </div>
        </form>
    </div>
</div>

<!-- 删除确认模态框 -->
<div id="deleteModal" class="fixed inset-0 bg-black bg-opacity-50 hidden items-center justify-center z-50">
    <div class="bg-white rounded-lg w-full max-w-sm mx-4 p-6">
        <div class="text-center">
            <i class="ri-error-warning-line text-5xl text-red-500 mb-4"></i>
            <h3 class="text-lg font-semibold text-gray-800 mb-2">确认删除</h3>
            <p class="text-gray-500 mb-6">确定要删除此视频类型吗？此操作不可恢复。</p>
            
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
function showAddModal() {
    document.getElementById('modalTitle').textContent = '添加视频类型';
    document.getElementById('typeForm').action = '<?php echo e(route('video.type.store')); ?>';
    document.getElementById('typeId').value = '';
    document.getElementById('typeName').value = '';
    document.getElementById('typeModal').classList.remove('hidden');
    document.getElementById('typeModal').classList.add('flex');
}

function editVideoType(id, name) {
    document.getElementById('modalTitle').textContent = '编辑视频类型';
    document.getElementById('typeForm').action = '/video-type/' + id + '/update';
    document.getElementById('typeId').value = id;
    document.getElementById('typeName').value = name;
    document.getElementById('typeModal').classList.remove('hidden');
    document.getElementById('typeModal').classList.add('flex');
}

function closeModal() {
    document.getElementById('typeModal').classList.add('hidden');
    document.getElementById('typeModal').classList.remove('flex');
}

function deleteVideoType(id) {
    document.getElementById('deleteForm').action = '/video-type/' + id + '/delete';
    document.getElementById('deleteModal').classList.remove('hidden');
    document.getElementById('deleteModal').classList.add('flex');
}

function closeDeleteModal() {
    document.getElementById('deleteModal').classList.add('hidden');
    document.getElementById('deleteModal').classList.remove('flex');
}
</script>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.elevator', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH D:\else\order\ElevatorManagement\ElevatorManagement\resources\views/video/index.blade.php ENDPATH**/ ?>