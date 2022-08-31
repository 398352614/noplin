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
        Schema::create('field', function (Blueprint $table) {
            $table->id();
            $table->string('name')->index()->comment('名称');
            $table->integer('user_id')->index()->comment('用户ID');
            $table->integer('card_id')->index()->comment('卡片ID');
            $table->integer('sort_id')->comment('顺序');
            $table->tinyInteger('type')->index()->comment('类型1-文本2-数值3-选项4-时间5-链接6-图片');
            $table->tinyInteger('is_list')->index()->comment('是否为列表1-否2-是');
            $table->tinyInteger('level')->index()->comment('等级1-自定义级2-系统级');
            $table->string('default')->nullable()->comment('默认值');
            $table->string('description')->default('')->comment('描述');
            $table->string('formula')->default('')->comment('公式');
            $table->string('config')->default('')->comment('配置');

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
        Schema::dropIfExists('field');
    }
};
