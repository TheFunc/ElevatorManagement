# ElevatorManagement API 文档

## 基础信息

- **API 版本**: v1
- **基础 URL**: `https://api.iamhc.cn/api/v1`
- **数据格式**: JSON
- **字符编码**: UTF-8

## 公共响应格式

### 成功响应
```json
{
  "code": 200,
  "message": "success",
  "data": {}
}
```

### 错误响应
```json
{
  "code": 500,
  "message": "错误描述",
  "errors": {} // 可选，包含详细错误信息
}
```

### 404 响应
```json
{
  "code": 404,
  "message": "资源不存在"
}
```

## 认证说明

当前API版本（v1）暂未启用认证机制，所有接口均可直接访问。

## API 接口列表

---

## 1. 获取视频分类列表

### 接口信息
- **URL**: `/video/types`
- **Method**: `GET`
- **认证**: 无需认证

### 请求参数
无

### 响应示例

#### 成功响应 (200)
```json
{
  "code": 200,
  "message": "success",
  "data": [
    {
      "id": 1,
      "type": "教学视频",
      "created_at": "2026-04-14 10:30:00",
      "updated_at": "2026-04-14 10:30:00"
    },
    {
      "id": 2,
      "type": "安全演示",
      "created_at": "2026-04-15 14:20:00",
      "updated_at": "2026-04-15 14:20:00"
    }
  ]
}
```

#### 错误响应 (500)
```json
{
  "code": 500,
  "message": "获取视频分类失败"
}
```

### 数据字段说明

| 字段名 | 类型 | 说明 |
|--------|------|------|
| id | integer | 视频分类ID |
| type | string | 视频分类名称 |
| created_at | datetime | 创建时间 |
| updated_at | datetime | 更新时间 |

---

## 2. 获取视频信息列表

### 接口信息
- **URL**: `/video/list`
- **Method**: `GET`
- **认证**: 无需认证

### 请求参数
无

### 响应示例

#### 成功响应 (200)
```json
{
  "code": 200,
  "message": "success",
  "data": [
    {
      "id": 1,
      "coverPath": "/uploads/videos/cover1.jpg",
      "videoPath": "/uploads/videos/video1.mp4",
      "videoType": "教学视频",
      "videoGroup": "电梯操作",
      "description": "电梯日常操作演示视频",
      "created_at": "2026-04-14 10:30:00",
      "updated_at": "2026-04-14 10:30:00"
    },
    {
      "id": 2,
      "coverPath": "/uploads/videos/cover2.jpg",
      "videoPath": "/uploads/videos/video2.mp4",
      "videoType": "安全演示",
      "videoGroup": "应急处理",
      "description": "电梯故障应急处理演示",
      "created_at": "2026-04-15 14:20:00",
      "updated_at": "2026-04-15 14:20:00"
    }
  ]
}
```

#### 错误响应 (500)
```json
{
  "code": 500,
  "message": "获取视频信息失败"
}
```

### 数据字段说明

| 字段名 | 类型 | 说明 |
|--------|------|------|
| id | integer | 视频ID |
| coverPath | string | 视频封面路径 |
| videoPath | string | 视频文件路径 |
| videoType | string | 视频分类名称 |
| videoGroup | string | 视频分组名称 |
| description | string | 视频描述 |
| created_at | datetime | 创建时间 |
| updated_at | datetime | 更新时间 |

---

## 3. 获取图片类型列表

### 接口信息
- **URL**: `/api/v1/image-text/types`
- **Method**: `GET`
- **认证**: 无需认证

### 请求参数
无

### 响应示例

#### 成功响应 (200)
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

### 数据字段说明

| 字段名 | 类型 | 说明 |
|--------|------|------|
| id | integer | 类型ID |
| type | string | 类型名称 |
| created_at | string | 创建时间 (ISO 8601格式) |
| updated_at | string | 更新时间 (ISO 8601格式) |

---

## 4. 获取图片信息列表

### 接口信息
- **URL**: `/api/v1/image-text/list`
- **Method**: `GET`
- **认证**: 无需认证

### 请求参数

| 参数名 | 类型 | 必填 | 说明 |
|--------|------|------|------|
| keyword | string | 否 | 关键词搜索（按图片组名模糊匹配） |
| imageType | string | 否 | 图片类型筛选（精确匹配） |

### 请求示例
```
GET /api/v1/image-text/list?keyword=电梯&imageType=安全须知
```

### 响应示例

#### 成功响应 (200)
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
        }
    ]
}
```

### 数据字段说明

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

---

## 5. 获取图片详情

### 接口信息
- **URL**: `/api/v1/image-text/{id}`
- **Method**: `GET`
- **认证**: 无需认证

### 路径参数

| 参数名 | 类型 | 必填 | 说明 |
|--------|------|------|------|
| id | integer | 是 | 图片ID |

### 请求示例
```
GET /api/v1/image-text/1
```

### 响应示例

#### 成功响应 (200)
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

#### 错误响应 (404)
```json
{
    "code": 404,
    "message": "图片不存在"
}
```

---

## 错误码说明

| 错误码 | 说明 |
|--------|------|
| 200 | 请求成功 |
| 404 | 资源不存在 |
| 500 | 服务器内部错误 |

---

## 版本历史

### v1 (当前版本)
- 2026-04-14: 初始版本发布
  - 获取视频分类列表接口
  - 获取视频信息列表接口
- 2026-05-18: 新增图文管理接口
  - 获取图片类型列表接口
  - 获取图片信息列表接口
  - 获取图片详情接口

---

## 注意事项

1. 所有时间字段格式为 `YYYY-MM-DD HH:mm:ss` 或 ISO 8601 格式
2. 路径字段（如 `coverPath`、`videoPath`、`imagePath`）为相对路径，需拼接基础URL才能访问完整路径
3. 视频分类和视频信息通过 `videoType` 字段关联
4. 当前API版本为预发布版本，后续可能会有较大调整

---

## 联系方式

如有问题或建议，请联系开发团队。

**官方网站**: https://www.hcnsec.cn/
**API支持**: https://api.iamhc.cn/

---

/*
 * 本代码由新疆幻城网安公益大模型API中转站提供API支持
 * 访问地址：https://api.iamhc.cn/
 */

/*
 * Copyright (c) 2026 新疆幻城网安科技有限责任公司
 * All rights reserved.
 * 官方网站：https://www.hcnsec.cn/
 */