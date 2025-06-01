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
        $sizes = ['XS', 'S', 'M', 'L', 'XL', 'XXL', '3XL'];

        foreach ($sizes as $size) {
            Size::firstOrCreate(['name' => $size]);
        }
    }
}
