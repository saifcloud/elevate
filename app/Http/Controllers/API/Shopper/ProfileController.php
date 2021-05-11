<?php

namespace App\Http\Controllers\API\Shopper;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Models\User;
use App\Models\Follow;
use Hash;    

class ProfileController extends Controller
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
         
         $basicdetails = [
         'id'=>$user->id,
         'image'=>$user->image,
         'name'=>$user->name,
         'email'=>$user->email,
         'phone'=>$user->phone,
         'bio'=>$user->bio,
         'followers'=>$user->follower->count(),,
         'following'=>$user->following->count()
         ];

         $data['status'] = true;
         $data['data'] = ['basic_details'=>$basicdetails,'repost'=>[],'user review'=>[]];
         $data['message'] = "User profile.";
         return response()->json($data);



    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function follow(Request $request)
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

        if(empty($request->vendor_id)) return response()->json([
            'status'=>false,
            'message'=>'Vendor id is required.'
        ]);
        
         $followcheck =Follow::where('user_id',$request->vendor_id)
                                 ->where('follower_id',$user->id)
                                 ->first();
        if(!empty($followcheck)){
         $followcheck->delete();

         $data['status'] = true;
         $data['message'] = "Unfollowed successfully.";

        }else{
        $follow = new Follow;
        $follow->user_id     = $request->vendor_id;
        $follow->follower_id = $user->id;  
        $follow->save();

         $data['status'] = true;
         $data['message'] = "Followed successfully..";

        }

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
        if(empty($request->token)) return response()->json(['status'=>false,'message'=>'Authorization token is required.']);
        if(empty($request->user_id)) return response()->json(['status'=>false,'message'=>'User is required.']);


        $user = User::where('id',$request->user_id)
        ->where('role',1)
        ->where('status',1)
        ->where('is_deleted',0)
        ->where('auth_token',$request->token)
        ->first();
        
        if(empty($user)) return response()->json(['status'=>false,'message'=>'Unauthorize user.']);

        if(empty($request->name)) return response()->json(['status'=>false,'message'=>'Name is required.']);

         $user->name = $request->name;
         if($request->has('image')){
            $file = $request->image;
            $filename = time().'.'.$file->getClientOriginalExtension();
            $file->move('public/images/user',$filename);
            $user->image               = '/public/images/user/'.$filename;
         }
          if($request->has('password')){
            $user->password = Hash::make($request->password);
          }
         $user->bio = $request->bio;
         $user->save();

        $data['status'] = true;
        $data['message'] = "Profile updated successfully.";
        return response()->json($data);
         


    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
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
