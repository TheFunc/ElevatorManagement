@extends('layouts.elevator')

@section('title', '视频组详情')
@section('page-title', '视频组详情 - ' . $group)

@section('content')
<div class="card">
    <div class="flex justify-between items-center mb-6">
        <div>
            <h3 class="text-xl font-semibold text-gray-800">视频组: {{ $group }}</h3>
            <p class="text-gray-500 mt-1">共 {{ $videos->count() }} 个视频文件</p>
        </div>
        <a href="{{ route('video.preview') }}" class="bg-gray-500 hover:bg-gray-600 text-white px-4 py-2 rounded-lg">
            <i class="ri-arrow-left-line"></i> 返回列表
        </a>
    </div>

    <div class="overflow-x-auto">
        <table class="w-full">
            <thead>
                <tr class="bg-gray-50 border-b">
                    <th class="text-left py-3 px-4 font-medium text-gray-600">封面</th>
                    <th class="text-left py-3 px-4 font-medium text-gray-600">视频文件名</th>
                    <th class="text-left py-3 px-4 font-medium text-gray-600">视频类型</th>
                    <th class="text-left py-3 px-4 font-medium text-gray-600">上传时间</th>
                    <th class="text-left py-3 px-4 font-medium text-gray-600">操作</th>
                </tr>
            </thead>
            <tbody>
                @foreach($videos as $video)
                <tr class="border-b hover:bg-gray-50">
                    <td class="py-3 px-4">
                        <img src="{{ asset($video->coverPath) }}" class="w-24 h-14 object-cover rounded-lg border">
                    </td>
                    <td class="py-3 px-4 font-medium">
                        {{ basename($video->videoPath) }}
                    </td>
                    <td class="py-3 px-4">
                        <span class="bg-blue-100 text-blue-700 px-2 py-1 rounded text-sm">{{ $video->videoType }}</span>
                    </td>
                    <td class="py-3 px-4 text-gray-500">{{ $video->created_at->format('Y-m-d H:i') }}</td>
                    <td class="py-3 px-4">
                        <div class="flex gap-2">
                            <button onclick="playVideo('{{ asset($video->videoPath) }}', '{{ basename($video->videoPath) }}')" class="bg-green-500 hover:bg-green-600 text-white px-3 py-1 rounded text-sm">
                                <i class="ri-play-circle-line"></i> 播放
                            </button>
                            <button onclick="showDeleteModal('{{ route('video.delete.single', $video->id) }}')" class="bg-red-500 hover:bg-red-600 text-white px-3 py-1 rounded text-sm">
                                <i class="ri-delete-bin-line"></i> 删除
                            </button>
                        </div>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>

<!-- 视频播放弹窗 -->
<div id="videoModal" class="fixed inset-0 bg-black bg-opacity-75 hidden items-center justify-center z-50">
    <div class="bg-white rounded-lg w-full max-w-4xl mx-4 p-6">
        <div class="flex justify-between items-center mb-4">
            <h3 id="videoTitle" class="text-lg font-semibold text-gray-800">视频播放</h3>
            <button onclick="closeVideoModal()" class="text-gray-400 hover:text-gray-600">
                <i class="ri-close-line text-xl"></i>
            </button>
        </div>
        <div class="bg-black rounded-lg overflow-hidden">
            <video id="videoPlayer" class="w-full" controls>
                <source src="" type="video/mp4">
                您的浏览器不支持视频播放
            </video>
        </div>
    </div>
</div>

<script>
function playVideo(videoUrl, videoName) {
    document.getElementById('videoTitle').textContent = videoName;
    document.getElementById('videoPlayer').src = videoUrl;
    document.getElementById('videoModal').classList.remove('hidden');
    document.getElementById('videoModal').classList.add('flex');
    document.getElementById('videoPlayer').play();
}

function closeVideoModal() {
    document.getElementById('videoPlayer').pause();
    document.getElementById('videoPlayer').src = '';
    document.getElementById('videoModal').classList.add('hidden');
    document.getElementById('videoModal').classList.remove('flex');
}
</script>
@endsection
