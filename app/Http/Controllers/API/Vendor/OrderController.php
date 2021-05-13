<?php

namespace App\Http\Controllers\API\Vendor;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Models\Order;
use App\Models\Order_details;
use App\Models\User;


use Carbon\Carbon;
use DB;

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
          //
        if(empty($request->token)) return response()->json(['status'=>false,'message'=>'Authorization token is required.']);
        if(empty($request->user_id)) return response()->json(['status'=>false,'message'=>'User is required.']);

        $user = User::where('id',$request->user_id)
                                        ->where('is_deleted',0)
                                        ->where('role',2)
                                        ->where('auth_token',$request->token)
                                        ->first();
                                        
        if(empty($user)) return response()->json(['status'=>false,'message'=>'Unauthorize user.']);


        $order_list = [];
        $order =  Order::where('vendor_id',$user->id)->where('is_deleted',0)->where('delivery_status',0)->get();

        if($request->filter_type=="CURRENT"){
           $order = $order->where('status',1);
        }
        if($request->filter_type=="COMPLETED"){
           $order = $order->where('status',2);
        }
        if($request->filter_type=="CANCELED"){
           $order = $order->where('status',3);
        }
        

        if(count($order)==0) return response()
                                             ->json(['status'=>false,'message'=>'No any order.']);

        foreach ($order as $key => $value) {
            
             $items = [];
             foreach ($value->order_details as $key1 => $value1) {
                $items[] = [
                           'product_id'=>$value1->product->id,
                           'image'=>$value1->product->img1,
                           'title'=>$value1->product->title,
                           'qty'=>$value1->qty,
                           'color'=>$value1->color->name,
                           'size'=>$value1->size->name,
                           'amount'=>$value1->amount *$value1->qty,
                           'time'=>Carbon::parse($value->created_at)->diffForHumans(),
                ];
             }



            if($value->status==1){
              $orderst = "CURRENT";
            }
            if($value->status==2){
                $orderst = "COMPLETED";
            }
            if($value->status==3){
                $orderst = "CANCELED";
            }



             $order_list[] = [
                        'shopper'  =>$value->user->name,
                        'order_id' =>$value->order_id,
                        'items'    =>$items,
                        'location' =>$value->shipping_address,
                        'lat'      =>$value->lat,
                        'long'     =>$value->long,
                        'subtotal' =>$value->total,
                        'shopping_fee'=> 2,
                        'VAT'      => 3,
                        'total'    =>$value->total,
                        'status'   =>$orderst
                       
                      
                        
             ];
             
        }
        
        $data['status']  = true;
        $data['data']    = ['orders'=>$order_list];
        $data['message'] = "Order lists";
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

        if(empty($request->token)) return response()->json(['status'=>false,'message'=>'Authorization token is required.']);
        if(empty($request->user_id)) return response()->json(['status'=>false,'message'=>'User is required.']);

        $user = User::where('id',$request->user_id)
                                        ->where('is_deleted',0)
                                        ->where('role',2)
                                        ->where('auth_token',$request->token)
                                        ->first();
                                        
        if(empty($user)) return response()->json(['status'=>false,'message'=>'Unauthorize user.']);

        if(empty($request->order_id)) return response()->json(['status'=>false,'message'=>'Order id is required.']);

        if(empty($request->status)) return response()->json(['status'=>false,'message'=>'Status is required.']);

        $order =  Order::where('order_id',$request->order_id)
                                                    ->where('vendor_id',$user->id)
                                                    ->where('status',1)
                                                    ->where('is_deleted',0)
                                                    ->where('delivery_status',0)
                                                   ->first();

        if(empty($order)) return response()->json(['status'=>false,'message'=>'Order not found check order id.']);

        if($request->status=="CURRENT"){
           $order->status = 1;
        }
        if($request->status=="COMPLETED"){
           $order->status = 2;
        }
        if($request->status=="CANCELED"){
           $order->status = 3;
        }
        if($order->save()){
            $data['status']  = true;
            $data['message'] = "Status has been changed";
        
        }else{
            $data['status']  = false;
            $data['message'] = "Status cannot changed please check, status key"; 
        }
        return response()->json($data); 

       



    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function sale(Request $request)
    {
        //

        if(empty($request->token)) return response()->json(['status'=>false,'message'=>'Authorization token is required.']);
        if(empty($request->user_id)) return response()->json(['status'=>false,'message'=>'User is required.']);

        $user = User::where('id',$request->user_id)
                                        ->where('is_deleted',0)
                                        ->where('role',2)
                                        ->where('auth_token',$request->token)
                                        ->first();
                                        
        if(empty($user)) return response()->json(['status'=>false,'message'=>'Unauthorize user.']);


        $saleTotal=  Order::where('vendor_id',$user->id)
                                                    ->where('status','!=',3)
                                                    ->where('is_deleted',0);

        // if($request->filter_type=="DAY"){
        //   $saleTotal = $saleTotal->select(DB::raw("total as amount"),DB::raw("DATE_FORMAT(created_at,'%h:%i %p') as name"))->whereDate('created_at',Carbon::today());
        // }

        if($request->filter_type =="WEEK"){
          $saleTotal = $saleTotal->select(DB::raw("(SUM(total)) as amount"),DB::raw("DAYNAME(created_at) as name"))->whereBetween('created_at',
            [Carbon::now()->startOfWeek(),Carbon::now()->endOfWeek()]
          )->groupBy('name');

        }else if($request->filter_type=="MONTH"){
          $saleTotal = $saleTotal->select(DB::raw("(SUM(total)) as amount"),DB::raw("DATE_FORMAT(created_at,'%d-%b-%Y') as name"))
          ->whereMonth('created_at',date('m'))
          ->whereYear('created_at',date('Y'))
          ->groupBy('name');

        }else if($request->filter_type=="YEAR"){
          $saleTotal = $saleTotal->select(DB::raw("(SUM(total)) as amount"),DB::raw("MONTHNAME(created_at) as name"))
          ->whereYear('created_at', date('Y'))
          ->groupBy('name');

        }else{
            $saleTotal = $saleTotal->select(DB::raw("total as amount"),DB::raw("DATE_FORMAT(created_at,'%h:%i %p') as name"))->whereDate('created_at',Carbon::today());
        }

        return $saleTotal->get();

        $total_sale   =[];
        $total_amount =[];


        $data['status']  = true;
        $data['data']    = ['total_sale'=>$total_sale,'total_amount'=>$total_amount];
        $data['message'] = "Sale"; 
        
        return response()->json($data); 


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
