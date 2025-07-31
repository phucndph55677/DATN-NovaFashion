<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Category;

class CategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Tạo 5 danh mục cha
        Category::factory()->count(5)->create()->each(function ($parent) {
            
                // Mỗi danh mục cha có 2 danh mục con
                Category::factory()->count(2)->create([
                        'parent_id' => $parent->id,
                    ]);
            });
    }
}