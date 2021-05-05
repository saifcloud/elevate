<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use Hash;
use Str;
use Auth;

use App\Models\User;
use App\Models\Vendor_subcategory;

class AuthController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        //
        if(empty($request->name)) return response()->json(['status'=>false,'message'=>'Name is required.']);

        if(empty($request->email)) return response()->json(['status'=>false,'message'=>'Name is required.']);

        if(empty($request->password)) return response()->json(['status'=>false,'message'=>'Password is required.']);

        if(empty($request->phone)) return response()->json(['status'=>false,'message'=>'Phone is required.']);

        if(empty($request->device_token)) return response()->json(['status'=>false,'message'=>'Device token is required.']);

        if(empty($request->fcm_token)) return response()->json(['status'=>false,'message'=>'Fcm token is required.']);

        if(empty($request->role)) return response()->json(['status'=>false,'message'=>'Role  is required.']);

        if($request->role==2){

           if(empty($request->commercial_reg_num)) return response()->json(['status'=>false,'message'=>'Commercial registration number is required.']);

           if(empty($request->category_id)) return response()->json(['status'=>false,'message'=>'Category is required.']);
        }



        
        $checkemail = User::where('email',$request->email)->where('status',1)->where('is_deleted',0)->first();

        if(!empty($checkemail)) return response()->json(['status'=>false,'message'=>'Email already exist.']);

        $checkphone = User::where('phone',$request->phone)->where('status',1)->where('is_deleted',0)->first();

        if(!empty($checkphone)) return response()->json(['status'=>false,'message'=>'Phone already exist.']);

        $user = new User;
        if($request->has('image')){
        $file = $request->image;
        $filename = time().'.'.$file->getClientOriginalExtension();
        $file->move('public/images/user',$filename);
        $user->image               = '/public/images/user/'.$filename;
        }
        $user->name                = $request->name;
        $user->email               = $request->email;
        $user->password            = Hash::make($request->password);
        $user->phone               = $request->phone;
        $user->commercial_reg_num  = $request->commercial_reg_num;
        $user->category_id         = $request->category_id;
        $user->fcm_token           = $request->fcm_token;
        $user->device_token        = $request->device_token;
        $user->role                = $request->role;
        $user->auth_token          =  Str::random(36);
        if($user->save()){
          

         if($request->role==2){
            
             foreach (explode(',',$request->subcategory) as  $value) {
                 # code...
                 $vdsc = new Vendor_subcategory;
                 $vdsc->vendor_id        = $user->id;
                 $vdsc->subcategory_id = $value;
                 $vdsc->category_id    = $request->category_id;
                 $vdsc->save();
             }
             
         }

         
         $data['status'] = true;
         $data['data'] = ['user_id'=>$user->id,'token'=>$user->auth_token,'role'=>$user->role]; 
         $data['message'] = 'Registration successfully.';
         return response()->json($data);
        }
        return response()->json(['status'=>false,'message'=>'Registration failed,try again.']);

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

         if(empty($request->loginby)) return response()->json(['status'=>false,'message'=>'Login by is required.']);

         if(filter_var(($request->loginby), FILTER_VALIDATE_EMAIL)){
          $user = User::where('email',$request->loginby)->where('status',1)->where('is_deleted',0)->first();
         }else{
          $user = User::where('phone',$request->loginby)->where('status',1)->where('is_deleted',0)->first();
         }
         if(empty($user)) return response()->json(['status'=>false,'message'=>'Account not found.']); 

         if(Hash::check($request->password,$user->password)){

            $user->auth_token = Str::random(36);
            $user->save();
            
            $data['status'] = true;
            $data['data'] = ['user_id'=>$user->id,'token'=>$user->auth_token,'role'=>$user->role];
            $data['message'] ='Login successfully.';

           return response()->json($data);
         }
         return response()->json(['status'=>false,'message'=>'Check your email or password.']);
            
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
