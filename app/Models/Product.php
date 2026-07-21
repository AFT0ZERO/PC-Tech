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

    /**
     * Every CTI spec relation on this model — used to eager-load specs in one query.
     */
    public const SPEC_RELATIONS = [
        'cpuSpec', 'motherboardSpec', 'ramSpec', 'storageSpec',
        'gpuSpec', 'psuSpec', 'caseSpec', 'cpuCoolerSpec',
    ];

    /**
     * The single mapping point between a category spec key (Category::specKey())
     * and the spec relation on this model. A category whose key is absent here is
     * treated as a generic category without a dedicated specs table.
     */
    private const SPEC_KEY_TO_RELATION = [
        'cpu' => 'cpuSpec',
        'motherboard' => 'motherboardSpec',
        'ram' => 'ramSpec',
        'storage' => 'storageSpec',
        'gpu' => 'gpuSpec',
        'psu' => 'psuSpec',
        'case' => 'caseSpec',
        'cpu_cooler' => 'cpuCoolerSpec',
    ];

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

    /**
     * Name of the spec relation matching this product's category,
     * or null for generic categories without a CTI specs table.
     */
    public function specRelationName(): ?string
    {
        $key = $this->category?->specKey();

        return $key !== null ? (self::SPEC_KEY_TO_RELATION[$key] ?? null) : null;
    }

    /**
     * The actual spec model attached to this product (cpu_specs row, ...),
     * or null when the category has no dedicated specs table.
     */
    public function specModel(): ?Model
    {
        $relation = $this->specRelationName();

        return $relation !== null ? $this->{$relation} : null;
    }
}
