@extends('layouts.dashboard-main')

@section('content')
    <div class="conatiner-fluid content-inner mt-n5 py-0">
        <div>
            <div class="row">
                <div class="col-sm-12 col-lg-12">
                    <div class="card">
                        <div class="card-header d-flex justify-content-between">
                            <div class="header-title">
                                <h4 class="card-title">Vendor Owner Edit</h4>
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

                          <form class="row g-3 needs-validation" method="POST" enctype="multipart/form-data"  action="{{route('admin.owner.update',$vendor->id)}}">
                            @method('PUT')
                            <input type="hidden" name="id" value="{{$vendor->id}}">
                            @csrf
                            <div class="col-md-6 mt-3">
                                <div class="form-group">
                                    <label class="input-label" for="first_name">First Name</label>
                                    <input id="first_name" type="text" name="f_name"
                                        class="form-control h--45px" placeholder="Ex. John"
                                        value="{{$vendor->f_name??old('f_name')}}" >
                                </div>
                            </div>
                            <div class="col-md-6 mt-3">
                                <div class="form-group">
                                    <label class="input-label" for="last_name">Last Name</label>
                                    <input id="last_name" type="text" name="l_name"
                                        class="form-control h--45px" placeholder="Ex. Doe"
                                        value="{{$vendor->l_name??old('l_name')}}" >
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="input-label" for="email">Email</label>
                                    <input id="email" type="email" name="email"
                                        class="form-control h--45px" placeholder="Ex."
                                        value="{{$vendor->email??old('email')}}" >
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="input-label" for="phone">Phone</label>
                                    <input id="phone" type="text" name="phone"
                                        class="form-control h--45px" placeholder="Ex. 017XXXXXXXX"
                                        value="{{$vendor->phone??old('phone')}}" >
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="input-label" for="password">Password</label>
                                    <input id="password" type="password" name="password"
                                        class="form-control h--45px" placeholder="Ex. 123456"
                                        value="{{old('password')}}" >
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="input-label" for="password_confirmation">Confirm Password</label>
                                    <input id="password_confirmation" type="password" name="password_confirmation"
                                        class="form-control h--45px" placeholder="Ex. 123456"
                                        value="{{old('password')}}" >
                                </div>
                            </div>

                             <div class="col-md-6 ">
                                <button class="btn btn-primary mt-4 mx-3" type="submit">Update form</button>
                             </div>
                          </form>
                       </div>
                    </div>
                 </div>
            </div>

        </div>
    </div>
@endsection
