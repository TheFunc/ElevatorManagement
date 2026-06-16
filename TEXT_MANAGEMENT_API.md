# 鏂囨湰绠＄悊 API 鎺ュ彛鏂囨。锛堟柊澧烇級

## 馃搶 姒傝堪

鏈枃妗ｆ弿杩颁簡鐢垫绠＄悊绯荤粺涓?*鏂板鐨勬枃鏈鐞?API 鎺ュ彛**锛岀敤浜庣鐞嗗拰鑾峰彇 Markdown 鏍煎紡鐨勬枃鏈唴瀹广€傝繖浜涙帴鍙ｅ熀浜?Laravel 妗嗘灦寮€鍙戯紝閬靛惊 RESTful 璁捐瑙勮寖銆?
### 鍩虹淇℃伅

- **Base URL**: `/api/v1/text`
- **璁よ瘉鏂瑰紡**: 鏃犻渶璁よ瘉锛堝叕寮€鎺ュ彛锛?- **鍝嶅簲鏍煎紡**: JSON
- **瀛楃缂栫爜**: UTF-8
- **HTTP 鏂规硶**: GET

---

## 馃搵 鎺ュ彛鍒楄〃

### 1. 鑾峰彇鏂囨湰绫诲瀷鍒楄〃

#### 鎺ュ彛淇℃伅
- **URL**: `GET /api/v1/text/types`
- **鍔熻兘**: 鑾峰彇鎵€鏈夊彲鐢ㄧ殑鏂囨湰绫诲瀷鍒嗙被
- **鏉冮檺**: 鍏紑璁块棶

#### 璇锋眰鍙傛暟
鏃?
#### 鍝嶅簲绀轰緥

**鎴愬姛鍝嶅簲 (200)**:
```json
{
    "code": 200,
    "message": "success",
    "data": [
        {
            "id": 1,
            "type": "瀹夊叏椤荤煡",
            "created_at": "2026-05-20T10:30:00.000000Z",
            "updated_at": "2026-05-20T10:30:00.000000Z"
        },
        {
            "id": 2,
            "type": "鎿嶄綔鎸囧崡",
            "created_at": "2026-05-20T11:00:00.000000Z",
            "updated_at": "2026-05-20T11:00:00.000000Z"
        },
        {
            "id": 3,
            "type": "缁存姢鍏憡",
            "created_at": "2026-05-20T12:00:00.000000Z",
            "updated_at": "2026-05-20T12:00:00.000000Z"
        }
    ]
}
```

**閿欒鍝嶅簲 (500)**:
```json
{
    "code": 500,
    "message": "鑾峰彇鏂囨湰鍒嗙被澶辫触"
}
```

#### 鍓嶇璋冪敤绀轰緥

```javascript
// 鏂瑰紡涓€锛欶etch API
async function getTextTypes() {
    try {
        const response = await fetch('/api/v1/text/types');
        const result = await response.json();
        
        if (result.code === 200) {
            console.log('鏂囨湰绫诲瀷鍒楄〃:', result.data);
            return result.data;
        } else {
            console.error('鑾峰彇澶辫触:', result.message);
            return [];
        }
    } catch (error) {
        console.error('缃戠粶閿欒:', error);
        return [];
    }
}

// 鏂瑰紡浜岋細Axios
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
        console.error('鑾峰彇鏂囨湰绫诲瀷澶辫触:', error.message);
        return [];
    }
}

// 浣跨敤绀轰緥
const types = await getTextTypes();
types.forEach(type => {
    console.log(`${type.id}: ${type.type}`);
});
```

---

### 2. 鑾峰彇鏂囨湰鍒楄〃

#### 鎺ュ彛淇℃伅
- **URL**: `GET /api/v1/text/list`
- **鍔熻兘**: 鑾峰彇鏂囨湰淇℃伅鍒楄〃锛屾敮鎸佸叧閿瘝鎼滅储鍜岀被鍨嬬瓫閫?- **鏉冮檺**: 鍏紑璁块棶

#### 璇锋眰鍙傛暟

| 鍙傛暟鍚?| 绫诲瀷 | 蹇呭～ | 璇存槑 | 绀轰緥 |
|--------|------|------|------|------|
| keyword | string | 鍚?| 鎼滅储鍏抽敭璇嶏紙鍖归厤鏂囨湰鍐呭鎴栫被鍨嬪悕绉帮級 | `keyword=瀹夊叏` |
| textType | string | 鍚?| 鏂囨湰绫诲瀷绛涢€?| `textType=瀹夊叏椤荤煡` |

#### 璇锋眰绀轰緥

```bash
# 鑾峰彇鎵€鏈夋枃鏈?GET /api/v1/text/list

# 鎸夊叧閿瘝鎼滅储
GET /api/v1/text/list?keyword=瀹夊叏

# 鎸夌被鍨嬬瓫閫?GET /api/v1/text/list?textType=瀹夊叏椤荤煡

# 缁勫悎鏌ヨ
GET /api/v1/text/list?keyword=鐢垫&textType=鎿嶄綔鎸囧崡
```

#### 鍝嶅簲绀轰緥

**鎴愬姛鍝嶅簲 (200)**:
```json
{
    "code": 200,
    "message": "success",
    "data": [
        {
            "id": 1,
            "TextType": "瀹夊叏椤荤煡",
            "TextGroup": null,
            "TextContent": "# 鐢垫瀹夊叏椤荤煡\n\n## 涔樺潗鍓嶆鏌n- 纭鐢垫姝ｅ父杩愯\n- 娉ㄦ剰瑙傚療妤煎眰鏄剧ず\n\n## 涔樺潗鏃舵敞鎰忎簨椤筡n1. 涓嶈鍊氶潬杞块棬\n2. 涓嶈鍦ㄧ數姊唴璺宠穬\n3. 濡傞亣鏁呴殰淇濇寔鍐烽潤\n\n## 绱ф€ユ儏鍐靛鐞哱n- 鎸変笅绱ф€ュ懠鍙寜閽甛n- 绛夊緟鏁戞彺浜哄憳\n- 涓嶈寮鸿鎵掗棬",
            "created_at": "2026-05-20T10:30:00.000000Z",
            "updated_at": "2026-05-20T10:30:00.000000Z"
        },
        {
            "id": 2,
            "TextType": "鎿嶄綔鎸囧崡",
            "TextGroup": null,
            "TextContent": "# 鐢垫鎿嶄綔鎸囧崡\n\n## 鍩烘湰鎿嶄綔\n- 鎸変笅涓婅/涓嬭鎸夐挳\n- 閫夋嫨鐩爣妤煎眰\n- 绛夊緟鐢垫鍒拌揪\n\n## 鐗规畩鍔熻兘\n- 寮€闂ㄤ繚鎸侊細闀挎寜寮€闂ㄩ敭\n- 鍏抽棬鍔犻€燂細鍙屽嚮鍏抽棬閿甛n- 绱ф€ュ仠姝細绾㈣壊鎬ュ仠鎸夐挳",
            "created_at": "2026-05-20T11:00:00.000000Z",
            "updated_at": "2026-05-20T11:00:00.000000Z"
        }
    ]
}
```

**绌烘暟鎹搷搴?(200)**:
```json
{
    "code": 200,
    "message": "success",
    "data": []
}
```

**閿欒鍝嶅簲 (500)**:
```json
{
    "code": 500,
    "message": "鑾峰彇鏂囨湰淇℃伅澶辫触"
}
```

#### 鍓嶇璋冪敤绀轰緥

```javascript
// 鏂瑰紡涓€锛氳幏鍙栨墍鏈夋枃鏈?async function getTextList() {
    try {
        const response = await fetch('/api/v1/text/list');
        const result = await response.json();
        
        if (result.code === 200) {
            return result.data;
        }
        return [];
    } catch (error) {
        console.error('鑾峰彇鏂囨湰鍒楄〃澶辫触:', error);
        return [];
    }
}

// 鏂瑰紡浜岋細甯︽悳绱㈡潯浠?async function searchTexts(keyword = '', textType = '') {
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
        console.error('鎼滅储澶辫触:', error);
        return [];
    }
}

// 鏂瑰紡涓夛細Vue + Axios 瀹屾暣绀轰緥
<template>
    <div class="text-list-page">
        <!-- 鎼滅储鏍?-->
        <div class="search-bar">
            <input 
                v-model="searchForm.keyword" 
                placeholder="鎼滅储鏂囨湰鍐呭..."
                @keyup.enter="loadTexts"
            />
            <select v-model="searchForm.textType">
                <option value="">鍏ㄩ儴绫诲瀷</option>
                <option 
                    v-for="type in textTypes" 
                    :key="type.id" 
                    :value="type.type"
                >
                    {{ type.type }}
                </option>
            </select>
            <button @click="loadTexts">
                <i class="ri-search-line"></i> 鎼滅储
            </button>
            <button @click="resetSearch">閲嶇疆</button>
        </div>

        <!-- 鏂囨湰鍒楄〃 -->
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

        <!-- 绌虹姸鎬?-->
        <div v-if="textList.length === 0 && !loading" class="empty-state">
            <i class="ri-inbox-line"></i>
            <p>鏆傛棤鏂囨湰鏁版嵁</p>
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

// 鍔犺浇鏂囨湰绫诲瀷
async function loadTypes() {
    try {
        const response = await axios.get('/api/v1/text/types');
        if (response.data.code === 200) {
            textTypes.value = response.data.data;
        }
    } catch (error) {
        console.error('鍔犺浇绫诲瀷澶辫触:', error);
    }
}

// 鍔犺浇鏂囨湰鍒楄〃
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
        console.error('鍔犺浇鍒楄〃澶辫触:', error);
    } finally {
        loading.value = false;
    }
}

// 閲嶇疆鎼滅储
function resetSearch() {
    searchForm.value = {
        keyword: '',
        textType: ''
    };
    loadTexts();
}

// 鏌ョ湅璇︽儏
function viewDetail(id) {
    // 璺宠浆鍒拌鎯呴〉鎴栨墦寮€寮圭獥
    window.location.href = `/text-management/detail/${id}`;
}

// 宸ュ叿鍑芥暟
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

### 3. 鑾峰彇鏂囨湰璇︽儏

#### 鎺ュ彛淇℃伅
- **URL**: `GET /api/v1/text/{id}`
- **鍔熻兘**: 鏍规嵁 ID 鑾峰彇鍗曚釜鏂囨湰鐨勮缁嗕俊鎭?- **鏉冮檺**: 鍏紑璁块棶

#### 璺緞鍙傛暟

| 鍙傛暟鍚?| 绫诲瀷 | 蹇呭～ | 璇存槑 | 绀轰緥 |
|--------|------|------|------|------|
| id | integer | 鏄?| 鏂囨湰 ID | `/api/v1/text/1` |

#### 璇锋眰绀轰緥

```bash
GET /api/v1/text/1
GET /api/v1/text/42
```

#### 鍝嶅簲绀轰緥

**鎴愬姛鍝嶅簲 (200)**:
```json
{
    "code": 200,
    "message": "success",
    "data": {
        "id": 1,
        "TextType": "瀹夊叏椤荤煡",
        "TextGroup": null,
        "TextContent": "# 鐢垫瀹夊叏椤荤煡\n\n## 涔樺潗鍓嶆鏌n- 纭鐢垫姝ｅ父杩愯\n- 娉ㄦ剰瑙傚療妤煎眰鏄剧ず\n\n## 涔樺潗鏃舵敞鎰忎簨椤筡n1. 涓嶈鍊氶潬杞块棬\n2. 涓嶈鍦ㄧ數姊唴璺宠穬\n3. 濡傞亣鏁呴殰淇濇寔鍐烽潤\n\n## 绱ф€ユ儏鍐靛鐞哱n- 鎸変笅绱ф€ュ懠鍙寜閽甛n- 绛夊緟鏁戞彺浜哄憳\n- 涓嶈寮鸿鎵掗棬\n\n## 绂佹琛屼负\n- 鉂?瓒呰浇杩愯\n- 鉂?寮鸿鎵掗棬\n- 鉂?鍦ㄧ數姊唴鍚哥儫\n- 鉂?鎼哄甫鏄撶噧鏄撶垎鐗╁搧",
        "created_at": "2026-05-20T10:30:00.000000Z",
        "updated_at": "2026-05-20T10:30:00.000000Z"
    }
}
```

**閿欒鍝嶅簲 - 鏂囨湰涓嶅瓨鍦?(404)**:
```json
{
    "code": 404,
    "message": "鏂囨湰涓嶅瓨鍦?
}
```

**閿欒鍝嶅簲 - 鏈嶅姟鍣ㄩ敊璇?(500)**:
```json
{
    "code": 500,
    "message": "鑾峰彇鏂囨湰璇︽儏澶辫触"
}
```

#### 鍓嶇璋冪敤绀轰緥

```javascript
// 鏂瑰紡涓€锛欶etch API
async function getTextDetail(id) {
    try {
        const response = await fetch(`/api/v1/text/${id}`);
        const result = await response.json();
        
        if (result.code === 200) {
            return result.data;
        } else if (result.code === 404) {
            console.error('鏂囨湰涓嶅瓨鍦?);
            return null;
        } else {
            console.error('鑾峰彇澶辫触:', result.message);
            return null;
        }
    } catch (error) {
        console.error('缃戠粶閿欒:', error);
        return null;
    }
}

// 鏂瑰紡浜岋細React + Axios 瀹屾暣绀轰緥
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
                setError('鍔犺浇澶辫触锛岃绋嶅悗閲嶈瘯');
            } finally {
                setLoading(false);
            }
        }

        fetchTextDetail();
    }, [textId]);

    // 閰嶇疆 marked.js
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
                <p>鍔犺浇涓?..</p>
            </div>
        );
    }

    if (error || !text) {
        return (
            <div className="error-state">
                <i className="ri-error-warning-line"></i>
                <p>{error || '鏈壘鍒版枃鏈?}</p>
                <button onClick={() => window.history.back()}>杩斿洖</button>
            </div>
        );
    }

    const renderedContent = marked.parse(text.TextContent);

    return (
        <div className="text-detail-page">
            {/* 澶撮儴淇℃伅 */}
            <header className="detail-header">
                <div className="type-badge">{text.TextType}</div>
                <h1>{getFirstLine(text.TextContent)}</h1>
                <div className="meta-info">
                    <span>鍒涘缓鏃堕棿: {formatDate(text.created_at)}</span>
                    <span>鏇存柊鏃堕棿: {formatDate(text.updated_at)}</span>
                </div>
            </header>

            {/* Markdown 鍐呭 */}
            <article 
                className="markdown-content"
                dangerouslySetInnerHTML={{ __html: renderedContent }}
            />

            {/* 鎿嶄綔鎸夐挳 */}
            <footer className="detail-footer">
                <button onClick={() => window.history.back()}>
                    <i className="ri-arrow-left-line"></i> 杩斿洖
                </button>
                <button onClick={() => window.print()}>
                    <i className="ri-printer-line"></i> 鎵撳嵃
                </button>
            </footer>
        </div>
    );
}

// 宸ュ叿鍑芥暟
function getFirstLine(content) {
    if (!content) return '鏃犳爣棰?;
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

## 馃敡 鎶€鏈疄鐜扮粏鑺?
### 鍚庣瀹炵幇

#### 鎺у埗鍣ㄦ柟娉曚綅缃?鏂囦欢: `app/Http/Controllers/FrontendAPI.php`

```php
/**
 * 鑾峰彇鎵€鏈夋枃鏈被鍨? */
public function textType(Request $request): JsonResponse
{
    try {
        $textTypes = TextType::all();
        return $this->successResponse($textTypes);
    } catch (QueryException $e) {
        report($e);
        return $this->errorResponse('鑾峰彇鏂囨湰鍒嗙被澶辫触', 500);
    } catch (\Exception $e) {
        report($e);
        return $this->errorResponse('鏈嶅姟鍣ㄥ唴閮ㄩ敊璇?, 500);
    }
}

/**
 * 鑾峰彇鎵€鏈夋枃鏈俊鎭? */
public function textList(Request $request): JsonResponse
{
    try {
        $query = TextInfo::query();

        // 鍏抽敭璇嶆悳绱?        if ($request->has('keyword') && $request->keyword != '') {
            $keyword = $request->keyword;
            $query->where(function($q) use ($keyword) {
                $q->where('TextContent', 'like', "%{$keyword}%")
                  ->orWhere('TextType', 'like', "%{$keyword}%");
            });
        }

        // 绫诲瀷杩囨护
        if ($request->has('textType') && $request->textType != '') {
            $query->where('TextType', $request->textType);
        }

        $textInfos = $query->orderBy('created_at', 'desc')->get();
        return $this->successResponse($textInfos);
    } catch (QueryException $e) {
        report($e);
        return $this->errorResponse('鑾峰彇鏂囨湰淇℃伅澶辫触', 500);
    } catch (\Exception $e) {
        report($e);
        return $this->errorResponse('鏈嶅姟鍣ㄥ唴閮ㄩ敊璇?, 500);
    }
}

/**
 * 鑾峰彇鏂囨湰璇︽儏
 */
public function textDetail($id): JsonResponse
{
    try {
        $textInfo = TextInfo::findOrFail($id);
        return $this->successResponse($textInfo);
    } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
        return $this->errorResponse('鏂囨湰涓嶅瓨鍦?, 404);
    } catch (QueryException $e) {
        report($e);
        return $this->errorResponse('鑾峰彇鏂囨湰璇︽儏澶辫触', 500);
    } catch (\Exception $e) {
        report($e);
        return $this->errorResponse('鏈嶅姟鍣ㄥ唴閮ㄩ敊璇?, 500);
    }
}
```

#### 璺敱閰嶇疆
鏂囦欢: `routes/api.php`

```php
// 鏂囨湰绠＄悊API璺敱
Route::prefix("/text")->group(function() {
    Route::get("/types", [FrontendAPI::class, "textType"]);
    Route::get("/list", [FrontendAPI::class, "textList"]);
    Route::get("/{id}", [FrontendAPI::class, "textDetail"]);
});
```

### 鏁版嵁搴撹〃缁撴瀯

#### text_types 琛?```sql
CREATE TABLE text_types (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    type VARCHAR(255) NOT NULL COMMENT '绫诲瀷鍚嶇О',
    created_at TIMESTAMP NULL,
    updated_at TIMESTAMP NULL
);
```

#### text_infos 琛?```sql
CREATE TABLE text_infos (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    TextType VARCHAR(100) NOT NULL COMMENT '鏂囨湰绫诲瀷',
    TextGroup VARCHAR(200) NULL COMMENT '鏂囨湰鍒嗙粍',
    TextContent TEXT NULL COMMENT '鏂囨湰鍐呭(Markdown鏍煎紡)',
    created_at TIMESTAMP NULL,
    updated_at TIMESTAMP NULL
);
```

---

## 馃挕 浣跨敤寤鸿

### 1. Markdown 娓叉煋

杩斿洖鐨?`TextContent` 瀛楁鍖呭惈 Markdown 鏍煎紡鏂囨湰锛屽缓璁娇鐢ㄤ互涓嬪簱杩涜娓叉煋锛?
- **marked.js** - 杞婚噺绾с€佸揩閫?- **markdown-it** - 鍙墿灞曟€у己
- **Showdown** - 鍏煎鎬уソ

### 2. 浠ｇ爜楂樹寒

濡傛灉鏂囨湰涓寘鍚唬鐮佸潡锛屽缓璁厤鍚?highlight.js 浣跨敤锛?
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

### 3. 鎬ц兘浼樺寲

- **缂撳瓨绛栫暐**: 鏂囨湰绫诲瀷鍒楄〃鍙樺寲棰戠巼浣庯紝寤鸿鍓嶇缂撳瓨
- **鎳掑姞杞?*: 鍒楄〃椤靛彧鍔犺浇鎽樿锛岃鎯呴〉鍐嶅姞杞藉畬鏁村唴瀹?- **鎼滅储浼樺寲**: 澶ф暟鎹噺鏃朵娇鐢ㄥ叧閿瘝鍜岀被鍨嬬瓫閫?
### 4. 閿欒澶勭悊

濮嬬粓妫€鏌ュ搷搴斾腑鐨?`code` 瀛楁锛?
```javascript
if (result.code === 200) {
    // 鎴愬姛澶勭悊
} else if (result.code === 404) {
    // 璧勬簮涓嶅瓨鍦?} else {
    // 鍏朵粬閿欒
    showError(result.message);
}
```

---

## 馃搳 鍝嶅簲鐮佽鏄?
| 鍝嶅簲鐮?| 璇存槑 | 鍦烘櫙 |
|--------|------|------|
| 200 | 鎴愬姛 | 璇锋眰鎴愬姛锛岃繑鍥炴暟鎹?|
| 404 | 鏈壘鍒?| 鏂囨湰 ID 涓嶅瓨鍦?|
| 500 | 鏈嶅姟鍣ㄩ敊璇?| 鏁版嵁搴撴煡璇㈠け璐ユ垨鍏朵粬寮傚父 |

---

## 馃攼 瀹夊叏鎬ц鏄?
- 褰撳墠鎺ュ彛涓?*鍏紑鎺ュ彛**锛屾棤闇€韬唤璁よ瘉
- 濡傞渶闄愬埗璁块棶锛屽彲鍦ㄨ矾鐢变腑娣诲姞 Sanctum 涓棿浠讹細
  ```php
  Route::middleware('auth:sanctum')->group(function() {
      Route::get("/types", [FrontendAPI::class, "textType"]);
      // ...
  });
  ```

---

## 馃摑 鏇存柊鏃ュ織

**v1.0.0** (2026-05-20)
- 鉁?鏂板鏂囨湰绫诲瀷鍒楄〃鎺ュ彛
- 鉁?鏂板鏂囨湰鍒楄〃鎺ュ彛锛堟敮鎸佹悳绱㈠拰绛涢€夛級
- 鉁?鏂板鏂囨湰璇︽儏鎺ュ彛
- 鉁?瀹屾暣鐨勫紓甯稿鐞嗘満鍒?- 鉁?缁熶竴鐨勫搷搴旀牸寮?
