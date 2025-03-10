<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasFactory,HasApiTokens;

    protected $fillable = [
        'name',
        'email',
        'licensed_number',
        'usertype',
        'password',
        'profile_picture',
    ];

    protected $hidden = [
        'password',
    ];

    public function sessions()
    {
        return $this->hasMany(\Session::class);
    }

    public function orderRequests()
    {
        return $this->hasMany(OrderRequest::class);
    }

    public function results()
    {
        return $this->hasMany(Result::class);
    }
}
