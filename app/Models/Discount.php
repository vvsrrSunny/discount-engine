<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Contracts\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Discount extends Model
{
    use HasFactory;

    protected $fillable = ['discount_type', 'name', 'amount', 'discount_applicable_type', 'active_from', 'active_to'];

    /**
     * The categories that belong to the discount.
     */
    public function categories()
    {
        return $this->belongsToMany(Category::class, 'category_discount');
    }

    public function activeDiscounts()
    {
        return $this->discounts()->active();
    }

    /**
     * Scope to get active discounts within the time range
     */
    public function scopeActive(Builder $query): Builder
    {
        $now = Carbon::now();

        return $query->where('active_from', '<=', $now)
            ->where('active_to', '>=', $now);
    }
}
