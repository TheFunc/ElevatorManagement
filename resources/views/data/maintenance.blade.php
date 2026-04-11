@extends('layouts.elevator')

@section('title', '维保资料上传')
@section('page-title', '维保资料上传')

@section('content')
<div class="card">
    <h3 class="text-xl font-semibold text-gray-800 mb-4">维保资料上传</h3>
    <p class="text-gray-600 mb-6">上传电梯维保相关资料</p>

    @if(session('success'))
        <div class="bg-green-50 border border-green-200 text-green-700 px-4 py-3 rounded-lg mb-6">
            {{ session('success') }}
        </div>
    @endif

    @if(session('error'))
        <div class="bg-red-50 border border-red-200 text-red-700 px-4 py-3 rounded-lg mb-6">
            {{ session('error') }}
        </div>
    @endif

    @if ($errors->any())
        <div class="bg-red-50 border border-red-200 text-red-700 px-4 py-3 rounded-lg mb-6">
            <ul class="list-disc pl-5">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form action="{{ route('file.upload') }}" method="POST" enctype="multipart/form-data" class="space-y-6">
        @csrf
        <input type="hidden" name="type" value="maintenance">
        
        <div>
            <label class="block text-sm font-medium text-gray-700 mb-2">文件标题</label>
            <input type="text" name="title" value="{{ old('title') }}" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary focus:border-primary outline-none">
        </div>

        <div>
            <label class="block text-sm font-medium text-gray-700 mb-2">文件描述</label>
            <textarea name="description" rows="3" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary focus:border-primary outline-none">{{ old('description') }}</textarea>
        </div>

        <div>
            <label class="block text-sm font-medium text-gray-700 mb-2">选择文件</label>
            <div class="border-2 border-dashed border-gray-300 rounded-lg p-8 text-center hover:border-primary transition-colors">
                <input type="file" name="file" id="file" class="hidden" accept=".doc,.docx,.pdf,.xls,.xlsx,.ppt,.pptx,.txt">
                <label for="file" class="cursor-pointer">
                    <i class="ri-upload-cloud-2-line text-4xl text-gray-400 mb-2"></i>
                    <p class="text-gray-600">点击选择文件或拖拽文件到此处</p>
                    <p class="text-sm text-gray-500 mt-1">支持 Word、PDF、Excel、PPT、TXT 格式，最大20MB</p>
                </label>
            </div>
            <div id="file-name" class="mt-2 text-sm text-gray-600"></div>
        </div>

        <div>
            <button type="submit" class="px-6 py-3 bg-primary text-white rounded-lg hover:bg-dark transition-colors font-medium">
                <i class="ri-upload-line mr-2"></i>上传文件
            </button>
        </div>
    </form>
</div>

<script>
document.getElementById('file').addEventListener('change', function(e) {
    if (e.target.files.length > 0) {
        document.getElementById('file-name').textContent = '已选择文件: ' + e.target.files[0].name;
    }
});
</script>
@endsection
