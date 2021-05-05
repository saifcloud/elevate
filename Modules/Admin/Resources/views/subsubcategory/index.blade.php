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
                        <li class="breadcrumb-item"><a href="{{ url('admin/dashboard')}}"><i class="icon-home"></i></a></li>
                        <!-- <li class="breadcrumb-item active"></li> -->
                    </ul>
                    <a href="{{ url('admin/sub-subcategory-create')}}" class="btn btn-sm btn-primary" title="">Create New</a>
                </div>
            </div>
        </div>

        <div class="container-fluid">

            <div class="row clearfix">
                <div class="col-12">
                    <div class="card top_report">
                        @if(session()->has('success'))
                        <div class="alert alert-success">
                            <p class="text-center">{{ session()->get('success')}}</p>
                        </div>
                        @endif
                        <div class="row clearfix">
                            <div class="col-lg-12 col-md-12 col-sm-12">
                                <div class="body">
                                    <table id="subcategory-table" class="table table-striped">
                                         <thead>
                                          <tr>
                                            <th>Image</th>
                                            <th>English Category</th>
                                            <th>Arabic Category</th>
                                            <th>Subcategory</th>
                                            <th>Category</th>
                                            <th>Action</th>
                                          </tr>
                                        </thead>
                                        <tbody>
                                         
                                            @if(isset($subsubcategory) && count($subsubcategory) > 0)
                                            @foreach($subsubcategory as $row)
                                            <tr>
                                            <td><img src="{{ url('/').$row->image}}" width="200px" height="100px"></td>
                                            <td>{{ $row->en_subsubcategory }}</td>
                                            <td>{{ $row->ar_subsubcategory }}</td>
                                            <td>{{ $row->subcategory->en_subcategory }}</td>
                                            <td>{{ $row->category->en_category }}</td>
                                            <td>
                                                <a href="{{ url('admin/sub-subcategory-edit/'.base64_encode($row->id))}}" class="btn btn-warning">Edit</a>
                                                <a href="{{ url('admin/sub-subcategory-delete/'.base64_encode($row->id))}}" class="btn btn-danger">Delete</a>
                                            </td>
                                            </tr>
                                            @endforeach
                                            @endif
                                         
                                        </tbody>
                                    </table>
                                    
                                   
                                </div>
                            </div>
                          
                          
                         
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </div>
    
</div>


@endsection