<?php

namespace App\Http\Controllers\API\Vendor;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Models\Category;
use App\Models\Subcategory;
use App\Models\Subsubcategory;
use App\Models\Type;
use App\Models\User;
use App\Models\Size;
use App\Models\Color;
use App\Models\Product;



class VendorController extends Controller
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


       $user = User::where('id',$request->user_id)->where('auth_token',$request->token)->first();
       if(empty($user)) return response()->json(['status'=>false,'message'=>'Unauthorize user.']);
        
       $basicinfo = [
        'image'=>$user->image,
        'name'=>$user->name,
        'en_category'=>$user->category->en_category,
        'ar_category'=>$user->category->ar_category,
        'bio'=>$user->bio,
        'followers'=>200,
        'following'=>250,
        'rating'=>4
       ];
       
       $categoryRaw = [];
       foreach ($user->vendor_subcategory as $key => $value) {
           # code...
        $categoryRaw[] = [
            'id' =>$value->subcategory->id,
            'en_subcategory' =>$value->subcategory->en_subcategory,
            'ar_subcategory' =>$value->subcategory->ar_subcategory,
            
        ];
       }
        
        $subcategory_id = $user->vendor_subcategory->first()->subcategory->id;
        $product        = Product::where('subcategory_id',$subcategory_id)
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
                'product_color'     =>$rawColor
            ];
        }

       $data['status']  = true;
       $data['data']    = ['basicinfo'=>$basicinfo,'subcategory'=>$categoryRaw,'product'=>$productRaw,'selected_subcategory'=>$subcategory_id];
       $data['message'] = 'Home page data.';
       return response()->json($data);

    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request)
    {
        //
        if(empty($request->token)) return response()->json(['status'=>false,'message'=>'Authorization token is required.']);
        if(empty($request->user_id)) return response()->json(['status'=>false,'message'=>'User is required.']);

        $user = User::where('id',$request->user_id)->where('auth_token',$request->token)->first();
        if(empty($user)) return response()->json(['status'=>false,'message'=>'Unauthorize user.']);

         $size = Size::where('subsubcategory_id',$request->sub_subcategory_id)
        ->where('subcategory_id',$request->subcategory_id)
        ->where('status',1)
        ->where('is_deleted',0)
        ->get();

         $color = Color::where('subsubcategory_id',$request->sub_subcategory_id)
        ->where('subcategory_id',$request->subcategory_id)
        ->where('status',1)
        ->where('is_deleted',0)
        ->get();


       $data['status']  = true;
       $data['data']    = ['sizes'=>$size,'colors'=>$color];
       $data['message'] = 'Selected category and subcategory attributes.';
       return response()->json($data);



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
