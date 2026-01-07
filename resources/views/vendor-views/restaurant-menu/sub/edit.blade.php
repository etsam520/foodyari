@extends('vendor-views.layouts.dashboard-main')
@section('content')
    <div class="conatiner-fluid content-inner mt-n5 py-0">
        <div>
            <div class="row">
                <div class="col-sm-12 col-lg-12">
                    <div class="card">
                        <div class="card-header d-flex justify-content-between">
                            <div class="header-title">
                                <h5 class="page-header-title">{{__('Edit Sub Menu') }}</h5>
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
                                action="{{route('vendor.restaurant-sub-menu.update')}}">
                                <input type="hidden" name="id" value="{{$submenu->id}}">
                                @csrf
                                @method('PUT')
                                <div class="col-md-6 mt-3">
                                    <div class="form-group">
                                        <label class="input-label" for="name">Sub Menu Name</label>
                                        <input id="name" type="text" name="name" class="form-control h--45px"
                                            placeholder="Enter Sub Menu Name" value="{{$submenu->name ?? old('name')}}">
                                    </div>
                                </div>
                                <div class="col-md-6 mt-3">
                                    <div class="form-group">
                                        <label class="input-label" for="name">Status</label>
                                        <select id="status" name="status" class="form-control h--45px" required>
                                            <option value="1" {{$submenu->status == 1 ? 'selected' : null}}>Active</option>
                                            <option value="0" {{$submenu->status == 0 ? 'selected' : null}}>Deactive</option>
                                        </select>

                                    </div>
                                </div>

                                <div class="col-md-12">
                                    <div class="form-group">
                                        <label class="input-label" for="menu_id">Menu Name</label>
                                        <select name="restaurant_menu_id" id="menu_id" class="form-control">
                                            @foreach ($menu as $item)
                                                <option value="{{$item->id}}"
                                                    {{$submenu->restaurant_menu_id == $item->id ? 'selected' : null}}>
                                                    {{Str::ucfirst($item->name)}}</option>
                                            @endforeach

                                        </select>
                                    </div>
                                </div>
                                <hr style="border: 1px solid #cecbcb;">
                                <div class="text-end mt-0">
                                    <button class="btn btn-primary " type="submit">Update </button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
