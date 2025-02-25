<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Contracts\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    use HasFactory;

    protected $fillable = ['name'];

    /**
     * Get the products for the category.
     */
    public function products()
    {
        return $this->hasMany(Product::class);
    }

    /**
     * The discounts that belong to the category.
     */
    public function discounts()
    {
        return $this->belongsToMany(Discount::class, 'category_discount');
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
