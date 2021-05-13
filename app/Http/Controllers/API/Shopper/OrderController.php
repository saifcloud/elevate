<?php

namespace App\Http\Controllers\API\Shopper;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Models\User;
use App\Models\Cart;
use App\Models\Order;
use App\Models\Order_details;
use App\Models\Product;

class OrderController extends Controller
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

        if(empty($request->location)) return response()->json(['status'=>false,'message'=>'location required.']);

        if(empty($request->lat)) return response()->json(['status'=>false,'message'=>'Latitude is required.']);


        if(empty($request->long)) return response()->json(['status'=>false,'message'=>'Longitude user.']);

        
        $cart = Cart::where('vendor_id',$request->vendor_id)
                                                    ->where('user_id',$user->id)
                                                    ->where('is_deleted',0)
                                                    ->get();

        $order_id =  'ORDER'.$user->id.mt_rand();

        
         $tamount = [];
         
        foreach ($cart as $key => $value) {

            $product = Product::find($value->product_id);

            $orderdetails = new Order_details;
            $orderdetails->order_id  = $order_id;
            $orderdetails->product_id= $value->product_id;
            $orderdetails->size_id   = $value->size_id;
            $orderdetails->color_id  = $value->color_id;
            $orderdetails->qty       = $value->qty;
            $orderdetails->amount    = $product->price;
            $orderdetails->user_id   = $value->user_id;
            $orderdetails->vendor_id = $value->vendor_id;
            $orderdetails->save();

            $tamount[] = $product->price * $value->qty;
        }

        $order = new Order;
        $order->order_id = $order_id;
        $order->user_id  = $user->id;
        $order->vendor_id = $request->vendor_id;
        $order->total    = array_sum($tamount);
        $order->shipping_address = $request->location;
        $order->lat      = $request->lat;
        $order->long     = $request->long;
        $order->save();


        $data['status'] = true;
        $data['data'] = ['order_id'=>$order_id];
        $data['message'] = "Order place successfully.";
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
