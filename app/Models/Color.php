<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Color extends Model
{
    use HasFactory;

     public function category(){
    	return $this->hasOne('App\Models\Category','id','category_id');
    }
     public function subcategory(){
    	return $this->hasOne('App\Models\Subcategory','id','subcategory_id');
    }

    public function subsubcategory(){
    	return $this->hasOne('App\Models\Subsubcategory','id','subsubcategory_id');
    }

     public function type(){
    	return $this->hasOne('App\Models\Type','id','type_id');
    }
}
