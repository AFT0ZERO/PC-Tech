<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BuildSlot extends Model
{
    use HasFactory;

    public $timestamps = false;

    protected $fillable = ['category_id', 'min_qty', 'max_qty'];

    public function category()
    {
        return $this->belongsTo(Category::class);
    }
}
