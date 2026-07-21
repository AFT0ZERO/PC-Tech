<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Build extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'pc_builds';

    protected $fillable = [
        'user_id',
        'name',
        'is_public',
    ];

    protected $casts = [
        'is_public' => 'boolean',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function products()
    {
        return $this->belongsToMany(Product::class, 'build_items')
                    ->withPivot('quantity')
                    ->withTimestamps();
    }

    public function items()
    {
        return $this->hasMany(BuildItem::class);
    }

    /**
     * Estimated total of the build from each product's cheapest current store
     * price (requires products eager-loaded with the cheapest_price sub-select,
     * see BuildRepository::getUserBuildsWithProducts). Prices change over time,
     * so the total is computed on read instead of being stored on the build.
     */
    public function estimatedTotal(): float
    {
        return (float) $this->products->sum(
            fn (Product $p) => (float) ($p->cheapest_price ?? 0) * (int) ($p->pivot->quantity ?? 1)
        );
    }
}
