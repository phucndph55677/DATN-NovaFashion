<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Location;

class LocationSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Location::insert([
            ['id' => 1, 'name' => 'Trang chủ - Đầu'],
            ['id' => 2, 'name' => 'Trang chủ - Giữa'],
            ['id' => 3, 'name' => 'Trang chủ - Cuối'],
            ['id' => 4, 'name' => 'Danh mục - Cuối'],
        ]);
    }
}
