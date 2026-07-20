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

    public function buildItems()
    {
        return $this->hasMany(BuildItem::class);
    }
}
