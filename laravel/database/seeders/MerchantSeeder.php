<?php

namespace Database\Seeders;

use App\Models\Merchant;
use App\Models\User;
use Illuminate\Database\Seeder;

class MerchantSeeder extends Seeder
{
    public function run(): void
    {
        $user = User::where('email', 'roti@foodsave.id')->first();

        if ($user) {
            Merchant::create([
                'user_id' => $user->id,
                'business_name' => 'Toko Roti Harum Manis',
                'category' => 'toko_roti',
                'address' => 'Jl. Dhoho No. 45, Kota Kediri',
                'is_verified' => true,
            ]);
        }
    }
}