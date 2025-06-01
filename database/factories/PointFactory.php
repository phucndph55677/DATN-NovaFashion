<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\User;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Point>
 */
class PointFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $reason = $this->faker->randomElement([
            'Đặt hàng thành công',
            'Hủy đơn hàng',
            'Khuyến mãi đặc biệt',
            'Sinh nhật thành viên',
            'Trừ điểm do hoàn hàng'
        ]);

        // Mảng lý do cần cộng điểm
        $positiveReasons = [
            'Đặt hàng thành công',
            'Khuyến mãi đặc biệt',
            'Sinh nhật thành viên',
        ];

        // Nếu lý do thuộc nhóm cộng điểm, amount luôn dương, ngược lại âm
        $amount = in_array($reason, $positiveReasons)
            ? $this->faker->numberBetween(10, 200)   // điểm cộng
            : -$this->faker->numberBetween(10, 100); // điểm trừ (âm)

        return [
            'user_id' => User::inRandomOrder()->first()?->id ?? User::factory(),
            'amount' => $amount,
            'reason' => $reason,
        ];
    }
}
