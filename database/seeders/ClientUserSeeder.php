<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;

class ClientUserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('users')->insert([
            'role_id'        => 3, // role client
            'image'          => null,
            'name'           => 'Client',
            'phone'          => '0987654321',
            'email'          => 'client@example.com',
            'password'       => Hash::make('12345678'),
            'address'        => 'TP. Hồ Chí Minh',
            'is_verified'    => true,
            'remember_token' => Str::random(10),
            'created_at'     => now(),
            'updated_at'     => now(),
        ]);
    }
}