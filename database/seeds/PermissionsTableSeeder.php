<?php

use App\Permission;
use Illuminate\Database\Seeder;

class PermissionsTableSeeder extends Seeder
{
    public function run()
    {
        $permissions = [
            [
                'id'    => '1',
                'title' => 'user_management_access',
            ],
            [
                'id'    => '2',
                'title' => 'permission_create',
            ],
            [
                'id'    => '3',
                'title' => 'permission_edit',
            ],
            [
                'id'    => '4',
                'title' => 'permission_show',
            ],
            [
                'id'    => '5',
                'title' => 'permission_delete',
            ],
            [
                'id'    => '6',
                'title' => 'permission_access',
            ],
            [
                'id'    => '7',
                'title' => 'role_create',
            ],
            [
                'id'    => '8',
                'title' => 'role_edit',
            ],
            [
                'id'    => '9',
                'title' => 'role_show',
            ],
            [
                'id'    => '10',
                'title' => 'role_delete',
            ],
            [
                'id'    => '11',
                'title' => 'role_access',
            ],
            [
                'id'    => '12',
                'title' => 'user_create',
            ],
            [
                'id'    => '13',
                'title' => 'user_edit',
            ],
            [
                'id'    => '14',
                'title' => 'user_show',
            ],
            [
                'id'    => '15',
                'title' => 'user_delete',
            ],
            [
                'id'    => '16',
                'title' => 'user_access',
            ],
            [
                'id'    => '17',
                'title' => 'manager_create',
            ],
            [
                'id'    => '18',
                'title' => 'manager_edit',
            ],
            [
                'id'    => '19',
                'title' => 'manager_show',
            ],
            [
                'id'    => '20',
                'title' => 'manager_delete',
            ],
            [
                'id'    => '21',
                'title' => 'manager_access',
            ],
            [
                'id'    => '22',
                'title' => 'ship_create',
            ],
            [
                'id'    => '23',
                'title' => 'ship_edit',
            ],
            [
                'id'    => '24',
                'title' => 'ship_show',
            ],
            [
                'id'    => '25',
                'title' => 'ship_delete',
            ],
            [
                'id'    => '26',
                'title' => 'ship_access',
            ],
            [
                'id'    => '27',
                'title' => 'terminal_create',
            ],
            [
                'id'    => '28',
                'title' => 'terminal_edit',
            ],
            [
                'id'    => '29',
                'title' => 'terminal_show',
            ],
            [
                'id'    => '30',
                'title' => 'terminal_delete',
            ],
            [
                'id'    => '31',
                'title' => 'terminal_access',
            ],
            [
                'id'    => '32',
                'title' => 'history_ship_create',
            ],
            [
                'id'    => '33',
                'title' => 'history_ship_edit',
            ],
            [
                'id'    => '34',
                'title' => 'history_ship_show',
            ],
            [
                'id'    => '35',
                'title' => 'history_ship_delete',
            ],
            [
                'id'    => '36',
                'title' => 'history_ship_access',
            ],
            [
                'id'    => '37',
                'title' => 'terminal_ship_create',
            ],
            [
                'id'    => '38',
                'title' => 'terminal_ship_edit',
            ],
            [
                'id'    => '39',
                'title' => 'terminal_ship_show',
            ],
            [
                'id'    => '40',
                'title' => 'terminal_ship_delete',
            ],
            [
                'id'    => '41',
                'title' => 'terminal_ship_access',
            ],
        ];

        Permission::insert($permissions);
    }
}
