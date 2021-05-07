<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Subsubcategory extends Model
{
    use HasFactory;

     public function category(){
    	return $this->hasOne('App\Models\Category','id','category_id');
    }
     public function subcategory(){
    	return $this->hasOne('App\Models\Subcategory','id','subcategory_id');
    }

     public function type(){
    	return $this->hasMany('App\Models\Type','subsubcategory_id','id');
    }

    public function size(){
        return $this->hasMany('App\Models\Size','subsubcategory_id','id');
    }

     public function color(){
        return $this->hasMany('App\Models\Color','subsubcategory_id','id');
    }
}
