<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BuildPart extends Model
{
    use HasFactory;

    protected $fillable = [
        'build_id',
        'product_id',
        'category_name',
    ];

    public function build()
    {
        return $this->belongsTo(Build::class);
    }

    public function product()
    {
        return $this->belongsTo(Product::class);
    }
}
