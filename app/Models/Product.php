<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory;

    public function user(){
    	return $this->hasOne('App\Models\User','id','vendor_id');
    }
    public function product_color(){
    	return $this->hasMany('App\Models\Product_color','product_id','id')->with('color');
    }
    public function product_size(){
    	return $this->hasMany('App\Models\Product_size','product_id','id')->with('size');
    }
    public function category(){
        return $this->belongsTo('App\Models\Category','category_id','id');
    }

    public function like(){
        return $this->hasMany('App\Models\Like','product_id','id');
    }
    public function review(){
        return $this->hasMany('App\Models\Review','product_id','id')->limit(2);
    }
    public function rating(){
        return $this->hasMany('App\Models\Review','product_id','id');
    }

}
