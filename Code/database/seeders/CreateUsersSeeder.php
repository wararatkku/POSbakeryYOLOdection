<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;

class CreateUsersSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //
        $user = [
            [
                'name' => 'Bannawat',
                'email' => 'admin@gmail.com',
                'is_admin' => '2',
                'password' => bcrypt('123456'),
                'work' => '11/01/2001'
            ],
            [
                'name' => 'Natthawat',
                'email' => 'owner@gmail.com',
                'is_admin' => '1',
                'password' => bcrypt('123456'),
                'work' => '11/01/2001'
            ],[
                'name' => 'ณัฐวัฒน์ โสมปิตะ',
                'email' => 'user@gmail.com',
                'is_admin' => '0',
                'password' => bcrypt('123456'),
                'work' => '11/01/2001'
            ]
        ];

        foreach($user as $key => $value){
            User::create($value);
        }
    }
}