@extends('admin::layouts.master')
@section('content')

<div id="wrapper">

  
  @include('admin::partials.navbar')
  @include('admin::partials.sidebar')

    <div id="main-content">
        <div class="block-header">
            <div class="row clearfix">
                <div class="col-md-6 col-sm-12">
                    <h2>{{ isset($page_title) ? $page_title:''}}</h2>
                </div>            
                <div class="col-md-6 col-sm-12 text-right">
                    <ul class="breadcrumb">
                        <li class="breadcrumb-item"><a href="index.html"><i class="icon-home"></i></a></li>
                        <!-- <li class="breadcrumb-item active">Dashboard</li> -->
                    </ul>
                   <!--  <a href="javascript:void(0);" class="btn btn-sm btn-primary" title="">Create New</a> -->
                </div>
            </div>
        </div>

        <div class="container-fluid">

           <div class="row clearfix">
                <div class="col-md-12">
                    <div class="card">
                        <div class="header">
                            <h2>Basic Validation</h2>
                        </div>
                        <div class="body">
                            <form id="basic-form" method="post" novalidate action="{{ url('admin/type-update')}}" enctype="multipart/form-data">
                                @csrf

                                <input type="hidden" name="type_id" value="{{ base64_encode($type->id) }}">

                                <div class="row">

                                <div class="form-group col-sm-6">
                                    <label>English type</label>
                                    <input type="text" class="form-control" name="en_type" value="{{ $type->en_type}}">
                                    <p class="text-danger">{{ $errors->first('en_type') }}</p>
                                </div>
                                <div class="form-group col-sm-6">
                                    <label>Arabic type</label>
                                    <input type="email" class="form-control" name="ar_type" value="{{ $type->ar_type}}">
                                    <p class="text-danger">{{ $errors->first('ar_type') }}</p>
                                </div>

                              

                                </div>


                                 <div class="row">

                               
                                <div class="form-group col-sm-6">
                                    <label>Image</label>
                                    <input type="file" class="form-control" name="image" required>
                                     <p class="text-danger">{{ $errors->first('image') }}</p>
                                </div>


                                 <div class="form-group col-sm-6">
                                    <label>Category</label>
                                    <select name="category" class="form-control" id="category">
                                        <option value="">--select--</option>
                                        @if(count($category) > 0)
                                        @foreach($category as $row )
                                        <option value="{{ $row->id }}" {{ $row->id == $type->category_id ? "selected":""}}>{{ $row->en_category }}</option>
                                        @endforeach
                                        @endif
                                    </select>
                                     <p class="text-danger">{{ $errors->first('category') }}</p>
                                </div>

                                </div>


                                <div class="row">
                                 <div class="form-group col-sm-6">
                                   <img src="{{ url('/').$row->image }}" width="200px" height="120px">
                                 </div>
                               </div>




                                  <div class="row">

                               

                                 <div class="form-group col-sm-6">
                                    <label>Subcategory</label>
                                    <select name="subcategory" class="form-control" id="subcategory">
                                         <option value="">--select--</option>
                                        @if(count($subcategory) > 0)
                                        @foreach($subcategory as $row )
                                        <option value="{{ $row->id }}" {{ $row->id == $type->subcategory_id ? "selected":""}}>{{ $row->en_subcategory }}</option>
                                        @endforeach
                                        @endif
                                       
                                    </select>
                                     <p class="text-danger">{{ $errors->first('subcategory') }}</p>
                                </div>


                                 <div class="form-group col-sm-6">
                                    <label>Subsubcategory</label>
                                    <select name="subsubcategory" class="form-control" id="subsubcategory">
                                         <option value="">--select--</option>
                                        @if(count($subsubcategory) > 0)
                                        @foreach($subsubcategory as $row )
                                        <option value="{{ $row->id }}" {{ $row->id == $type->subsubcategory_id ? "selected":""}}>{{ $row->en_subsubcategory }}</option>
                                        @endforeach
                                        @endif
                                       
                                    </select>
                                     <p class="text-danger">{{ $errors->first('subsubcategory') }}</p>
                                </div>

                                </div>




                               <br> 
                                <button type="submit" class="btn btn-primary">Update</button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
</div>


@endsection