@extends('layouts.elevator')

@section('title', '增加视频')
@section('page-title', '增加视频')

@section('content')
<div class="card">
    <div class="flex justify-between items-center mb-6">
        <h3 class="text-xl font-semibold text-gray-800">批量上传视频</h3>
    </div>
    
    <form action="{{ route('video.store') }}" method="POST" enctype="multipart/form-data" class="space-y-6">
        <input type="hidden" id="debugFlag" value="1">
        <script>
        document.addEventListener('DOMContentLoaded', function() {
            console.log('✅ 页面加载完成');
            
            const videoType = document.querySelector('select[name="videoType"]');
            const videoGroup = document.querySelector('input[name="videoGroup"]');
            const cover = document.querySelector('input[name="cover"]');
            const videos = document.querySelector('input[name="videos[]"]');
            
            console.log('🔍 DOM元素检查:');
            console.log('videoType:', videoType);
            console.log('videoGroup:', videoGroup);
            console.log('cover:', cover);
            console.log('videos:', videos);
        });
        </script>
        @csrf
        <!-- 前端表单验证 -->
        <script>
        function validateForm(e) {
            console.log('🚀 表单提交事件触发');
            const videoType = document.querySelector('select[name="videoType"]').value;
            const videoGroup = document.querySelector('input[name="videoGroup"]').value;
            const coverInput = document.querySelector('input[name="cover"]');
            const videosInput = document.querySelector('input[name="videos[]"]');
            
            const cover = coverInput.files.length;
            const videos = videosInput.files.length;
            
            let errors = [];
            
            if (!videoType) errors.push('请选择视频类型');
            if (!videoGroup) errors.push('请输入视频文件夹名');
            if (cover === 0) errors.push('请上传视频封面');
            if (videos === 0) errors.push('请选择至少一个视频文件');
            
            // 新增上传大小限制检查
            const MAX_SINGLE_SIZE = 500 * 1024 * 1024; // 单个文件最大500MB
            const MAX_TOTAL_SIZE = 2 * 1024 * 1024 * 1024; // 总大小最大2GB
            
            let totalSize = 0;
            
            // 检查封面大小
            if (cover > 0) {
                totalSize += coverInput.files[0].size;
                if (coverInput.files[0].size > 20 * 1024 * 1024) {
                    errors.push('封面图片不能超过20MB');
                }
            }
            
            // 检查每个视频大小
            for (let i = 0; i < videos; i++) {
                const file = videosInput.files[i];
                totalSize += file.size;
                
                if (file.size > MAX_SINGLE_SIZE) {
                    errors.push(`视频文件 "${file.name}" 超过500MB限制`);
                }
            }
            
            // 检查总大小
            if (totalSize > MAX_TOTAL_SIZE) {
                errors.push(`所有文件总大小超过2GB限制，请减少选择文件数量`);
            }
            
            if (errors.length > 0) {
                e.preventDefault();
                alert('⚠️ 请完善以下内容:\n\n' + errors.join('\n'));
                return false;
            }
            
            // 防止重复提交
            const submitBtn = document.querySelector('button[type="submit"]');
            submitBtn.disabled = true;
            submitBtn.innerHTML = '<i class="ri-loader-4-line animate-spin mr-2"></i> 正在上传中...';
            
            return true;
        }
        
        // 绑定到form的submit事件 而不是按钮的click事件
        document.addEventListener('DOMContentLoaded', function() {
            const form = document.querySelector('form');
            form.addEventListener('submit', validateForm);
        });
        </script>
        
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">视频类型 <span class="text-red-500">*</span></label>
                <select name="videoType" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary focus:border-primary outline-none">
                    <option value="">请选择视频类型</option>
                    @foreach($videoTypes as $type)
                    <option value="{{ $type->type }}">{{ $type->type }}</option>
                    @endforeach
                </select>
            </div>
            
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">视频文件夹名 <span class="text-red-500">*</span></label>
                <input type="text" name="videoGroup" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary focus:border-primary outline-none" placeholder="例如: 202604_安全培训">
            </div>
        </div>
        
        <div>
            <label class="block text-sm font-medium text-gray-700 mb-2">视频描述</label>
            <textarea name="description" rows="3" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary focus:border-primary outline-none" placeholder="输入此批视频的公共描述信息"></textarea>
        </div>
        
        <div>
            <label class="block text-sm font-medium text-gray-700 mb-2">视频封面图片 <span class="text-red-500">*</span></label>
            <input type="file" name="cover" accept="image/*" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary focus:border-primary outline-none">
        </div>
        
        <div>
            <label class="block text-sm font-medium text-gray-700 mb-2">选择视频文件 <span class="text-red-500">*</span></label>
            <input type="file" name="videos[]" accept="video/mp4" multiple class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary focus:border-primary outline-none">
            <p class="text-sm text-gray-500 mt-1">支持批量选择多个 mp4 文件</p>
        </div>
        
        @if ($errors->any())
        <div class="bg-red-50 border border-red-200 text-red-700 px-4 py-3 rounded-lg">
            <ul class="list-disc pl-5">
                @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
        @endif
        
        <div class="flex justify-end">
            <button type="submit" class="px-6 py-3 bg-primary text-white rounded-lg hover:bg-dark transition-colors">
                <i class="ri-upload-cloud-line mr-2"></i> 开始批量上传
            </button>
        </div>
    </form>
</div>
@endsection