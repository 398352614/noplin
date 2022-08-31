<?php

namespace Database\Seeders;

use App\Constants\Constant;
use App\Models\Card;
use App\Models\Field;
use App\Models\User;
use Illuminate\Database\Seeder;
use JetBrains\PhpStorm\ArrayShape;

class CardSeeder extends Seeder
{
    private null|object $user;
    private int $card_id;

    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $this->user = User::query()->first();
        $this->card_id = Card::query()->insertGetId($this->data());
        Field::query()->insert($this->fieldData());

    }

    /**
     * @return array
     */
    #[ArrayShape(['name' => "string", 'user_id' => "mixed", 'parent_id' => "int", 'sort_id' => "int", 'level' => "int", 'body' => "string", 'logo' => "string", 'cover' => "string", 'custom_data' => "false|string"])]
    public function data(): array
    {
        return [
            'name' => 'card',
            'user_id' => $this->user->id,
            'parent_id' => 0,
            'sort_id' => 1,
            'level' => Constant::LEVEL_SYSTEM,
            'body' => '功能核心，相当于文档。与文档不同的是，除了正文内容以外，会有一个可选的数据库表格。在这个数据库表格里的每条数据，都会自动生成一个卡片。也就是说，卡片与卡片之间呈树状分部，因此，也就没有文件夹了，万物皆卡片。',
            'logo' => '',
            'cover' => '',
            'custom_data' => json_encode([
                '自定义字段' => '123'
            ])
        ];
    }

    /**
     * @return array
     */
    public function fieldData(): array
    {
        return [
            [
                'name' => 'name',
                'user_id' => $this->user->id,
                'card_id' => $this->card_id,
                'sort_id' => 1,
                'level' => Constant::LEVEL_SYSTEM,
                'description'=>'卡片名称',
                'default' => '新卡片',
                'type' => Constant::TEXT,
                'formula' => '',
                'is_list' => 1,
                'config'=>'',
            ],
            [
                'name' => 'user_id',
                'user_id' => $this->user->id,
                'card_id' => $this->card_id,
                'sort_id' => 2,
                'level' => Constant::LEVEL_SYSTEM,
                'description'=>'用户ID',
                'default' => null,
                'type' => Constant::NUMBER,
                'formula' => '',
                'is_list' => 1,
                'config'=>'',
            ],
            [
                'name' => 'parent_id',
                'user_id' => $this->user->id,
                'card_id' => $this->card_id,
                'sort_id' => 3,
                'level' => Constant::LEVEL_SYSTEM,
                'description'=>'父卡片ID',
                'default' => null,
                'type' => Constant::NUMBER,
                'formula' => '',
                'is_list' => 1,
                'config'=>'',
            ],
            [
                'name' => 'sort_id',
                'user_id' => $this->user->id,
                'card_id' => $this->card_id,
                'sort_id' => 4,
                'level' => Constant::LEVEL_SYSTEM,
                'description'=>'序号',
                'default' => null,
                'type' => Constant::NUMBER,
                'formula' => '',
                'is_list' => 1,
                'config'=>'',
            ],
            [
                'name' => 'level',
                'user_id' => $this->user->id,
                'card_id' => $this->card_id,
                'sort_id' => 5,
                'level' => Constant::LEVEL_SYSTEM,
                'description'=>'等级',
                'default' => null,
                'type' => Constant::SELECT,
                'formula' => '',
                'is_list' => 1,
                'config'=>'',
            ],
            [
                'name' => 'body',
                'user_id' => $this->user->id,
                'card_id' => $this->card_id,
                'sort_id' => 6,
                'level' => Constant::LEVEL_SYSTEM,
                'description'=>'正文',
                'default' => null,
                'type' => Constant::TEXT,
                'formula' => '',
                'is_list' => 1,
                'config'=>'',
            ],
            [
                'name' => 'logo',
                'user_id' => $this->user->id,
                'card_id' => $this->card_id,
                'sort_id' => 7,
                'level' => Constant::LEVEL_SYSTEM,
                'description'=>'标志',
                'default' => null,
                'type' => Constant::IMAGE,
                'formula' => '',
                'is_list' => 1,
                'config'=>'',
            ],
            [
                'name' => 'cover',
                'user_id' => $this->user->id,
                'card_id' => $this->card_id,
                'sort_id' => 8,
                'level' => Constant::LEVEL_SYSTEM,
                'description'=>'正文',
                'default' => null,
                'type' => Constant::TEXT,
                'formula' => '',
                'is_list' => 1,
                'config'=>'',
            ],
            [
                'name' => 'custom_data',
                'user_id' => $this->user->id,
                'card_id' => $this->card_id,
                'sort_id' => 9,
                'level' => Constant::LEVEL_SYSTEM,
                'description'=>'自定义字段',
                'default' => null,
                'type' => Constant::TEXT,
                'formula' => '',
                'is_list' => 1,
                'config'=>'',
            ],
            [
                'name' => 'created_at',
                'user_id' => $this->user->id,
                'card_id' => $this->card_id,
                'sort_id' => 10,
                'level' => Constant::LEVEL_SYSTEM,
                'description'=>'创建时间',
                'default' => null,
                'type' => Constant::TEXT,
                'formula' => '',
                'is_list' => 1,
                'config'=>'',
            ],
            [
                'name' => 'updated_at',
                'user_id' => $this->user->id,
                'card_id' => $this->card_id,
                'sort_id' => 11,
                'level' => Constant::LEVEL_SYSTEM,
                'description'=>'更新时间',
                'default' => null,
                'type' => Constant::TEXT,
                'formula' => '',
                'is_list' => 1,
                'config'=>'',
            ],
        ];
    }
}
