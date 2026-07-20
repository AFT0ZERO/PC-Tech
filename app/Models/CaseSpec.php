<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CaseSpec extends Model
{
    use HasFactory;

    public $incrementing = false;
    protected $primaryKey = 'product_id';
    protected $keyType = 'string';
    public $timestamps = false;

    protected $fillable = [
        'product_id', 'supported_form_factors',
        'max_gpu_length_mm', 'max_cooler_height_mm', 'specs',
    ];

    protected $casts = [
        'supported_form_factors' => 'json',
        'specs' => 'json',
    ];

    public function product()
    {
        return $this->belongsTo(Product::class);
    }
}
