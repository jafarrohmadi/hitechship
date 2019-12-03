<?php

use App\User;
use Illuminate\Database\Seeder;

class UsersTableSeeder extends Seeder
{
    public function run ()
    {
        $users = [
            [
                'id' => 1,
                'name' => 'Admin',
                'username' => 'admin',
                'password' => '$2y$10$DukeZajx01xlu5gOaKKTFevWUjtvZSY6AXKQwFAnE4Cu/pRyBbckW',
                'remember_token' => null,
            ],
        ];
        User::insert($users);
    }
}
