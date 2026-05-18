@extends('layouts.elevator')

@section('title', isset($imageText) ? '编辑图文' : '新建图文')

@push('styles')
<style>
    /* 编辑器容器样式 - 左右分栏布局 */
    .editor-container {
        display: flex;
        height: calc(100vh - 140px);
        background: #f5f5f5;
        gap: 0;
    }
    
    /* 左侧工具栏 - 编辑工具区域 */
    .toolbar {
        width: 320px;
        min-width: 320px;
        background: white;
        border-right: 2px solid #e5e7eb;
        padding: 24px;
        overflow-y: auto;
        box-shadow: 2px 0 8px rgba(0, 0, 0, 0.05);
    }
    
    /* 右侧画布区域 - 编辑页面 */
    .canvas-area {
        flex: 1;
        display: flex;
        align-items: center;
        justify-content: center;
        padding: 40px;
        overflow: auto;
        background: linear-gradient(135deg, #f5f7fa 0%, #e8ecf1 100%);
    }
    
    /* 画布容器 */
    .canvas-wrapper {
        position: relative;
        box-shadow: 0 8px 24px rgba(0, 0, 0, 0.12);
        background: white;
        border-radius: 8px;
        overflow: visible; /* 改为visible以支持缩放显示 */
        transition: transform 0.2s ease-out; /* 添加平滑过渡动画 */
    }
    
    /* 工具按钮组 */
    .tool-group {
        margin-bottom: 24px;
        padding-bottom: 24px;
        border-bottom: 1px solid #e5e7eb;
    }
    
    .tool-group:last-child {
        border-bottom: none;
        margin-bottom: 0;
        padding-bottom: 0;
    }
    
    .tool-group h3 {
        font-size: 15px;
        font-weight: 600;
        color: #1f2937;
        margin-bottom: 16px;
        display: flex;
        align-items: center;
        gap: 8px;
    }
    
    .tool-group h3::before {
        content: '';
        width: 4px;
        height: 16px;
        background: linear-gradient(135deg, #3b82f6, #2563eb);
        border-radius: 2px;
    }
    
    .tool-btn {
        width: 100%;
        padding: 12px 16px;
        margin-bottom: 10px;
        border: 1.5px solid #e5e7eb;
        border-radius: 8px;
        background: white;
        cursor: pointer;
        transition: all 0.2s ease;
        display: flex;
        align-items: center;
        gap: 10px;
        font-size: 14px;
        color: #374151;
        font-weight: 500;
    }
    
    .tool-btn:hover {
        background: #f9fafb;
        border-color: #3b82f6;
        color: #3b82f6;
        transform: translateX(2px);
        box-shadow: 0 2px 8px rgba(59, 130, 246, 0.15);
    }
    
    .tool-btn:active {
        transform: translateX(2px) scale(0.98);
    }
    
    .tool-btn.primary {
        background: linear-gradient(135deg, #3b82f6, #2563eb);
        color: white;
        border-color: #3b82f6;
        box-shadow: 0 2px 8px rgba(59, 130, 246, 0.3);
    }
    
    .tool-btn.primary:hover {
        background: linear-gradient(135deg, #2563eb, #1d4ed8);
        box-shadow: 0 4px 12px rgba(59, 130, 246, 0.4);
        transform: translateY(-1px);
    }
    
    .tool-btn.danger {
        background: linear-gradient(135deg, #ef4444, #dc2626);
        color: white;
        border-color: #ef4444;
        box-shadow: 0 2px 8px rgba(239, 68, 68, 0.3);
    }
    
    .tool-btn.danger:hover {
        background: linear-gradient(135deg, #dc2626, #b91c1c);
        box-shadow: 0 4px 12px rgba(239, 68, 68, 0.4);
        transform: translateY(-1px);
    }
    
    /* 属性面板 */
    .property-panel {
        margin-top: 12px;
        padding: 16px;
        background: #f9fafb;
        border-radius: 8px;
        border: 1px solid #e5e7eb;
    }
    
    .property-item {
        margin-bottom: 14px;
    }
    
    .property-item:last-child {
        margin-bottom: 0;
    }
    
    .property-item label {
        display: block;
        font-size: 13px;
        color: #6b7280;
        margin-bottom: 6px;
        font-weight: 500;
    }
    
    .property-item input,
    .property-item select,
    .property-item textarea {
        width: 100%;
        padding: 10px 12px;
        border: 1.5px solid #e5e7eb;
        border-radius: 6px;
        font-size: 14px;
        transition: all 0.2s;
        background: white;
    }
    
    .property-item input:focus,
    .property-item select:focus,
    .property-item textarea:focus {
        outline: none;
        border-color: #3b82f6;
        box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.1);
    }
    
    .property-item input[type="color"] {
        height: 44px;
        padding: 4px;
        cursor: pointer;
    }
    
    /* 缩放控制 */
    .zoom-controls {
        position: fixed;
        bottom: 24px;
        right: 24px;
        display: flex;
        gap: 8px;
        background: white;
        padding: 10px;
        border-radius: 12px;
        box-shadow: 0 4px 16px rgba(0, 0, 0, 0.12);
        border: 1px solid #e5e7eb;
        z-index: 100;
    }
    
    .zoom-btn {
        width: 40px;
        height: 40px;
        border: 1.5px solid #e5e7eb;
        border-radius: 8px;
        background: white;
        cursor: pointer;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 20px;
        color: #374151;
        transition: all 0.2s;
    }
    
    .zoom-btn:hover {
        background: #f9fafb;
        border-color: #3b82f6;
        color: #3b82f6;
        transform: scale(1.05);
    }
    
    .zoom-btn:active {
        transform: scale(0.95);
    }
    
    /* 通知提示动画 */
    @keyframes slideIn {
        from {
            transform: translateX(400px);
            opacity: 0;
        }
        to {
            transform: translateX(0);
            opacity: 1;
        }
    }
    
    @keyframes slideOut {
        from {
            transform: translateX(0);
            opacity: 1;
        }
        to {
            transform: translateX(400px);
            opacity: 0;
        }
    }
    
    /* 顶部操作栏 */
    .top-bar {
        background: white;
        border-bottom: 2px solid #e5e7eb;
        padding: 16px 24px;
        display: flex;
        justify-content: space-between;
        align-items: center;
        box-shadow: 0 2px 8px rgba(0, 0, 0, 0.05);
    }
    
    .top-bar-left {
        display: flex;
        gap: 16px;
        align-items: center;
    }
    
    .top-bar-right {
        display: flex;
        gap: 12px;
    }
    
    /* 表单输入 */
    .form-input {
        padding: 10px 16px;
        border: 1.5px solid #e5e7eb;
        border-radius: 8px;
        font-size: 14px;
        transition: all 0.2s;
        background: white;
    }
    
    .form-input:focus {
        outline: none;
        border-color: #3b82f6;
        box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.1);
    }
    
    /* 提示信息 */
    .info-tip {
        background: linear-gradient(135deg, #dbeafe, #bfdbfe);
        border-left: 4px solid #3b82f6;
        padding: 12px 16px;
        border-radius: 6px;
        margin-bottom: 16px;
        font-size: 13px;
        color: #1e40af;
    }
    
    .info-tip i {
        margin-right: 6px;
    }
</style>
@endpush

@section('content')
<form id="imageTextForm" method="POST" action="{{ isset($imageText) ? route('image-text.update', $imageText->id) : route('image-text.store') }}" enctype="multipart/form-data">
    @csrf
    @if(isset($imageText))
        @method('PUT')
    @endif
    
    <!-- 顶部操作栏 -->
    <div class="top-bar">
        <div class="top-bar-left">
            <a href="{{ route('image-text.index') }}" class="text-gray-600 hover:text-gray-900">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                </svg>
            </a>
            <input type="text" 
                   name="title" 
                   value="{{ old('title', $imageText->title ?? '') }}"
                   placeholder="图文标题" 
                   class="form-input w-64"
                   required>
            <input type="file" 
                   name="thumbnail" 
                   accept="image/*"
                   class="form-input text-sm">
        </div>
        <div class="top-bar-right">
            <button type="button" onclick="clearCanvas()" class="tool-btn danger" style="width: auto; padding: 8px 16px;">
                清空画布
            </button>
            <button type="submit" class="tool-btn primary" style="width: auto; padding: 8px 16px;">
                {{ isset($imageText) ? '保存修改' : '保存图文' }}
            </button>
        </div>
    </div>

    <!-- 编辑器主体 -->
    <div class="editor-container">
        <!-- 左侧编辑工具栏 -->
        <div class="toolbar">
            <div class="info-tip">
                <i>💡</i> 提示：在右侧画布上自由拖拽放置元素
            </div>
            
            <!-- 基础设置 -->
            <div class="tool-group">
                <h3>📝 基础设置</h3>
                <div class="property-item">
                    <label>描述（可选）</label>
                    <textarea name="description" rows="3" class="form-input" placeholder="请输入图文描述...">{{ old('description', $imageText->description ?? '') }}</textarea>
                </div>
            </div>

            <!-- 添加元素工具 -->
            <div class="tool-group">
                <h3>➕ 添加元素</h3>
                <button type="button" onclick="addText()" class="tool-btn">
                    <span style="font-size: 18px;">📄</span> 
                    <div>
                        <div style="font-weight: 600;">添加文字</div>
                        <div style="font-size: 12px; color: #9ca3af; margin-top: 2px;">点击后在画布上添加可编辑文字</div>
                    </div>
                </button>
                <button type="button" onclick="document.getElementById('imageUpload').click()" class="tool-btn">
                    <span style="font-size: 18px;">🖼️</span>
                    <div>
                        <div style="font-weight: 600;">添加图片</div>
                        <div style="font-size: 12px; color: #9ca3af; margin-top: 2px;">从本地选择图片上传到画布</div>
                    </div>
                </button>
                <input type="file" id="imageUpload" accept="image/*" style="display: none;" onchange="handleImageUpload(event)">
            </div>

            <!-- 属性调整面板 -->
            <div class="tool-group" id="propertiesPanel" style="display: none;">
                <h3>⚙️ 属性调整</h3>
                <div class="info-tip" style="margin-bottom: 12px; padding: 8px 12px; font-size: 12px;">
                    <i>ℹ️</i> 选中元素后调整其属性
                </div>
                <div class="property-panel">
                    <div class="property-item">
                        <label>宽度 (px)</label>
                        <input type="number" id="propWidth" onchange="updateElementProperty('width', this.value)" placeholder="例如: 400">
                    </div>
                    <div class="property-item">
                        <label>高度 (px)</label>
                        <input type="number" id="propHeight" onchange="updateElementProperty('height', this.value)" placeholder="例如: 300">
                    </div>
                    <div class="property-item">
                        <label>旋转角度 (°)</label>
                        <input type="number" id="propAngle" onchange="updateElementProperty('angle', this.value)" min="0" max="360" placeholder="0-360">
                    </div>
                    <div class="property-item" id="textColorGroup">
                        <label>文字颜色</label>
                        <input type="color" id="propFillColor" onchange="updateElementProperty('fill', this.value)">
                    </div>
                    <div class="property-item" id="fontSizeGroup">
                        <label>字体大小 (px)</label>
                        <input type="number" id="propFontSize" onchange="updateElementProperty('fontSize', this.value)" min="8" max="200" placeholder="例如: 24">
                    </div>
                    <div class="property-item">
                        <label>透明度 (0-1)</label>
                        <input type="number" id="propOpacity" step="0.1" min="0" max="1" onchange="updateElementProperty('opacity', this.value)" placeholder="0.0 - 1.0">
                    </div>
                    <button type="button" onclick="deleteSelectedElement()" class="tool-btn danger" style="margin-top: 12px;">
                        <span>🗑️</span> 删除选中元素
                    </button>
                </div>
            </div>

            <!-- 模板选择 -->
            <div class="tool-group">
                <h3>📋 快速模板</h3>
                <div class="info-tip" style="margin-bottom: 12px; padding: 8px 12px; font-size: 12px;">
                    <i>⚡</i> 选择模板快速开始设计
                </div>
                <select id="templateSelect" onchange="loadTemplate(this.value)" class="form-input">
                    <option value="">-- 选择模板 --</option>
                    <option value="blank">🎨 空白画布</option>
                    <option value="single-image-text">📄 单图+文字</option>
                    <option value="double-image-text">📑 双图+文字</option>
                </select>
            </div>
        </div>

        <!-- 右侧编辑画布区域 -->
        <div class="canvas-area">
            <div class="canvas-wrapper">
                <canvas id="editorCanvas" width="800" height="1200"></canvas>
            </div>
        </div>
    </div>

    <!-- 隐藏字段存储布局数据 -->
    <input type="hidden" name="layout_data" id="layoutData">

    <!-- 缩放控制 -->
    <div class="zoom-controls">
        <button type="button" class="zoom-btn" onclick="zoomOut()">−</button>
        <span id="zoomLevel" style="padding: 0 8px; line-height: 36px; font-size: 14px;">100%</span>
        <button type="button" class="zoom-btn" onclick="zoomIn()">+</button>
        <button type="button" class="zoom-btn" onclick="resetZoom()" title="重置">⟲</button>
    </div>
</form>

<!-- Fabric.js 本地文件 -->
<script src="{{ asset('js/fabric.min.js') }}"></script>
<script>
// 全局变量声明
let canvas;
let currentZoom = 1;

// 检查Fabric.js是否成功加载
if (typeof fabric === 'undefined') {
    console.error('Fabric.js 加载失败');
    alert('Fabric.js 加载失败，请检查文件是否存在: public/js/fabric.min.js');
} else {
    console.log('✅ Fabric.js 从本地文件加载成功');
    
    // Fabric.js 加载成功后初始化编辑器
    document.addEventListener('DOMContentLoaded', function() {
        initEditor();
    });
}

// 初始化编辑器
function initEditor() {
    console.log('开始初始化编辑器...');
    
    // 初始化画布
    canvas = new fabric.Canvas('editorCanvas', {
        backgroundColor: '#ffffff',
        selection: true
    });

    // 加载现有数据（如果是编辑模式）
    @if(isset($imageText) && $imageText->layout_data)
        const existingData = @json($imageText->layout_data);
        if (existingData && existingData.elements) {
            loadCanvasData(existingData);
        }
    @endif

    // 监听对象选择事件
    canvas.on('selection:created', updatePropertiesPanel);
    canvas.on('selection:updated', updatePropertiesPanel);
    canvas.on('selection:cleared', hidePropertiesPanel);
    
    // 监听对象修改事件
    canvas.on('object:modified', function() {
        saveLayoutData();
    });
    
    console.log('✅ 编辑器初始化成功');
}

// 添加文字
function addText() {
    console.log('addText 被调用');
    
    if (!canvas) {
        console.error('Canvas 未初始化');
        alert('编辑器尚未准备好，请稍后再试');
        return;
    }
    
    try {
        // 计算画布中心位置
        const canvasWidth = canvas.getWidth();
        const canvasHeight = canvas.getHeight();
        const centerX = canvasWidth / 2 - 150; // 预留文字宽度的一半
        const centerY = canvasHeight / 2 - 25;  // 预留文字高度的一半
        
        const text = new fabric.IText('双击编辑文字', {
            left: centerX,
            top: centerY,
            fontSize: 32,
            fill: '#165DFF',
            fontFamily: 'Microsoft YaHei, Arial',
            fontWeight: 'bold',
            editable: true,
            originX: 'center',
            originY: 'center'
        });
        
        canvas.add(text);
        canvas.setActiveObject(text);
        
        // 进入编辑模式，自动聚焦
        text.enterEditing();
        text.selectAll();
        
        canvas.renderAll();
        saveLayoutData();
        
        console.log('✅ 文字已添加到画布中心位置');
        
        // 显示提示
        showNotification('文字已添加到画布中心，可以直接输入内容', 'success');
    } catch (error) {
        console.error('添加文字失败:', error);
        alert('添加文字失败: ' + error.message);
    }
}

// 处理图片上传
function handleImageUpload(event) {
    console.log('handleImageUpload 被调用');
    
    if (!canvas) {
        console.error('Canvas 未初始化');
        alert('编辑器尚未准备好，请稍后再试');
        return;
    }
    
    const file = event.target.files[0];
    if (!file) {
        console.log('没有选择文件');
        return;
    }

    console.log('选择的文件:', file.name, file.size, file.type);

    const reader = new FileReader();
    reader.onload = function(e) {
        console.log('文件读取成功');
        fabric.Image.fromURL(e.target.result, function(img) {
            // 计算画布中心位置
            const canvasWidth = canvas.getWidth();
            const canvasHeight = canvas.getHeight();
            
            // 缩放图片以适应画布
            const maxWidth = Math.min(canvasWidth * 0.6, 600);
            const maxHeight = Math.min(canvasHeight * 0.6, 400);
            
            let scale = 1;
            if (img.width > maxWidth || img.height > maxHeight) {
                scale = Math.min(maxWidth / img.width, maxHeight / img.height);
            }
            
            img.scale(scale);
            
            // 设置图片到画布中心
            const centerX = canvasWidth / 2;
            const centerY = canvasHeight / 2;
            
            img.set({
                left: centerX,
                top: centerY,
                originX: 'center',
                originY: 'center'
            });
            
            canvas.add(img);
            canvas.setActiveObject(img);
            canvas.renderAll();
            saveLayoutData();
            
            console.log('✅ 图片已添加到画布中心位置');
            showNotification('图片已添加到画布中心，可以拖拽调整位置', 'success');
        }, {
            crossOrigin: 'anonymous'
        });
    };
    reader.onerror = function(error) {
        console.error('文件读取失败:', error);
        alert('图片读取失败，请重试');
    };
    reader.readAsDataURL(file);
    
    // 清空input以便可以重复选择同一文件
    event.target.value = '';
}

// 更新属性面板
function updatePropertiesPanel() {
    const activeObject = canvas.getActiveObject();
    if (!activeObject) return;

    document.getElementById('propertiesPanel').style.display = 'block';
    
    // 更新属性值
    document.getElementById('propWidth').value = Math.round(activeObject.width * activeObject.scaleX);
    document.getElementById('propHeight').value = Math.round(activeObject.height * activeObject.scaleY);
    document.getElementById('propAngle').value = Math.round(activeObject.angle);
    document.getElementById('propOpacity').value = activeObject.opacity || 1;
    
    // 文字相关属性
    if (activeObject.type === 'i-text' || activeObject.type === 'text') {
        document.getElementById('textColorGroup').style.display = 'block';
        document.getElementById('fontSizeGroup').style.display = 'block';
        document.getElementById('propFillColor').value = activeObject.fill || '#000000';
        document.getElementById('propFontSize').value = activeObject.fontSize || 24;
    } else {
        document.getElementById('textColorGroup').style.display = 'none';
        document.getElementById('fontSizeGroup').style.display = 'none';
    }
}

// 隐藏属性面板
function hidePropertiesPanel() {
    document.getElementById('propertiesPanel').style.display = 'none';
}

// 更新元素属性
function updateElementProperty(property, value) {
    const activeObject = canvas.getActiveObject();
    if (!activeObject) return;

    value = parseFloat(value);
    
    switch(property) {
        case 'width':
            activeObject.scaleToWidth(value);
            break;
        case 'height':
            activeObject.scaleToHeight(value);
            break;
        case 'angle':
            activeObject.rotate(value);
            break;
        case 'fill':
            activeObject.set('fill', value);
            break;
        case 'fontSize':
            if (activeObject.type === 'i-text' || activeObject.type === 'text') {
                activeObject.set('fontSize', value);
            }
            break;
        case 'opacity':
            activeObject.set('opacity', value);
            break;
    }
    
    canvas.renderAll();
    saveLayoutData();
}

// 删除选中元素
function deleteSelectedElement() {
    const activeObject = canvas.getActiveObject();
    if (activeObject) {
        canvas.remove(activeObject);
        canvas.renderAll();
        saveLayoutData();
        hidePropertiesPanel();
    }
}

// 清空画布
function clearCanvas() {
    if (confirm('确定要清空画布吗？此操作不可恢复。')) {
        canvas.clear();
        canvas.backgroundColor = '#ffffff';
        canvas.renderAll();
        saveLayoutData();
        hidePropertiesPanel();
    }
}

// 保存布局数据
function saveLayoutData() {
    const elements = [];
    
    canvas.getObjects().forEach(obj => {
        // 获取元素的左上角坐标（考虑originX和originY）
        let left = obj.left;
        let top = obj.top;
        
        // 如果originX是center，需要转换为左上角坐标
        if (obj.originX === 'center') {
            left = obj.left - (obj.width * obj.scaleX) / 2;
        }
        
        // 如果originY是center，需要转换为左上角坐标
        if (obj.originY === 'center') {
            top = obj.top - (obj.height * obj.scaleY) / 2;
        }
        
        const elementData = {
            type: obj.type,
            left: left,
            top: top,
            width: obj.width * obj.scaleX,
            height: obj.height * obj.scaleY,
            angle: obj.angle,
            opacity: obj.opacity,
            scaleX: obj.scaleX,
            scaleY: obj.scaleY
        };
        
        if (obj.type === 'i-text' || obj.type === 'text') {
            elementData.text = obj.text;
            elementData.fontSize = obj.fontSize;
            elementData.fill = obj.fill;
            elementData.fontFamily = obj.fontFamily;
        } else if (obj.type === 'image') {
            elementData.src = obj.toDataURL();
        }
        
        elements.push(elementData);
    });
    
    const layoutData = {
        canvasWidth: canvas.width,
        canvasHeight: canvas.height,
        elements: elements
    };
    
    document.getElementById('layoutData').value = JSON.stringify(layoutData);
}

// 加载画布数据
function loadCanvasData(data) {
    canvas.clear();
    canvas.backgroundColor = '#ffffff';
    
    if (data.elements) {
        data.elements.forEach(element => {
            if (element.type === 'i-text' || element.type === 'text') {
                const text = new fabric.IText(element.text, {
                    left: element.left,
                    top: element.top,
                    fontSize: element.fontSize,
                    fill: element.fill,
                    fontFamily: element.fontFamily || 'Arial',
                    angle: element.angle,
                    opacity: element.opacity
                });
                canvas.add(text);
            } else if (element.type === 'image') {
                fabric.Image.fromURL(element.src, function(img) {
                    img.set({
                        left: element.left,
                        top: element.top,
                        angle: element.angle,
                        opacity: element.opacity
                    });
                    img.scaleToWidth(element.width);
                    img.scaleToHeight(element.height);
                    canvas.add(img);
                });
            }
        });
    }
    
    canvas.renderAll();
    saveLayoutData();
}

// 加载模板
function loadTemplate(templateName) {
    if (!templateName) return;
    
    if (!confirm('加载模板将清空当前内容，是否继续？')) {
        document.getElementById('templateSelect').value = '';
        return;
    }
    
    let templateData = null;
    
    switch(templateName) {
        case 'blank':
            templateData = {
                canvasWidth: 800,
                canvasHeight: 1200,
                elements: []
            };
            break;
        case 'single-image-text':
            templateData = {
                canvasWidth: 800,
                canvasHeight: 1200,
                elements: [
                    {
                        type: 'i-text',
                        text: '标题文字',
                        left: 100,
                        top: 100,
                        width: 400,
                        height: 50,
                        fontSize: 32,
                        fill: '#000000',
                        fontFamily: 'Arial',
                        angle: 0,
                        opacity: 1
                    },
                    {
                        type: 'i-text',
                        text: '在这里添加描述文字...',
                        left: 100,
                        top: 200,
                        width: 500,
                        height: 100,
                        fontSize: 18,
                        fill: '#666666',
                        fontFamily: 'Arial',
                        angle: 0,
                        opacity: 1
                    }
                ]
            };
            break;
        case 'double-image-text':
            templateData = {
                canvasWidth: 800,
                canvasHeight: 1200,
                elements: [
                    {
                        type: 'i-text',
                        text: '双栏布局示例',
                        left: 100,
                        top: 100,
                        width: 400,
                        height: 50,
                        fontSize: 32,
                        fill: '#000000',
                        fontFamily: 'Arial',
                        angle: 0,
                        opacity: 1
                    }
                ]
            };
            break;
    }
    
    if (templateData) {
        loadCanvasData(templateData);
    }
}

// 缩放控制
function zoomIn() {
    currentZoom = Math.min(currentZoom + 0.1, 2);
    applyZoom();
}

function zoomOut() {
    currentZoom = Math.max(currentZoom - 0.1, 0.5);
    applyZoom();
}

function resetZoom() {
    currentZoom = 1;
    applyZoom();
}

function applyZoom() {
    // 方法1: 使用Fabric.js的setZoom
    canvas.setZoom(currentZoom);
    canvas.renderAll();
    
    // 方法2: 同时使用CSS transform作为备用方案（增强视觉效果）
    const canvasElement = document.getElementById('editorCanvas');
    const wrapper = document.querySelector('.canvas-wrapper');
    if (wrapper) {
        wrapper.style.transform = `scale(${currentZoom})`;
        wrapper.style.transformOrigin = 'center center';
    }
    
    document.getElementById('zoomLevel').textContent = Math.round(currentZoom * 100) + '%';
    console.log('缩放应用到:', currentZoom * 100 + '%');
}

// 显示通知提示
function showNotification(message, type = 'info') {
    // 创建通知元素
    const notification = document.createElement('div');
    notification.style.cssText = `
        position: fixed;
        top: 80px;
        right: 20px;
        padding: 12px 20px;
        background: ${type === 'success' ? '#10b981' : '#3b82f6'};
        color: white;
        border-radius: 8px;
        box-shadow: 0 4px 12px rgba(0,0,0,0.15);
        z-index: 9999;
        font-size: 14px;
        animation: slideIn 0.3s ease-out;
    `;
    notification.textContent = message;
    
    document.body.appendChild(notification);
    
    // 3秒后自动消失
    setTimeout(() => {
        notification.style.animation = 'slideOut 0.3s ease-out';
        setTimeout(() => {
            document.body.removeChild(notification);
        }, 300);
    }, 3000);
}

// 表单提交前确保数据已保存
document.getElementById('imageTextForm').addEventListener('submit', function(e) {
    saveLayoutData();
    
    // 验证是否有内容
    const layoutData = JSON.parse(document.getElementById('layoutData').value);
    if (!layoutData.elements || layoutData.elements.length === 0) {
        e.preventDefault();
        alert('请至少添加一个元素（文字或图片）');
        return false;
    }
});
</script>
@endsection
