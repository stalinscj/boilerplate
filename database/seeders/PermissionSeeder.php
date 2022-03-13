<?php

namespace Database\Seeders;

use App\Models\Permission;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\File;

class PermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        app()['cache']->forget('spatie.permission.cache');

        $permissionsToCreate = [];

        $json = File::get("database/data/permissions.json");
        $permissions = json_decode($json);
        foreach ($permissions as $permission) {
            foreach ($permission->labels as $label) {
                $permissionsToCreate[] = [
                    'name'       => $label->name,
                    'route_name' => $label->route_name,
                    'group'      => $permission->group,
                    'level'      => $permission->level,
                    'guard_name' => 'web',
                    'created_at' => now(),
                    'updated_at' => now(),
                ];
            }
        }

        Permission::insert($permissionsToCreate);
    }
}
