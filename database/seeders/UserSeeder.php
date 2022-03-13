<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        User::factory()->create([
            'name'     => config('permission.super_admin.name'),
            'email'    => config('permission.super_admin.email'),
            'password' => Hash::make(config('permission.super_admin.password')),
        ])->assignRole(config('permission.super_admin.name'));

        if (!app()->environment('production')) {
            User::factory()->create([
                'name'  => 'Stalin Sánchez',
                'email' => 'stalin@email.com',
            ])->assignRole(config('permission.super_admin.name'));
        }
    }
}
