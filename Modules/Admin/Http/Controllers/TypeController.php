<?php

namespace Modules\Admin\Http\Controllers;

use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;

use App\Models\Type;
use App\Models\Subsubcategory;
use App\Models\Subcategory;
use App\Models\Category;

class TypeController extends Controller
{
    /**
     * Display a listing of the resource.
     * @return Renderable
     */
    public function index()
    {
        $page_title = "Type";
        $type = Type::where('status',1)->where('is_deleted',0)->latest()->get();
        return view('admin::type.index',compact('page_title','type'));
    }

    /**
     * Show the form for creating a new resource.
     * @return Renderable
     */
    public function create()
    {
        $page_title = "Type create";
        $subsubcategory = Subcategory::where('status',1)->where('is_deleted',0)->latest()->get();
        $subcategory = Subcategory::where('status',1)->where('is_deleted',0)->latest()->get();
        $category = Category::where('status',1)->where('is_deleted',0)->latest()->get();
        return view('admin::type.create',compact('page_title','subcategory','category'));
    }

    /**
     * Store a newly created resource in storage.
     * @param Request $request
     * @return Renderable
     */
    public function store(Request $request)
    {
        //
         $request->validate([
        'en_type' =>'required',
        'ar_type' =>'required', 
        'subsubcategory' =>'required', 
        'subcategory' =>'required', 
        'category' =>'required', 
        'image'       =>'required'
        ],[
          'en_type.required'=>'English type name is required.',
          'ar_type.required'=>'Arabic type name is required.'
        ]);

        $file = $request->image;
        $filename = time().'.'.$file->getClientOriginalExtension();
        $file->move('public/images/type',$filename);

        $type = new Type;
        $type->en_type           = $request->en_type;
        $type->ar_type           = $request->ar_type;
        $type->subsubcategory_id = $request->subsubcategory;
        $type->subcategory_id    = $request->subcategory;
        $type->category_id       = $request->category;
        $type->image             = '/public/images/type/'.$filename;
        $type->save();
        return  redirect('admin/type')->with('success','Type added successfully.');
    }

    /**
     * Show the specified resource.
     * @param int $id
     * @return Renderable
     */
    public function show($id)
    {
        return view('admin::type.show');
    }

    /**
     * Show the form for editing the specified resource.
     * @param int $id
     * @return Renderable
     */
    public function edit($id)
    {
        $page_title = "Type edit";
        
        $type =  Type::find(base64_decode($id));
        $subsubcategory =  Subsubcategory::where('subcategory_id',$type->subcategory_id)->where('status',1)->where('is_deleted',0)->latest()->get();
        $subcategory = Subcategory::where('category_id',$type->category_id)->where('status',1)->where('is_deleted',0)->latest()->get();
        $category = Category::where('status',1)->where('is_deleted',0)->latest()->get();
        return view('admin::type.edit',compact('page_title','subsubcategory','subcategory','category','type'));
    }

    /**
     * Update the specified resource in storage.
     * @param Request $request
     * @param int $id
     * @return Renderable
     */
    public function update(Request $request)
    {
        //

         $request->validate([
        'en_type' =>'required',
        'ar_type' =>'required', 
        'subsubcategory' =>'required', 
        'subcategory' =>'required', 
        'category' =>'required', 
        // 'image'       =>'required'
        ],[
          'en_type.required'=>'English type name is required.',
          'ar_type.required'=>'Arabic type name is required.'
        ]);
          

        $type =  Type::find(base64_decode($request->type_id));
        $type->en_type           = $request->en_type;
        $type->ar_type           = $request->ar_type;
        $type->subsubcategory_id = $request->subsubcategory;
        $type->subcategory_id    = $request->subcategory;
        $type->category_id       = $request->category;
        if($request->has('image')){
            $file = $request->image;
            $filename = time().'.'.$file->getClientOriginalExtension();
            $file->move('public/images/type',$filename);
            $type->image             = '/public/images/type/'.$filename;
        }
        $type->save();
        return  redirect('admin/type')->with('success','Type updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     * @param int $id
     * @return Renderable
     */
    public function destroy($id)
    {
        //
          $type =  Type::find(base64_decode($id));
          $type->is_deleted = 1;
          $type->save();
          return back()->with('success','Type deleted successfully.');
    }


    public function get_subsubcategory(Request $request){
      $subsubcategory = Subsubcategory::where('subcategory_id',$request->subcategory_id)->where('status',1)->where('is_deleted',0)->latest()->get();
           
           $html= '<option value="">--select--</option>';
           if(count($subsubcategory) > 0){
             foreach($subsubcategory as $row ){
             $html.= '<option value="'.$row->id.'">'.$row->en_subsubcategory.'</option>';
             }
          }

          echo $html;
    }


     public function get_type(Request $request){
       $type = Type::where('subsubcategory_id',$request->subsubcategory_id)->where('status',1)->where('is_deleted',0)->latest()->get();
           
           $html= '<option value="">--select--</option>';
           if(count($type) > 0){
             foreach($type as $row ){
             $html.= '<option value="'.$row->id.'">'.$row->en_type.'</option>';
             }
          }

          echo $html;
    }
}
