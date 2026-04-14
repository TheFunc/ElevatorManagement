// 全局删除确认弹窗
function showDeleteModal(actionUrl) {
    const modal = document.createElement('div');
    modal.id = 'deleteModal';
    modal.className = 'fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50';
    modal.innerHTML = `
        <div class="bg-white rounded-lg shadow-xl max-w-md w-full mx-4 transform transition-all">
            <div class="p-6">
                <div class="flex items-center justify-center w-12 h-12 mx-auto bg-red-100 rounded-full mb-4">
                    <i class="ri-alert-line text-red-500 text-2xl"></i>
                </div>
                <h3 class="text-lg font-semibold text-center text-gray-800 mb-2">确认删除</h3>
                <p class="text-center text-gray-500 mb-6">确定要删除这个项目吗？此操作无法撤销。</p>
                <div class="flex gap-3 justify-center">
                    <button onclick="closeDeleteModal()" class="px-6 py-2 bg-gray-100 hover:bg-gray-200 text-gray-700 rounded-lg font-medium">
                        取消
                    </button>
                    <button onclick="submitDelete('${actionUrl}')" class="px-6 py-2 bg-red-500 hover:bg-red-600 text-white rounded-lg font-medium">
                        确认删除
                    </button>
                </div>
            </div>
        </div>
    `;
    document.body.appendChild(modal);
}

function closeDeleteModal() {
    const modal = document.getElementById('deleteModal');
    if (modal) {
        modal.remove();
    }
}

function submitDelete(actionUrl) {
    // 创建表单直接提交
    const form = document.createElement('form');
    form.method = 'POST';
    form.action = actionUrl;
    
    // 从meta标签获取CSRF Token
    const csrfToken = document.querySelector('meta[name="csrf-token"]').content;
    
    form.innerHTML = `<input type="hidden" name="_token" value="${csrfToken}">`;
    document.body.appendChild(form);
    form.submit();
    
    closeDeleteModal();
}
