<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        User::create([
            'name' => 'Admin FoodSave',
            'email' => 'admin@foodsave.id',
            'password' => 'password',
            'role' => 'admin',
            'phone' => '081200000001',
        ]);

        User::create([
            'name' => 'Budi Santoso (Owner Bakery)',
            'email' => 'roti@foodsave.id',
            'password' => 'password',
            'role' => 'merchant',
            'phone' => '081200000002',
        ]);

        User::create([
            'name' => 'Panti Asuhan Kasih Bunda',
            'email' => 'panti@foodsave.id',
            'password' => 'password',
            'role' => 'recipient',
            'phone' => '081200000003',
        ]);

        $this->call([
            MerchantSeeder::class,
            FoodSurplusSeeder::class,
            ClaimSeeder::class,
        ]);
    }
}