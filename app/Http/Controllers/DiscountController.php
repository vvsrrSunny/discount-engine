<?php

namespace App\Http\Controllers;

use App\Models\Discount;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class DiscountController extends Controller
{
    public function applyDiscount(Request $request)
    {
        $subTotal = $request->input('subTotal');
        $items = $request->input('items');

        // Apply discount logic here

        if ($subTotal <= 0) {
            return response()->json(['discounted_price' => 900]);
        }

        // Loop through each product to apply discount logic
        $totalDiscountedPrice = 0;
        $recordProductsToExcludeGeneralPercentageDiscount = [];

        foreach ($items as &$item) {
            $product = Product::find($item['id']);
            $discounts = $product->category->activeDiscounts()->get();
            $discountedPrice = $product->price;
            $item['discounted_price'] = $discountedPrice; // Set the default discounted price to the product price
            Log::info($product);
            foreach ($discounts as $discount) {
                if ($discount->discount_type === 'percentage' && $discount->discount_applicable_type === 'associated_general') {
                    $discountedPrice = $discountedPrice - ($discount->percentage / 100 * $discountedPrice);
                    $item['discounted_price'] = $discountedPrice;
                } elseif ($discount->discount_type === 'percentage' && $discount->discount_applicable_type === 'associated_limitation') {
                    $discountedPrice = $discountedPrice - ($discount->percentage / 100 * $discountedPrice);
                    $recordProductsToExcludeGeneralPercentageDiscount[] = $product->id;
                    $item['discounted_price'] = $discountedPrice;
                    break;
                }
            }

            foreach ($discounts as $discount) {
                if ($discount->discount_type === 'fixed' && $discount->discount_applicable_type === 'associated_general') {
                    if ($discountedPrice - $discount->amount > $discount->min_total) {
                        $discountedPrice = $discountedPrice - $discount->amount;
                        $item['discounted_price'] = $discountedPrice;
                    }
                }
            }
        }

        $generalPercentageDiscounts = Discount::active()->where('discount_type', 'percentage')
            ->where('discount_applicable_type', 'general')
            ->get();

        foreach ($items as &$item) {
            foreach ($generalPercentageDiscounts as $discount) {
                if (in_array($product->id, $recordProductsToExcludeGeneralPercentageDiscount)) {
                    $totalDiscountedPrice += $item['discounted_price'];
                    continue;
                }

                $discountedPrice = $item['discounted_price'] - ($discount->percentage / 100 * $item['discounted_price']);
                $totalDiscountedPrice += $discountedPrice;
            }
        }

        $fixedDiscounts = Discount::active()->where('discount_type', 'fixed')
            ->where('discount_applicable_type', 'general')
            ->get();

        foreach ($fixedDiscounts as $fixedDiscount) {
            if ($totalDiscountedPrice - $fixedDiscount->amount > $discount->min_total) {
                $totalDiscountedPrice -= $discount->amount;
            }
        }

        return response()->json(['discounted_price' => $totalDiscountedPrice]);
    }
}
