<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BuildItem extends Model
{
    use HasFactory;

    protected $table = 'build_items';

    protected $fillable = ['build_id', 'product_id', 'quantity'];

    public function build()
    {
        return $this->belongsTo(Build::class);
    }

    public function product()
    {
        return $this->belongsTo(Product::class);
    }
}
