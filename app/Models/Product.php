<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Laravel\Scout\Searchable;

class Product extends Model
{
    use HasFactory,softDeletes,Searchable;
    protected $guarded = [];
    public function toSearchableArray()
    {
        return [
            'name'=>$this->name
        ];
    }

    public function category(){
        return $this->belongsTo(Category::class);
    }

    public function images(){
        return $this->hasMany(ProductImage::class);
    }

    public function stores()
    {
        return $this->belongsToMany(Store::class, 'store_product', 'product_id', 'store_id')
            ->withPivot('product_price', 'product_url');
    }

    public function favoredBy()
    {
        return $this->belongsToMany(User::class, 'favorite');
    }

    public function feedbacks()
    {
        return $this->hasMany(Feedback::class);
    }
}
