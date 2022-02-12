<?php

namespace Database\Seeders;

use App\Models\Role;
use Illuminate\Database\Seeder;

class RoleSeeder extends Seeder
{
    public function run()
    {
        $roles =
            [
                ['name' => 'Пользователь'],
                ['name' => 'Администратор'],
                ['name' => 'Модератор'],
            ];

        collect($roles)->each(fn($role) => Role::create($role));
    }
}
