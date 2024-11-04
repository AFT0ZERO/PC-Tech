<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Store extends Model
{
    use HasFactory,softDeletes,SoftDeletes;
    protected $guarded = [];
    public function toSearchableArray()
    {
        return [
            'name'=>$this->name
        ];
    }
    public function products()
    {
        return $this->belongsToMany(Product::class, 'store_product', 'store_id','product_id' )
            ->withPivot('product_price', 'product_url' ,'product_status');
    }
}
