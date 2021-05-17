<?php

namespace App\Http\Controllers\API\Shopper;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Models\User;
use App\Models\Product;
use App\Models\Like;
use Carbon\Carbon;


class ShopperController extends Controller
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
        ->where('status',1)
        ->where('is_deleted',0)
        ->where('role',1)
        ->where('auth_token',$request->token)
        ->first();


        if(empty($user)) return response()->json(['status'=>false,'message'=>'Unauthorize user.']);  

       // return $vendors =  User::where('role',2)
       //  ->select('id','image','name')
       //  ->where('status',1)
       //  ->where('is_deleted',0)
       //  ->get();

       $productRaw = Product::where('status',1)
                                ->where('is_deleted',0)
                                ->latest()
                                ->get();

       $product =[];
       foreach ($productRaw as $key => $value) {
         
          $fav = Like::where('product_id',$value->id)
                              ->where('user_id',$user->id)
                              ->first();

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

            $rawRewiew  =[];
            foreach ($value->review as $key2 => $value2) {
               $rawRewiew[] = [
                'id'     =>$value2->user->id,
                'name'   =>$value2->user->image,
                'name'   =>$value2->user->name,
                'comment'=>$value2->comment,
                'date'   =>Carbon::parse($value2->created_at)->format('d F Y ')
               ];
            }


        $product[] = [
                    'vendor_id'           =>$value->user->id,
                    'vendor_image'      =>$value->user->image,
                    'vendor_name'       =>$value->user->name,
                    'category'          =>$value->category->en_category,
                    'category'          =>$value->category->ar_category,
                    'brief'             =>"New Collection from ".$value->user->name,
                    'product_id'        =>$value->id,
                    'image1'            =>$value->img1,
                    'image2'            =>($value->img2) ? $value->img2:'',
                    'image3'            =>($value->img3) ? $value->img3:'',
                    'image4'            =>($value->img4) ? $value->img4:'',
                    'title'             =>$value->title,
                    'description'       =>$value->description,
                    'price'             =>$value->price,
                    'liked'             =>(!empty($fav) ? 1:0),
                    'like_count'        =>$value->like->count(),
                    'comments_count'    =>count($rawRewiew),
                    'product_size'      =>$rawSize,
                    'product_color'     =>$rawColor,
                    'reviews'           =>$rawRewiew
                   
        ];
       }
      

       $data['status']  = true;
       $data['data']    = ['product'=>$product];
       $data['message'] = "Shopper home data.";

       return response()->json($data);






    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function show_stores_list(Request $request)
    {
        //
        if(empty($request->token)) return response()->json(['status'=>false,'message'=>'Authorization token is required.']);
        if(empty($request->user_id)) return response()->json(['status'=>false,'message'=>'User is required.']);


        $user = User::where('id',$request->user_id)->where('role',1)->where('auth_token',$request->token)->where('status',1)->where('is_deleted',0)->first();
        if(empty($user)) return response()->json(['status'=>false,'message'=>'Unauthorize user.']);  



       $vendor = User::select('id','image','name')->where('role',2)->where('status',1)->where('is_deleted',0)->get();
       
       $list = [];
       foreach ($vendor as $key => $value) {
           # code...
        $list[] = [
                   'id'=>$value->id,
                   'image'=>$value->image,
                   'name'=>$value->name,
                   'followers'=>$value->follower->count(),
        ];

       }

       $data['status']  = true;
       $data['data']    = ['vendor_list'=>$list];
       $data['message'] = "Stores list data.";

       return response()->json($data);


    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
   public function store_details(Request $request)
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


       $vendor = User::where('role',2)
                                        ->where('id',$request->vendor_id)
                                        ->where('status',1)
                                        ->where('is_deleted',0)
                                        ->first();
       $basicDetails = [
                         'id'   =>$vendor->id,
                         'image'=>$vendor->image,
                         'name'=>$vendor->name,
                         'en_category'=>$vendor->category->en_category,
                         'ar_category'=>$vendor->category->ar_category,
                         'bio'=>$vendor->bio,
                         'rating'=>4,
                         'followers'=>$vendor->follower->count(),
                         'following'=>$vendor->following->count(),
        ];


       
        $subcategory =[];
        foreach ($vendor->vendor_subcategory as $key => $value) {
            # code...
            $subcategory[] = [
                              'id'=>$value->subcategory->id,
                              'image'=>$value->subcategory->image,
                              'en_subcategory'=>$value->subcategory->en_subcategory,
                              'ar_subcategory'=>$value->subcategory->ar_subcategory,
                              'status'=> ($value->subcategory->id==$request->subcategory_id) ? 1:0,           
            ];
        }


        $banner = [
         ['id'=>1, 'image'=>$vendor->image],
         ['id'=>2, 'image'=>$vendor->image],
         ['id'=>3, 'image'=>$vendor->image],
        ];

         $summerCollection = [
         ['id'=>1, 'image'=>$vendor->image,'title'=>'static','price'=>10, 'liked'=>0],
         ['id'=>2, 'image'=>$vendor->image,'title'=>"static",'price'=>10, 'liked'=>0],
         ['id'=>3, 'image'=>$vendor->image,'title'=>'static','price'=>10, 'liked'=>0],
        ];




       $product =[];
       foreach ($vendor->products as $key => $value) {

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


        $product[] = [
                    'id'=>$value->user->id,
                    'vendor_image'=>$value->user->image,
                    'vendor_name'=>$value->user->name,
                    'en_category'=>$value->category->en_category,
                    'ar_category'=>$value->category->ar_category,
                    'brief'=>"New Collection from ".$value->user->name,
                    'product_id'=>$value->id,
                    'image1'=>$value->img1,
                    'image2'=>($value->img2) ? $value->img2:'',
                    'image3'=>($value->img3) ? $value->img3:'',
                    'image4'=>($value->img4) ? $value->img4:'',
                    'title'=>$value->title,
                    'description'=>$value->description,
                    'price'=>$value->price,
                    'favourited_status'=>1,
                    'like_count'=>$value->like->count(),
                    'comments_count'=>10,
                    'product_size'      =>$rawSize,
                    'product_color'     =>$rawColor,
                    'reviews'           =>''
                   
        ];
       }
      


   
       $data['status']  = true;
       $data['data']    = ['basic_details'=>$basicDetails,'subcategory'=>$subcategory,'banner'=>$banner,'summer_collection'=>$summerCollection,'product'=>$product];
       $data['message'] = "Stores details.";

       return response()->json($data);



        
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function like(Request $request)
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

        $product = Product::find($request->product_id);

        $checkLike = Like::where('product_id',$product->id)
         ->where('vendor_id',$product->vendor_id)
         ->first();

         if(!empty($checkLike)){
            $checkLike->delete();
            $data['status'] = true;
            $data['message'] = "Removed from like list.";
         }else{

            $like = new Like;
            $like->product_id = $product->id;
            $like->vendor_id  = $product->vendor_id;
            $like->user_id    = $request->user_id;
            $like->save();
            $data['status'] = true;
            $data['message'] = "Added in like list.";
         }

        return response()->json($data);

       







    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function like_list(Request $request)
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


        $productRaw = Like::where('user_id',$user->id)->get();
        $product = [];
        foreach ($productRaw as $key => $value) {
            # code...
            $rawSize  =[];
            foreach ($value->product->product_size as $key1 => $value1) {
               $rawSize[] = [
                'id'=>$value1->size->id,
                'name'=>$value1->size->name
               ];
            }


            $rawColor = [];
            foreach ($value->product->product_color as $key2 => $value2) {

              $fav = Like::where('product_id',$value->product->id)
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

            $product[] = [
                'id'                =>$value->product->id, 
                'title'             =>$value->product->title, 
                'description'       =>$value->product->description, 
                'img1'              =>$value->product->img1,
                'img2'              =>($value->product->img2) ? $value->product->img2:'',
                'img3'              =>($value->product->img3) ? $value->product->img3:'',
                'img4'              =>($value->product->img4) ? $value->product->img4:'', 
                'sub_subcategory_id'=>$value->product->sub_subcategory_id, 
                'subcategory_id'    =>$value->product->subcategory_id, 
                'category_id'       =>$value->product->category_id,
                'price'             =>$value->product->price,
                'product_size'      =>$rawSize,
                'product_color'     =>$rawColor,
                'liked'             =>(!empty($fav) ? 1:0),
                'reviews'           =>''

            ];
        }


          $data['status'] = true;
          $data['data'] = ['products'=>$product];
          $data['message'] = "Added in liked list.";
         

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
