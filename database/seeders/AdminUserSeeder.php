<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;

class AdminUserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('users')->insert([
            'role_id'       => 1, // bạn thay theo id role Admin nếu có
            'ranking_id'    => 1, // hoặc null nếu không cần
            'image'         => null,
            'name'          => 'Admin',
            'phone'         => '0123456789',
            'email'         => 'admin@example.com',
            'password'      => Hash::make('12345678'), // mã hóa password
            'address'       => 'Hà Nội',
            'is_verified'   => true,
            'remember_token'=> Str::random(10),
            'created_at'    => now(),
            'updated_at'    => now(),
        ]);
    }
}
