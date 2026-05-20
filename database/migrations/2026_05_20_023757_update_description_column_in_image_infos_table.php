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
        Schema::table('image_infos', function (Blueprint $table) {
            // 将 description 字段从 VARCHAR(255) 改为 TEXT，支持更长的描述文本
            $table->text('description')->nullable()->comment('图片描述')->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('image_infos', function (Blueprint $table) {
            // 回滚时恢复为 VARCHAR(255)
            $table->string('description')->nullable()->comment('图片描述')->change();
        });
    }
};
