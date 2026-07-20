<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class StoreProduct extends Model
{
    protected $table = 'store_product';

    protected $fillable = [
        'store_id', 'product_id', 'product_price', 'product_url', 'product_status',
    ];

    public function store()
    {
        return $this->belongsTo(Store::class);
    }

    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    public function priceHistories()
    {
        return $this->hasMany(PriceHistory::class, 'sp_id');
    }
}
