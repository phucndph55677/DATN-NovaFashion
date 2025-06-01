<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Ranking;

class RankingSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Danh sách thứ hạng cố định, có thể sửa theo nhu cầu
        $rankings = [
            ['name' => 'Đồng', 'level' => 1],
            ['name' => 'Bạc', 'level' => 2],
            ['name' => 'Vàng', 'level' => 3],
            ['name' => 'Bạch Kim', 'level' => 4],
            ['name' => 'Kim Cương', 'level' => 5],
            ['name' => 'Tinh Anh', 'level' => 6],
            ['name' => 'Cao Thủ', 'level' => 7],
            ['name' => 'Chiến Tướng', 'level' => 8],
            ['name' => 'Chiến Thần', 'level' => 9],
            ['name' => 'Thách Đấu', 'level' => 10],
        ];

        foreach ($rankings as $ranking) {
            Ranking::updateOrCreate(['level' => $ranking['level']], $ranking);
        }
    }
}
