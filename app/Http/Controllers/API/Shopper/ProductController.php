<?php

namespace App\Http\Controllers\API\Shopper;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\User;
use App\Models\Category;
use App\Models\Vendor_subcategory;
use App\Models\Like;
use App\Models\Review;
use App\Models\Order;
use Carbon\Carbon;


class ProductController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        //
        if(empty($request->token)) return response()->json(['status'=>false,'message'=>'Authorization token is required.']);
        if(empty($request->user_id)) return response()->json(['status'=>false,'message'=>'User is required.']);


        $user = User::where('id',$request->user_id)
        ->where('role',1)
        ->where('status',1)
        ->where('is_deleted',0)
        ->where('auth_token',$request->token)
        ->first();

        if(empty($user)) return response()->json(['status'=>false,'message'=>'Unauthorize user.']);

        $category = Category::where('status',1)
                                ->where('is_deleted',0)
                                ->latest()
                                ->get();

        $product = Product::where('status',1)->where('is_deleted',0);

        //search by category
        if(!empty($request->category_id)){
             $product = $product->where('category_id',$request->category_id);
        }
        
        //search by keywords
        if(!empty($request->search)){
             $product = $product->where('title','LIKE',"%".$request->search."%");
        }
                               
        $product = $product->latest()->get();



       
        //size
        $productRaw = [];    
        foreach ($product as $key => $value) {

            $rawSize  =[];
            foreach ($value->product_size as $key1 => $value1) {
               $rawSize[] = [
                'id'=>$value1->size->id,
                'name'=>$value1->size->name
               ];
            }

            
             //color
            $rawColor = [];
            foreach ($value->product_color as $key2 => $value2) {

              $fav = Like::where('product_id',$value->id)
                              ->where('user_id',$user->id)
                              ->first();


               $rawColor[] = [
                'id'=>$value2->color->id,
                'name'=>$value2->color->name,
                'img1'=>$value2->img1,
                'img2'=>($value2->img2) ? $value2->img2:'',
                'img3'=>($value2->img3) ? $value2->img3:'',
                'img4'=>($value2->img4) ? $value2->img4:'',
               ]; 
            }


             //rewiew
            $rawRewiew  =[];
            foreach ($value->review as $key2 => $value2) {
               $rawRewiew[] = [
                'id'=>$value2->user->id,
                'name'=>$value2->user->name,
                'comment'=>$value2->comment,
                'date'=>Carbon::parse($value2->created_at)->format('d F Y ')
               ];
            }

            $productRaw[] = [
                'id'                =>$value->id, 
                'title'             =>$value->title, 
                'description'       =>$value->description, 
                'img1'              =>$value->img1,
                'img2'              =>($value->img2) ? $value->img2:'',
                'img3'              =>($value->img3) ? $value->img3:'',
                'img4'              =>($value->img4) ? $value->img4:'', 
                'sub_subcategory_id'=>$value->sub_subcategory_id, 
                'subcategory_id'    =>$value->subcategory_id, 
                'category_id'       =>$value->category_id,
                'price'             =>$value->price,
                'product_size'      =>$rawSize,
                'product_color'     =>$rawColor,
                'liked'             =>(!empty($fav) ? 1:0),
                'reviews'           =>$rawRewiew

            ];
        }


       $data['status']  = true;
       $data['data']    = [
        'category'=>$category,
        'selected_category'=>isset($request->category_id) ?$request->category_id:"",
        'product'=>$productRaw,
        
       ];
       $data['message'] = 'Explorer data.';
       return response()->json($data);




    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function store_products(Request $request)
    {
        //
        if(empty($request->token)) return response()->json(['status'=>false,'message'=>'Authorization token is required.']);
        if(empty($request->user_id)) return response()->json(['status'=>false,'message'=>'User is required.']);


        $user = User::where('id',$request->user_id)
        ->where('role',1)
        ->where('status',1)
        ->where('is_deleted',0)
        ->where('auth_token',$request->token)
        ->first();
        
        if(empty($user)) return response()->json(['status'=>false,'message'=>'Unauthorize user.']);


        if(empty($request->vendor_id)) return response()->json(['status'=>false,'message'=>'Vendor id is required.']);



        $subcategoryRaw = Vendor_subcategory::where('vendor_id',$request->vendor_id)
                                ->where('status',1)
                                ->where('is_deleted',0)
                                ->latest()
                                ->get();

        
        $subcategory =[];
        foreach ($subcategoryRaw as $key => $value) {
                   # code...
            $subcategory[] = [
                'id'=>$value->subcategory->id,
                'image'=>$value->subcategory->image,
                'en_subcategory'=>$value->subcategory->en_subcategory,
                'ar_subcategory'=>$value->subcategory->ar_subcategory,
                'category_id'=>$value->subcategory->category_id
            ];
        }       

        $product = Product::where('vendor_id',$request->vendor_id)
        ->where('status',1)
        ->where('is_deleted',0);

        //search by category
        if(!empty($request->subcategory_id)){

             $product = $product->where('subcategory_id',$request->subcategory_id);
        }
        
        //search by keywords
        if(!empty($request->search)){
             $product = $product->where('title','LIKE',"%".$request->search."%");
        }
                               
        $product = $product->latest()->get();




        $productRaw = [];    
        foreach ($product as $key => $value) {

            $rawSize  =[];
            foreach ($value->product_size as $key1 => $value1) {
               $rawSize[] = [
                'id'=>$value1->size->id,
                'name'=>$value1->size->name
               ];
            }


            $rawColor = [];
            foreach ($value->product_color as $key2 => $value2) {

              $fav = Like::where('product_id',$value->id)
                              ->where('user_id',$user->id)
                              ->first();

            $rawColor[] = [
                'id'=>$value2->color->id,
                'name'=>$value2->color->name,
                'img1'=>$value2->img1,
                'img2'=>($value2->img2) ? $value2->img2:'',
                'img3'=>($value2->img3) ? $value2->img3:'',
                'img4'=>($value2->img4) ? $value2->img4:'',
               ]; 
            }


            $rawRewiew  =[];
            foreach ($value->review as $key2 => $value2) {
               $rawRewiew[] = [
                'id'=>$value2->id,
                'user_id'=>$value2->user->id,
                'name'=>$value2->user->name,
                'comment'=>$value2->comment,
                'date'=>Carbon::parse($value2->created_at)->format('d F Y ')
               ];
            }


            $productRaw[] = [
                'id'                =>$value->id, 
                'title'             =>$value->title, 
                'description'       =>$value->description, 
                'img1'              =>$value->img1,
                'img2'              =>($value->img2) ? $value->img2:'',
                'img3'              =>($value->img3) ? $value->img3:'',
                'img4'              =>($value->img4) ? $value->img4:'', 
                'sub_subcategory_id'=>($value->sub_subcategory_id) ? $value->sub_subcategory_id:'', 
                'subcategory_id'    =>$value->subcategory_id, 
                'category_id'       =>$value->category_id,
                'price'             =>$value->price,
                'product_size'      =>$rawSize,
                'product_color'     =>$rawColor,
                'vendor_id'         =>$value->vendor_id,
                'liked'             =>(!empty($fav) ? 1:0),
                'reviews'           =>$rawRewiew

            ];
        }


       $data['status']  = true;
       $data['data']    = [
        'subcategory'=>$subcategory,
        'selected_subcategory'=>isset($request->subcategory_id) ?$request->subcategory_id:"",
        'product'=>$productRaw,
        
       ];
       $data['message'] = 'Store products.';
       return response()->json($data);






    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function review(Request $request)
    {
        //
        if(empty($request->token)) return response()->json(['status'=>false,'message'=>'Authorization token is required.']);
        if(empty($request->user_id)) return response()->json(['status'=>false,'message'=>'User is required.']);


        $user = User::where('id',$request->user_id)
        ->where('role',1)
        ->where('status',1)
        ->where('is_deleted',0)
        ->where('auth_token',$request->token)
        ->first();
        
        if(empty($user)) return response()->json(['status'=>false,'message'=>'Unauthorize user.']);

        if(empty($request->product_id)) return response()->json(['status'=>false,'message'=>'Product id is required.']);

        if(empty($request->rating)) return response()->json(['status'=>false,'message'=>'Rating is required.']);

        
        $product = Product::find($request->product_id);

        $review = new Review;
        $review->product_id = $product->id;
        $review->vendor_id  = $product->vendor_id;
        $review->user_id    = $user->id;
        $review->rating     = $request->rating;
        $review->comment    = $request->comment;
        $review->save();

        $data['status']  = true;
        $data['message'] = "Review has been submitted successfully.";
        return response()->json($data);



    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
