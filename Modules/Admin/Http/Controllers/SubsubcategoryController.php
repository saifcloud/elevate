<?php

namespace Modules\Admin\Http\Controllers;

use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;

use App\Models\Subsubcategory;
use App\Models\Subcategory;
use App\Models\Category;

class SubsubcategoryController extends Controller
{
    /**
     * Display a listing of the resource.
     * @return Renderable
     */
    public function index()
    {
        $page_title = "Subsubcategory";
        $subsubcategory = Subsubcategory::where('status',1)->where('is_deleted',0)->latest()->get();
        return view('admin::subsubcategory.index',compact('page_title','subsubcategory'));
    }

    /**
     * Show the form for creating a new resource.
     * @return Renderable
     */
    public function create()
    {
        $page_title = "Subsubcategory create";
        $subcategory = Subcategory::where('status',1)->where('is_deleted',0)->latest()->get();
        $category = Category::where('status',1)->where('is_deleted',0)->latest()->get();
        return view('admin::subsubcategory.create',compact('page_title','subcategory','category'));
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
        'en_subsubcategory' =>'required',
        'ar_subsubcategory' =>'required', 
        'subcategory' =>'required', 
        'category' =>'required', 
        'image'       =>'required'
        ],[
          'en_subsubcategory.required'=>'English subcategory name is required.',
          'ar_subsubcategory.required'=>'Arabic subcategory name is required.'
        ]);

        $file = $request->image;
        $filename = time().'.'.$file->getClientOriginalExtension();
        $file->move('public/images/subsubcategory',$filename);

        $subsubcategory = new Subsubcategory;
        $subsubcategory->en_subsubcategory = $request->en_subsubcategory;
        $subsubcategory->ar_subsubcategory = $request->ar_subsubcategory;
        $subsubcategory->subcategory_id = $request->subcategory;
        $subsubcategory->category_id = $request->category;
        $subsubcategory->image       = '/public/images/subsubcategory/'.$filename;
        $subsubcategory->save();
        return  redirect('admin/sub-subcategory')->with('success','Subcategory added successfully.');
    }

    /**
     * Show the specified resource.
     * @param int $id
     * @return Renderable
     */
    public function show($id)
    {
        return view('admin::subsubcategory.show');
    }

    /**
     * Show the form for editing the specified resource.
     * @param int $id
     * @return Renderable
     */
    public function edit($id)
    {
        $page_title = "Subsubcategory edit";

        $subsubcategory =  Subsubcategory::find(base64_decode($id));
        $subcategory = Subcategory::where('category_id',$subsubcategory->category_id)->where('status',1)->where('is_deleted',0)->latest()->get();
        $category = Category::where('status',1)->where('is_deleted',0)->latest()->get();
        return view('admin::subsubcategory.edit',compact('page_title','subsubcategory','subcategory','category'));
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
        'en_subsubcategory' =>'required',
        'ar_subsubcategory' =>'required', 
        'subcategory' =>'required', 
        'category' =>'required', 
        // 'image'       =>'required'
        ],[
          'en_subsubcategory.required'=>'English subcategory name is required.',
          'ar_subsubcategory.required'=>'Arabic subcategory name is required.'
        ]);
        
        $subsubcategory =  Subsubcategory::find(base64_decode($request->subsubcategory_id));
        $subsubcategory->en_subsubcategory = $request->en_subsubcategory;
        $subsubcategory->ar_subsubcategory = $request->ar_subsubcategory;
        $subsubcategory->subcategory_id = $request->subcategory;
        $subsubcategory->category_id = $request->category;

        if($request->has('image')){
            $file = $request->image;
            $filename = time().'.'.$file->getClientOriginalExtension();
            $file->move('public/images/subsubcategory',$filename);
            $subsubcategory->image       = '/public/images/subsubcategory/'.$filename;
        }
        $subsubcategory->save();
        return  redirect('admin/sub-subcategory')->with('success','Subcategory updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     * @param int $id
     * @return Renderable
     */
    public function destroy($id)
    {
        //
          $subsubcategory =  Subsubcategory::find(base64_decode($id));
          $subsubcategory->is_deleted = 1;
          $subsubcategory->save();
          return back()->with('success','Subcategory deleted successfully.');
    }


    public function get_subcategory(Request $request){
     $subcategory = Subcategory::where('category_id',$request->category_id)->where('status',1)->where('is_deleted',0)->latest()->get();
           
           $html= '<option value="">--select--</option>';
           if(count($subcategory) > 0){
             foreach($subcategory as $row ){
             $html.= '<option value="'.$row->id.'">'.$row->en_subcategory.'</option>';
             }
          }

          echo $html;
    }


    
}
