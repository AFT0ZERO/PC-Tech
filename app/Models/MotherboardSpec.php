<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MotherboardSpec extends Model
{
    use HasFactory;

    public $incrementing = false;
    protected $primaryKey = 'product_id';
    protected $keyType = 'string';
    public $timestamps = false;

    protected $fillable = [
        'product_id', 'socket', 'supported_ram_type',
        'ram_slots', 'max_ram_capacity_gb', 'form_factor',
    ];

    public function product()
    {
        return $this->belongsTo(Product::class);
    }
}
