<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\Product;
use App\Models\Setting;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // 1. Create Default Admin User
        $admin = User::firstOrCreate(
            ['email' => 'admin@gmail.com'],
            [
                'name' => 'System Administrator',
                'password' => Hash::make('password'),
                'role' => 'admin',
                'email_verified_at' => now(),
            ]
        );

        // 2. Create Default Staff User
        $staff = User::firstOrCreate(
            ['email' => 'staff@gmail.com'],
            [
                'name' => 'POS Cashier Staff',
                'password' => Hash::make('password'),
                'role' => 'staff',
                'email_verified_at' => now(),
            ]
        );

        // 3. Create Sample Categories
        $fastFood = Category::firstOrCreate(['name' => 'Fast Food'], ['is_active' => true]);
        $beverages = Category::firstOrCreate(['name' => 'Beverages'], ['is_active' => true]);
        $bakery = Category::firstOrCreate(['name' => 'Bakery & Dessert'], ['is_active' => true]);

        // 4. Create Sample Products
        Product::firstOrCreate(
            ['sku' => 'PRD-1001'],
            [
                'category_id' => $fastFood->id,
                'name' => 'Cheese Burger',
                'price' => 8.50,
                'stock' => 50,
                'is_active' => true,
            ]
        );

        Product::firstOrCreate(
            ['sku' => 'PRD-1002'],
            [
                'category_id' => $fastFood->id,
                'name' => 'Pepperoni Pizza',
                'price' => 12.00,
                'stock' => 30,
                'is_active' => true,
            ]
        );

        Product::firstOrCreate(
            ['sku' => 'PRD-1003'],
            [
                'category_id' => $beverages->id,
                'name' => 'Iced Americano',
                'price' => 3.50,
                'stock' => 100,
                'is_active' => true,
            ]
        );

        Product::firstOrCreate(
            ['sku' => 'PRD-1004'],
            [
                'category_id' => $bakery->id,
                'name' => 'Chocolate Donut',
                'price' => 2.50,
                'stock' => 40,
                'is_active' => true,
            ]
        );

        // 5. Default Settings
        Setting::set('store_name', 'Finexy POS Store');
        Setting::set('store_address', '123 Main Street, Yangon, Myanmar');
        Setting::set('store_phone', '+95 9 123 456 789');
        Setting::set('tax_rate', '5');
        Setting::set('currency_symbol', '$');
    }
}
