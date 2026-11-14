<?php

namespace Database\Seeders;

use App\Models\Customer;
use App\Models\Product;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        User::updateOrCreate(
            ['email' => 'admin@example.com'],
            ['name' => 'Store Admin', 'password' => Hash::make('password')],
        );

        $product = Product::updateOrCreate(
            ['sku' => 'WM-001'],
            ['name' => 'Wireless Mouse', 'price' => 19.99, 'stock' => 25],
        );
        $customer = Customer::updateOrCreate(
            ['email' => 'customer@example.com'],
            ['name' => 'Demo Customer', 'phone' => '0123456789'],
        );
    }
}
