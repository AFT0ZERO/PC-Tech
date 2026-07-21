<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Laravel\Scout\Searchable;

class Category extends Model
{
    use HasFactory , softDeletes  , Searchable;
    public function toSearchableArray()
    {
        return [
            'name'=>$this->name
        ];
    }
    protected $guarded = [];
    public function products(){
        return $this->hasMany(Product::class);
    }

    /**
     * The build slot configured for this category (min/max quantities in a PC build),
     * or null when the category is not part of the PC Builder.
     */
    public function buildSlot()
    {
        return $this->hasOne(BuildSlot::class);
    }

    /**
     * Whether this category participates in the PC Builder.
     * Buildability is data-driven (a build_slots row), never a hardcoded list.
     */
    public function isBuildable(): bool
    {
        return $this->buildSlot()->exists();
    }

    /**
     * The key identifying this category inside the compatibility engine
     * (e.g. "cpu", "cpu_cooler"). Derived from specs_table (cpu_specs => cpu)
     * so rules never reference category IDs or display names.
     * Null for generic categories without a dedicated CTI specs table.
     */
    public function specKey(): ?string
    {
        if ($this->specs_table === null || $this->specs_table === '') {
            return null;
        }

        return preg_replace('/_specs$/', '', $this->specs_table);
    }

    protected static function boot()
    {
        parent::boot();

        static::deleting(function ($category) {
            $category->products()->delete();
        });
    }

}
