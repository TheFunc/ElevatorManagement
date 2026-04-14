<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */

    //电梯设备信息
    public function up(): void
    {
        Schema::create('devices', function (Blueprint $table) {
            $table->id();

            $table->string("number")->comment("电梯编号");
            $table->string("register")->comment("电梯注册编号");
            $table->string("FactorySerial")->comment("出厂（产品）编号");
            $table->string("name")->comment("设备名称");
            $table->string("Model")->comment("设备型号");
            $table->string("Manufacturer")->comment("制造厂家");
            $table->integer("status")->comment("设备使用状态 0 => 不可用 1 => 再用");

            $table->string("Position")->comment("电梯位置");
            $table->string("desc")->comment("电梯描述");
            $table->string("Campus")->comment("校区");
            $table->string("building")->comment("楼号");

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('devices');
    }
};
