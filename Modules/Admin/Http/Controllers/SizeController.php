<?php

namespace Modules\Admin\Http\Controllers;

use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;

use App\Models\Category;
use App\Models\Subcategory;
use App\Models\Subsubcategory;
use App\Models\Type;
use App\Models\Size;

class SizeController extends Controller
{
    /**
     * Display a listing of the resource.
     * @return Renderable
     */
     public function index()
    {
        $page_title = "Size";
        $size = Size::where('status',1)->where('is_deleted',0)->latest()->get();
        return view('admin::size.index',compact('page_title','size'));
    }

    /**
     * Show the form for creating a new resource.
     * @return Renderable
     */
    public function create()
    {
        $page_title = "Size create";
        $subcategory = Size::where('status',1)->where('is_deleted',0)->latest()->get();
        $category = Category::where('status',1)->where('is_deleted',0)->latest()->get();
        return view('admin::size.create',compact('page_title','subcategory','category'));
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
        // 'type' =>'required',
        'sub_subcategory' =>'required', 
        'subcategory' =>'required', 
        'category' =>'required', 
        'size'       =>'required'
        ]);

        // $file = $request->image;
        // $filename = time().'.'.$file->getClientOriginalExtension();
        // $file->move('public/images/subsubcategory',$filename);

        $subsubcategory = new Size;
        $subsubcategory->name = $request->size;
        $subsubcategory->type = $request->type;
        $subsubcategory->subsubcategory_id = $request->sub_subcategory;
        $subsubcategory->subcategory_id = $request->subcategory;
        $subsubcategory->category_id = $request->category;
        // $subsubcategory->image       = '/public/images/subsubcategory/'.$filename;
        $subsubcategory->save();
        return  redirect('admin/size')->with('success','Size added successfully.');
    }

    /**
     * Show the specified resource.
     * @param int $id
     * @return Renderable
     */
    public function show($id)
    {
        return view('admin::size.show');
    }

    /**
     * Show the form for editing the specified resource.
     * @param int $id
     * @return Renderable
     */
    public function edit($id)
    {
        $page_title = "Size edit";

        $size =  Size::find(base64_decode($id));

        $type = Type::where('subsubcategory_id',$size->subsubcategory_id)->where('status',1)->where('is_deleted',0)->latest()->get();
        $subsubcategory = Subsubcategory::where('subcategory_id',$size->subcategory_id)->where('status',1)->where('is_deleted',0)->latest()->get();
        $subcategory = Subcategory::where('category_id',$size->category_id)->where('status',1)->where('is_deleted',0)->latest()->get();
        $category = Category::where('status',1)->where('is_deleted',0)->latest()->get();
        return view('admin::size.edit',compact('page_title','subsubcategory','subcategory','category','size'));
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
        // 'type' =>'required',
        'sub_subcategory' =>'required', 
        'subcategory' =>'required', 
        'category' =>'required', 
        'size'       =>'required'
        ]);

        // $file = $request->image;
        // $filename = time().'.'.$file->getClientOriginalExtension();
        // $file->move('public/images/subsubcategory',$filename);

        $subsubcategory = Size::find(base64_decode($request->size_id));
        $subsubcategory->name = $request->size;
        $subsubcategory->type = $request->type;
        $subsubcategory->subsubcategory_id = $request->sub_subcategory;
        $subsubcategory->subcategory_id = $request->subcategory;
        $subsubcategory->category_id = $request->category;
        // $subsubcategory->image       = '/public/images/subsubcategory/'.$filename;
        $subsubcategory->save();
        return  redirect('admin/size')->with('success','Size updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     * @param int $id
     * @return Renderable
     */
    public function destroy($id)
    {
        //
          $size =  Size::find(base64_decode($id));
          $size->is_deleted = 1;
          $size->save();
          return back()->with('success','Size deleted successfully.');
    }
}
