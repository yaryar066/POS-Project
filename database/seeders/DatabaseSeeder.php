<?php

namespace Database\Seeders; // <-- ဒီနေရာမှာ Backslash (\) ပါရပါမည်

use App\Models\Category;
use App\Models\Product;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // 1. Default Admin & Staff Accounts
        User::firstOrCreate(
            ['email' => 'admin@gmail.com'],
            [
                'name' => 'System Administrator',
                'password' => Hash::make('password'),
                'role' => 'admin',
            ]
        );

        User::firstOrCreate(
            ['email' => 'staff@gmail.com'],
            [
                'name' => 'Cashier Staff',
                'password' => Hash::make('password'),
                'role' => 'staff',
            ]
        );

        // 2. Categories
        $categoriesData = [
            ['name' => 'Fast Food', 'slug' => 'fast-food', 'is_active' => true],
            ['name' => 'Beverages & Coffee', 'slug' => 'beverages-coffee', 'is_active' => true],
            ['name' => 'Bakery & Snacks', 'slug' => 'bakery-snacks', 'is_active' => true],
            ['name' => 'Desserts', 'slug' => 'desserts', 'is_active' => true],
        ];

        $categories = [];
        foreach ($categoriesData as $cat) {
            $categories[$cat['name']] = Category::firstOrCreate(['slug' => $cat['slug']], $cat);
        }

        // 3. Products List
        $products = [
            // Fast Food
            [
                'category_id' => $categories['Fast Food']->id,
                'name' => 'Cheeseburger Special',
                'sku' => 'PRD-BURGER01',
                'price' => 8.50,
                'stock' => 25,
                'is_active' => true,
            ],
            [
                'category_id' => $categories['Fast Food']->id,
                'name' => 'Pepperoni Pizza Large',
                'sku' => 'PRD-PIZZA02',
                'price' => 15.99,
                'stock' => 12,
                'is_active' => true,
            ],
            [
                'category_id' => $categories['Fast Food']->id,
                'name' => 'Crispy French Fries',
                'sku' => 'PRD-FRIES03',
                'price' => 3.50,
                'stock' => 3, // Low Stock Demo
                'is_active' => true,
            ],
            [
                'category_id' => $categories['Fast Food']->id,
                'name' => 'Hotdog Deluxe',
                'sku' => 'PRD-HOTDOG04',
                'price' => 5.00,
                'stock' => 10,
                'is_active' => true,
            ],

            // Beverages & Coffee
            [
                'category_id' => $categories['Beverages & Coffee']->id,
                'name' => 'Iced Americano',
                'sku' => 'PRD-COFFEE01',
                'price' => 3.00,
                'stock' => 50,
                'is_active' => true,
            ],
            [
                'category_id' => $categories['Beverages & Coffee']->id,
                'name' => 'Caramel Latte Ice',
                'sku' => 'PRD-LATTE02',
                'price' => 4.50,
                'stock' => 2, // Low Stock Demo
                'is_active' => true,
            ],

            // Bakery & Snacks
            [
                'category_id' => $categories['Bakery & Snacks']->id,
                'name' => 'Butter Croissant',
                'sku' => 'PRD-BAKE01',
                'price' => 2.50,
                'stock' => 30,
                'is_active' => true,
            ],
            [
                'category_id' => $categories['Bakery & Snacks']->id,
                'name' => 'Chocolate Donut',
                'sku' => 'PRD-DONUT02',
                'price' => 2.00,
                'stock' => 0, // Out of Stock Demo
                'is_active' => true,
            ],
        ];

        foreach ($products as $p) {
            Product::firstOrCreate(['sku' => $p['sku']], $p);
        }
    }
}