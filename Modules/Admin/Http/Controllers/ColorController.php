<?php

namespace Modules\Admin\Http\Controllers;

use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;

use App\Models\Color;
use App\Models\Category;
use App\Models\Subcategory;
use App\Models\Subsubcategory;
use App\Models\Type;

class ColorController extends Controller
{
    /**
     * Display a listing of the resource.
     * @return Renderable
     */
     public function index()
    {
        $page_title = "Color";
        $color = Color::where('status',1)->where('is_deleted',0)->latest()->get();
        return view('admin::color.index',compact('page_title','color'));
    }

    /**
     * Show the form for creating a new resource.
     * @return Renderable
     */
    public function create()
    {
        $page_title = "Color create";
        $subcategory = Color::where('status',1)->where('is_deleted',0)->latest()->get();
        $category = Category::where('status',1)->where('is_deleted',0)->latest()->get();
        return view('admin::color.create',compact('page_title','subcategory','category'));
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
        'color'       =>'required'
        ]);

        // $file = $request->image;
        // $filename = time().'.'.$file->getClientOriginalExtension();
        // $file->move('public/images/subsubcategory',$filename);

        $color = new Color;
        $color->name = $request->color;
        $color->type = $request->type;
        $color->subsubcategory_id = $request->sub_subcategory;
        $color->subcategory_id = $request->subcategory;
        $color->category_id = $request->category;
        // $subsubcategory->image       = '/public/images/subsubcategory/'.$filename;
        $color->save();
        return  redirect('admin/color')->with('success','Size added successfully.');
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
        $page_title = "Color edit";

        $color =  Color::find(base64_decode($id));

        $type = Type::where('subsubcategory_id',$color->subsubcategory_id)->where('status',1)->where('is_deleted',0)->latest()->get();
        $subsubcategory = Subsubcategory::where('subcategory_id',$color->subcategory_id)->where('status',1)->where('is_deleted',0)->latest()->get();
        $subcategory = Subcategory::where('category_id',$color->category_id)->where('status',1)->where('is_deleted',0)->latest()->get();
        $category = Category::where('status',1)->where('is_deleted',0)->latest()->get();

        return view('admin::color.edit',compact('page_title','subsubcategory','subcategory','category','color'));
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
        'color'       =>'required'
        ]);

        // $file = $request->image;
        // $filename = time().'.'.$file->getClientOriginalExtension();
        // $file->move('public/images/subsubcategory',$filename);

        $color = Color::find(base64_decode($request->color_id));
        $color->name = $request->color;
        $color->type = $request->type;
        $color->subsubcategory_id = $request->sub_subcategory;
        $color->subcategory_id = $request->subcategory;
        $color->category_id = $request->category;
        // $subsubcategory->image       = '/public/images/subsubcategory/'.$filename;
        $color->save();
        return  redirect('admin/color')->with('success','Color updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     * @param int $id
     * @return Renderable
     */
    public function destroy($id)
    {
        //
          $color =  Color::find(base64_decode($id));
          $color->is_deleted = 1;
          $color->save();
          return back()->with('success','Color deleted successfully.');
    }
}
