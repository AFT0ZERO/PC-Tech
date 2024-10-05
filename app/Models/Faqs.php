<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Laravel\Scout\Searchable;

class Faqs extends Model
{
    use HasFactory ,SoftDeletes,Searchable;
    public function toSearchableArray()
    {
        return [
            'question'=>$this->question,
            'answer'=>$this->answer,
            'created_at'=>$this->created_at,
        ];
    }

    public $guarded = [];
}
