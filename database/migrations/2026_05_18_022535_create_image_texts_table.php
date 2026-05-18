<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('image_texts', function (Blueprint $table) {
            $table->id();
            $table->string('title')->comment('标题');
            $table->text('description')->nullable()->comment('描述');
            $table->json('layout_data')->nullable()->comment('布局数据（JSON格式）');
            $table->string('thumbnail')->nullable()->comment('缩略图路径');
            $table->boolean('is_template')->default(false)->comment('是否为模板');
            $table->string('template_name')->nullable()->comment('模板名称');
            $table->integer('created_by')->nullable()->comment('创建者ID');
            $table->timestamps();
            $table->softDeletes(); // 软删除
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('image_texts');
    }
};
