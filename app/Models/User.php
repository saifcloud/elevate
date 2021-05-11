<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name',
        'email',
        'password',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];


    public function category(){
        return $this->hasOne('App\Models\Category','id','category_id');
    }
    public function vendor_subcategory(){
        return $this->hasMany('App\Models\Vendor_subcategory','category_id','category_id');
    }


    public function products(){
        return $this->hasMany('App\Models\Product','vendor_id','id');
    }

    public function following(){
        return $this->hasMany('App\Models\Follow','follower_id','id');
    }

    public function follower(){
        return $this->hasMany('App\Models\Follow','user_id','id');
    }

   


   
    // public function vendor_sub_subcategory(){
    //     return $this->hasMany('App\Models\Subsubcategory','category_id','category_id');
    // }
}
