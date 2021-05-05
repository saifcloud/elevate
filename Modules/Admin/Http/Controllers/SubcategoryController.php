<?php

namespace Modules\Admin\Http\Controllers;

use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;

use App\Models\Subcategory;
use App\Models\Category;

class SubcategoryController extends Controller
{
    /**
     * Display a listing of the resource.
     * @return Renderable
     */
   public function index()
    {
        $page_title = "Subcategory";
        $subcategory = Subcategory::where('status',1)->where('is_deleted',0)->latest()->get();
        return view('admin::subcategory.index',compact('page_title','subcategory'));
    }

    /**
     * Show the form for creating a new resource.
     * @return Renderable
     */
    public function create()
    {
        $page_title = "Subcategory create";
        $category = Category::where('status',1)->where('is_deleted',0)->latest()->get();
        return view('admin::subcategory.create',compact('page_title','category'));
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
        'en_subcategory' =>'required',
        'ar_subcategory' =>'required', 
        'category' =>'required', 
        'image'       =>'required'
        ],[
          'en_subcategory.required'=>'English subcategory name is required.',
          'ar_subcategory.required'=>'Arabic subcategory name is required.'
        ]);

        $file = $request->image;
        $filename = time().'.'.$file->getClientOriginalExtension();
        $file->move('public/images/subcategory',$filename);

        $subcategory = new Subcategory;
        $subcategory->en_subcategory = $request->en_subcategory;
        $subcategory->ar_subcategory = $request->ar_subcategory;
        $subcategory->category_id = $request->category;
        $subcategory->image       = '/public/images/subcategory/'.$filename;
        $subcategory->save();
        return  redirect('admin/subcategory')->with('success','Subcategory added successfully.');
    }

    /**
     * Show the specified resource.
     * @param int $id
     * @return Renderable
     */
    public function show($id)
    {
        return view('admin::subcategory.show');
    }

    /**
     * Show the form for editing the specified resource.
     * @param int $id
     * @return Renderable
     */
    public function edit($id)
    {
        $page_title = "Subcategory edit";
        $subcategory = Subcategory::find(base64_decode($id));
        $category = Category::where('status',1)->where('is_deleted',0)->latest()->get();
        return view('admin::subcategory.edit',compact('page_title','subcategory','category'));
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
        'en_subcategory' =>'required',
        'ar_subcategory' =>'required', 
        'category'       =>'required'
        ],[
          'en_subcategory.required'=>'English subcategory name is required.',
          'ar_subcategory.required'=>'Arabic subcategory name is required.'
        ]);

        $subcategory = Subcategory::find(base64_decode($request->subcategory_id));
        $subcategory->en_subcategory = $request->en_subcategory;
        $subcategory->ar_subcategory = $request->ar_subcategory;
        $subcategory->category_id = $request->category;
        
        if($request->has('image')){
        $file = $request->image;
        $filename = time().'.'.$file->getClientOriginalExtension();
        $file->move('public/images/subcategory',$filename);
        $subcategory->image       = '/public/images/subcategory/'.$filename;
        }
        $subcategory->save();
        return  redirect('admin/subcategory')->with('success','Subcategory updated successfully.');

    }

    /**
     * Remove the specified resource from storage.
     * @param int $id
     * @return Renderable
     */
    public function destroy($id)
    {
        //
         $subcategory = Subcategory::find(base64_decode($id));
         $subcategory->is_deleted = 1;
         $subcategory->save();

         return back()->with('success','Subcategory deleted successfully.');
    }
}
