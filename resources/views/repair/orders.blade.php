@extends('layouts.elevator')

@section('title', '电梯单管理')
@section('page-title', '电梯单上传管理')

@section('content')
<div class="card">
    <div class="flex justify-between items-center mb-6">
        <h3 class="text-xl font-semibold text-gray-800">电梯单列表</h3>
        <button type="button" onclick="showUploadModal()" class="px-4 py-2 bg-primary text-white rounded-lg hover:bg-dark transition-colors">
            <i class="ri-add-line mr-1"></i>上传电梯单
        </button>
    </div>

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
                @foreach($orders as $order)
                <tr class="border-b border-gray-100 hover:bg-gray-50">
                    <td class="px-4 py-3">
                        <img src="{{ asset($order->path) }}" alt="{{ $order->title }}" class="w-20 h-20 object-cover rounded-lg cursor-pointer hover:scale-105 transition-transform" onclick="showImage('{{ asset($order->path) }}', '{{ $order->title }}')">
                    </td>
                    <td class="px-4 py-3 text-gray-800 font-medium">{{ $order->title }}</td>
                    <td class="px-4 py-3 text-gray-600">{{ $order->description }}</td>
                    <td class="px-4 py-3 text-gray-600">{{ \Carbon\Carbon::parse($order->time)->format('Y-m-d H:i') }}</td>
                    <td class="px-4 py-3">
                        <div class="flex gap-2">
                            <button onclick="showImage('{{ asset($order->path) }}', '{{ $order->title }}')" class="px-3 py-1 bg-primary/10 text-primary rounded-lg hover:bg-primary/20 transition-colors text-sm font-medium">
                                <i class="ri-eye-line mr-1"></i>查看
                            </button>
                            <a href="{{ route('repair.download', $order->id) }}" class="px-3 py-1 bg-green-100 text-green-700 rounded-lg hover:bg-green-200 transition-colors text-sm font-medium">
                                <i class="ri-download-line mr-1"></i>下载
                            </a>
                            <form action="{{ route('repair.delete', $order->id) }}" method="POST" class="inline" onsubmit="return confirm('确定要删除此电梯单吗？')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="px-3 py-1 bg-red-100 text-red-700 rounded-lg hover:bg-red-200 transition-colors text-sm font-medium">
                                    <i class="ri-delete-bin-line mr-1"></i>删除
                                </button>
                            </form>
                        </div>
                    </td>
                </tr>
                @endforeach

                @if($orders->isEmpty())
                <tr>
                    <td colspan="5" class="px-4 py-8 text-center text-gray-500">
                        暂无电梯单数据，请点击上方按钮上传
                    </td>
                </tr>
                @endif
            </tbody>
        </table>
    </div>
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

        <form action="{{ route('repair.upload') }}" method="POST" enctype="multipart/form-data" class="space-y-4" id="uploadForm">
            @csrf

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">标题 <span class="text-red-500">*</span></label>
                <input type="text" name="title" id="title" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary focus:border-primary outline-none" placeholder="请输入电梯单标题">
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">描述</label>
                <textarea name="description" id="description" rows="2" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary focus:border-primary outline-none" placeholder="可选描述信息"></textarea>
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
</script>
@endsection