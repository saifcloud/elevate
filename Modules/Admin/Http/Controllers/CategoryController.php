<?php

namespace Modules\Admin\Http\Controllers;

use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;

use App\Models\Category;

class CategoryController extends Controller
{
    /**
     * Display a listing of the resource.
     * @return Renderable
     */
    public function index()
    {
        $page_title = "Category";
        $category = Category::where('status',1)->where('is_deleted',0)->latest()->get();
        return view('admin::category.index',compact('page_title','category'));
    }

    /**
     * Show the form for creating a new resource.
     * @return Renderable
     */
    public function create()
    {
        $page_title = "Category create";
        return view('admin::category.create',compact('page_title'));
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
        'en_category' =>'required',
        'ar_category' =>'required', 
        'image'       =>'required'
        ],[
          'en_category.required'=>'English category name is required.',
          'ar_category.required'=>'Arabic category name is required.'
        ]);

        $file = $request->image;
        $filename = time().'.'.$file->getClientOriginalExtension();
        $file->move('public/images/category',$filename);

        $category = new Category;
        $category->en_category = $request->en_category;
        $category->ar_category = $request->ar_category;
        $category->image       = '/public/images/category/'.$filename;
        $category->save();
        return  redirect('admin/category')->with('success','Category added successfully.');
    }

    /**
     * Show the specified resource.
     * @param int $id
     * @return Renderable
     */
    public function show($id)
    {
        return view('admin::category.show');
    }

    /**
     * Show the form for editing the specified resource.
     * @param int $id
     * @return Renderable
     */
    public function edit($id)
    {
        $page_title = "Category edit";
        $category = Category::find(base64_decode($id));
        return view('admin::category.edit',compact('page_title','category'));
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
        'en_category' =>'required',
        'ar_category' =>'required', 
        // 'image'       =>'required'
        ],[
          'en_category.required'=>'English category name is required.',
          'ar_category.required'=>'Arabic category name is required.'
        ]);

        $category = Category::find(base64_decode($request->category_id));
        $category->en_category = $request->en_category;
        $category->ar_category = $request->ar_category;
        
        if($request->has('image')){
        $file = $request->image;
        $filename = time().'.'.$file->getClientOriginalExtension();
        $file->move('public/images/category',$filename);
        $category->image       = '/public/images/category/'.$filename;
        }
        $category->save();
        return  redirect('admin/category')->with('success','Category added successfully.');

    }

    /**
     * Remove the specified resource from storage.
     * @param int $id
     * @return Renderable
     */
    public function destroy($id)
    {
        //
         $category = Category::find(base64_decode($id));
         $category->is_deleted = 1;
         $category->save();

         return back()->with('success','Category deleted successfully.');
    }
}
