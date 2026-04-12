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
        // "title",
        // "desc",
        // "path",
        // "type"

        Schema::create('files', function (Blueprint $table) {
            $table->id();

            $table->string("title")->comment("文件标题");
            $table->string("desc")->nullable()->comment("文件描述");
            $table->string("path")->comment("文件路径");
            $table->string("type")->comment("文件类型");

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('files');
    }
};
