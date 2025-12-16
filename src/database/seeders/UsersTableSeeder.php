<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class UsersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $param = [
            'name' => '山田　太郎',
            'email' => 'example@example.com',
            'password' => 'z1234567',
            'role' => '1'
        ];
        DB::table('users')->insert($param);
    }
}
