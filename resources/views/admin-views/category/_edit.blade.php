@extends('layouts.dashboard-main')

@section('content')
    <div class="conatiner-fluid content-inner mt-n5 py-0">
        <div class="row">
            <div class="col-sm-12 col-lg-12">
                <div class="card">
                    <div class="card-header d-flex justify-content-between">
                        <div class="header-title">
                            <h4 class="card-title">{{ __('Edit Category') }}</h4>
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
                        
                        <form class="row g-3 needs-validation" method="POST" enctype="multipart/form-data"  action="{{route('admin.category.update')}}">
                            @csrf
                            <input type="hidden" name="id" value="{{$category->id}}">
                            <div class="col-md-6 mt-3">
                                <div class="form-group">
                                    <label class="input-label" for="name">Category Name</label>
                                    <input id="name" type="text" name="name"
                                        class="form-control h--45px" placeholder="Enter Category Name"
                                        value="{{$category->name??old('name')}}" >
                                    <input type="hidden" name="position" value="1">
                                </div>
                                
                            </div>
                            <div class="col-md-5 mx-5 mt-3">
                                <img class="initial-57-2" id="category-viewer"
                                    src="{{ $category->image ? asset('Category/'.$category->image) : asset('assets/images/icons/restaurant-default-image.png') }}"
                                    alt="delivery-man image">

                                <div class="form-group pt-3">
                                    <label class="input-label">Image<small class="text-danger">
                                            (Ratio 1:1)</small></label>
                                    <div class="custom-file">
                                        <input type="file" name="image" id="customFileEg1" class="custom-file-input" onchange="readImage(this, '#category-viewer')" accept=".jpg, .png, .jpeg, .gif, .bmp, .tif, .tiff|image/*">
                                    </div>
                                </div>
                                <button class="btn btn-primary " type="submit">update form</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
      
    </div>

@endsection
