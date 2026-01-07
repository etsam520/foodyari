@extends('vendor-views.layouts.dashboard-main')

@section('content')
    <div class="conatiner-fluid content-inner mt-n5 py-0">
        <div>
            <div class="row">
                <div class="col-sm-12 col-lg-12">
                    <div class="card">
                        <div class="card-header d-flex justify-content-between">
                            <div class="header-title">
                                <h4 class="card-title">{{ __('messages.addon').' '.__('messages.update') }}</h4>
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
                            
                          <form class="row g-3 needs-validation" method="POST" enctype="multipart/form-data"  action="{{route('vendor.addon.update')}}">
                            @method('PUT')
                            <input type="hidden" name="id" value="{{$addon->id}}">
                            @csrf
                            <div class="col-md-6 mt-3">
                                <div class="form-group">
                                    <label class="input-label" for="name">Name</label>
                                    <input id="name" type="text" name="name"
                                        class="form-control h--45px" placeholder="Ex. Water"
                                        value="{{$addon->name??old('name')}}" >
                                </div> 
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="input-label" for="name">Price</label>
                                    <input id="price" type="number" name="price"
                                        class="form-control h--45px" placeholder="Ex. 20"
                                        value="{{$addon->price??old('price')}}" >
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
