<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\ImageText;

class ImageTextSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // 创建示例模板
        ImageText::create([
            'title' => '电梯安全须知模板',
            'description' => '用于展示电梯安全注意事项的标准模板',
            'layout_data' => [
                'canvasWidth' => 1200,
                'canvasHeight' => 800,
                'elements' => [
                    [
                        'type' => 'i-text',
                        'text' => '电梯安全使用须知',
                        'left' => 400,
                        'top' => 50,
                        'width' => 400,
                        'height' => 50,
                        'fontSize' => 36,
                        'fill' => '#1a73e8',
                        'fontFamily' => 'Microsoft YaHei',
                        'angle' => 0
                    ],
                    [
                        'type' => 'i-text',
                        'text' => '1. 请勿在电梯内跳跃',
                        'left' => 100,
                        'top' => 150,
                        'width' => 500,
                        'height' => 40,
                        'fontSize' => 24,
                        'fill' => '#333333',
                        'fontFamily' => 'Microsoft YaHei',
                        'angle' => 0
                    ],
                    [
                        'type' => 'i-text',
                        'text' => '2. 超载时请最后进入的乘客退出',
                        'left' => 100,
                        'top' => 220,
                        'width' => 600,
                        'height' => 40,
                        'fontSize' => 24,
                        'fill' => '#333333',
                        'fontFamily' => 'Microsoft YaHei',
                        'angle' => 0
                    ],
                    [
                        'type' => 'i-text',
                        'text' => '3. 紧急情况请按紧急呼叫按钮',
                        'left' => 100,
                        'top' => 290,
                        'width' => 600,
                        'height' => 40,
                        'fontSize' => 24,
                        'fill' => '#d32f2f',
                        'fontFamily' => 'Microsoft YaHei',
                        'angle' => 0
                    ]
                ]
            ],
            'is_template' => true,
            'template_name' => '安全须知模板',
            'created_by' => 1,
        ]);

        // 创建示例普通图文
        ImageText::create([
            'title' => '电梯维护保养公告',
            'description' => '关于近期电梯维护安排的通知',
            'layout_data' => [
                'canvasWidth' => 1200,
                'canvasHeight' => 800,
                'elements' => [
                    [
                        'type' => 'i-text',
                        'text' => '维护公告',
                        'left' => 450,
                        'top' => 80,
                        'width' => 300,
                        'height' => 60,
                        'fontSize' => 42,
                        'fill' => '#ff6b00',
                        'fontFamily' => 'Microsoft YaHei',
                        'angle' => 0
                    ],
                    [
                        'type' => 'i-text',
                        'text' => '尊敬的业主：',
                        'left' => 100,
                        'top' => 200,
                        'width' => 200,
                        'height' => 40,
                        'fontSize' => 28,
                        'fill' => '#333333',
                        'fontFamily' => 'Microsoft YaHei',
                        'angle' => 0
                    ],
                    [
                        'type' => 'i-text',
                        'text' => '为保障电梯安全运行，我们将于本周六进行例行维护。',
                        'left' => 100,
                        'top' => 280,
                        'width' => 800,
                        'height' => 40,
                        'fontSize' => 24,
                        'fill' => '#666666',
                        'fontFamily' => 'Microsoft YaHei',
                        'angle' => 0
                    ],
                    [
                        'type' => 'i-text',
                        'text' => '维护期间部分电梯可能暂停使用，敬请谅解。',
                        'left' => 100,
                        'top' => 350,
                        'width' => 800,
                        'height' => 40,
                        'fontSize' => 24,
                        'fill' => '#666666',
                        'fontFamily' => 'Microsoft YaHei',
                        'angle' => 0
                    ]
                ]
            ],
            'is_template' => false,
            'created_by' => 1,
        ]);
    }
}
