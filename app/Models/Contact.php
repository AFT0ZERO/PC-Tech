<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Laravel\Scout\Searchable;
class Contact extends Model
{
    use HasFactory, softDeletes, Searchable;

    public function user()  {
        return $this->belongsTo(User::class);
    }

   protected $guarded = [];
    public function toSearchableArray()
    {
        return [
            'name'=>$this->name,
            'email'=>$this->email,
            'mobile'=>$this->mobile,
            'message'=>$this->message,
            'created_at'=>$this->created_at,
        ];
    }
}
