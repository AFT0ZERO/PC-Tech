<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CpuSpec extends Model
{
    use HasFactory;

    public $incrementing = false;
    protected $primaryKey = 'product_id';
    protected $keyType = 'string';
    public $timestamps = false;

    protected $fillable = ['product_id', 'socket', 'specs'];

    protected $casts = ['specs' => 'json'];

    public function product()
    {
        return $this->belongsTo(Product::class);
    }
}
