<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Order_details extends Model
{
    use HasFactory;

    public function product(){
    	return $this->hasOne('App\Models\Product','id','product_id');
    }
    public function color(){
    	return $this->hasOne('App\Models\Color','id','color_id');
    }
    public function size(){
    	return $this->hasOne('App\Models\Size','id','size_id');
    }
}
