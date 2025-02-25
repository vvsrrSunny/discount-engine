<?php

use App\Http\Controllers\DiscountController;
use Illuminate\Support\Facades\Route;

// Route::get('/user', function (Request $request) {
//     return $request->user();
// })->middleware('auth:sanctum');

Route::post('discounts/apply', [DiscountController::class, 'applyDiscount'])->name('discounts.apply');
