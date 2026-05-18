# 图文管理功能使用说明

## 📋 功能概述

图文管理是一个类似PDF编辑器的可视化编辑工具，允许管理员在画布上自由拖拽、放置图片和文字元素，创建精美的图文内容。所有布局数据以JSON格式存储，前端可以通过API接口获取并渲染。

## 🎯 核心特性

### 1. 可视化编辑器
- **自由拖拽**: 支持任意拖动图片和文字元素
- **实时编辑**: 双击文字即可直接编辑内容
- **属性调整**: 可修改大小、旋转角度、透明度、颜色等
- **缩放控制**: 支持50%-200%的画布缩放

### 2. 元素类型
- **文字元素**: 支持自定义字体、大小、颜色
- **图片元素**: 支持上传本地图片，自动适配尺寸

### 3. 模板系统
- 空白画布
- 单图+文字模板
- 双图+文字模板

### 4. 数据持久化
- 布局数据以JSON格式存储
- 支持缩略图预览
- 软删除机制保护数据

## 🚀 使用流程

### 创建新图文

1. **进入图文管理**
   - 点击左侧菜单"图文管理"
   - 点击右上角"新建图文"按钮

2. **编辑内容**
   ```
   a. 填写标题和描述
   b. 添加文字：点击"添加文字"按钮
   c. 添加图片：点击"添加图片"按钮，选择本地文件
   d. 调整元素：选中元素后在左侧属性面板调整
   e. 保存：点击右上角"保存图文"
   ```

3. **操作技巧**
   - **移动元素**: 鼠标拖拽
   - **调整大小**: 拖动元素四角控制点
   - **旋转**: 拖动元素上方的旋转手柄
   - **删除**: 选中元素后点击"删除选中元素"按钮
   - **编辑文字**: 双击文字元素

### 编辑现有图文

1. 在列表页找到要编辑的图文
2. 点击"编辑"按钮
3. 修改内容后保存

### 查看图文

- **管理员**: 点击"查看"按钮，在新窗口打开
- **普通用户**: 通过分享链接访问（需要权限配置）

## 📡 API接口

### 获取图文数据

```javascript
// 接口地址
GET /image-text/{id}/api

// 返回示例
{
  "success": true,
  "data": {
    "id": 1,
    "title": "电梯安全须知",
    "description": "电梯使用注意事项",
    "layout_data": {
      "canvasWidth": 1200,
      "canvasHeight": 800,
      "elements": [
        {
          "type": "i-text",
          "text": "安全提示",
          "left": 100,
          "top": 50,
          "width": 200,
          "height": 40,
          "fontSize": 24,
          "fill": "#000000",
          "fontFamily": "Arial",
          "angle": 0,
          "opacity": 1
        },
        {
          "type": "image",
          "src": "data:image/png;base64,...",
          "left": 100,
          "top": 150,
          "width": 400,
          "height": 300,
          "angle": 0,
          "opacity": 1
        }
      ]
    },
    "thumbnail": "storage/uploads/image-text/thumbnails/xxx.jpg",
    "created_at": "2026-05-18 14:00:00"
  }
}
```

### 前端渲染示例

```javascript
// 获取数据
fetch('/image-text/1/api')
  .then(response => response.json())
  .then(data => {
    if (data.success) {
      renderCanvas(data.data.layout_data);
    }
  });

// 渲染函数
function renderCanvas(layoutData) {
  const container = document.getElementById('canvasDisplay');
  container.innerHTML = '';
  
  layoutData.elements.forEach(element => {
    let el;
    
    if (element.type === 'i-text' || element.type === 'text') {
      // 创建文字元素
      el = document.createElement('div');
      el.className = 'canvas-element text-element';
      el.textContent = element.text;
      el.style.cssText = `
        position: absolute;
        left: ${element.left}px;
        top: ${element.top}px;
        width: ${element.width}px;
        height: ${element.height}px;
        font-size: ${element.fontSize}px;
        color: ${element.fill};
        font-family: ${element.fontFamily};
        transform: rotate(${element.angle}deg);
        opacity: ${element.opacity};
      `;
    } else if (element.type === 'image') {
      // 创建图片元素
      el = document.createElement('div');
      el.className = 'canvas-element';
      el.style.cssText = `
        position: absolute;
        left: ${element.left}px;
        top: ${element.top}px;
        width: ${element.width}px;
        height: ${element.height}px;
        transform: rotate(${element.angle}deg);
        opacity: ${element.opacity};
      `;
      
      const img = document.createElement('img');
      img.src = element.src;
      img.style.cssText = 'width: 100%; height: 100%; object-fit: contain;';
      el.appendChild(img);
    }
    
    if (el) {
      container.appendChild(el);
    }
  });
}
```

## 🎨 布局数据结构

```json
{
  "canvasWidth": 1200,
  "canvasHeight": 800,
  "elements": [
    {
      "type": "i-text",           // 元素类型：i-text(文字), image(图片)
      "text": "标题文字",          // 文字内容（仅文字元素）
      "left": 100,                // X坐标
      "top": 100,                 // Y坐标
      "width": 300,               // 宽度
      "height": 50,               // 高度
      "fontSize": 24,             // 字体大小（仅文字）
      "fill": "#000000",          // 填充颜色（仅文字）
      "fontFamily": "Arial",      // 字体（仅文字）
      "angle": 0,                 // 旋转角度
      "opacity": 1,               // 透明度 (0-1)
      "src": "data:image/..."     // 图片Base64（仅图片）
    }
  ]
}
```

## 💡 最佳实践

### 1. 设计建议
- **画布尺寸**: 建议使用1200x800标准尺寸
- **元素间距**: 保持适当的留白，避免拥挤
- **色彩搭配**: 文字颜色与背景形成足够对比
- **字体选择**: 中文推荐使用"Microsoft YaHei"

### 2. 性能优化
- **图片压缩**: 上传前压缩图片，建议不超过2MB
- **元素数量**: 单个图文建议不超过20个元素
- **缩略图**: 自动生成小尺寸缩略图用于列表展示

### 3. 兼容性
- **浏览器**: Chrome、Firefox、Safari、Edge最新版
- **移动端**: 展示页完全响应式，编辑器建议PC端使用

## 🔧 技术栈

- **后端**: Laravel 12 + PHP 8.2
- **前端**: Fabric.js 5.3.1 (Canvas库)
- **数据库**: MySQL/SQLite
- **样式**: Tailwind CSS 4

## 📝 常见问题

### Q1: 图片无法上传？
A: 检查文件大小是否超过限制（2MB），格式是否为JPEG/PNG/GIF。

### Q2: 文字无法编辑？
A: 双击文字元素进入编辑模式，按ESC或点击空白处退出。

### Q3: 如何精确对齐元素？
A: 目前版本暂不支持辅助线，可通过属性面板输入精确坐标值。

### Q4: 能否导出PDF？
A: PDF导出功能正在开发中，目前可使用浏览器打印功能（Ctrl+P）。

## 🚧 后续规划

1. ✅ 基础编辑器功能
2. ✅ JSON数据存储
3. ✅ API接口
4. ⏳ PDF导出功能
5. ⏳ 更多预设模板
6. ⏳ 元素对齐辅助线
7. ⏳ 撤销/重做功能
8. ⏳ 图层管理
9. ⏳ 批量操作

---

**技术支持**: 如有问题请联系系统管理员
**最后更新**: 2026-05-18
