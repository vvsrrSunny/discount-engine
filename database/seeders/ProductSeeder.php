<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\Product;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ProductSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $electronics = Category::where('name', 'Electronics')->first();
        $clothing = Category::where('name', 'Clothing')->first();
        $books = Category::where('name', 'Books')->first();

        Product::insert([
            ['name' => 'Smartphone', 'price' => 699.99, 'category_id' => $electronics->id],
            ['name' => 'Laptop', 'price' => 1299.99, 'category_id' => $electronics->id],
            ['name' => 'T-Shirt', 'price' => 19.99, 'category_id' => $clothing->id],
            ['name' => 'Jeans', 'price' => 49.99, 'category_id' => $clothing->id],
            ['name' => 'Fiction Novel', 'price' => 14.99, 'category_id' => $books->id],
            ['name' => 'Science Book', 'price' => 24.99, 'category_id' => $books->id],
        ]);
    }
}
