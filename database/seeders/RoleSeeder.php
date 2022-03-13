<?php

namespace Database\Seeders;

use App\Models\Role;
use Illuminate\Database\Seeder;

class RoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        app()['cache']->forget('spatie.permission.cache');

        Role::create([
            'name'  => config('permission.super_admin.name'),
            'level' => Role::LEVEL_SUPER_ADMIN
        ]);
    }
}
