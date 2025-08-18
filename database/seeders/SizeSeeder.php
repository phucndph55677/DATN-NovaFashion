<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Size;

class SizeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Bạn có thể seed chính xác những size cố định
        $sizes = [
            ['name' => 'XS', 'code' => 'XS'],
            ['name' => 'S', 'code' => 'S'],
            ['name' => 'M', 'code' => 'M'],
            ['name' => 'L', 'code' => 'L'],
            ['name' => 'XL', 'code' => 'XL'],
            ['name' => 'XXL', 'code' => 'XXL'],
            ['name' => '3XL', 'code' => '3XL'],
        ];

        foreach ($sizes as $size) {
            Size::firstOrCreate([
                'name' => $size['name'],
                'size_code' => $size['code'],
            ]);
        }
    }
}