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
                            <form id="basic-form" method="post" novalidate action="{{ url('admin/category-update')}}" enctype="multipart/form-data">
                                @csrf
                                <input type="hidden" name="category_id" value="{{ base64_encode($category->id)}}">

                                <div class="row">

                                <div class="form-group col-sm-6">
                                    <label>English category</label>
                                    <input type="text" class="form-control" name="en_category" value="{{ $category->en_category}}">
                                    <p class="text-danger">{{ $errors->first('en_category') }}</p>
                                </div>
                                <div class="form-group col-sm-6">
                                    <label>Arabic category</label>
                                    <input type="email" class="form-control" name="ar_category" value="{{ $category->ar_category}}">
                                    <p class="text-danger">{{ $errors->first('ar_category') }}</p>
                                </div>

                              

                                </div>


                                 <div class="row">

                               
                                <div class="form-group col-sm-6">
                                    <label>Image</label>
                                    <input type="file" class="form-control" name="image" required>
                                     <p class="text-danger">{{ $errors->first('image') }}</p>
                                </div>

                                </div>


                                <div class="row">

                               
                                <div class="form-group col-sm-6">
                                    <!-- <label>Image</label> -->
                                    <img src="{{ url('/').$category->image}}" width="400px" height="250px">
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