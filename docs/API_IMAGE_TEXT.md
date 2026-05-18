# 图文管理 API 接口文档

## 📋 基础信息

- **Base URL**: `/api/v1/image-text`
- **认证方式**: 无需认证（公开接口）
- **响应格式**: JSON
- **字符编码**: UTF-8
- **版本**: v1.0.0

---

## 📦 通用响应格式

### ✅ 成功响应
```json
{
    "code": 200,
    "message": "success",
    "data": {...}
}
```

### ❌ 错误响应
```json
{
    "code": 500,
    "message": "错误描述信息"
}
```

### 🔍 404 响应
```json
{
    "code": 404,
    "message": "资源不存在"
}
```

---

## 🔗 接口列表

### 1️⃣ 获取图片类型列表

**接口地址**: `GET /api/v1/image-text/types`

**接口描述**: 获取所有可用的图片分类类型

**请求参数**: 无

**响应示例**:
```json
{
    "code": 200,
    "message": "success",
    "data": [
        {
            "id": 1,
            "type": "安全须知",
            "created_at": "2026-05-18T12:00:00.000000Z",
            "updated_at": "2026-05-18T12:00:00.000000Z"
        },
        {
            "id": 2,
            "type": "维护公告",
            "created_at": "2026-05-18T13:00:00.000000Z",
            "updated_at": "2026-05-18T13:00:00.000000Z"
        }
    ]
}
```

**字段说明**:
| 字段名 | 类型 | 说明 |
|--------|------|------|
| id | integer | 类型ID |
| type | string | 类型名称 |
| created_at | string | 创建时间 (ISO 8601格式) |
| updated_at | string | 更新时间 (ISO 8601格式) |

---

### 2️⃣ 获取图片信息列表

**接口地址**: `GET /api/v1/image-text/list`

**接口描述**: 获取图片信息列表，支持关键词搜索和类型筛选

**请求参数**:
| 参数名 | 类型 | 必填 | 说明 |
|--------|------|------|------|
| keyword | string | 否 | 关键词搜索（按图片组名模糊匹配） |
| imageType | string | 否 | 图片类型筛选（精确匹配） |

**请求示例**:
```
GET /api/v1/image-text/list?keyword=电梯&imageType=安全须知
```

**响应示例**:
```json
{
    "code": 200,
    "message": "success",
    "data": [
        {
            "id": 1,
            "coverPath": "storage/images/电梯安全/1716024000_cover.jpg",
            "imagePath": "storage/images/电梯安全/1716024000_img1.jpg",
            "imageType": "安全须知",
            "imageGroup": "电梯安全",
            "description": "电梯安全知识宣传图片",
            "created_at": "2026-05-18T12:00:00.000000Z",
            "updated_at": "2026-05-18T12:00:00.000000Z"
        },
        {
            "id": 2,
            "coverPath": "storage/images/电梯安全/1716024000_cover.jpg",
            "imagePath": "storage/images/电梯安全/1716024001_img2.png",
            "imageType": "安全须知",
            "imageGroup": "电梯安全",
            "description": "电梯安全知识宣传图片",
            "created_at": "2026-05-18T12:01:00.000000Z",
            "updated_at": "2026-05-18T12:01:00.000000Z"
        }
    ]
}
```

**字段说明**:
| 字段名 | 类型 | 说明 |
|--------|------|------|
| id | integer | 图片ID |
| coverPath | string | 封面图片路径（相对路径，需拼接域名） |
| imagePath | string | 图片文件路径（相对路径，需拼接域名） |
| imageType | string | 图片类型 |
| imageGroup | string | 图片分组名称 |
| description | string | 图片描述（可为空） |
| created_at | string | 创建时间 (ISO 8601格式) |
| updated_at | string | 更新时间 (ISO 8601格式) |

**完整URL示例**:
```
https://your-domain.com/storage/images/电梯安全/1716024000_img1.jpg
```

---

### 3️⃣ 获取图片详情

**接口地址**: `GET /api/v1/image-text/{id}`

**接口描述**: 根据ID获取单个图片的详细信息

**路径参数**:
| 参数名 | 类型 | 必填 | 说明 |
|--------|------|------|------|
| id | integer | 是 | 图片ID |

**请求示例**:
```
GET /api/v1/image-text/1
```

**响应示例**:
```json
{
    "code": 200,
    "message": "success",
    "data": {
        "id": 1,
        "coverPath": "storage/images/电梯安全/1716024000_cover.jpg",
        "imagePath": "storage/images/电梯安全/1716024000_img1.jpg",
        "imageType": "安全须知",
        "imageGroup": "电梯安全",
        "description": "电梯安全知识宣传图片",
        "created_at": "2026-05-18T12:00:00.000000Z",
        "updated_at": "2026-05-18T12:00:00.000000Z"
    }
}
```

**错误响应示例** (图片不存在):
```json
{
    "code": 404,
    "message": "图片不存在"
}
```

---

## 💻 使用示例

### JavaScript (Fetch API)

```javascript
// 1. 获取图片类型列表
async function getImageTypes() {
    try {
        const response = await fetch('/api/v1/image-text/types');
        const result = await response.json();
        
        if (result.code === 200) {
            console.log('图片类型列表:', result.data);
            return result.data;
        } else {
            console.error('获取失败:', result.message);
        }
    } catch (error) {
        console.error('请求错误:', error);
    }
}

// 2. 获取图片列表（带筛选）
async function getImageList(keyword = '', imageType = '') {
    try {
        let url = '/api/v1/image-text/list';
        const params = new URLSearchParams();
        
        if (keyword) params.append('keyword', keyword);
        if (imageType) params.append('imageType', imageType);
        
        if (params.toString()) {
            url += '?' + params.toString();
        }
        
        const response = await fetch(url);
        const result = await response.json();
        
        if (result.code === 200) {
            console.log('图片列表:', result.data);
            return result.data;
        } else {
            console.error('获取失败:', result.message);
        }
    } catch (error) {
        console.error('请求错误:', error);
    }
}

// 3. 获取图片详情
async function getImageDetail(id) {
    try {
        const response = await fetch(`/api/v1/image-text/${id}`);
        const result = await response.json();
        
        if (result.code === 200) {
            console.log('图片详情:', result.data);
            return result.data;
        } else {
            console.error('获取失败:', result.message);
        }
    } catch (error) {
        console.error('请求错误:', error);
    }
}

// 使用示例
getImageTypes();
getImageList('电梯', '安全须知');
getImageDetail(1);
```

### Vue.js 示例

```vue
<template>
    <div class="image-gallery">
        <!-- 图片类型选择 -->
        <select v-model="selectedType" @change="loadImages">
            <option value="">全部类型</option>
            <option v-for="type in types" :key="type.id" :value="type.type">
                {{ type.type }}
            </option>
        </select>
        
        <!-- 搜索框 -->
        <input 
            v-model="keyword" 
            @input="debounceSearch"
            placeholder="搜索图片组名..."
        />
        
        <!-- 图片列表 -->
        <div class="image-grid">
            <div v-for="image in images" :key="image.id" class="image-card">
                <img :src="getFullUrl(image.imagePath)" :alt="image.description" />
                <h3>{{ image.imageGroup }}</h3>
                <p>{{ image.description }}</p>
            </div>
        </div>
    </div>
</template>

<script>
export default {
    name: 'ImageGallery',
    data() {
        return {
            types: [],
            images: [],
            selectedType: '',
            keyword: '',
            searchTimer: null
        };
    },
    
    mounted() {
        this.loadTypes();
        this.loadImages();
    },
    
    methods: {
        // 加载图片类型
        async loadTypes() {
            try {
                const response = await fetch('/api/v1/image-text/types');
                const result = await response.json();
                
                if (result.code === 200) {
                    this.types = result.data;
                }
            } catch (error) {
                console.error('加载类型失败:', error);
            }
        },
        
        // 加载图片列表
        async loadImages() {
            try {
                let url = '/api/v1/image-text/list';
                const params = new URLSearchParams();
                
                if (this.keyword) params.append('keyword', this.keyword);
                if (this.selectedType) params.append('imageType', this.selectedType);
                
                if (params.toString()) {
                    url += '?' + params.toString();
                }
                
                const response = await fetch(url);
                const result = await response.json();
                
                if (result.code === 200) {
                    this.images = result.data;
                }
            } catch (error) {
                console.error('加载图片失败:', error);
            }
        },
        
        // 防抖搜索
        debounceSearch() {
            clearTimeout(this.searchTimer);
            this.searchTimer = setTimeout(() => {
                this.loadImages();
            }, 300);
        },
        
        // 获取完整URL
        getFullUrl(path) {
            return window.location.origin + '/' + path;
        }
    }
};
</script>

<style scoped>
.image-gallery {
    padding: 20px;
}

.image-grid {
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(250px, 1fr));
    gap: 20px;
    margin-top: 20px;
}

.image-card {
    border: 1px solid #ddd;
    border-radius: 8px;
    overflow: hidden;
    transition: transform 0.3s;
}

.image-card:hover {
    transform: translateY(-5px);
    box-shadow: 0 4px 12px rgba(0,0,0,0.1);
}

.image-card img {
    width: 100%;
    height: 200px;
    object-fit: cover;
}

.image-card h3 {
    padding: 10px;
    margin: 0;
    font-size: 16px;
}

.image-card p {
    padding: 0 10px 10px;
    margin: 0;
    color: #666;
    font-size: 14px;
}
</style>
```

### React 示例

```jsx
import React, { useState, useEffect } from 'react';

function ImageGallery() {
    const [types, setTypes] = useState([]);
    const [images, setImages] = useState([]);
    const [selectedType, setSelectedType] = useState('');
    const [keyword, setKeyword] = useState('');
    
    // 加载图片类型
    useEffect(() => {
        fetch('/api/v1/image-text/types')
            .then(res => res.json())
            .then(result => {
                if (result.code === 200) {
                    setTypes(result.data);
                }
            });
    }, []);
    
    // 加载图片列表
    useEffect(() => {
        const params = new URLSearchParams();
        if (keyword) params.append('keyword', keyword);
        if (selectedType) params.append('imageType', selectedType);
        
        const queryString = params.toString();
        const url = '/api/v1/image-text/list' + (queryString ? '?' + queryString : '');
        
        fetch(url)
            .then(res => res.json())
            .then(result => {
                if (result.code === 200) {
                    setImages(result.data);
                }
            });
    }, [keyword, selectedType]);
    
    // 获取完整URL
    const getFullUrl = (path) => {
        return window.location.origin + '/' + path;
    };
    
    return (
        <div style={{ padding: '20px' }}>
            {/* 筛选器 */}
            <div style={{ marginBottom: '20px' }}>
                <select 
                    value={selectedType} 
                    onChange={(e) => setSelectedType(e.target.value)}
                    style={{ marginRight: '10px', padding: '8px' }}
                >
                    <option value="">全部类型</option>
                    {types.map(type => (
                        <option key={type.id} value={type.type}>
                            {type.type}
                        </option>
                    ))}
                </select>
                
                <input
                    type="text"
                    value={keyword}
                    onChange={(e) => setKeyword(e.target.value)}
                    placeholder="搜索图片组名..."
                    style={{ padding: '8px', width: '200px' }}
                />
            </div>
            
            {/* 图片列表 */}
            <div style={{ 
                display: 'grid', 
                gridTemplateColumns: 'repeat(auto-fill, minmax(250px, 1fr))',
                gap: '20px' 
            }}>
                {images.map(image => (
                    <div 
                        key={image.id} 
                        style={{ 
                            border: '1px solid #ddd',
                            borderRadius: '8px',
                            overflow: 'hidden'
                        }}
                    >
                        <img 
                            src={getFullUrl(image.imagePath)} 
                            alt={image.description}
                            style={{ width: '100%', height: '200px', objectFit: 'cover' }}
                        />
                        <h3 style={{ padding: '10px', margin: 0 }}>{image.imageGroup}</h3>
                        <p style={{ padding: '0 10px 10px', margin: 0, color: '#666' }}>
                            {image.description}
                        </p>
                    </div>
                ))}
            </div>
        </div>
    );
}

export default ImageGallery;
```

---

## ⚠️ 注意事项

### 1. 🖼️ 图片路径处理
- API返回的路径是相对路径（如：`storage/images/xxx.jpg`）
- 前端需要拼接完整的域名才能访问
- 完整URL格式：`https://your-domain.com/storage/images/xxx.jpg`

### 2. 🔍 搜索功能
- 关键词搜索仅支持按 `imageGroup`（图片组名）进行模糊匹配
- 不支持按描述或其他字段搜索

### 3. 🏷️ 筛选功能
- 类型筛选是精确匹配
- 可以同时使用关键词和类型筛选

### 4. 📊 排序规则
- 所有列表默认按 `created_at` 降序排列（最新的在前）

### 5. 🛡️ 错误处理
- 所有接口都包含统一的异常处理
- 网络错误、数据库错误都会返回相应的错误码和消息
- 建议前端做好错误处理和用户提示

### 6. ⚡ 性能优化建议
- 列表数据量较大时，建议前端实现分页或虚拟滚动
- 可以使用防抖（debounce）优化搜索体验
- 图片加载建议使用懒加载（lazy loading）

---

## ❓ 常见问题

### Q1: 如何显示图片？
**A**: 需要将API返回的相对路径转换为完整URL：
```javascript
const fullUrl = window.location.origin + '/' + imagePath;
// 例如: https://example.com/storage/images/电梯安全/img1.jpg
```

### Q2: 如何实现搜索功能？
**A**: 在URL中添加查询参数：
```
/api/v1/image-text/list?keyword=电梯
```

### Q3: 如何同时使用多个筛选条件？
**A**: 可以组合多个查询参数：
```
/api/v1/image-text/list?keyword=电梯&imageType=安全须知
```

### Q4: 返回的数据是否包含分页信息？
**A**: 当前版本不分页，返回所有匹配记录。如需分页，可在后续版本中扩展。

### Q5: 图片路径中的 storage 是什么？
**A**: 这是Laravel的公共存储目录，需要通过 `php artisan storage:link` 创建符号链接后才能正常访问。

### Q6: 如何处理图片加载失败？
**A**: 建议添加图片加载失败的占位图：
```javascript
<img 
    src={getFullUrl(image.imagePath)} 
    alt={image.description}
    onError={(e) => {
        e.target.src = '/images/placeholder.png';
    }}
/>
```

---

## 📝 更新日志

### v1.0.0 (2026-05-18)
- ✨ 初始版本发布
- ✨ 支持获取图片类型列表
- ✨ 支持获取图片信息列表（含搜索和筛选）
- ✨ 支持获取图片详情
- ✨ 统一的JSON响应格式
- ✨ 完整的异常处理机制

---

## 📞 技术支持

如有问题或建议，请联系开发团队。

**文档最后更新时间**: 2026-05-18
