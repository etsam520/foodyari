@extends('mess-views.layouts.dashboard-main')

@section('content')
<div class="conatiner-fluid content-inner mt-n5 py-0">
    <div class="row">
        <div class="col-sm-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between">
                    <div class="header-title">
                       <h4 class="card-title">
                        <svg width="30px" viewBox="-2.1 -2.1 25.20 25.20" version="1.1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" fill="#000000"><g id="SVGRepo_bgCarrier" stroke-width="0"></g><g id="SVGRepo_tracerCarrier" stroke-linecap="round" stroke-linejoin="round"></g><g id="SVGRepo_iconCarrier"> <title>plus_circle [#1441]</title> <desc>Created with Sketch.</desc> <defs> </defs> <g id="Page-1" stroke-width="0.315" fill="none" fill-rule="evenodd"> <g id="Dribbble-Light-Preview" transform="translate(-419.000000, -520.000000)" fill="currentColor"> <g id="icons" transform="translate(56.000000, 160.000000)"> <path d="M374.55,369 L377.7,369 L377.7,371 L374.55,371 L374.55,374 L372.45,374 L372.45,371 L369.3,371 L369.3,369 L372.45,369 L372.45,366 L374.55,366 L374.55,369 Z M373.5,378 C368.86845,378 365.1,374.411 365.1,370 C365.1,365.589 368.86845,362 373.5,362 C378.13155,362 381.9,365.589 381.9,370 C381.9,374.411 378.13155,378 373.5,378 L373.5,378 Z M373.5,360 C367.70085,360 363,364.477 363,370 C363,375.523 367.70085,380 373.5,380 C379.29915,380 384,375.523 384,370 C384,364.477 379.29915,360 373.5,360 L373.5,360 Z" id="plus_circle-[#1441]"> </path> </g> </g> </g> </g></svg>
                        {{__('messages.new')." ".__('messages.delivery_man')}}
                        </h4>
                    </div>
                </div>
                <div class="card-body">
                   
                    <form action="{{ route('mess.delivery-man.store') }}" method="post" enctype="multipart/form-data">
                        <div class="card">
                            <div class="card-header">
                                <h5 class="card-title">
                                    <span class="card-title-icon"><i class="tio-user"></i></span>
                                    <span>
                                        {{ __('messages.general_info') }}
                                    </span>
                                </h5>
                            </div>
                            @csrf
                            <div class="card-body pb-2">
                                <div class="row g-3">
                                    <div class="col-lg-8">
                                        <div class="row g-3">
                                            <div class="col-sm-6">
                                                <div class="form-group m-0">
                                                    <label class="input-label"
                                                        for="exampleFormControlInput1">{{ __('messages.first') }}
                                                        {{ __('messages.name') }}</label>
                                                    <input type="text" name="f_name" class="form-control h--45px"
                                                        placeholder="{{ __('Enter First Name') }}" required>
                                                </div>
                                            </div>
                                            <div class="col-sm-6">
                                                <div class="form-group m-0">
                                                    <label class="input-label"
                                                        for="exampleFormControlInput1">{{ __('messages.last') }}
                                                        {{ __('messages.name') }}</label>
                                                    <input type="text" name="l_name" class="form-control h--45px"
                                                        placeholder="{{ __('Enter Last Name') }}" required>
                                                </div>
                                            </div>
                                            <div class="form-group col-md-6">
                                                <label class="form-label" for="email">Email:</label>
                                                <input type="email" class="form-control" name="email" id="email" placeholder="Email">
                                            </div>
                                            <div class="form-group col-md-6">
                                                <label class="form-label" for="add1">Street Address :</label>
                                                <input type="text" class="form-control" name="street" id="add1" placeholder="Street Address ">
                                             </div>
                                            <div class="form-group col-md-6">
                                                <label class="form-label" for="pno">Pin Code:</label>
                                                <input type="text" class="form-control" name="pincode" id="pno" placeholder="Pin Code">
                                             </div>
                                             <div class="form-group col-md-6">
                                                <label class="form-label" for="city">Town/City:</label>
                                                <input type="text" class="form-control" id="city" name="city" placeholder="Town/City">
                                             </div>
            
                                            <div class="col-sm-6">
                                                <div class="form-group m-0">
                                                    <label class="input-label"
                                                        for="exampleFormControlInput1">{{ __('messages.Vehicle') }}</label>
                                                    <select name="vehicle_id" class="form-control js-select2-custom h--45px" required
                                                        data-placeholder="{{ __('messages.select') }} {{ __('messages.vehicle') }}">
                                                        <option value="" readonly="true" hidden="true">{{ __('messages.select') }} {{ __('messages.vehicle') }}</option>
                                                        @foreach (\App\Models\Vehicle::where('status',1)->get(['id','type']) as $v)
                                                                    <option value="{{ $v->id }}" >{{ $v->type }}
                                                                    </option>
                                                        @endforeach
                                                    </select>
                                                </div>
                                            </div>
                                            <div class="col-sm-6">
                                                <div class="form-group m-0">
                                                    <label class="input-label"
                                                        for="exampleFormControlInput1">Shift</label>
                                                    <select name="shift_id" class="form-control js-select2-custom h--45px" required
                                                        data-placeholder="{{ __('messages.select') }} {{ __('messages.shift') }}">
                                                        <option value="" readonly="true" hidden="true">{{ __('messages.select') }} {{ __('messages.shift') }}</option>
                                                        @foreach (\App\Models\Shift::where('status',1)->get(['id','name']) as $s)
                                                                    <option value="{{ $s->id }}" >{{ $s->name }}
                                                                    </option>
                                                        @endforeach
                                                    </select>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-lg-4">
                                        <div class="form-group m-0">
                                            <label class="d-block mb-lg-5 text-center">{{ __('messages.delivery_man_image') }} <small class="text-danger">* ( {{ __('messages.ratio') }} {{ __('100x100') }} )</small></label>
                                            <center>
                                                <img class="initial-24"  data-image="userImagr1"  style="width: 100px;height:100px;"
                                                    src="{{ asset('assets/images/icons/user.png') }}"
                                                    alt="delivery-man image" />
                                            </center>
                                            <label class="d-block mb-lg-3 "></label>
                                            <div class="custom-file">
                                                <input type="file" name="image"  id="customFileEg1" class="custom-file-input h--45px"
                                                    accept=".jpg, .png, .jpeg, .gif, .bmp, .tif, .tiff|image/*" onchange="readImage(this, '[data-image=userImagr1]')" required>
                                                <label class="custom-file-label" for="customFileEg1">{{ __('messages.choose') }}
                                                    {{ __('messages.file') }}</label>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-lg-4">
                                        <div class="row g-3">
                                            <div class="col-sm-6 col-lg-12">
                                                <div class="form-group m-0">
                                                    <label class="input-label"
                                                        for="exampleFormControlInput1">{{ __('messages.identity') }}
                                                        {{ __('messages.type') }}</label>
                                                    <select name="identity_type" class="form-control h--45px">
                                                        <option value="passport">{{ __('messages.passport') }}</option>
                                                        <option value="driving_license">{{ __('messages.driving') }}
                                                            {{ __('messages.license') }}</option>
                                                        <option value="nid">{{ __('messages.nid') }}</option>
                                                        <option value="restaurant_id">{{ __('messages.restaurant') }}
                                                            {{ __('messages.id') }}</option>
                                                    </select>
                                                </div>
                                            </div>
                                            <div class="col-sm-6 col-lg-12">
                                                <div class="form-group m-0">
                                                    <label class="input-label"
                                                        for="exampleFormControlInput1">{{ __('messages.identity') }}
                                                        {{ __('messages.number') }}</label>
                                                    <input type="text" name="identity_number" class="form-control h--45px"
                                                        placeholder="{{ __('Enter Identity Number ') }}" required>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-lg-8">
                                        <div class="form-group m-0">
                                            <label class="input-label"
                                                for="exampleFormControlInput1">{{ __('messages.identity') }}
                                                {{ __('messages.image') }}</label>
                                            <div>
                                                <div class="row" id="coba"></div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
            
                        <div class="card mt-3">
                            <div class="card-header">
                                <h5 class="card-title">
                                    <span class="card-header-icon"><i class="tio-user"></i></span>
                                    <span>{{ __('messages.account_info') }}</span>
                                </h5>
                            </div>
                            <div class="card-body">
                                <div class="row g-3">
                                    <div class="col-md-4">
                                        <div class="form-group m-0">
                                            <label class="input-label" for="phone">{{ __('messages.phone') }}</label>
                                            <div class="input-group">
                                                <input type="tel" name="phone" id="phone" placeholder="{{ __('Enter Phone Number') }}"
                                                    class="form-control h--45px" required>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-group m-0">
                                            <label class="input-label"
                                                for="exampleFormControlInput1">{{ __('messages.password') }}</label>
                                            <input type="text" name="password" class="form-control h--45px" placeholder="{{ __('Enter Password') }}"
                                                required>
                                        </div>
                                    </div>
                                    <!-- This is Static -->
                                    <div class="col-md-4">
                                        <div class="form-group m-0">
                                            <label class="input-label"
                                            for="exampleFormControlInput1">{{ __('messages.confirm_password') }}</label>
                                            <input type="text" name="c_password" class="form-control h--45px" placeholder="{{ __('Enter Confirm Password') }}"
                                            required>
                                        </div>
                                    </div>
                                    <!-- This is Static -->
                                    <div class="col-md-12 mt-3">
                                        <button type="reset" id="reset_btn" class="btn btn-outline-secondary">Reset</button>
                                        <button type="submit" class="btn btn-outline-primary">Submit</button>
                                    </div>
                                </div>
                            </div>
                        </div>
                        {{-- <div class="btn-container justify-content-end mt-3">
                            <button type="reset" id="reset_btn" class="btn btn-outline-secondary">Reset</button>
                            <button type="submit" class="btn btn-outline-primary">Submit</button>
                        </div> --}}
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
@push('javascript')
<script src="{{asset('assets/js/plugins/spartan-multi-image-picker.min.js')}}"></script>


<script>
    $("#coba").spartanMultiImagePicker({
  placeholderImage: {
    image : "{{asset('assets/images/icons/user2.png')}}",
    width : '200px',
    fieldName:  'identity_image[]',
    maxCount : 3,
    dropFileLabel:   'Drop file here',
    allowedExt:'png|jpg|jpeg',
  },
});

function readImage(input,selector) {
    try{
        const reader = new FileReader();
        reader.onload = function (e) {
            const imgSrc = e.target.result;
            document.querySelector(selector).src = imgSrc;
        };
        reader.readAsDataURL(input.files[0]);
    }catch(error){
        console.error(error);
    }
    
}
</script>
    
@endpush
            