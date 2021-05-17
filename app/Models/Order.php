<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    use HasFactory;

    public function order_details(){
    	return $this->hasMany('App\Models\Order_details','order_id','order_id');
    }
    public function user(){
        return $this->hasOne('App\Models\User','id','user_id');
    }

     public function tracking(){
        return $this->hasOne('App\Models\Tracking','order_id','order_id');
    }
}
