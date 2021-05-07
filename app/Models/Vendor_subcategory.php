<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Vendor_subcategory extends Model
{
    use HasFactory;

    public function subcategory(){
    	return $this->hasOne('App\Models\Subcategory','id','subcategory_id');
    }
    public function sub_subcategory(){
    	return $this->hasMany('App\Models\Subsubcategory','subcategory_id','subcategory_id')->with('type');
    }

    // public function type(){
    // 	return $this->hasMany('App\Models\Subsubcategory','id','subsubcategory_id');
    // }
}
