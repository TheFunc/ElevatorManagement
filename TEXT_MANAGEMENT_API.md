# 文本管理 API 接口文档（新增）

## 📌 概述

本文档描述了电梯管理系统中**新增的文本管理 API 接口**，用于管理和获取 Markdown 格式的文本内容。这些接口基于 Laravel 框架开发，遵循 RESTful 设计规范。

### 基础信息

- **Base URL**: `/api/v1/text`
- **认证方式**: 无需认证（公开接口）
- **响应格式**: JSON
- **字符编码**: UTF-8
- **HTTP 方法**: GET

---

## 📋 接口列表

### 1. 获取文本类型列表

#### 接口信息
- **URL**: `GET /api/v1/text/types`
- **功能**: 获取所有可用的文本类型分类
- **权限**: 公开访问

#### 请求参数
无

#### 响应示例

**成功响应 (200)**:
```json
{
    "code": 200,
    "message": "success",
    "data": [
        {
            "id": 1,
            "type": "安全须知",
            "created_at": "2026-05-20T10:30:00.000000Z",
            "updated_at": "2026-05-20T10:30:00.000000Z"
        },
        {
            "id": 2,
            "type": "操作指南",
            "created_at": "2026-05-20T11:00:00.000000Z",
            "updated_at": "2026-05-20T11:00:00.000000Z"
        },
        {
            "id": 3,
            "type": "维护公告",
            "created_at": "2026-05-20T12:00:00.000000Z",
            "updated_at": "2026-05-20T12:00:00.000000Z"
        }
    ]
}
```

**错误响应 (500)**:
```json
{
    "code": 500,
    "message": "获取文本分类失败"
}
```

#### 前端调用示例

```javascript
// 方式一：Fetch API
async function getTextTypes() {
    try {
        const response = await fetch('/api/v1/text/types');
        const result = await response.json();
        
        if (result.code === 200) {
            console.log('文本类型列表:', result.data);
            return result.data;
        } else {
            console.error('获取失败:', result.message);
            return [];
        }
    } catch (error) {
        console.error('网络错误:', error);
        return [];
    }
}

// 方式二：Axios
import axios from 'axios';

async function getTextTypes() {
    try {
        const response = await axios.get('/api/v1/text/types');
        const { code, data, message } = response.data;
        
        if (code === 200) {
            return data;
        } else {
            throw new Error(message);
        }
    } catch (error) {
        console.error('获取文本类型失败:', error.message);
        return [];
    }
}

// 使用示例
const types = await getTextTypes();
types.forEach(type => {
    console.log(`${type.id}: ${type.type}`);
});
```

---

### 2. 获取文本列表

#### 接口信息
- **URL**: `GET /api/v1/text/list`
- **功能**: 获取文本信息列表，支持关键词搜索和类型筛选
- **权限**: 公开访问

#### 请求参数

| 参数名 | 类型 | 必填 | 说明 | 示例 |
|--------|------|------|------|------|
| keyword | string | 否 | 搜索关键词（匹配文本内容或类型名称） | `keyword=安全` |
| textType | string | 否 | 文本类型筛选 | `textType=安全须知` |

#### 请求示例

```bash
# 获取所有文本
GET /api/v1/text/list

# 按关键词搜索
GET /api/v1/text/list?keyword=安全

# 按类型筛选
GET /api/v1/text/list?textType=安全须知

# 组合查询
GET /api/v1/text/list?keyword=电梯&textType=操作指南
```

#### 响应示例

**成功响应 (200)**:
```json
{
    "code": 200,
    "message": "success",
    "data": [
        {
            "id": 1,
            "TextType": "安全须知",
            "TextGroup": null,
            "TextContent": "# 电梯安全须知\n\n## 乘坐前检查\n- 确认电梯正常运行\n- 注意观察楼层显示\n\n## 乘坐时注意事项\n1. 不要倚靠轿门\n2. 不要在电梯内跳跃\n3. 如遇故障保持冷静\n\n## 紧急情况处理\n- 按下紧急呼叫按钮\n- 等待救援人员\n- 不要强行扒门",
            "created_at": "2026-05-20T10:30:00.000000Z",
            "updated_at": "2026-05-20T10:30:00.000000Z"
        },
        {
            "id": 2,
            "TextType": "操作指南",
            "TextGroup": null,
            "TextContent": "# 电梯操作指南\n\n## 基本操作\n- 按下上行/下行按钮\n- 选择目标楼层\n- 等待电梯到达\n\n## 特殊功能\n- 开门保持：长按开门键\n- 关门加速：双击关门键\n- 紧急停止：红色急停按钮",
            "created_at": "2026-05-20T11:00:00.000000Z",
            "updated_at": "2026-05-20T11:00:00.000000Z"
        }
    ]
}
```

**空数据响应 (200)**:
```json
{
    "code": 200,
    "message": "success",
    "data": []
}
```

**错误响应 (500)**:
```json
{
    "code": 500,
    "message": "获取文本信息失败"
}
```

#### 前端调用示例

```javascript
// 方式一：获取所有文本
async function getTextList() {
    try {
        const response = await fetch('/api/v1/text/list');
        const result = await response.json();
        
        if (result.code === 200) {
            return result.data;
        }
        return [];
    } catch (error) {
        console.error('获取文本列表失败:', error);
        return [];
    }
}

// 方式二：带搜索条件
async function searchTexts(keyword = '', textType = '') {
    const params = new URLSearchParams();
    
    if (keyword) {
        params.append('keyword', keyword);
    }
    if (textType) {
        params.append('textType', textType);
    }
    
    const queryString = params.toString();
    const url = `/api/v1/text/list${queryString ? '?' + queryString : ''}`;
    
    try {
        const response = await fetch(url);
        const result = await response.json();
        
        if (result.code === 200) {
            return result.data;
        }
        return [];
    } catch (error) {
        console.error('搜索失败:', error);
        return [];
    }
}

// 方式三：Vue + Axios 完整示例
<template>
    <div class="text-list-page">
        <!-- 搜索栏 -->
        <div class="search-bar">
            <input 
                v-model="searchForm.keyword" 
                placeholder="搜索文本内容..."
                @keyup.enter="loadTexts"
            />
            <select v-model="searchForm.textType">
                <option value="">全部类型</option>
                <option 
                    v-for="type in textTypes" 
                    :key="type.id" 
                    :value="type.type"
                >
                    {{ type.type }}
                </option>
            </select>
            <button @click="loadTexts">
                <i class="ri-search-line"></i> 搜索
            </button>
            <button @click="resetSearch">重置</button>
        </div>

        <!-- 文本列表 -->
        <div class="text-grid">
            <div 
                v-for="text in textList" 
                :key="text.id" 
                class="text-card"
                @click="viewDetail(text.id)"
            >
                <div class="text-type-badge">{{ text.TextType }}</div>
                <h3>{{ getFirstLine(text.TextContent) }}</h3>
                <p class="text-preview">{{ truncateText(text.TextContent, 150) }}</p>
                <div class="text-meta">
                    <span>{{ formatDate(text.created_at) }}</span>
                </div>
            </div>
        </div>

        <!-- 空状态 -->
        <div v-if="textList.length === 0 && !loading" class="empty-state">
            <i class="ri-inbox-line"></i>
            <p>暂无文本数据</p>
        </div>
    </div>
</template>

<script setup>
import { ref, onMounted } from 'vue';
import axios from 'axios';

const textTypes = ref([]);
const textList = ref([]);
const loading = ref(false);

const searchForm = ref({
    keyword: '',
    textType: ''
});

// 加载文本类型
async function loadTypes() {
    try {
        const response = await axios.get('/api/v1/text/types');
        if (response.data.code === 200) {
            textTypes.value = response.data.data;
        }
    } catch (error) {
        console.error('加载类型失败:', error);
    }
}

// 加载文本列表
async function loadTexts() {
    loading.value = true;
    try {
        const params = {};
        if (searchForm.value.keyword) {
            params.keyword = searchForm.value.keyword;
        }
        if (searchForm.value.textType) {
            params.textType = searchForm.value.textType;
        }
        
        const response = await axios.get('/api/v1/text/list', { params });
        
        if (response.data.code === 200) {
            textList.value = response.data.data;
        }
    } catch (error) {
        console.error('加载列表失败:', error);
    } finally {
        loading.value = false;
    }
}

// 重置搜索
function resetSearch() {
    searchForm.value = {
        keyword: '',
        textType: ''
    };
    loadTexts();
}

// 查看详情
function viewDetail(id) {
    // 跳转到详情页或打开弹窗
    window.location.href = `/text-management/detail/${id}`;
}

// 工具函数
function getFirstLine(content) {
    if (!content) return '';
    const lines = content.split('\n');
    return lines[0].replace(/^#+\s*/, '');
}

function truncateText(text, length) {
    if (!text) return '';
    const plainText = text.replace(/[#*_`\[\]]/g, '');
    return plainText.length > length 
        ? plainText.substring(0, length) + '...' 
        : plainText;
}

function formatDate(dateString) {
    const date = new Date(dateString);
    return date.toLocaleDateString('zh-CN');
}

onMounted(() => {
    loadTypes();
    loadTexts();
});
</script>
```

---

### 3. 获取文本详情

#### 接口信息
- **URL**: `GET /api/v1/text/{id}`
- **功能**: 根据 ID 获取单个文本的详细信息
- **权限**: 公开访问

#### 路径参数

| 参数名 | 类型 | 必填 | 说明 | 示例 |
|--------|------|------|------|------|
| id | integer | 是 | 文本 ID | `/api/v1/text/1` |

#### 请求示例

```bash
GET /api/v1/text/1
GET /api/v1/text/42
```

#### 响应示例

**成功响应 (200)**:
```json
{
    "code": 200,
    "message": "success",
    "data": {
        "id": 1,
        "TextType": "安全须知",
        "TextGroup": null,
        "TextContent": "# 电梯安全须知\n\n## 乘坐前检查\n- 确认电梯正常运行\n- 注意观察楼层显示\n\n## 乘坐时注意事项\n1. 不要倚靠轿门\n2. 不要在电梯内跳跃\n3. 如遇故障保持冷静\n\n## 紧急情况处理\n- 按下紧急呼叫按钮\n- 等待救援人员\n- 不要强行扒门\n\n## 禁止行为\n- ❌ 超载运行\n- ❌ 强行扒门\n- ❌ 在电梯内吸烟\n- ❌ 携带易燃易爆物品",
        "created_at": "2026-05-20T10:30:00.000000Z",
        "updated_at": "2026-05-20T10:30:00.000000Z"
    }
}
```

**错误响应 - 文本不存在 (404)**:
```json
{
    "code": 404,
    "message": "文本不存在"
}
```

**错误响应 - 服务器错误 (500)**:
```json
{
    "code": 500,
    "message": "获取文本详情失败"
}
```

#### 前端调用示例

```javascript
// 方式一：Fetch API
async function getTextDetail(id) {
    try {
        const response = await fetch(`/api/v1/text/${id}`);
        const result = await response.json();
        
        if (result.code === 200) {
            return result.data;
        } else if (result.code === 404) {
            console.error('文本不存在');
            return null;
        } else {
            console.error('获取失败:', result.message);
            return null;
        }
    } catch (error) {
        console.error('网络错误:', error);
        return null;
    }
}

// 方式二：React + Axios 完整示例
import React, { useState, useEffect } from 'react';
import axios from 'axios';
import { marked } from 'marked';
import hljs from 'highlight.js';
import 'highlight.js/styles/default.css';

function TextDetailPage({ textId }) {
    const [text, setText] = useState(null);
    const [loading, setLoading] = useState(true);
    const [error, setError] = useState(null);

    useEffect(() => {
        async function fetchTextDetail() {
            try {
                setLoading(true);
                const response = await axios.get(`/api/v1/text/${textId}`);
                
                if (response.data.code === 200) {
                    setText(response.data.data);
                } else {
                    setError(response.data.message);
                }
            } catch (err) {
                setError('加载失败，请稍后重试');
            } finally {
                setLoading(false);
            }
        }

        fetchTextDetail();
    }, [textId]);

    // 配置 marked.js
    marked.setOptions({
        breaks: true,
        gfm: true,
        highlight: function(code, lang) {
            if (lang && hljs.getLanguage(lang)) {
                return hljs.highlight(code, { language: lang }).value;
            }
            return hljs.highlightAuto(code).value;
        }
    });

    if (loading) {
        return (
            <div className="loading-spinner">
                <div className="spinner"></div>
                <p>加载中...</p>
            </div>
        );
    }

    if (error || !text) {
        return (
            <div className="error-state">
                <i className="ri-error-warning-line"></i>
                <p>{error || '未找到文本'}</p>
                <button onClick={() => window.history.back()}>返回</button>
            </div>
        );
    }

    const renderedContent = marked.parse(text.TextContent);

    return (
        <div className="text-detail-page">
            {/* 头部信息 */}
            <header className="detail-header">
                <div className="type-badge">{text.TextType}</div>
                <h1>{getFirstLine(text.TextContent)}</h1>
                <div className="meta-info">
                    <span>创建时间: {formatDate(text.created_at)}</span>
                    <span>更新时间: {formatDate(text.updated_at)}</span>
                </div>
            </header>

            {/* Markdown 内容 */}
            <article 
                className="markdown-content"
                dangerouslySetInnerHTML={{ __html: renderedContent }}
            />

            {/* 操作按钮 */}
            <footer className="detail-footer">
                <button onClick={() => window.history.back()}>
                    <i className="ri-arrow-left-line"></i> 返回
                </button>
                <button onClick={() => window.print()}>
                    <i className="ri-printer-line"></i> 打印
                </button>
            </footer>
        </div>
    );
}

// 工具函数
function getFirstLine(content) {
    if (!content) return '无标题';
    const lines = content.split('\n');
    return lines[0].replace(/^#+\s*/, '');
}

function formatDate(dateString) {
    const date = new Date(dateString);
    return date.toLocaleString('zh-CN', {
        year: 'numeric',
        month: '2-digit',
        day: '2-digit',
        hour: '2-digit',
        minute: '2-digit'
    });
}

export default TextDetailPage;
```

---

## 🔧 技术实现细节

### 后端实现

#### 控制器方法位置
文件: `app/Http/Controllers/FrontendAPI.php`

```php
/**
 * 获取所有文本类型
 */
public function textType(Request $request): JsonResponse
{
    try {
        $textTypes = TextType::all();
        return $this->successResponse($textTypes);
    } catch (QueryException $e) {
        report($e);
        return $this->errorResponse('获取文本分类失败', 500);
    } catch (\Exception $e) {
        report($e);
        return $this->errorResponse('服务器内部错误', 500);
    }
}

/**
 * 获取所有文本信息
 */
public function textList(Request $request): JsonResponse
{
    try {
        $query = TextInfo::query();

        // 关键词搜索
        if ($request->has('keyword') && $request->keyword != '') {
            $keyword = $request->keyword;
            $query->where(function($q) use ($keyword) {
                $q->where('TextContent', 'like', "%{$keyword}%")
                  ->orWhere('TextType', 'like', "%{$keyword}%");
            });
        }

        // 类型过滤
        if ($request->has('textType') && $request->textType != '') {
            $query->where('TextType', $request->textType);
        }

        $textInfos = $query->orderBy('created_at', 'desc')->get();
        return $this->successResponse($textInfos);
    } catch (QueryException $e) {
        report($e);
        return $this->errorResponse('获取文本信息失败', 500);
    } catch (\Exception $e) {
        report($e);
        return $this->errorResponse('服务器内部错误', 500);
    }
}

/**
 * 获取文本详情
 */
public function textDetail($id): JsonResponse
{
    try {
        $textInfo = TextInfo::findOrFail($id);
        return $this->successResponse($textInfo);
    } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
        return $this->errorResponse('文本不存在', 404);
    } catch (QueryException $e) {
        report($e);
        return $this->errorResponse('获取文本详情失败', 500);
    } catch (\Exception $e) {
        report($e);
        return $this->errorResponse('服务器内部错误', 500);
    }
}
```

#### 路由配置
文件: `routes/api.php`

```php
// 文本管理API路由
Route::prefix("/text")->group(function() {
    Route::get("/types", [FrontendAPI::class, "textType"]);
    Route::get("/list", [FrontendAPI::class, "textList"]);
    Route::get("/{id}", [FrontendAPI::class, "textDetail"]);
});
```

### 数据库表结构

#### text_types 表
```sql
CREATE TABLE text_types (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    type VARCHAR(255) NOT NULL COMMENT '类型名称',
    created_at TIMESTAMP NULL,
    updated_at TIMESTAMP NULL
);
```

#### text_infos 表
```sql
CREATE TABLE text_infos (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    TextType VARCHAR(100) NOT NULL COMMENT '文本类型',
    TextGroup VARCHAR(200) NULL COMMENT '文本分组',
    TextContent TEXT NULL COMMENT '文本内容(Markdown格式)',
    created_at TIMESTAMP NULL,
    updated_at TIMESTAMP NULL
);
```

---

## 💡 使用建议

### 1. Markdown 渲染

返回的 `TextContent` 字段包含 Markdown 格式文本，建议使用以下库进行渲染：

- **marked.js** - 轻量级、快速
- **markdown-it** - 可扩展性强
- **Showdown** - 兼容性好

### 2. 代码高亮

如果文本中包含代码块，建议配合 highlight.js 使用：

```javascript
import hljs from 'highlight.js';
import 'highlight.js/styles/github.css';

marked.setOptions({
    highlight: function(code, lang) {
        if (lang && hljs.getLanguage(lang)) {
            return hljs.highlight(code, { language: lang }).value;
        }
        return hljs.highlightAuto(code).value;
    }
});
```

### 3. 性能优化

- **缓存策略**: 文本类型列表变化频率低，建议前端缓存
- **懒加载**: 列表页只加载摘要，详情页再加载完整内容
- **搜索优化**: 大数据量时使用关键词和类型筛选

### 4. 错误处理

始终检查响应中的 `code` 字段：

```javascript
if (result.code === 200) {
    // 成功处理
} else if (result.code === 404) {
    // 资源不存在
} else {
    // 其他错误
    showError(result.message);
}
```

---

## 📊 响应码说明

| 响应码 | 说明 | 场景 |
|--------|------|------|
| 200 | 成功 | 请求成功，返回数据 |
| 404 | 未找到 | 文本 ID 不存在 |
| 500 | 服务器错误 | 数据库查询失败或其他异常 |

---

## 🔐 安全性说明

- 当前接口为**公开接口**，无需身份认证
- 如需限制访问，可在路由中添加 Sanctum 中间件：
  ```php
  Route::middleware('auth:sanctum')->group(function() {
      Route::get("/types", [FrontendAPI::class, "textType"]);
      // ...
  });
  ```

---

## 📝 更新日志

**v1.0.0** (2026-05-20)
- ✅ 新增文本类型列表接口
- ✅ 新增文本列表接口（支持搜索和筛选）
- ✅ 新增文本详情接口
- ✅ 完整的异常处理机制
- ✅ 统一的响应格式
