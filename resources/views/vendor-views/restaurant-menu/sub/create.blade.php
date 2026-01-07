@extends('vendor-views.layouts.dashboard-main')

@section('content')
    <div class="conatiner-fluid content-inner mt-n5 py-0">
        <div>
            <div class="row">
                <div class="col-sm-12 col-lg-12">
                    <div class="card">
                        <div class="card-header">

                            <div class="header-title">
                                <h5 class="page-header-title">{{__('Create Sub Menu') }}</h5>
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
                                action="{{route('vendor.restaurant-sub-menu.store')}}">
                                @csrf
                                <div class="col-md-12 mt-3">
                                    <div class="form-group">
                                        <label class="input-label" for="name">Sub Menu Name</label>
                                        <input id="name" type="text" name="name" class="form-control h--45px"
                                            placeholder="Enter Sub Menu Name" value="{{old('name')}}">
                                    </div>
                                </div>
                                <div class="col-md-6 mt-3">
                                    <div class="form-group">
                                        <label class="input-label" for="name">Status</label>
                                        <select id="status" name="status" class="form-control h--45px" required>
                                            <option value="1">Active</option>
                                            <option value="0">Deactive</option>
                                        </select>

                                    </div>
                                </div>


                                <div class="col-md-6 mt-3">
                                    <div class="form-group">
                                        <label class="input-label" for="menu_id">Menu Name</label>
                                        <select name="menu_id" id="menu_id" class="form-control">
                                            <option value="" selected desabled>Select One</option>
                                            @foreach ($menu as $item)
                                                <option value="{{$item->id}}">{{Str::ucfirst($item->name)}}</option>
                                            @endforeach

                                        </select>
                                    </div>
                                </div>
                                <hr style="border: 1px solid #cecbcb;">
                                <div class="text-end mt-0">
                                    <button class="btn btn-primary " type="submit">Add </button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
