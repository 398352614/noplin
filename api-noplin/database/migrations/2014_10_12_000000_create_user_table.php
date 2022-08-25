<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('user', function (Blueprint $table) {
            $table->comment('用户表');
            $table->id();
            $table->string('username')->unique()->comment('用户名');
            $table->tinyInteger('type')->default(1)->index()->comment('类型1-管理员2-普通用户');
            $table->tinyInteger('status')->default(1)->index()->comment('状态1-启用2-禁用');
            $table->string('password')->comment('密码');
            $table->string('name')->default('')->comment('名字');
            $table->string('avatar')->default('')->comment('头像');

            $table->softDeletes();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('user');
    }
};
