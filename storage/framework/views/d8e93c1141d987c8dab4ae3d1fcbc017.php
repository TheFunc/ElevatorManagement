<?php $__env->startSection('title', '编辑文本'); ?>
<?php $__env->startSection('page-title', '编辑文本'); ?>

<?php $__env->startSection('content'); ?>
<!-- 引入 Markdown 相关样式和脚本 -->
<link rel="stylesheet" href="<?php echo e(asset('css/markdown/default.min.css')); ?>">
<style>
.markdown-editor-container {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 20px;
    min-height: 500px;
}

@media (max-width: 768px) {
    .markdown-editor-container {
        grid-template-columns: 1fr;
        gap: 15px;
    }
}

.markdown-editor, .markdown-preview {
    border: 1px solid #e5e7eb;
    border-radius: 8px;
    overflow: hidden;
    background: white;
}

.markdown-editor-header, .markdown-preview-header {
    background: #f9fafb;
    padding: 12px 16px;
    border-bottom: 1px solid #e5e7eb;
    font-weight: 600;
    color: #374151;
    font-size: 14px;
}

.markdown-textarea {
    width: 100%;
    min-height: 450px;
    padding: 16px;
    border: none;
    resize: vertical;
    font-family: 'Consolas', 'Monaco', 'Courier New', monospace;
    font-size: 14px;
    line-height: 1.6;
    outline: none;
}

.markdown-textarea:focus {
    box-shadow: inset 0 0 0 2px rgba(59, 130, 246, 0.2);
}

.markdown-preview-content {
    padding: 16px;
    min-height: 450px;
    overflow-y: auto;
    max-height: 600px;
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
}

.markdown-preview-content h1 { font-size: 2em; border-bottom: 1px solid #eaecef; padding-bottom: 0.3em; }
.markdown-preview-content h2 { font-size: 1.5em; border-bottom: 1px solid #eaecef; padding-bottom: 0.3em; }
.markdown-preview-content h3 { font-size: 1.25em; }
.markdown-preview-content h4 { font-size: 1em; }

.markdown-preview-content p {
    margin: 16px 0;
    line-height: 1.6;
}

.markdown-preview-content code {
    background: rgba(27, 31, 35, 0.05);
    padding: 0.2em 0.4em;
    border-radius: 3px;
    font-family: 'Consolas', 'Monaco', monospace;
    font-size: 85%;
}

.markdown-preview-content pre {
    background: #f6f8fa;
    padding: 16px;
    overflow: auto;
    border-radius: 6px;
    margin: 16px 0;
}

.markdown-preview-content pre code {
    background: transparent;
    padding: 0;
    font-size: 100%;
}

.markdown-preview-content blockquote {
    padding: 0 1em;
    color: #6a737d;
    border-left: 0.25em solid #dfe2e5;
    margin: 16px 0;
}

/* 无序列表样式 */
.markdown-preview-content ul {
    padding-left: 2em;
    margin: 16px 0;
    list-style-type: disc;
}

.markdown-preview-content ul ul {
    list-style-type: circle;
    margin: 8px 0;
}

.markdown-preview-content ul ul ul {
    list-style-type: square;
}

/* 有序列表样式 */
.markdown-preview-content ol {
    padding-left: 2em;
    margin: 16px 0;
    list-style-type: decimal;
}

.markdown-preview-content ol ol {
    list-style-type: lower-alpha;
    margin: 8px 0;
}

.markdown-preview-content ol ol ol {
    list-style-type: lower-roman;
}

/* 列表项样式 */
.markdown-preview-content li {
    margin: 0.5em 0;
    line-height: 1.6;
}

.markdown-preview-content li p {
    margin: 0.5em 0;
}

/* 任务列表样式（GitHub Flavored Markdown） */
.markdown-preview-content input[type="checkbox"] {
    margin-right: 0.5em;
}

.markdown-preview-content ul.contains-task-list {
    list-style-type: none;
    padding-left: 0;
}

.markdown-preview-content ul.contains-task-list li {
    padding-left: 1.5em;
    position: relative;
}

.markdown-preview-content table {
    border-collapse: collapse;
    width: 100%;
    margin: 16px 0;
}

.markdown-preview-content table th,
.markdown-preview-content table td {
    padding: 6px 13px;
    border: 1px solid #dfe2e5;
}

.markdown-preview-content table tr:nth-child(2n) {
    background-color: #f6f8fa;
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
    background-color: #e1e4e8;
    border: 0;
}
</style>

<div class="card max-w-6xl mx-auto">
    <div class="mb-6">
        <h3 class="text-xl font-semibold text-gray-800">编辑文本</h3>
        <p class="text-gray-500 mt-1">使用 Markdown 语法修改文本内容，右侧实时预览效果</p>
    </div>

    <?php if($errors->any()): ?>
        <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-4">
            <ul class="list-disc list-inside">
                <?php $__currentLoopData = $errors->all(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $error): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <li><?php echo e($error); ?></li>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </ul>
        </div>
    <?php endif; ?>

    <form action="<?php echo e(route('text-info.update', $textInfo->id)); ?>" method="POST">
        <?php echo csrf_field(); ?>
        
        <!-- 隐藏字段：TextGroup 默认为 null -->
        <input type="hidden" name="TextGroup" value="null">
        
        <!-- 文本类型 -->
        <div class="mb-6">
            <label class="block text-sm font-medium text-gray-700 mb-2">
                文本类型 <span class="text-red-500">*</span>
            </label>
            <select name="TextType" required class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
                <option value="">请选择文本类型</option>
                <?php $__currentLoopData = $textTypes; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $type): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <option value="<?php echo e($type->type); ?>" <?php echo e($textInfo->TextType == $type->type ? 'selected' : ''); ?>><?php echo e($type->type); ?></option>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </select>
        </div>

        <!-- Markdown 编辑器区域 -->
        <div class="mb-6">
            <label class="block text-sm font-medium text-gray-700 mb-2">
                文本内容（支持 Markdown 语法）
            </label>
            
            <div class="markdown-editor-container">
                <!-- 左侧：编辑区 -->
                <div class="markdown-editor">
                    <div class="markdown-editor-header">
                        📝 编辑区 - 支持 Markdown 语法
                    </div>
                    <textarea 
                        name="TextContent" 
                        id="markdownEditor"
                        class="markdown-textarea"
                        placeholder="# 标题&#10;&#10;## 二级标题&#10;&#10;**粗体** *斜体*&#10;&#10;- 列表项 1&#10;- 列表项 2&#10;&#10;``php&#10;// 代码块&#10;echo 'Hello World';&#10;```&#10;&#10;[链接文本](https://example.com)&#10;&#10;> 引用文本"><?php echo e(old('TextContent', $textInfo->TextContent)); ?></textarea>
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

        <!-- 操作按钮 -->
        <div class="flex justify-end gap-3 pt-4 border-t">
            <a href="<?php echo e(route('text-management.preview')); ?>" class="px-6 py-2 border border-gray-300 rounded-lg text-gray-700 hover:bg-gray-50">
                取消
            </a>
            <button type="submit" class="px-6 py-2 bg-blue-500 text-white rounded-lg hover:bg-blue-600">
                <i class="ri-save-line"></i> 保存修改
            </button>
        </div>
    </form>
</div>

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
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.elevator', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH D:\else\order\ElevatorManagement\ElevatorManagement\resources\views/text-management/edit.blade.php ENDPATH**/ ?>