@extends('layouts.elevator')

@section('title', '增加视频')
@section('page-title', '增加视频')

@section('content')
<div class="card">
    <div class="flex justify-between items-center mb-6">
        <h3 class="text-xl font-semibold text-gray-800">视频批量上传</h3>
    </div>

    <div id="successMessage" class="hidden bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-4"></div>
    <div id="errorMessage" class="hidden bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-4"></div>

    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
        <div>
            <label class="block text-sm font-medium text-gray-700 mb-2">视频文件夹名称</label>
            <input type="text" id="videoGroup" required class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500" placeholder="请输入视频文件夹名称">
            <p class="text-sm text-gray-500 mt-1">上传路径格式: videos/[文件夹名]/*.mp4</p>
        </div>
        
        <div>
            <label class="block text-sm font-medium text-gray-700 mb-2">视频类型</label>
            <select id="videoType" required class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
                <option value="">请选择视频类型</option>
                @foreach($videoTypes as $type)
                    <option value="{{ $type->type }}">{{ $type->type }}</option>
                @endforeach
            </select>
        </div>
    </div>

    <div class="mb-6">
        <label class="block text-sm font-medium text-gray-700 mb-2">视频描述</label>
        <textarea id="description" rows="3" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500" placeholder="请输入视频描述"></textarea>
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
        <label class="block text-sm font-medium text-gray-700 mb-2">选择视频封面图片</label>
        <div class="file-input-wrapper">
            <div id="coverBtn" class="file-input-btn">
                <i class="ri-image-add-line text-3xl mb-2 block"></i>
                <span id="coverText">点击选择封面图片</span>
            </div>
            <input type="file" id="cover" accept="image/*" required onchange="updateCoverStatus(this)">
        </div>
        <p class="text-sm text-gray-500 mt-1">封面图片将保存到视频文件夹中</p>
    </div>

    <div class="mb-6">
        <label class="block text-sm font-medium text-gray-700 mb-2">选择视频文件（支持批量选择）</label>
        <div class="file-input-wrapper">
            <div id="videosBtn" class="file-input-btn">
                <i class="ri-video-add-line text-3xl mb-2 block"></i>
                <span id="videosText">点击选择多个MP4视频文件</span>
            </div>
            <input type="file" id="videos" multiple accept="video/mp4" required onchange="updateVideosStatus(this)">
        </div>
        <p class="text-sm text-gray-500 mt-1">支持同时选择多个MP4视频文件，系统将逐个上传处理</p>
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
    
    function updateVideosStatus(input) {
        const btn = document.getElementById('videosBtn');
        const text = document.getElementById('videosText');
        if (input.files.length > 0) {
            btn.classList.add('file-selected');
            text.textContent = '✓ 已选择 ' + input.files.length + ' 个视频文件';
        } else {
            btn.classList.remove('file-selected');
            text.textContent = '点击选择多个MP4视频文件';
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
    const videoGroup = document.getElementById('videoGroup').value;
    const videoType = document.getElementById('videoType').value;
    const description = document.getElementById('description').value;
    const coverFile = document.getElementById('cover').files[0];
    const videoFiles = document.getElementById('videos').files;

    if (!videoGroup || !videoType || !coverFile || videoFiles.length === 0) {
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
        coverFormData.append('videoGroup', videoGroup);
        coverFormData.append('cover', coverFile);
        coverFormData.append('_token', '{{ csrf_token() }}');

        const coverResponse = await fetch('{{ route('video.upload.cover') }}', {
            method: 'POST',
            body: coverFormData
        });

        if (!coverResponse.ok) {
            throw new Error('封面上传失败');
        }

        const coverResult = await coverResponse.json();
        const coverPath = coverResult.path;
        addLog('✓ 封面上传完成: ' + coverPath);

        // 逐个上传视频
        let successCount = 0;
        const totalFiles = videoFiles.length;

        for (let i = 0; i < totalFiles; i++) {
            const videoFile = videoFiles[i];
            const progress = Math.round(((i + 1) / totalFiles) * 100);
            
            document.getElementById('progressBar').style.width = progress + '%';
            document.getElementById('progressText').textContent = `正在上传 ${i + 1}/${totalFiles}: ${videoFile.name}`;
            addLog(`正在上传 (${i + 1}/${totalFiles}): ${videoFile.name}`);

            const videoFormData = new FormData();
            videoFormData.append('videoGroup', videoGroup);
            videoFormData.append('videoType', videoType);
            videoFormData.append('description', description);
            videoFormData.append('coverPath', coverPath);
            videoFormData.append('video', videoFile);
            videoFormData.append('_token', '{{ csrf_token() }}');

            const videoResponse = await fetch('{{ route('video.upload.single') }}', {
                method: 'POST',
                body: videoFormData
            });

            if (videoResponse.ok) {
                successCount++;
                addLog(`✓ ${videoFile.name} 上传成功`);
            } else {
                addLog(`✗ ${videoFile.name} 上传失败`);
            }
        }

        document.getElementById('progressBar').style.width = '100%';
        document.getElementById('progressText').textContent = `上传完成！成功 ${successCount}/${totalFiles} 个文件`;
        showSuccess(`视频上传完成！共成功上传 ${successCount} 个视频文件`);

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