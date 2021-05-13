<?php

namespace App\Http\Controllers\API\Vendor;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Product;
use App\Models\Product_size;
use App\Models\Product_color;
use App\Models\Review;


use Carbon\Carbon;

class ProductController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */

    // subcategory-selected-products
    public function index(Request $request)
    {
        //
        if(empty($request->token)) return response()->json(['status'=>false,'message'=>'Authorization token is required.']);
        if(empty($request->user_id)) return response()->json(['status'=>false,'message'=>'User is required.']);

        $user = User::where('id',$request->user_id)->where('auth_token',$request->token)->first();
        if(empty($user)) return response()->json(['status'=>false,'message'=>'Unauthorize user.']);


        $product        = Product::where('subcategory_id',$request->subcategory_id)
                                                        ->where('vendor_id',$user->id)
                                                        ->where('status',1)
                                                        ->where('is_deleted',0)
                                                        // ->limit(18)
                                                        ->get(); 
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
               $rawColor[] = [
                'id'=>$value2->color->id,
                'name'=>$value2->color->name,
                'img1'=>$value2->img1,
                'img2'=>($value2->img2) ? $value2->img2:'',
                'img3'=>($value2->img3) ? $value2->img3:'',
                'img4'=>($value2->img4) ? $value2->img4:'',
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
                'reviews'           =>'',
            ];
        }


       $data['status']  = true;
       $data['data']    = ['product'=>$productRaw,'selected_subcategory'=>$request->subcategory_id];
       $data['message'] = 'Home page data.';
       return response()->json($data);


        
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
           // print_r($request->all()); die;
        
        if(empty($request->token)) return response()->json(['status'=>false,'message'=>'Authorization token is required.']);
        if(empty($request->user_id)) return response()->json(['status'=>false,'message'=>'User is required.']);

        $user = User::where('id',$request->user_id)->where('auth_token',$request->token)->where('role',2)->first();
        if(empty($user)) return response()->json(['status'=>false,'message'=>'Unauthorize user.']);

          

        $product                       = new Product;
        $product->title                = $request->title;
        $product->description          = $request->description;
        $product->price                = $request->price;
        
        $file1 = $request->image[0];
        $filename1 = time().'1.'.$file1->getClientOriginalExtension();
        $file1->move('public/images/product', $filename1);
        $product->img1                 = '/public/images/product/'.$filename1;

        if(isset($request->image[1])){
        $file2 = $request->image[1];
        $filename2 = time().'2.'.$file2->getClientOriginalExtension();
        $file2->move('public/images/product', $filename2);  
        $product->img2                 = '/public/images/product/'.$filename2;
        }
        
        
        if(isset($request->image[2])){
        $file3 = $request->image[2];
        $filename3 = time().'3.'.$file3->getClientOriginalExtension();
        $file3->move('public/images/product', $filename3);
        $product->img3                 = '/public/images/product/'.$filename3;
        }
       
        
        if(isset($request->image[3])){
        $file4 = $request->image[3];
        $filename4 = time().'4.'.$file4->getClientOriginalExtension();
        $file4->move('public/images/product', $filename4);
        $product->img4                 = '/public/images/product/'.$filename4;
        }
        
 
        $product->vendor_id            = $user->id;
        $product->type_id              = $request->type_id;
        $product->sub_subcategory_id   = $request->sub_subcategory_id;
        $product->subcategory_id       = $request->subcategory_id;
        $product->category_id          = $user->category_id;
        $product->save();

        
        foreach (explode(",", $request->size) as $key => $value) {
            # code...
            $p_size = new Product_size;
            $p_size->size_id    = $value;
            $p_size->product_id = $product->id;
            $p_size->save();
        }

         foreach ($request->color as $key => $value) {
           
           
                $p_size = new Product_color;
                $p_size->color_id    = $key;
                $p_size->product_id  = $product->id;

                $file5 = $value[0];
                $filename5 = time().$key.'1.'.$file5->getClientOriginalExtension();
                $file5->move('public/images/product', $filename5);
                $p_size->img1        = '/public/images/product/'.$filename5;

                if(isset($value[1])){
                $file6 = $value[1];
                $filename6 = time().$key.'2.'.$file6->getClientOriginalExtension();
                $file6->move('public/images/product', $filename6);
                $p_size->img2        = '/public/images/product/'.$filename6;
                }

                if(isset($value[2])){
                $file7 = $value[2];
                $filename7 = time().$key.'3.'.$file7->getClientOriginalExtension();
                $file7->move('public/images/product', $filename7);
                 $p_size->img3        = '/public/images/product/'.$filename7;
                }


                if(isset($value[3])){
                $file8 = $value[3];
                $filename8 = time().$key.'4.'.$file8->getClientOriginalExtension();
                $file8->move('public/images/product', $filename8);
                $p_size->img4        = '/public/images/product/'.$filename8;
                }
                
                $p_size->save();
           
          
          
        
        }
       $data['status']  = true;
       $data['message'] = 'Item added successfully.';
       return response()->json($data);







    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function product_details(Request $request)
    {
        //
        if(empty($request->token)) return response()->json(['status'=>false,'message'=>'Authorization token is required.']);
        if(empty($request->user_id)) return response()->json(['status'=>false,'message'=>'User is required.']);

        $user = User::where('id',$request->user_id)->where('auth_token',$request->token)->where('role',2)->first();
        if(empty($user)) return response()->json(['status'=>false,'message'=>'Unauthorize user.']);
 
          $product = Product::where('id',$request->product_id)
                                                          ->where('vendor_id',$user->id)
                                                          ->where('status',1)
                                                          ->where('is_deleted',0)
                                                          ->first();


        $productRaw = [];    
       

            $rawSize  =[];
            foreach ($product->product_size as $key1 => $value1) {
               $rawSize[] = [
                'id'=>$value1->size->id,
                'name'=>$value1->size->name
               ];
            }


            $rawColor = [];
            foreach ($product->product_color as $key2 => $value2) {
               $rawColor[] = [
                'id'=>$value2->color->id,
                'name'=>$value2->color->name,
                'img1'=>$value2->img1,
                'img2'=>($value2->img2) ? $value2->img2:'',
                'img3'=>($value2->img3) ? $value2->img3:'',
                'img4'=>($value2->img4) ? $value2->img4:'',
               ]; 
            }


            $rawReview = [];
            foreach ($product->review as $key3 => $value3) {

                $rvimg = [

                ];
               $rawReview[] = [
                'id'=>$value3->id,
                'user_id'  =>$value3->user->id,
                'image'  =>$value3->user->image,
                'name'   =>$value3->user->name,
                'comment'=>$value3->comment,
                'review_images' =>[
                            $value3->img1,
                            $value3->img2,
                            $value3->img3,
                            $value3->img4,
                ],
                'date'=>Carbon::parse($value3->created_at)->format('d F Y ')
                
               ]; 
            }

            $productRaw = [
                'id'                =>$product->id, 
                'title'             =>$product->title, 
                'description'       =>$product->description, 
                'img1'              =>$product->img1,
                'img2'              =>($product->img2) ? $product->img2:'',
                'img3'              =>($product->img3) ? $product->img3:'',
                'img4'              =>($product->img4) ? $product->img4:'', 
                'sub_subcategory_id'=>$product->sub_subcategory_id, 
                'subcategory_id'    =>$product->subcategory_id, 
                'category_id'       =>$product->category_id,
                'price'             =>$product->price,
                'product_size'      =>$rawSize,
                'product_color'     =>$rawColor,
                'reviews'           =>$rawReview,
            ];
        




       $data['status']  = true;
       $data['data']    = ['product'=>$productRaw];
       $data['message'] = 'Single product details.';
       return response()->json($data);



    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function get_reviews(Request $request)
    {
        //
        if(empty($request->token)) return response()->json(['status'=>false,'message'=>'Authorization token is required.']);
        if(empty($request->user_id)) return response()->json(['status'=>false,'message'=>'User is required.']);

        $user = User::where('id',$request->user_id)
                                            ->where('auth_token',$request->token)
                                            ->where('role',2)
                                            ->where('is_deleted',0)
                                            ->first();


        if(empty($user)) return response()->json(['status'=>false,'message'=>'Unauthorize user.']);

        if(empty($request->product_id)) return response()->json(['status'=>false,'message'=>'Product id is required.']);
        $skip = (empty($request->skip)) ? 0:$request->skip;
        
        $reviewRaw = Review::where('product_id',$request->product_id)
        ->where('is_deleted',0)
        ->skip($skip)
        ->limit(2)
        ->get();
        $review = [];
        foreach ($reviewRaw as $key => $value) {
            $review[] = [
                'id'=>$value->id,
                'user_id'  =>$value->user->id,
                'image'  =>$value->user->image,
                'name'   =>$value->user->name,
                'comment'=>$value->comment,
                'review_images' =>[
                            $value->img1,
                            $value->img2,
                            $value->img3,
                            $value->img4,
                ],
                'date'=>Carbon::parse($value->created_at)->format('d F Y ')
            ];
        }

        
       
       $data['status']  = true;
       $data['data']    = ['review'=>$review];
       $data['message'] = 'Reviews list.';
       return response()->json($data);





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
