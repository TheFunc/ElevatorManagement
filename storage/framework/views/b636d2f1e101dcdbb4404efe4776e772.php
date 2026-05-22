<?php $__env->startSection('title', '增加图文'); ?>
<?php $__env->startSection('page-title', '增加图文'); ?>

<?php $__env->startSection('content'); ?>
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
                <?php $__currentLoopData = $imageTypes; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $type): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <option value="<?php echo e($type->type); ?>"><?php echo e($type->type); ?></option>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </select>
        </div>
    </div>

    <!-- Markdown 编辑器区域 -->
    <div class="mb-6">
        <label class="block text-sm font-medium text-gray-700 mb-2">
            图片描述（支持 Markdown 语法）
        </label>
        
        <div class="markdown-editor-container">
            <!-- 左侧：编辑区 -->
            <div class="markdown-editor">
                <div class="markdown-editor-header">
                    📝 编辑区 - 支持 Markdown 语法
                </div>
                <textarea 
                    id="markdownEditor"
                    class="markdown-textarea"
                    placeholder="# 标题&#10;&#10;## 二级标题&#10;&#10;**粗体** *斜体*&#10;&#10;- 列表项 1&#10;- 列表项 2&#10;&#10;``php&#10;// 代码块&#10;echo 'Hello World';&#10;```&#10;&#10;[链接文本](https://example.com)&#10;&#10;> 引用文本"></textarea>
            </div>

            <!-- 右侧：预览区 -->
            <div class="markdown-preview">
                <div class="markdown-preview-header">
                    👁️ 实时预览
                </div>
                <div id="markdownPreview" class="markdown-preview-content">
                    <p class="text-gray-400 italic">在左侧输入 Markdown 文本，这里将实时显示渲染效果...</p>
                </div>
            </div>
        </div>
        
        <p class="text-sm text-gray-500 mt-2">
            💡 提示：支持标题、粗体、斜体、列表、链接、图片、代码块、引用、表格等 Markdown 语法
        </p>
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

    /* Markdown 编辑器容器 - 左右分栏布局 */
    .markdown-editor-container {
        display: flex;
        gap: 16px;
        height: 400px;
        border: 1px solid #e5e7eb;
        border-radius: 8px;
        overflow: hidden;
        background: white;
    }

    /* 左侧编辑区 */
    .markdown-editor {
        flex: 1;
        display: flex;
        flex-direction: column;
        border-right: 2px solid #e5e7eb;
    }

    .markdown-editor-header {
        padding: 12px 16px;
        background: #f9fafb;
        border-bottom: 1px solid #e5e7eb;
        font-weight: 600;
        color: #374151;
        font-size: 14px;
    }

    .markdown-textarea {
        flex: 1;
        width: 100%;
        padding: 16px;
        border: none;
        outline: none;
        resize: none;
        font-family: 'Consolas', 'Monaco', 'Courier New', monospace;
        font-size: 14px;
        line-height: 1.6;
        color: #1f2937;
        background: #ffffff;
    }

    .markdown-textarea:focus {
        background: #fefefe;
    }

    /* 右侧预览区 */
    .markdown-preview {
        flex: 1;
        display: flex;
        flex-direction: column;
        overflow: hidden;
    }

    .markdown-preview-header {
        padding: 12px 16px;
        background: #f9fafb;
        border-bottom: 1px solid #e5e7eb;
        font-weight: 600;
        color: #374151;
        font-size: 14px;
    }

    .markdown-preview-content {
        flex: 1;
        padding: 16px;
        overflow-y: auto;
        font-size: 14px;
        line-height: 1.6;
        color: #1f2937;
        background: #ffffff;
    }

    /* Markdown 渲染样式 */
    .markdown-preview-content h1,
    .markdown-preview-content h2,
    .markdown-preview-content h3,
    .markdown-preview-content h4,
    .markdown-preview-content h5,
    .markdown-preview-content h6 {
        margin-top: 24px;
        margin-bottom: 16px;
        font-weight: 600;
        line-height: 1.25;
        color: #111827;
    }

    .markdown-preview-content h1 {
        font-size: 2em;
        border-bottom: 1px solid #e5e7eb;
        padding-bottom: 0.3em;
    }

    .markdown-preview-content h2 {
        font-size: 1.5em;
        border-bottom: 1px solid #e5e7eb;
        padding-bottom: 0.3em;
    }

    .markdown-preview-content h3 {
        font-size: 1.25em;
    }

    .markdown-preview-content p {
        margin-top: 0;
        margin-bottom: 16px;
    }

    .markdown-preview-content strong {
        font-weight: 600;
        color: #111827;
    }

    .markdown-preview-content em {
        font-style: italic;
    }

    .markdown-preview-content ul,
    .markdown-preview-content ol {
        margin-top: 0;
        margin-bottom: 16px;
        padding-left: 2em;
    }

    .markdown-preview-content ul {
        list-style-type: disc;
    }

    .markdown-preview-content ul ul {
        list-style-type: circle;
    }

    .markdown-preview-content ul ul ul {
        list-style-type: square;
    }

    .markdown-preview-content ol {
        list-style-type: decimal;
    }

    .markdown-preview-content ol ol {
        list-style-type: lower-alpha;
    }

    .markdown-preview-content ol ol ol {
        list-style-type: lower-roman;
    }

    .markdown-preview-content li {
        margin: 0.25em 0;
        line-height: 1.6;
    }

    .markdown-preview-content li p {
        margin: 0.5em 0;
    }

    .markdown-preview-content code {
        padding: 0.2em 0.4em;
        margin: 0;
        font-size: 85%;
        background-color: rgba(175, 184, 193, 0.2);
        border-radius: 6px;
        font-family: 'Consolas', 'Monaco', 'Courier New', monospace;
    }

    .markdown-preview-content pre {
        padding: 16px;
        overflow: auto;
        font-size: 85%;
        line-height: 1.45;
        background-color: #f6f8fa;
        border-radius: 6px;
        margin: 16px 0;
    }

    .markdown-preview-content pre code {
        padding: 0;
        margin: 0;
        font-size: 100%;
        background-color: transparent;
        border: 0;
    }

    .markdown-preview-content blockquote {
        padding: 0 1em;
        color: #6b7280;
        border-left: 0.25em solid #d1d5db;
        margin: 16px 0;
    }

    .markdown-preview-content a {
        color: #0366d6;
        text-decoration: none;
    }

    .markdown-preview-content a:hover {
        text-decoration: underline;
    }

    .markdown-preview-content img {
        max-width: 100%;
        height: auto;
    }

    .markdown-preview-content hr {
        height: 0.25em;
        padding: 0;
        margin: 24px 0;
        background-color: #e5e7eb;
        border: 0;
    }

    .markdown-preview-content table {
        border-collapse: collapse;
        width: 100%;
        margin: 16px 0;
    }

    .markdown-preview-content table th,
    .markdown-preview-content table td {
        padding: 6px 13px;
        border: 1px solid #d1d5db;
    }

    .markdown-preview-content table tr:nth-child(2n) {
        background-color: #f9fafb;
    }

    .markdown-preview-content table th {
        background-color: #f3f4f6;
        font-weight: 600;
    }

    /* 响应式设计：小屏幕时切换为上下布局 */
    @media (max-width: 768px) {
        .markdown-editor-container {
            flex-direction: column;
            height: auto;
        }

        .markdown-editor {
            border-right: none;
            border-bottom: 2px solid #e5e7eb;
            height: 300px;
        }

        .markdown-preview {
            height: 300px;
        }
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

    <!-- 引入 Markdown 相关脚本 -->
    <script src="<?php echo e(asset('js/markdown/marked.min.js')); ?>"></script>
    <script src="<?php echo e(asset('js/markdown/highlight.min.js')); ?>"></script>
    <script>
    // 配置 marked.js
    marked.setOptions({
        breaks: true,           // 支持 GFM 换行
        gfm: true,              // 启用 GitHub Flavored Markdown
        headerIds: true,        // 为标题添加 ID
        mangle: false,          // 不转义邮箱
        sanitize: false,        // 允许 HTML
        smartLists: true,       // 智能列表
        smartypants: true,      // 智能标点
        highlight: function(code, lang) {
            // 自定义高亮函数
            if (lang && hljs.getLanguage(lang)) {
                try {
                    return hljs.highlight(code, { language: lang }).value;
                } catch (err) {
                    console.warn('代码高亮失败:', err);
                }
            }
            // 如果没有指定语言或高亮失败，返回转义后的代码
            return hljs.highlightAuto(code).value;
        }
    });

    // 获取元素
    const editor = document.getElementById('markdownEditor');
    const preview = document.getElementById('markdownPreview');

    // 防抖函数
    function debounce(func, wait) {
        let timeout;
        return function executedFunction(...args) {
            const later = () => {
                clearTimeout(timeout);
                func(...args);
            };
            clearTimeout(timeout);
            timeout = setTimeout(later, wait);
        };
    }

    // 渲染 Markdown
    function renderMarkdown() {
        const markdownText = editor.value;
        
        if (!markdownText.trim()) {
            preview.innerHTML = '<p class="text-gray-400 italic">在左侧输入 Markdown 文本，这里将实时显示渲染效果...</p>';
            return;
        }
        
        try {
            // 解析 Markdown（已包含高亮逻辑）
            const html = marked.parse(markdownText);
            preview.innerHTML = html;
            
            // 对所有代码块应用高亮样式类
            preview.querySelectorAll('pre code').forEach((block) => {
                if (!block.classList.contains('hljs')) {
                    block.classList.add('hljs');
                }
            });
        } catch (error) {
            console.warn('Markdown 解析警告:', error.message);
            // 即使解析失败，也尝试显示原始内容（转义 HTML）
            const escapedText = markdownText
                .replace(/&/g, '&amp;')
                .replace(/</g, '&lt;')
                .replace(/>/g, '&gt;')
                .replace(/\n/g, '<br>');
            preview.innerHTML = '<div class="text-gray-600">' + escapedText + '</div>';
        }
    }

    // 监听输入事件（带防抖）
    editor.addEventListener('input', debounce(renderMarkdown, 300));

    // Tab 键缩进支持
    editor.addEventListener('keydown', function(e) {
        if (e.key === 'Tab') {
            e.preventDefault();
            const start = this.selectionStart;
            const end = this.selectionEnd;
            
            // 插入两个空格作为缩进
            this.value = this.value.substring(0, start) + '  ' + this.value.substring(end);
            
            // 移动光标位置
            this.selectionStart = this.selectionEnd = start + 2;
        }
    });

    // 页面加载时渲染已有内容
    if (editor.value.trim()) {
        renderMarkdown();
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
    const description = document.getElementById('markdownEditor').value;
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
        coverFormData.append('_token', '<?php echo e(csrf_token()); ?>');

        const coverResponse = await fetch('<?php echo e(route('image-text.upload.cover')); ?>', {
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
        imageFormData.append('_token', '<?php echo e(csrf_token()); ?>');

        const imageResponse = await fetch('<?php echo e(route('image-text.upload.single')); ?>', {
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
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.elevator', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH D:\else\order\ElevatorManagement\ElevatorManagement\resources\views/image-text/create.blade.php ENDPATH**/ ?>