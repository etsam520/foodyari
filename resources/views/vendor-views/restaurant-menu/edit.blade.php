@extends('vendor-views.layouts.dashboard-main')

@section('content')
    <div class="conatiner-fluid content-inner mt-n5 py-0">
        <div>
            <div class="row">
                <div class="col-sm-12 col-lg-12">
                    <div class="card">
                        <div class="card-header d-flex justify-content-between">
                            <div class="header-title">
                                <h5 class="page-header-title">{{ __('messages.edit') . ' ' . __('messages.Menu') }}</h5>
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

                            <form class="row g-3 needs-validation" method="POST" enctype="multipart/form-data"
                                action="{{route('vendor.restaurant-menu.update', ['restaurant_menu' => $restaurantMenu->id])}}">
                                <input type="hidden" name="id" value="{{$restaurantMenu->id}}">
                                @method('PUT')
                                @csrf
                                <div class="col-md-6 mt-3">
                                    <div class="form-group">
                                        <label class="input-label" for="name">Menu Name</label>
                                        <input id="name" type="text" name="name" class="form-control h--45px"
                                            placeholder="Enter Menu Name" value="{{$restaurantMenu->name ?? old('name')}}">
                                        <input type="hidden" name="position" value="2">
                                    </div>
                                {{-- </div>
                                <div class="col-md-6 mt-3"> --}}
                                    <div class="form-group">
                                        <label class="input-label" for="name">Status</label>
                                        <select id="status" name="status" class="form-control h--45px" required>
                                            <option value="1" {{$restaurantMenu->status ? 'selected' : null}}>Active</option>
                                            <option value="0" {{$restaurantMenu->status ? null : 'selected'}}>Deactive</option>
                                        </select>

                                    </div>

                                </div>

                                <div class="col-md-6">
                                    <img class="initial-57-2 w-100" id="category-viewer"
                                        src="{{ $restaurantMenu->image ? asset('Category/' . $restaurantMenu->image) : asset('assets/images/icons/restaurant-default-image.png') }}"
                                        alt="delivery-man image">

                                    <div class="form-group pt-3 text-center">
                                        <label class="input-label">Image<small class="text-danger">
                                                (Ratio 1:1)</small></label>
                                        <div class="custom-file">
                                            <input type="file" name="image" id="customFileEg1" class="custom-file-input"
                                                onchange="readImage(this, '#category-viewer')" style="    width: 220px;"
                                                accept=".jpg, .png, .jpeg, .gif, .bmp, .tif, .tiff|image/*">
                                        </div>
                                    </div>
                                </div>
                                <hr style="border: 1px solid #cecbcb;">
                                <div class="text-end mt-0">
                                    <button class="btn btn-primary " type="submit">update form</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
