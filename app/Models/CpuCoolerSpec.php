<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CpuCoolerSpec extends Model
{
    use HasFactory;

    public $incrementing = false;
    protected $primaryKey = 'product_id';
    protected $keyType = 'string';
    public $timestamps = false;

    protected $fillable = ['product_id', 'supported_sockets', 'height_mm'];

    protected $casts = [
        'supported_sockets' => 'json',
    ];

    public function product()
    {
        return $this->belongsTo(Product::class);
    }
}
