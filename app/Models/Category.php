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
}