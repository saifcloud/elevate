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
}
