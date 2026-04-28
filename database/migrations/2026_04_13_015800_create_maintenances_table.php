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
        Schema::create('maintenances', function (Blueprint $table) {
            $table->id();

            $table->string("inspection_devices")->comment("年检的电梯");
            $table->date("next_inspection_date")->comment("检查日期");
            $table->string("responsible_person")->comment("检查人");
            $table->string("contact_phone")->comment("负责人电话");
            $table->string("remark")->default("无")->comment("备注");

            $table->tinyInteger("status")->default(0)->comment("状态: 0=>未检查 1=>已检查 2=>已过期 3=>废用");


            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('maintenances');
    }
};
