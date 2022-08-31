<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use JetBrains\PhpStorm\ArrayShape;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        User::query()->create($this->data());
    }

    /**
     * @return array
     */
    #[ArrayShape(['username' => "\Illuminate\Config\Repository|\Illuminate\Contracts\Foundation\Application|mixed", 'password' => "\Illuminate\Config\Repository|\Illuminate\Contracts\Foundation\Application|mixed"])]
    public function data(): array
    {
        return [
            'username' => config('project.root.username'),
            'password' => config('project.root.password')
        ];
    }


}
