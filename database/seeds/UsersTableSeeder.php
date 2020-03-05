<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class UsersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('users')->insert([
            'name' => 'Admin Clint',
            'type' => 'Admin',
            'username' => 'clint114',
            'email' => 'clint@me.com',
            'password' => Hash::make('11111111'),
        ]);
    }
}
