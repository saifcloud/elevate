<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

use App\Http\Controllers\API\AuthController;


//vendor
use App\Http\Controllers\API\Vendor\CategoryController;
use App\Http\Controllers\API\Vendor\VendorController;
use App\Http\Controllers\API\Vendor\ProfileController;
use App\Http\Controllers\API\Vendor\ProductController;
use App\Http\Controllers\API\Vendor\SubcategoryController;



//shopper

use App\Http\Controllers\API\Shopper\ShopperController;


/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/
Route::get('category-list',[CategoryController::class,'index']);

//register
Route::post('register',[AuthController::class,'index']);

//login
Route::post('login',[AuthController::class,'store']);

//vendor
Route::group(['prefix'=>'vendor'],function(){

	Route::post('home',[VendorController::class,'index']);

	Route::post('subcategory-selected-products',[ProductController::class,'index']);


    //PROFILE
	Route::post('get-profile-details',[ProfileController::class,'index']);

	Route::post('update-profile-details',[ProfileController::class,'store']);
   

   //ADD PRODUCT
	Route::post('get-vendor-subcategory',[SubcategoryController::class,'create']);

	Route::post('get-color-size-for-product',[VendorController::class,'create']);

	Route::post('add-product',[ProductController::class,'store']);

});



Route::group(['prefix'=>'shopper'],function(){

	Route::post('home',[ShopperController::class,'index']);
});


// Route::middleware('auth:api')->get('/user', function (Request $request) {
//     return $request->user();
// });
