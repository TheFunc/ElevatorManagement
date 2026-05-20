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
        Schema::create('text_infos', function (Blueprint $table) {
            $table->id();

            $table->string("TextType")->comment("文本类型");
            $table->string("TextGroup")->comment("文本分组");

            $table->text("TextContent")->nullable()->comment("文本内容");

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('text_infos');
    }
};
