<?php

use App\Permission;
use Illuminate\Database\Seeder;

class PermissionsTableSeeder extends Seeder
{
    public function run ()
    {
        $permissions = [
            [
                'title' => 'user_management_access',
            ],
            [
                'title' => 'permission_create',
            ],
            [
                'title' => 'permission_edit',
            ],
            [
                'title' => 'permission_show',
            ],
            [
                'title' => 'permission_delete',
            ],
            [
                'title' => 'permission_access',
            ],
            [
                'title' => 'role_create',
            ],
            [
                'title' => 'role_edit',
            ],
            [
                'title' => 'role_show',
            ],
            [
                'title' => 'role_delete',
            ],
            [
                'title' => 'role_access',
            ],
            [
                'title' => 'user_create',
            ],
            [
                'title' => 'user_edit',
            ],
            [
                'title' => 'user_show',
            ],
            [
                'title' => 'user_delete',
            ],
            [
                'title' => 'user_access',
            ],
            [
                'title' => 'manager_create',
            ],
            [
                'title' => 'manager_edit',
            ],
            [
                'title' => 'manager_show',
            ],
            [
                'title' => 'manager_delete',
            ],
            [
                'id' => '21',
                'title' => 'manager_access',
            ],
            [
                'title' => 'ship_create',
            ],
            [
                'title' => 'ship_edit',
            ],
            [
                'id' => '24',
                'title' => 'ship_show',
            ],
            [
                'title' => 'ship_delete',
            ],
            [
                'title' => 'ship_access',
            ],
            [
                'title' => 'terminal_create',
            ],
            [
                'title' => 'terminal_edit',
            ],
            [
                'title' => 'terminal_show',
            ],
            [
                'title' => 'terminal_delete',
            ],
            [
                'title' => 'terminal_access',
            ],
            [
                'title' => 'history_ship_create',
            ],
            [
                'title' => 'history_ship_edit',
            ],
            [
                'title' => 'history_ship_show',
            ],
            [
                'title' => 'history_ship_delete',
            ],
            [
                'title' => 'history_ship_access',
            ],
            [
                'title' => 'setting_access',
            ],
        ];
        Permission::insert($permissions);
    }

}
