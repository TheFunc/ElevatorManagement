# 视频接口 API 文档 (v1)

## 基础信息

| 项 | 值 |
|----|----|
| 基础路径 | `/api/v1` |
| 请求格式 | `application/json` |
| 响应格式 | `application/json` |
| 限流规则 | 每分钟 60 次请求 |

---

## 统一响应格式

### ✅ 成功响应
```json
{
  "code": 200,
  "message": "success",
  "data": []
}
```

### ❌ 错误响应
```json
{
  "code": 500,
  "message": "错误描述信息"
}
```

### 状态码说明
| 状态码 | 说明 |
|--------|------|
| 200 | 请求成功 |
| 429 | 请求过于频繁 |
| 500 | 服务器内部错误 |

---

## 1. 视频分类列表

### 接口信息
| 项 | 值 |
|----|----|
| 接口地址 | `GET /api/v1/video/types` |
| 本地开发地址 | `http://127.0.0.1:8000/api/v1/video/types` |
| 接口功能 | 获取所有视频分类 |

### 请求参数
无参数，直接调用即可返回全部数据

### 响应示例
```json
{
  "code": 200,
  "message": "success",
  "data": [
    {
      "id": 1,
      "type": "电梯安全",
      "created_at": "2026-04-10T12:00:00Z",
      "updated_at": "2026-04-10T12:00:00Z"
    }
  ]
}
```

---

## 2. 视频列表

### 接口信息
| 项 | 值 |
|----|----|
| 接口地址 | `GET /api/v1/video/list` |
| 本地开发地址 | `http://127.0.0.1:8000/api/v1/video/list` |
| 接口功能 | 获取所有视频，按创建时间倒序排列 |

### 请求参数
无参数，直接调用即可返回全部数据

### 响应示例
```json
{
  "code": 200,
  "message": "success",
  "data": [
    {
      "id": 1,
      "coverPath": "/storage/covers/xxx.jpg",
      "videoPath": "/storage/videos/xxx.mp4",
      "videoType": "电梯安全",
      "videoGroup": "安全培训",
      "description": "电梯日常安全检查操作流程",
      "created_at": "2026-04-10T12:00:00Z",
      "updated_at": "2026-04-10T12:00:00Z"
    }
  ]
}
```

---

## 前端调用示例 (JavaScript)

```javascript
// 获取所有视频分类
async function getVideoTypes() {
  const response = await fetch('http://127.0.0.1:8000/api/v1/video/types');
  return await response.json();
}

// 获取所有视频列表
async function getVideoList() {
  const response = await fetch('http://127.0.0.1:8000/api/v1/video/list');
  return await response.json();
}

// 调用示例
getVideoTypes().then(result => {
  if (result.code === 200) {
    console.log('视频分类列表:', result.data);
  }
});

getVideoList().then(result => {
  if (result.code === 200) {
    console.log('视频列表:', result.data);
  }
});
```

---

## 接口完整地址列表

| 接口名称 | 请求方法 | 完整请求地址 |
|----------|----------|--------------|
| 视频分类列表 | GET | `http://127.0.0.1:8000/api/v1/video/types` |
| 视频列表 | GET | `http://127.0.0.1:8000/api/v1/video/list` |

> 💡 Laravel 本地开发默认端口 8000，实际部署时请替换为正式域名和端口

## 注意事项

1. 所有接口均支持跨域请求
2. 接口返回时间均为 UTC 时间，前端需要根据时区转换
3. 生产环境请使用 HTTPS 协议访问