<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\Discount;
use Carbon\Carbon;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DiscountSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $category2 = Category::where('name', 'Clothing')->first();
        $category3 = Category::where('name', 'Books')->first();
        $now = Carbon::now();
        // Create discounts
        Discount::create([
            'discount_type' => 'percentage',
            'name' => 'discount1',
            'active_from' => $now,
            'active_to' => $now->copy()->addDay(), // Adds 1 day to active_from
            'amount' => null, // 10% discount
            'percentage' => 10.00, // 10% discount
            'min_total' => null, // 10% discount
            'discount_applicable_type' => 'general',
        ]);

        $discount2 = Discount::create([
            'discount_type' => 'fixed',
            'name' => 'discount2',
            'active_from' => $now,
            'active_to' => $now->copy()->addDay(), // Adds 1 day to active_from
            'amount' => 10.00, // $50 discount
            'percentage' => null,
            'min_total' => 100.00, // $50
            'discount_applicable_type' => 'associated_general',
        ]);

        $discount3 = Discount::create([
            'discount_type' => 'percentage',
            'name' => 'discount3',
            'active_from' => $now,
            'active_to' => $now, // Adds 1 day to active_from
            'amount' => null,
            'percentage' => 15.00, // 15% discount
            'min_total' => null,
            'discount_applicable_type' => 'associated_limitation',
        ]);

        // Attach discounts to categories
        if ($category2) {
            $category2->discounts()->attach($discount2->id);
        }

        if ($category3) {
            $category3->discounts()->attach($discount3->id);
        }
    }
}
