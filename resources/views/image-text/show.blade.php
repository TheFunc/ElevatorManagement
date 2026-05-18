@extends('layouts.elevator')

@section('title', $imageText->title)

@push('styles')
<style>
    .show-container {
        max-width: 1200px;
        margin: 40px auto;
        padding: 20px;
        background: white;
        box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
        border-radius: 8px;
    }
    
    .show-header {
        margin-bottom: 30px;
        padding-bottom: 20px;
        border-bottom: 2px solid #e5e7eb;
    }
    
    .show-title {
        font-size: 28px;
        font-weight: bold;
        color: #1f2937;
        margin-bottom: 10px;
    }
    
    .show-description {
        font-size: 16px;
        color: #6b7280;
        line-height: 1.6;
    }
    
    .show-meta {
        display: flex;
        gap: 20px;
        margin-top: 10px;
        font-size: 14px;
        color: #9ca3af;
    }
    
    .canvas-display {
        position: relative;
        width: 100%;
        overflow: hidden;
        background: #ffffff;
    }
    
    .canvas-element {
        position: absolute;
        box-sizing: border-box;
    }
    
    .canvas-element img {
        width: 100%;
        height: 100%;
        object-fit: contain;
    }
    
    .canvas-element.text-element {
        word-wrap: break-word;
        overflow: hidden;
    }
</style>
@endpush

@section('content')
<div class="show-container">
    <!-- 头部信息 -->
    <div class="show-header">
        <h1 class="show-title">{{ $imageText->title }}</h1>
        @if($imageText->description)
        <p class="show-description">{{ $imageText->description }}</p>
        @endif
        <div class="show-meta">
            <span>📅 {{ $imageText->created_at->format('Y-m-d H:i') }}</span>
            @if($imageText->creator)
            <span>👤 {{ $imageText->creator->name }}</span>
            @endif
            @if($imageText->is_template)
            <span class="px-2 py-1 bg-purple-100 text-purple-700 rounded text-xs">模板</span>
            @endif
        </div>
    </div>

    <!-- 画布展示区域 -->
    <div class="canvas-display" 
         id="canvasDisplay"
         style="height: {{ ($imageText->layout_data['canvasHeight'] ?? 800) }}px;">
        
        @if($imageText->layout_data && isset($imageText->layout_data['elements']))
            @foreach($imageText->layout_data['elements'] as $element)
                @if($element['type'] === 'i-text' || $element['type'] === 'text')
                    <!-- 文字元素 -->
                    <div class="canvas-element text-element"
                         style="left: {{ $element['left'] }}px;
                                top: {{ $element['top'] }}px;
                                width: {{ $element['width'] ?? 200 }}px;
                                height: {{ $element['height'] ?? 50 }}px;
                                font-size: {{ $element['fontSize'] ?? 16 }}px;
                                color: {{ $element['fill'] ?? '#000000' }};
                                font-family: {{ $element['fontFamily'] ?? 'Arial' }};
                                transform: rotate({{ $element['angle'] ?? 0 }}deg);
                                opacity: {{ $element['opacity'] ?? 1 }};">
                        {{ $element['text'] ?? '' }}
                    </div>
                @elseif($element['type'] === 'image')
                    <!-- 图片元素 -->
                    <div class="canvas-element"
                         style="left: {{ $element['left'] }}px;
                                top: {{ $element['top'] }}px;
                                width: {{ $element['width'] ?? 200 }}px;
                                height: {{ $element['height'] ?? 200 }}px;
                                transform: rotate({{ $element['angle'] ?? 0 }}deg);
                                opacity: {{ $element['opacity'] ?? 1 }};">
                        <img src="{{ $element['src'] ?? '' }}" alt="图文元素">
                    </div>
                @endif
            @endforeach
        @endif
    </div>

    <!-- 操作按钮 -->
    @if(Auth::check() && Auth::user()->role == 1)
    <div class="mt-6 flex gap-4 justify-center">
        <a href="{{ route('image-text.edit', $imageText->id) }}" 
           class="px-6 py-2 bg-green-600 text-white rounded hover:bg-green-700 transition-colors">
            ✏️ 编辑此图文
        </a>
        <button onclick="window.print()" 
                class="px-6 py-2 bg-blue-600 text-white rounded hover:bg-blue-700 transition-colors">
            🖨️ 打印/导出PDF
        </button>
    </div>
    @endif
</div>

<!-- API数据获取示例（供前端开发者参考） -->
<script>
// 前端可以通过以下API获取图文数据
const apiUrl = '{{ route("image-text.api", $imageText->id) }}';

// 示例：使用 fetch 获取数据
/*
fetch(apiUrl)
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            console.log('图文数据:', data.data);
            // 根据 layout_data 渲染内容
            renderCanvas(data.data.layout_data);
        }
    })
    .catch(error => console.error('获取数据失败:', error));
*/

// 渲染函数示例
function renderCanvas(layoutData) {
    const container = document.getElementById('canvasDisplay');
    container.innerHTML = '';
    
    if (!layoutData || !layoutData.elements) return;
    
    layoutData.elements.forEach(element => {
        let el;
        
        if (element.type === 'i-text' || element.type === 'text') {
            el = document.createElement('div');
            el.className = 'canvas-element text-element';
            el.textContent = element.text;
            el.style.left = element.left + 'px';
            el.style.top = element.top + 'px';
            el.style.width = (element.width || 200) + 'px';
            el.style.height = (element.height || 50) + 'px';
            el.style.fontSize = (element.fontSize || 16) + 'px';
            el.style.color = element.fill || '#000000';
            el.style.fontFamily = element.fontFamily || 'Arial';
            el.style.transform = `rotate(${element.angle || 0}deg)`;
            el.style.opacity = element.opacity || 1;
        } else if (element.type === 'image') {
            el = document.createElement('div');
            el.className = 'canvas-element';
            el.style.left = element.left + 'px';
            el.style.top = element.top + 'px';
            el.style.width = (element.width || 200) + 'px';
            el.style.height = (element.height || 200) + 'px';
            el.style.transform = `rotate(${element.angle || 0}deg)`;
            el.style.opacity = element.opacity || 1;
            
            const img = document.createElement('img');
            img.src = element.src;
            img.alt = '图文元素';
            el.appendChild(img);
        }
        
        if (el) {
            container.appendChild(el);
        }
    });
}
</script>
@endsection
