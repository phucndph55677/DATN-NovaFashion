<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Color;

class ColorSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $colors = [
            ['name' => 'Đen',           'color_code' => '#000000'],
            ['name' => 'Trắng',         'color_code' => '#FFFFFF'],
            ['name' => 'Đỏ',            'color_code' => '#FF0000'],
            ['name' => 'Vàng',          'color_code' => '#FFFF00'],
            ['name' => 'Xanh lá',       'color_code' => '#00FF00'],
            ['name' => 'Xanh dương',    'color_code' => '#0000FF'],
            ['name' => 'Hồng',          'color_code' => '#FFC0CB'],
            ['name' => 'Nâu',           'color_code' => '#8B4513'],
            ['name' => 'Tím',           'color_code' => '#800080'],
            ['name' => 'Ghi',           'color_code' => '#808080'],
            ['name' => 'Be',            'color_code' => '#F5F5DC'],
            ['name' => 'Navy',          'color_code' => '#000080'],
            ['name' => 'Xanh mint',     'color_code' => '#AAF0D1'],
            ['name' => 'Cam đất',       'color_code' => '#D2691E'],
            ['name' => 'Xanh rêu',      'color_code' => '#556B2F'],
            ['name' => 'Xám nhạt',      'color_code' => '#D3D3D3'],
            ['name' => 'Xanh pastel',   'color_code' => '#B2EBF2'],
            ['name' => 'Tím pastel',    'color_code' => '#D8BFD8'],
            ['name' => 'Vàng mustard',  'color_code' => '#FFDB58'],
            ['name' => 'Cam cháy',      'color_code' => '#FF4500'],
        ];

        foreach ($colors as $color) {
            Color::firstOrCreate([
                'name' => $color['name'],
                'color_code' => $color['color_code'],
            ]);
        }
    }
}
