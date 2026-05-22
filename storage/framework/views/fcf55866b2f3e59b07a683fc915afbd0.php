<!-- 删除确认弹窗 -->
<div id="deleteConfirmModal" class="fixed inset-0 bg-black/50 z-50 hidden items-center justify-center">
    <div class="bg-white rounded-xl shadow-xl max-w-md w-11/12 transform transition-all scale-95 opacity-0" id="deleteConfirmBox">
        <div class="p-6 text-center">
            <div class="w-16 h-16 mx-auto mb-4 rounded-full bg-red-100 flex items-center justify-center">
                <i class="ri-delete-bin-6-line text-3xl text-red-600"></i>
            </div>
            <h3 class="text-xl font-semibold text-gray-800 mb-2">确认删除</h3>
            <p class="text-gray-600 mb-1">您确定要删除 <span class="font-medium text-gray-800" id="deleteTitle"></span> 吗？</p>
            <p class="text-sm text-gray-500 mb-6">此操作将同时删除 <span id="deleteCount" class="font-medium">1</span> 张图片，删除后无法恢复！</p>
            
            <div class="flex gap-3 justify-center">
                <button type="button" onclick="closeDeleteConfirm()" class="px-6 py-2.5 bg-gray-200 text-gray-700 rounded-lg hover:bg-gray-300 transition-colors font-medium">
                    取消
                </button>
                <form action="" method="POST" id="deleteForm" class="inline">
                    <?php echo csrf_field(); ?>
                    <?php echo method_field('DELETE'); ?>
                    <button type="submit" class="px-6 py-2.5 bg-red-600 text-white rounded-lg hover:bg-red-700 transition-colors font-medium">
                        <i class="ri-delete-bin-line mr-1"></i>确认删除
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>

<style>
#deleteConfirmModal.hidden + #deleteConfirmBox {
    transform: scale(0.95);
    opacity: 0;
}
#deleteConfirmModal.flex + #deleteConfirmBox {
    transform: scale(1);
    opacity: 1;
}
</style>

<script>
function showDeleteConfirm(id, title, count) {
    const modal = document.getElementById('deleteConfirmModal');
    const box = document.getElementById('deleteConfirmBox');
    
    document.getElementById('deleteTitle').textContent = title;
    document.getElementById('deleteCount').textContent = count;
    document.getElementById('deleteForm').action = `/repair-orders/${id}`;
    
    modal.classList.remove('hidden');
    modal.classList.add('flex');
    
    setTimeout(() => {
        box.style.transform = 'scale(1)';
        box.style.opacity = '1';
    }, 10);
}

function closeDeleteConfirm() {
    const modal = document.getElementById('deleteConfirmModal');
    const box = document.getElementById('deleteConfirmBox');
    
    box.style.transform = 'scale(0.95)';
    box.style.opacity = '0';
    
    setTimeout(() => {
        modal.classList.add('hidden');
        modal.classList.remove('flex');
    }, 200);
}

// 点击背景关闭
document.getElementById('deleteConfirmModal').addEventListener('click', function(e) {
    if (e.target === this) {
        closeDeleteConfirm();
    }
});

// ESC关闭
document.addEventListener('keydown', function(e) {
    if (e.key === 'Escape' && !document.getElementById('deleteConfirmModal').classList.contains('hidden')) {
        closeDeleteConfirm();
    }
});
</script><?php /**PATH D:\else\order\ElevatorManagement\ElevatorManagement\resources\views/partials/delete-confirm.blade.php ENDPATH**/ ?>