<?php

namespace App\Http\Controllers\API\Vendor;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Product;
use App\Models\Product_size;
use App\Models\Product_color;

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

        $user = User::where('id',$request->user_id)->where('auth_token',$request->token)->first();
        if(empty($user)) return response()->json(['status'=>false,'message'=>'Unauthorize user.']);

          

        $product                       = new Product;
        $product->title                = $request->title;
        $product->description          = $request->description;
        $product->price                = $request->price;
        
        $file1 = $request->image[0];
        $filename1 = time().'1.'.$file1->getClientOriginalExtension();
        $file1->move('public/images/product', $filename1);
        $product->img1                 = $filename1;

        if(isset($request->image[1])){
        $file2 = $request->image[1];
        $filename2 = time().'2.'.$file2->getClientOriginalExtension();
        $file2->move('public/images/product', $filename2);  
        $product->img2                 = $filename2;
        }
        
        
        if(isset($request->image[2])){
        $file3 = $request->image[2];
        $filename3 = time().'3.'.$file3->getClientOriginalExtension();
        $file3->move('public/images/product', $filename3);
        $product->img3                 = $filename3;
        }
       
        
        if(isset($request->image[3])){
        $file4 = $request->image[3];
        $filename4 = time().'4.'.$file4->getClientOriginalExtension();
        $file4->move('public/images/product', $filename4);
        $product->img4                 = $filename4;
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
                $p_size->img1        = $filename5;

                if(isset($value[1])){
                $file6 = $value[1];
                $filename6 = time().$key.'2.'.$file6->getClientOriginalExtension();
                $file6->move('public/images/product', $filename6);
                $p_size->img2        = $filename6;
                }

                if(isset($value[2])){
                $file7 = $value[2];
                $filename7 = time().$key.'3.'.$file7->getClientOriginalExtension();
                $file7->move('public/images/product', $filename7);
                 $p_size->img3        = $filename7;
                }


                if(isset($value[3])){
                $file8 = $value[3];
                $filename8 = time().$key.'4.'.$file8->getClientOriginalExtension();
                $file8->move('public/images/product', $filename8);
                $p_size->img4        = $filename8;
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
