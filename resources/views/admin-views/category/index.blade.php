@extends('layouts.dashboard-main')

@section('content')
    <div class="conatiner-fluid content-inner mt-n5 py-0">
        <div class="row">
            <div class="col-sm-12 col-lg-12">
                <div class="card">
                    <div class="card-header d-flex justify-content-between">
                        <div class="header-title">
                            <h4 class="card-title">{{ __('messages.add-category') }}</h4>
                        </div>
                    </div>
                    <div class="card-body">
                    @if ($errors->any())
                        <div class="alert alert-danger">
                            <ul>
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                        <form class="row g-3 needs-validation" method="POST" enctype="multipart/form-data"  action="{{route('admin.category.store')}}">
                            @csrf
                            <div class="col-md-6 mt-3">
                                <div class="form-group">
                                    <label class="input-label" for="name">Category Name</label>
                                    <input id="name" type="text" name="name"
                                        class="form-control h--45px" placeholder="Enter Category Name"
                                        value="{{old('name')}}" >
                                    <input type="hidden" name="position" value="1">
                                </div>

                            </div>
                            <div class="col-md-5 mx-5 mt-3">
                                <img class="initial-57-2" id="category-viewer"
                                    src="{{ asset('assets/images/icons/restaurant-default-image.png') }}"
                                    alt="delivery-man image">

                                <div class="form-group pt-3">
                                    <label class="input-label">Image<small class="text-danger">
                                            (Ratio 1:1)</small></label>
                                    <div class="custom-file">
                                        <input type="file" name="image" id="customFileEg1" class="custom-file-input" onchange="readImage(this, '#category-viewer')" accept=".jpg, .png, .jpeg, .gif, .bmp, .tif, .tiff|image/*">
                                    </div>
                                </div>
                                <button class="btn btn-primary " type="submit">Submit form</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-sm-12">
                <div class="card">
                    <div class="card-header d-flex justify-content-between">
                        <div class="header-title">
                        <h4 class="card-title">Category Table</h4>
                        </div>
                    </div>
                    <div class="card-body p-0">
                        <div class="table-responsive mt-4">
                            <table id="datatable" data-toggle="data-table" class="table mb-0" role="grid">
                                <thead>
                                    <tr>
                                        <th>ID</th>
                                        <th>Image</th>
                                        <th>Category Name</th>
                                        <th>Status</th>
                                        <th>action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($categories as $category)
                                    {{-- @dd($category) --}}
                                    <tr>
                                        <td>{{$category->id}}</td>
                                        <td><img src="{{asset("Category/$category->image")}}"
                                            onerror="this.src='{{asset('assets/images/icons/food-default-image.png')}}'" alt="" style="width:75px;border-radius:5px;" ></td>
                                        <td>{{$category->name}}</td>
                                        <td>
                                            <label class="form-check form-check form-switch form-check-inline" for="stocksCheckbox{{$category->id}}">
                                                <input type="checkbox" onclick="location.href='{{route('admin.category.status',[$category['id'], 'status'=> $category->status?0:1])}}'"class="form-check-input" id="stocksCheckbox{{$category->id}}" {{$category->status ===1?'checked':''}} >
                                                <span class="toggle-switch-label">
                                                    <span class="toggle-switch-indicator"></span>
                                                </span>
                                            </label>
                                        </td>
                                        <td><a href="{{route('admin.category.edit',$category->id)}}" class="fa fa-edit text-warning">Edit</a></td>
                                    </tr>
                                    @endforeach

                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

@endsection
