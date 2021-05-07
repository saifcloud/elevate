<?php

namespace App\Http\Controllers\API\Shopper;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Models\User;
use App\Models\Product;

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


        $user = User::where('id',$request->user_id)->where('auth_token',$request->token)->first();
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
                    'category'=>$value->category->en_category,
                    'category'=>$value->category->ar_category,
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
                    'like_count'=>20,
                    'comments_count'=>10,
                    'product_size'      =>$rawSize,
                    'product_color'     =>$rawColor,
                    'reviews'           =>''
                   
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
