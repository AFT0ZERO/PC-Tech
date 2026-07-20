<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Laravel\Scout\Searchable;

class Product extends Model
{
    use HasFactory, SoftDeletes, Searchable;

    protected $fillable = ['category_id', 'name', 'description', 'brand', 'power_draw_watts'];

    public function toSearchableArray()
    {
        return [
            'name'=>$this->name,
            'brand'=>$this->brand
        ];
    }

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function images()
    {
        return $this->hasMany(ProductImage::class);
    }

    public function stores()
    {
        return $this->belongsToMany(Store::class, 'store_product', 'product_id', 'store_id')
            ->withPivot('product_price', 'product_url','product_status');
    }

    public function favoredBy()
    {
        return $this->belongsToMany(User::class, 'favorite');
    }

    public function feedbacks()
    {
        return $this->hasMany(Feedback::class);
    }

    public function builds()
    {
        return $this->belongsToMany(Build::class, 'build_items')
                    ->withPivot('quantity')
                    ->withTimestamps();
    }

    public function cpuSpec()
    {
        return $this->hasOne(CpuSpec::class);
    }

    public function motherboardSpec()
    {
        return $this->hasOne(MotherboardSpec::class);
    }

    public function ramSpec()
    {
        return $this->hasOne(RamSpec::class);
    }

    public function storageSpec()
    {
        return $this->hasOne(StorageSpec::class);
    }

    public function gpuSpec()
    {
        return $this->hasOne(GpuSpec::class);
    }

    public function psuSpec()
    {
        return $this->hasOne(PsuSpec::class);
    }

    public function caseSpec()
    {
        return $this->hasOne(CaseSpec::class);
    }

    public function cpuCoolerSpec()
    {
        return $this->hasOne(CpuCoolerSpec::class);
    }

    public function coolerSpec()
    {
        return $this->hasOne(CpuCoolerSpec::class);
    }
}
