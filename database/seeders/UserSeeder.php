<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $users = [

            [
                'name' => 'Admin',
                'email' => 'admin@itsolutionstuff.com',
                'type' => 1,
                'password' => bcrypt('123456'),
            ],
            [
                'name' => 'Student 1r',
                'email' => 'manager@itsolutionstuff.com',
                'type' => 0,
                'password' => bcrypt('123456'),
            ],
            [
                'name' => 'Student 2',
                'email' => 'user@itsolutionstuff.com',
                'type' => 0,
                'password' => bcrypt('123456'),
            ],
        ];
        foreach ($users as $key => $user) {

            User::create($user);
        }
    }
}
