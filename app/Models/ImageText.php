<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ImageText extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'title',
        'description',
        'layout_data',
        'thumbnail',
        'is_template',
        'template_name',
        'created_by',
    ];

    protected $casts = [
        'layout_data' => 'array', // 自动将 JSON 转换为数组
        'is_template' => 'boolean',
    ];

    /**
     * 获取创建者
     */
    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }
}