<?php

namespace Database\Seeders;

use App\Models\FoodSurplus;
use App\Models\Merchant;
use Illuminate\Database\Seeder;

class FoodSurplusSeeder extends Seeder
{
    public function run(): void
    {
        $merchant = Merchant::first();

        if ($merchant) {
            FoodSurplus::create([
                'merchant_id' => $merchant->id,
                'title' => 'Paket Roti Manis & Croissant Sore',
                'description' => 'Roti produksi pagi hari, kondisi masih sangat segar dan layak konsumsi.',
                'initial_portions' => 30,
                'remaining_portions' => 17,
                'weight_per_portion' => 250,
                'expired_at' => now()->addHours(6),
            ]);

            FoodSurplus::create([
                'merchant_id' => $merchant->id,
                'title' => 'Nasi Kotak Ayam Bakar (Surplus Event)',
                'description' => 'Kelebihan pesanan katering rapat, dikemas higienis dalam boks.',
                'initial_portions' => 20,
                'remaining_portions' => 20,
                'weight_per_portion' => 450,
                'expired_at' => now()->addHours(4),
            ]);
        }
    }
}