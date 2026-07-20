<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RamSpec extends Model
{
    use HasFactory;

    public $incrementing = false;
    protected $primaryKey = 'product_id';
    protected $keyType = 'string';
    public $timestamps = false;

    protected $fillable = ['product_id', 'type', 'capacity_gb', 'specs'];

    protected $casts = ['specs' => 'json'];

    public function product()
    {
        return $this->belongsTo(Product::class);
    }
}
