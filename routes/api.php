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
use App\Http\Controllers\API\Vendor\OrderController;



//shopper

use App\Http\Controllers\API\Shopper\ShopperController;
use App\Http\Controllers\API\Shopper\ProductController as ShopperProduct;
use App\Http\Controllers\API\Shopper\ProfileController as ShopperProfile;
use App\Http\Controllers\API\Shopper\CartController;
use App\Http\Controllers\API\Shopper\OrderController  as ShopperOrder; 


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

	Route::post('order',[OrderController::class,'index']);

	Route::post('change-order-status',[OrderController::class,'store']);

	Route::post('sale',[OrderController::class,'sale']);


});


  
Route::group(['prefix'=>'shopper'],function(){
    
    //home
	Route::post('home',[ShopperController::class,'index']);
    
    //store profile
	Route::post('store-details',[ShopperController::class,'store_details']);
    
    //explorer
	Route::post('products-list',[ShopperProduct::class,'index']);

	//store list
	Route::post('show-stores-list',[ShopperController::class,'show_stores_list']);

	//store products
	Route::post('store-products',[ShopperProduct::class,'store_products']);


	//like
	Route::post('like',[ShopperController::class,'like']);

	//liked list
	Route::post('like-list',[ShopperController::class,'like_list']);

    //profile
	Route::post('profile',[ShopperProfile::class,'index']);

	 //profile
	Route::post('profile-update',[ShopperProfile::class,'store']);

	//follow unfollow
	Route::post('follow',[ShopperProfile::class,'follow']);

	//add to cart
	Route::post('add-to-cart',[CartController::class,'store']);
    
    //manage quantity
    Route::post('cart-product-qty',[CartController::class,'manager_qty']);

    //cart
	Route::post('cart',[CartController::class,'index']);


	 //remove from card
	Route::post('remove-cart-product',[CartController::class,'remove_product']);

	//order
	Route::post('order',[ShopperOrder::class,'index']);

	//review
	Route::post('review',[ShopperProduct::class,'review']);
});


// Route::middleware('auth:api')->get('/user', function (Request $request) {
//     return $request->user();
// });
