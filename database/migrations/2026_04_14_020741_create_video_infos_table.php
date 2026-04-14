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
        Schema::create('video_infos', function (Blueprint $table) {
            $table->id();

            $table->string("coverPath")->comment("视频封面路径");
            $table->string("videoPath")->comment("视频路径");
            $table->string("videoType")->comment("视频类型");
            $table->string("videoGroup")->comment("视频分组");

            $table->string("description")->comment("视频描述");

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('video_infos');
    }
};
