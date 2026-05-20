@extends('layouts.elevator')

@section('title', '增加图文')
@section('page-title', '增加图文')

@section('content')
<div class="card">
    <div class="flex justify-between items-center mb-6">
        <h3 class="text-xl font-semibold text-gray-800">图文上传</h3>
    </div>

    <div id="successMessage" class="hidden bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-4"></div>
    <div id="errorMessage" class="hidden bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-4"></div>

    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
        <div>
            <label class="block text-sm font-medium text-gray-700 mb-2">图片文件夹名称</label>
            <input type="text" id="imageGroup" required class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500" placeholder="请输入图片文件夹名称">
            <p class="text-sm text-gray-500 mt-1">上传路径格式: images/[文件夹名]/*.jpg</p>
        </div>
        
        <div>
            <label class="block text-sm font-medium text-gray-700 mb-2">图片类型</label>
            <select id="imageType" required class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
                <option value="">请选择图片类型</option>
                @foreach($imageTypes as $type)
                    <option value="{{ $type->type }}">{{ $type->type }}</option>
                @endforeach
            </select>
        </div>
    </div>

    <div class="mb-6">
        <label class="block text-sm font-medium text-gray-700 mb-2">图片描述</label>
        <textarea id="description" rows="3" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500" placeholder="请输入图片描述"></textarea>
    </div>

    <style>
    .file-input-wrapper {
        position: relative;
        overflow: hidden;
        display: inline-block;
        width: 100%;
    }
    .file-input-wrapper input[type=file] {
        position: absolute;
        left: 0;
        top: 0;
        opacity: 0;
        width: 100%;
        height: 100%;
        cursor: pointer;
    }
    .file-input-btn {
        border: 2px dashed #c7d2fe;
        background-color: #eef2ff;
        color: #4f46e5;
        padding: 2rem 1rem;
        text-align: center;
        border-radius: 0.75rem;
        transition: all 0.3s;
        width: 100%;
    }
    .file-input-btn:hover {
        border-color: #818cf8;
        background-color: #e0e7ff;
    }
    .file-selected {
        background-color: #dcfce7 !important;
        border-color: #4ade80 !important;
        color: #166534 !important;
    }
    </style>

    <div class="mb-6">
        <label class="block text-sm font-medium text-gray-700 mb-2">选择封面图片</label>
        <div class="file-input-wrapper">
            <div id="coverBtn" class="file-input-btn">
                <i class="ri-image-add-line text-3xl mb-2 block"></i>
                <span id="coverText">点击选择封面图片</span>
            </div>
            <input type="file" id="cover" accept="image/*" required onchange="updateCoverStatus(this)">
        </div>
        <p class="text-sm text-gray-500 mt-1">封面图片将保存到图片文件夹中</p>
    </div>

    <div class="mb-6">
        <label class="block text-sm font-medium text-gray-700 mb-2">选择图片文件</label>
        <div class="file-input-wrapper">
            <div id="imageBtn" class="file-input-btn">
                <i class="ri-image-line text-3xl mb-2 block"></i>
                <span id="imageText">点击选择图片文件</span>
            </div>
            <input type="file" id="image" accept="image/jpeg,image/png,image/jpg,image/gif" required onchange="updateImageStatus(this)">
        </div>
        <p class="text-sm text-gray-500 mt-1">支持 JPG、PNG、GIF 格式的图片</p>
    </div>

    <script>
    function updateCoverStatus(input) {
        const btn = document.getElementById('coverBtn');
        const text = document.getElementById('coverText');
        if (input.files.length > 0) {
            btn.classList.add('file-selected');
            text.textContent = '✓ 已选择: ' + input.files[0].name;
        } else {
            btn.classList.remove('file-selected');
            text.textContent = '点击选择封面图片';
        }
    }
    
    function updateImageStatus(input) {
        const btn = document.getElementById('imageBtn');
        const text = document.getElementById('imageText');
        if (input.files.length > 0) {
            btn.classList.add('file-selected');
            text.textContent = '✓ 已选择: ' + input.files[0].name;
        } else {
            btn.classList.remove('file-selected');
            text.textContent = '点击选择图片文件';
        }
    }
    </script>

    <div id="uploadProgress" class="hidden mb-6">
        <div class="bg-gray-200 rounded-full h-4 overflow-hidden">
            <div id="progressBar" class="bg-blue-500 h-full transition-all duration-300" style="width: 0%"></div>
        </div>
        <p id="progressText" class="text-sm text-gray-600 mt-2">准备上传...</p>
    </div>

    <div id="uploadLog" class="hidden mb-6 max-h-48 overflow-y-auto bg-gray-50 p-4 rounded-lg border border-gray-200"></div>

    <div class="flex justify-end">
        <button type="button" id="uploadBtn" onclick="startUpload()" class="bg-blue-500 hover:bg-blue-600 text-white px-6 py-3 rounded-lg flex items-center gap-2">
            <i class="ri-upload-cloud-line"></i>
            开始上传
        </button>
    </div>
</div>

<script>
async function startUpload() {
    const imageGroup = document.getElementById('imageGroup').value;
    const imageType = document.getElementById('imageType').value;
    const description = document.getElementById('description').value;
    const coverFile = document.getElementById('cover').files[0];
    const imageFile = document.getElementById('image').files[0];

    if (!imageGroup || !imageType || !coverFile || !imageFile) {
        showError('请填写所有必填项并选择文件');
        return;
    }

    document.getElementById('successMessage').classList.add('hidden');
    document.getElementById('errorMessage').classList.add('hidden');
    document.getElementById('uploadProgress').classList.remove('hidden');
    document.getElementById('uploadLog').classList.remove('hidden');
    document.getElementById('uploadBtn').disabled = true;
    document.getElementById('uploadBtn').innerHTML = '<i class="ri-loader-4-line animate-spin"></i> 上传中...';
    document.getElementById('uploadLog').innerHTML = '';

    try {
        // 先上传封面
        addLog('正在上传封面图片...');
        const coverFormData = new FormData();
        coverFormData.append('imageGroup', imageGroup);
        coverFormData.append('cover', coverFile);
        coverFormData.append('_token', '{{ csrf_token() }}');

        const coverResponse = await fetch('{{ route('image-text.upload.cover') }}', {
            method: 'POST',
            body: coverFormData
        });

        if (!coverResponse.ok) {
            let errorMsg = '未知错误';
            try {
                const errorData = await coverResponse.json();
                errorMsg = errorData.message || errorData.error || JSON.stringify(errorData);
            } catch (e) {
                errorMsg = `HTTP ${coverResponse.status} ${coverResponse.statusText}`;
            }
            throw new Error('封面上传失败: ' + errorMsg);
        }

        const coverResult = await coverResponse.json();
        const coverPath = coverResult.path;
        const safeGroupName = coverResult.groupName || imageGroup;  // 使用后端返回的清理后的组名
        addLog('✓ 封面上传完成: ' + coverPath);

        // 上传图片
        document.getElementById('progressBar').style.width = '50%';
        document.getElementById('progressText').textContent = `正在上传图片: ${imageFile.name}`;
        addLog(`正在上传图片: ${imageFile.name}`);

        const imageFormData = new FormData();
        imageFormData.append('imageGroup', safeGroupName);  // 使用清理后的组名
        imageFormData.append('imageType', imageType);
        imageFormData.append('description', description);
        imageFormData.append('coverPath', coverPath);
        imageFormData.append('image', imageFile);
        imageFormData.append('_token', '{{ csrf_token() }}');

        const imageResponse = await fetch('{{ route('image-text.upload.single') }}', {
            method: 'POST',
            body: imageFormData
        });

        if (imageResponse.ok) {
            document.getElementById('progressBar').style.width = '100%';
            document.getElementById('progressText').textContent = '上传完成！';
            addLog(`✓ ${imageFile.name} 上传成功`);
            showSuccess('图片上传成功！');
        } else {
            let errorMsg = '未知错误';
            try {
                const errorData = await imageResponse.json();
                errorMsg = errorData.message || errorData.error || JSON.stringify(errorData);
            } catch (e) {
                errorMsg = `HTTP ${imageResponse.status} ${imageResponse.statusText}`;
            }
            throw new Error('图片上传失败: ' + errorMsg);
        }

    } catch (error) {
        showError('上传出错: ' + error.message);
        addLog('✗ 上传出错: ' + error.message);
    }

    document.getElementById('uploadBtn').disabled = false;
    document.getElementById('uploadBtn').innerHTML = '<i class="ri-upload-cloud-line"></i> 开始上传';
}

function addLog(text) {
    const logDiv = document.getElementById('uploadLog');
    logDiv.innerHTML += '<p class="text-sm text-gray-600 py-1">' + text + '</p>';
    logDiv.scrollTop = logDiv.scrollHeight;
}

function showSuccess(message) {
    const msgDiv = document.getElementById('successMessage');
    msgDiv.textContent = message;
    msgDiv.classList.remove('hidden');
}

function showError(message) {
    const msgDiv = document.getElementById('errorMessage');
    msgDiv.textContent = message;
    msgDiv.classList.remove('hidden');
}
</script>
@endsection
