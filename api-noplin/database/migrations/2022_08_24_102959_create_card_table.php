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
        Schema::create('card', function (Blueprint $table) {
            $table->comment('卡片表');

            $table->id();
            $table->integer('user_id')->index()->comment('用户ID');
            $table->integer('parent_id')->index()->comment('父卡片');
            $table->integer('sort_id')->comment('顺序');
            $table->tinyInteger('level')->index()->comment('等级1-自定义级2-系统级');
            $table->string('name')->default('')->comment('名字');
            $table->text('body')->nullable()->comment('正文');
            $table->string('logo')->default('')->comment('标志');
            $table->string('cover')->default('')->comment('封面');
            $table->json('custom_data')->nullable()->comment('自定义数据');

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
        Schema::dropIfExists('card');
    }
};
