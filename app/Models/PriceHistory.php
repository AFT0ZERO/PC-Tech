<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PriceHistory extends Model
{
    use HasFactory;

    protected $table = 'price_history';

    protected $fillable = [
        'sp_id',
        'price',
        'currency',
        'scraped_at',
        'status',
    ];

    protected $casts = [
        'scraped_at' => 'datetime',
        'price'      => 'decimal:2',
    ];

    public function storeProduct()
    {
        return $this->belongsTo(\App\Models\StoreProduct::class, 'sp_id');
    }
}
