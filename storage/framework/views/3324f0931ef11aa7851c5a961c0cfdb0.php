<?php $__env->startSection('title', '电梯单管理'); ?>
<?php $__env->startSection('page-title', '电梯单上传管理'); ?>

<?php $__env->startSection('content'); ?>
<div class="card">
    <div class="flex justify-between items-center mb-6">
        <h3 class="text-xl font-semibold text-gray-800">电梯单列表</h3>
        <button type="button" onclick="showUploadModal()" class="px-4 py-2 bg-primary text-white rounded-lg hover:bg-dark transition-colors">
            <i class="ri-add-line mr-1"></i>上传电梯单
        </button>
    </div>

    <!-- 搜索栏 -->
    <form action="" method="GET" class="mb-6">
        <div class="flex gap-3 flex-wrap">
            <input type="text" name="keyword" value="<?php echo e(request('keyword')); ?>" placeholder="搜索标题或描述..." class="flex-1 min-w-[200px] px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary focus:border-primary outline-none">
            <button type="submit" class="px-6 py-2 bg-gray-700 text-white rounded-lg hover:bg-gray-800 transition-colors">
                <i class="ri-search-line mr-1"></i>搜索
            </button>
            <?php if(request('keyword')): ?>
            <a href="<?php echo e(route('repair.orders')); ?>" class="px-6 py-2 bg-gray-200 text-gray-700 rounded-lg hover:bg-gray-300 transition-colors">
                重置
            </a>
            <?php endif; ?>
        </div>
    </form>

    <div class="overflow-x-auto">
        <table class="w-full">
            <thead>
                <tr class="bg-gray-50">
                    <th class="px-4 py-3 text-left text-sm font-medium text-gray-600">缩略图</th>
                    <th class="px-4 py-3 text-left text-sm font-medium text-gray-600">标题</th>
                    <th class="px-4 py-3 text-left text-sm font-medium text-gray-600">描述</th>
                    <th class="px-4 py-3 text-left text-sm font-medium text-gray-600">上传时间</th>
                    <th class="px-4 py-3 text-left text-sm font-medium text-gray-600">操作</th>
                </tr>
            </thead>
            <tbody>
                <?php $__currentLoopData = $orders; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $order): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <tr class="border-b border-gray-100 hover:bg-gray-50">
            <td class="px-4 py-3">
                <div class="relative w-20 h-20 overflow-hidden rounded-lg">
                    <?php if(isset($order->images) && $order->images->count() > 0): ?>
                        <div class="carousel-thumb" data-images='<?php echo json_encode($order->images->map(fn($img) => ["path" => asset($img->path), "title" => $img->title]), 512) ?>'>
                            <img src="<?php echo e(asset($order->images->first()->path)); ?>" alt="<?php echo e($order->title); ?>" class="w-full h-full object-cover cursor-pointer hover:scale-105 transition-transform carousel-image" onclick="showImageGroup(this)">
                            <?php if($order->images->count() > 1): ?>
                            <div class="absolute bottom-1 right-1 bg-black/70 text-white text-xs px-2 py-0.5 rounded">
                                1/<?php echo e($order->images->count()); ?>

                            </div>
                            <button class="absolute left-1 top-1/2 -translate-y-1/2 bg-black/50 text-white w-5 h-5 rounded-full text-xs hover:bg-black/70 carousel-prev" onclick="event.stopPropagation(); prevThumb(this)">
                                <i class="ri-arrow-left-s-line"></i>
                            </button>
                            <button class="absolute right-1 top-1/2 -translate-y-1/2 bg-black/50 text-white w-5 h-5 rounded-full text-xs hover:bg-black/70 carousel-next" onclick="event.stopPropagation(); nextThumb(this)">
                                <i class="ri-arrow-right-s-line"></i>
                            </button>
                            <?php endif; ?>
                        </div>
                    <?php else: ?>
                        <img src="<?php echo e(asset($order->path)); ?>" alt="<?php echo e($order->title); ?>" class="w-full h-full object-cover cursor-pointer hover:scale-105 transition-transform" onclick="showImage('<?php echo e(asset($order->path)); ?>', '<?php echo e($order->title); ?>')">
                    <?php endif; ?>
                </div>
            </td>
                    <td class="px-4 py-3 text-gray-800 font-medium"><?php echo e($order->title); ?></td>
                    <td class="px-4 py-3 text-gray-600"><?php echo e($order->description); ?></td>
                    <td class="px-4 py-3 text-gray-600"><?php echo e(\Carbon\Carbon::parse($order->time)->format('Y-m-d H:i')); ?></td>
                    <td class="px-4 py-3">
                        <div class="flex gap-2">
                            <button onclick="showImageGroup(this)" data-images='<?php echo json_encode($order->images->map(fn($img) => ["path" => asset($img->path), "title" => $img->title]), 512) ?>' class="px-3 py-1 bg-primary/10 text-primary rounded-lg hover:bg-primary/20 transition-colors text-sm font-medium">
                                <i class="ri-eye-line mr-1"></i>查看
                            </button>
                            <a href="<?php echo e(route('repair.download', $order->id)); ?>" class="px-3 py-1 bg-green-100 text-green-700 rounded-lg hover:bg-green-200 transition-colors text-sm font-medium">
                                <i class="ri-download-line mr-1"></i>下载
                            </a>
                            <button onclick="showDeleteConfirm(<?php echo e($order->id); ?>, '<?php echo e($order->title); ?>', <?php echo e(isset($order->images) ? $order->images->count() : 1); ?>)" class="px-3 py-1 bg-red-100 text-red-700 rounded-lg hover:bg-red-200 transition-colors text-sm font-medium">
                                <i class="ri-delete-bin-line mr-1"></i>删除
                            </button>
                        </div>
                    </td>
                </tr>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>

                <?php if($orders->isEmpty()): ?>
                <tr>
                    <td colspan="5" class="px-4 py-8 text-center text-gray-500">
                        暂无电梯单数据，请点击上方按钮上传
                    </td>
                </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
    
    <!-- 分页 -->
    <?php if($orders->hasPages()): ?>
    <div class="mt-6 flex justify-center">
        <div class="flex items-center gap-1.5">
            
            <?php if($orders->onFirstPage()): ?>
                <span class="px-3 py-2 text-sm text-gray-400 bg-gray-50 rounded-xl cursor-not-allowed">
                    <i class="ri-arrow-left-s-line"></i>
                </span>
            <?php else: ?>
                <a href="<?php echo e($orders->previousPageUrl()); ?>" class="px-3 py-2 text-sm text-gray-700 bg-white border border-gray-200 rounded-xl hover:bg-light hover:border-primary hover:text-primary transition-all duration-200">
                    <i class="ri-arrow-left-s-line"></i>
                </a>
            <?php endif; ?>
            
            
            <?php $__currentLoopData = $orders->getUrlRange(1, $orders->lastPage()); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $page => $url): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <?php if($page == $orders->currentPage()): ?>
                    <span class="px-4 py-2 text-sm font-medium text-white bg-primary rounded-xl shadow-sm shadow-primary/20"><?php echo e($page); ?></span>
                <?php else: ?>
                    <a href="<?php echo e($url); ?>" class="px-4 py-2 text-sm text-gray-700 bg-white border border-gray-200 rounded-xl hover:bg-light hover:border-primary hover:text-primary transition-all duration-200"><?php echo e($page); ?></a>
                <?php endif; ?>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            
            
            <?php if($orders->hasMorePages()): ?>
                <a href="<?php echo e($orders->nextPageUrl()); ?>" class="px-3 py-2 text-sm text-gray-700 bg-white border border-gray-200 rounded-xl hover:bg-light hover:border-primary hover:text-primary transition-all duration-200">
                    <i class="ri-arrow-right-s-line"></i>
                </a>
            <?php else: ?>
                <span class="px-3 py-2 text-sm text-gray-400 bg-gray-50 rounded-xl cursor-not-allowed">
                    <i class="ri-arrow-right-s-line"></i>
                </span>
            <?php endif; ?>
        </div>
    </div>
    <?php endif; ?>
</div>

<!-- 上传弹窗 -->
<div id="uploadModal" class="fixed inset-0 bg-black/50 z-50 hidden items-center justify-center">
    <div class="bg-white rounded-xl shadow-xl p-6 max-w-2xl w-11/12 transform transition-all">
        <div class="flex justify-between items-center mb-4">
            <h3 class="text-lg font-semibold text-gray-800">上传电梯单</h3>
            <button type="button" onclick="closeUploadModal()" class="text-gray-400 hover:text-gray-600">
                <i class="ri-close-line text-xl"></i>
            </button>
        </div>

        <form action="<?php echo e(route('repair.upload')); ?>" method="POST" enctype="multipart/form-data" class="space-y-4" id="uploadForm">
            <?php echo csrf_field(); ?>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">标题 <span class="text-red-500">*</span></label>
                <input type="text" name="title" id="title" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary focus:border-primary outline-none" placeholder="请输入电梯单标题">
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">描述</label>
                <textarea name="description" id="description" rows="2" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary focus:border-primary outline-none" placeholder="可选描述信息"></textarea>
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">自定义上传时间（可选）</label>
                <input type="datetime-local" name="customUploadTime" id="customUploadTime" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary focus:border-primary outline-none">
                <p class="text-sm text-gray-500 mt-1">如果不填写，将使用当前时间作为上传时间</p>
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">选择图片 <span class="text-red-500">*</span> (可多选)</label>
                <div id="dropZone" class="border-2 border-dashed border-gray-300 rounded-lg p-8 text-center cursor-pointer hover:border-primary transition-colors">
                    <i class="ri-upload-cloud-line text-4xl text-gray-400 mb-2"></i>
                    <p class="text-gray-500">点击或拖拽图片到此处上传</p>
                    <p class="text-sm text-gray-400 mt-1">支持 JPG, PNG, GIF 格式，可批量选择</p>
                    <input type="file" name="images[]" id="images" accept="image/*" multiple class="hidden">
                </div>
                <div id="previewArea" class="mt-4 grid grid-cols-4 gap-3"></div>
            </div>

            <div id="uploadError" class="hidden bg-red-50 border border-red-200 text-red-700 px-4 py-3 rounded-lg">
                请填写标题并选择至少一张图片
            </div>

            <div class="flex gap-3 justify-end">
                <button type="button" onclick="closeUploadModal()" class="px-5 py-2 bg-gray-200 text-gray-700 rounded-lg hover:bg-gray-300 transition-colors">
                    取消
                </button>
                <button type="submit" class="px-5 py-2 bg-primary text-white rounded-lg hover:bg-dark transition-colors">
                    上传
                </button>
            </div>
        </form>
    </div>
</div>

<!-- 图片查看弹窗 -->
<div id="imageModal" class="fixed inset-0 bg-black/80 z-50 hidden items-center justify-center">
    <button type="button" onclick="closeImageModal()" class="absolute top-6 right-6 text-white hover:text-gray-300 transition-colors z-10">
        <i class="ri-close-line text-3xl"></i>
    </button>
    <div class="max-w-none w-full h-full flex flex-col items-center justify-center p-4 overflow-hidden">
        <div id="imageWrapper" class="cursor-move select-none">
            <img id="modalImage" src="" alt="" class="max-h-[85vh] max-w-full object-contain rounded-lg shadow-2xl" ondblclick="closeImageModal()">
        </div>
        <p id="modalTitle" class="text-white mt-4 text-lg font-medium"></p>
        <div class="text-gray-300 mt-2 text-sm space-x-4">
            <span>Ctrl+滚轮缩放</span>
            <span>|</span>
            <span>鼠标拖动移动</span>
            <span>|</span>
            <span>双击/ESC/右上角× 关闭</span>
            <span>|</span>
            <span>当前缩放: <span id="zoomLevel">100%</span></span>
        </div>
    </div>
</div>

<script>
function showUploadModal() {
    const modal = document.getElementById('uploadModal');
    modal.classList.remove('hidden');
    modal.classList.add('flex');
    document.getElementById('uploadError').classList.add('hidden');
    document.getElementById('previewArea').innerHTML = '';
}

function closeUploadModal() {
    const modal = document.getElementById('uploadModal');
    modal.classList.add('hidden');
    modal.classList.remove('flex');
    document.getElementById('uploadForm').reset();
    document.getElementById('previewArea').innerHTML = '';
}

let currentZoom = 1;
let isDragging = false;
let startX, startY;
let offsetX = 0, offsetY = 0;

function showImage(src, title) {
    const modal = document.getElementById('imageModal');
    const img = document.getElementById('modalImage');
    const wrapper = document.getElementById('imageWrapper');
    
    img.src = src;
    img.style.transform = 'scale(1)';
    wrapper.style.transform = 'translate(0px, 0px)';
    currentZoom = 1;
    offsetX = 0;
    offsetY = 0;
    
    document.getElementById('zoomLevel').textContent = '100%';
    document.getElementById('modalTitle').textContent = title;
    modal.classList.remove('hidden');
    modal.classList.add('flex');

    // ESC关闭
    document.addEventListener('keydown', closeByEsc);
}

function closeByEsc(e) {
    if (e.key === 'Escape') {
        closeImageModal();
    }
}

function closeImageModal() {
    const modal = document.getElementById('imageModal');
    modal.classList.add('hidden');
    modal.classList.remove('flex');
    currentZoom = 1;
    document.removeEventListener('keydown', closeByEsc);
}


function applyZoom() {
    const img = document.getElementById('modalImage');
    currentZoom = Math.max(0.5, Math.min(3, currentZoom));
    img.style.transform = `scale(${currentZoom})`;
    img.style.transition = 'transform 0.2s ease';
    document.getElementById('zoomLevel').textContent = Math.round(currentZoom * 100) + '%';
}

// 图片拖动功能
document.getElementById('imageWrapper').addEventListener('mousedown', function(e) {
    if (currentZoom > 1) {
        isDragging = true;
        startX = e.clientX - offsetX;
        startY = e.clientY - offsetY;
        e.preventDefault();
    }
});

document.addEventListener('mousemove', function(e) {
    if (isDragging) {
        const wrapper = document.getElementById('imageWrapper');
        offsetX = e.clientX - startX;
        offsetY = e.clientY - startY;
        wrapper.style.transform = `translate(${offsetX}px, ${offsetY}px)`;
    }
});

document.addEventListener('mouseup', function() {
    isDragging = false;
});

// Ctrl+滚轮缩放
document.getElementById('imageModal').addEventListener('wheel', function(e) {
    e.preventDefault();
    if (e.ctrlKey) {
        if (e.deltaY < 0) {
            currentZoom += 0.1;
        } else {
            currentZoom -= 0.1;
        }
        applyZoom();
    }
});

// 拖拽上传
const dropZone = document.getElementById('dropZone');
const imageInput = document.getElementById('images');
const previewArea = document.getElementById('previewArea');

dropZone.addEventListener('click', () => imageInput.click());

dropZone.addEventListener('dragover', (e) => {
    e.preventDefault();
    dropZone.classList.add('border-primary', 'bg-primary/5');
});

dropZone.addEventListener('dragleave', () => {
    dropZone.classList.remove('border-primary', 'bg-primary/5');
});

dropZone.addEventListener('drop', (e) => {
    e.preventDefault();
    dropZone.classList.remove('border-primary', 'bg-primary/5');
    
    if (e.dataTransfer.files.length) {
        imageInput.files = e.dataTransfer.files;
        showPreview();
    }
});

imageInput.addEventListener('change', showPreview);

function showPreview() {
    previewArea.innerHTML = '';
    const files = imageInput.files;
    
    for (let i = 0; i < files.length; i++) {
        const file = files[i];
        if (file.type.startsWith('image/')) {
            const reader = new FileReader();
            reader.onload = function(e) {
                const div = document.createElement('div');
                div.className = 'relative';
                div.innerHTML = `
                    <img src="${e.target.result}" class="w-full h-24 object-cover rounded-lg">
                    <span class="absolute bottom-1 left-1 bg-black/70 text-white text-xs px-2 py-0.5 rounded">${file.name}</span>
                `;
                previewArea.appendChild(div);
            }
            reader.readAsDataURL(file);
        }
    }
}

// 表单验证
document.getElementById('uploadForm').addEventListener('submit', function(e) {
    const title = document.getElementById('title').value;
    const images = document.getElementById('images').files;
    
    if (!title || images.length === 0) {
        document.getElementById('uploadError').classList.remove('hidden');
        e.preventDefault();
        return false;
    }
    
    return true;
});

// 点击背景关闭
document.getElementById('uploadModal').addEventListener('click', function(e) {
    if (e.target === this) {
        closeUploadModal();
    }
});

// 缩略图轮播功能
let thumbCarouselIndex = {};

function prevThumb(btn) {
    const container = btn.closest('.carousel-thumb');
    const images = JSON.parse(container.dataset.images);
    const groupId = container.closest('tr').rowIndex;
    
    if(!thumbCarouselIndex[groupId]) thumbCarouselIndex[groupId] = 0;
    
    thumbCarouselIndex[groupId] = (thumbCarouselIndex[groupId] - 1 + images.length) % images.length;
    updateThumb(container, images, groupId);
}

function nextThumb(btn) {
    const container = btn.closest('.carousel-thumb');
    const images = JSON.parse(container.dataset.images);
    const groupId = container.closest('tr').rowIndex;
    
    if(!thumbCarouselIndex[groupId]) thumbCarouselIndex[groupId] = 0;
    
    thumbCarouselIndex[groupId] = (thumbCarouselIndex[groupId] + 1) % images.length;
    updateThumb(container, images, groupId);
}

function updateThumb(container, images, groupId) {
    const img = container.querySelector('.carousel-image');
    const counter = container.querySelector('.absolute.bottom-1');
    
    img.src = images[thumbCarouselIndex[groupId]].path;
    if(counter) {
        counter.textContent = `${thumbCarouselIndex[groupId] + 1}/${images.length}`;
    }
}

// 分组图片查看模态窗口
let groupImages = [];
let currentGroupIndex = 0;

function showImageGroup(element) {
    let images;
    if(element.dataset.images) {
        images = JSON.parse(element.dataset.images);
    } else {
        const container = element.closest('.carousel-thumb');
        images = JSON.parse(container.dataset.images);
        const groupId = container.closest('tr').rowIndex;
        currentGroupIndex = thumbCarouselIndex[groupId] || 0;
    }
    
    groupImages = images;
    
    // 添加轮播导航按钮
    const modal = document.getElementById('imageModal');
    const existingNav = modal.querySelector('.carousel-nav');
    if(existingNav) existingNav.remove();
    
    if(images.length > 1) {
        const navHtml = `
        <div class="carousel-nav absolute top-1/2 -translate-y-1/2 left-4 right-4 flex justify-between pointer-events-none">
            <button class="bg-white/20 hover:bg-white/30 text-white w-12 h-12 rounded-full text-2xl pointer-events-auto transition-colors" onclick="prevGroupImage()">
                <i class="ri-arrow-left-s-line"></i>
            </button>
            <button class="bg-white/20 hover:bg-white/30 text-white w-12 h-12 rounded-full text-2xl pointer-events-auto transition-colors" onclick="nextGroupImage()">
                <i class="ri-arrow-right-s-line"></i>
            </button>
        </div>
        <div class="absolute bottom-20 text-white text-lg">
            ${currentGroupIndex + 1} / ${images.length}
        </div>
        `;
        document.getElementById('imageWrapper').insertAdjacentHTML('afterend', navHtml);
        
        // 键盘左右键导航
        document.addEventListener('keydown', groupImageNav);
    }
    
    showImage(images[currentGroupIndex].path, images[currentGroupIndex].title);
}

function prevGroupImage() {
    currentGroupIndex = (currentGroupIndex - 1 + groupImages.length) % groupImages.length;
    updateGroupImage();
}

function nextGroupImage() {
    currentGroupIndex = (currentGroupIndex + 1) % groupImages.length;
    updateGroupImage();
}

function updateGroupImage() {
    const img = document.getElementById('modalImage');
    const title = document.getElementById('modalTitle');
    const counter = document.querySelector('#imageModal .absolute.bottom-20');
    
    img.src = groupImages[currentGroupIndex].path;
    title.textContent = groupImages[currentGroupIndex].title;
    if(counter) {
        counter.textContent = `${currentGroupIndex + 1} / ${groupImages.length}`;
    }
    
    // 重置缩放位置
    currentZoom = 1;
    offsetX = 0;
    offsetY = 0;
    applyZoom();
    document.getElementById('imageWrapper').style.transform = 'translate(0, 0)';
}

function groupImageNav(e) {
    if(e.key === 'ArrowLeft') prevGroupImage();
    if(e.key === 'ArrowRight') nextGroupImage();
}

// 重写关闭事件清理键盘监听
const originalCloseImageModal = closeImageModal;
closeImageModal = function() {
    originalCloseImageModal();
    document.removeEventListener('keydown', groupImageNav);
}

</script>

<?php echo $__env->make('partials.delete-confirm', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.elevator', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH D:\else\order\ElevatorManagement\ElevatorManagement\resources\views/repair/orders.blade.php ENDPATH**/ ?>