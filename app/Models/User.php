<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Laravel\Scout\Searchable;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable ,Searchable,softDeletes;

    public function contacts()  {
        return $this->hasMany(Contact::class);
    }

    public function toSearchableArray()
    {
        return [
            'fname'=>$this->fname,
            'lname'=>$this->lname,
            'email'=>$this->email,
            'mobile'=>$this->mobile,
            'gender'=>$this->gender,
            'role'=>$this->role,
            'created_at'=>$this->created_at,
        ];
    }


    public $guarded = [];
    protected $hidden = [
        'password',
        'remember_token',
    ];


    protected $casts = [
        'email_verified_at' => 'datetime',
    ];
}
