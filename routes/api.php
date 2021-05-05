<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

use App\Http\Controllers\API\AuthController;

use App\Http\Controllers\API\Vendor\CategoryController;
use App\Http\Controllers\API\Vendor\VendorController;
use App\Http\Controllers\API\Vendor\ProfileController;

use App\Http\Controllers\API\Vendor\SubcategoryController;


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
	Route::post('get-profile-details',[ProfileController::class,'index']);
	Route::post('update-profile-details',[ProfileController::class,'store']);

	//
	Route::post('get-vendor-subcategory',[SubcategoryController::class,'create']);
	Route::post('add-product',[ProfileController::class,'store']);

});


// Route::middleware('auth:api')->get('/user', function (Request $request) {
//     return $request->user();
// });
