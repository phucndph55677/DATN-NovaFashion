<?php

namespace Database\Seeders;

use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // User::factory(10)->create();

        // User::factory()->create([
        //     'name' => 'Test User',
        //     'email' => 'test@example.com',
        // ]);

        $this->call([
            // RoleSeeder::class,
            // RankingSeeder::class,
            // UserSeeder::class,
            // CategorySeeder::class,
            // ProductSeeder::class,
            // ColorSeeder::class,
            // SizeSeeder::class,
            // ProductVariantSeeder::class,
            // ProductFavoriteSeeder::class,
            // ProductPhotoAlbumSeeder::class,
            // ReviewSeeder::class,
            // CartSeeder::class,
            // CartDetailSeeder::class,
            // ChatSeeder::class,
            // ChatDetailSeeder::class,
            // OrderStatusSeeder::class,
            // OrderSeeder::class,
            // OrderDetailSeeder::class,
            // PaymentSeeder::class,
            // InvoiceSeeder::class,
            // NotificationSeeder::class,
            // VoucherSeeder::class,
            // PointSeeder::class,
            // LocationSeeder::class,
            // BannerSeeder::class,            
        ]);
    }
}
