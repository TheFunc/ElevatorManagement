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
        Schema::create('image_infos', function (Blueprint $table) {
            $table->id();

            $table->string("coverPath")->comment("图片封面路径");
            $table->string("imagePath")->comment("图片路径");
            $table->string("imageType")->comment("图片类型");
            $table->string("imageGroup")->comment("图片分组");

            $table->string("description")->nullable()->comment("图片描述");

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('image_infos');
    }
};
