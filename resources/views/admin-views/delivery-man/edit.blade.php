@extends('layouts.dashboard-main')
@push('css')
<link rel="stylesheet" href="{{asset('assets/vendor/flatpickr/dist/flatpickr.min.css')}}">
<link rel="stylesheet" href="{{asset('assets/vendor/image-uploader/dist/image-uploader.min.css')}}">

@endpush
{{-- @dd($dm) --}}
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

                    @if ($errors->any())
                        <div class="alert alert-danger">
                            <ul>
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    <form action="{{ route('admin.delivery-man.update',['id'=> $dm->id]) }}" method="post" enctype="multipart/form-data">
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
                                                        placeholder="{{ __('Enter First Name') }}" required value="{{$dm->f_name}}">
                                                </div>
                                            </div>
                                            <div class="col-sm-6">
                                                <div class="form-group m-0">
                                                    <label class="input-label"
                                                        for="exampleFormControlInput1">{{ __('messages.last') }}
                                                        {{ __('messages.name') }}</label>
                                                    <input type="text" name="l_name" class="form-control h--45px"
                                                        placeholder="{{ __('Enter Last Name') }}" value="{{$dm->l_name}}">
                                                </div>
                                            </div>
                                            <div class="col-sm-6">
                                                <div class="form-group m-0">
                                                    <label class="input-label"
                                                        for="exampleFormControlInput1">{{ __('messages.email') }}</label>
                                                    <input type="email" name="email" class="form-control h--45px"
                                                        placeholder="{{ __('Enter Email ID') }}" required value="{{$dm->email}}">
                                                </div>
                                            </div>

                                            <div class="col-sm-6">
                                                <div class="form-group m-0">
                                                    <label class="input-label"
                                                        for="exampleFormControlInput1">{{ __('messages.zone') }}</label>
                                                    <select name="zone_id" class="form-control js-select2-custom h--45px" required
                                                        data-placeholder="{{ __('messages.select') }} {{ __('messages.zone') }}">
                                                        <option value="" readonly="true" hidden="true">{{ __('Ex: XYZ Zone') }}</option>
                                                        @foreach (\App\Models\Zone::where('status',1)->get(['id','name']) as $zone)
                                                        <option value="{{ $zone->id }}" {{$zone->id ==$dm->zone_id  ? 'selected':null}}>{{ $zone->name }}</option>
                                                        @endforeach
                                                    </select>
                                                </div>
                                            </div>
                                            <div class="col-sm-6">
                                                <div class="form-group m-0">
                                                    <label class="input-label"
                                                        for="exampleFormControlInput1">{{ __('messages.Vehicle') }}</label>
                                                    <select name="vehicle_id" class="form-control js-select2-custom h--45px" required
                                                        data-placeholder="{{ __('messages.select') }} {{ __('messages.vehicle') }}">
                                                        <option value="" readonly="true" hidden="true">{{ __('messages.select') }} {{ __('messages.vehicle') }}</option>
                                                        @foreach (\App\Models\Vehicle::where('status',1)->get(['id','type']) as $v)
                                                                    <option value="{{ $v->id }} "{{$v->id ==$dm->vehicle_id  ? 'selected':null}} >{{ $v->type }}
                                                                    </option>
                                                        @endforeach
                                                    </select>
                                                </div>
                                            </div>

                                            <div class="col-sm-6">
                                                <div class="form-group m-0">
                                                    <label class="input-label"
                                                        for="exampleFormControlInput1">{{ __('messages.deliveryman') }}
                                                        {{ __('messages.type') }}</label>
                                                    <select name="earning" class="form-control h--45px">
                                                        <option value="" readonly="true" hidden="true">{{ __('messages.delivery_man_type') }}</option>
                                                        <option value="1">{{ __('messages.freelancer') }}</option>
                                                        <option value="0">{{ __('messages.salary_based') }}</option>
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
                                                    src="{{ asset('delivery-man/'.$dm->image) }}"
                                                    alt="delivery-man image" />
                                            </center>
                                            <label class="d-block mb-lg-3 "></label>
                                            <div class="custom-file">
                                                <input type="file" name="image"  id="customFileEg1" class="custom-file-input h--45px"
                                                    accept=".jpg, .png, .jpeg, .gif, .bmp, .tif, .tiff|image/*" onchange="readImage(this, '[data-image=userImagr1]')" >
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
                                                        <option value="passport" {{ 'passport'==$dm->identity_type  ? 'selected':null}}>{{ __('messages.passport') }}</option>
                                                        <option value="driving_license" {{ 'driving_license'==$dm->identity_type  ? 'selected':null}}>{{ __('messages.driving') }}
                                                            {{ __('messages.license') }}</option>
                                                        <option value="nid" {{ 'nid'==$dm->identity_type  ? 'selected':null}}>{{ __('messages.nid') }}</option>
                                                        <option value="restaurant_id" {{ 'restaurant_id'==$dm->identity_type  ? 'selected':null}}>{{ __('messages.restaurant') }}
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
                                                        placeholder="{{ __('Enter Identity Number ') }}" required value="{{$dm->identity_number}}">
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
                                                <div class="input-images"></div>
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
                                                    class="form-control h--45px" required value="{{$dm->phone}}">
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
                                    <span>{{ __("Documents") }}</span>
                                </h5>
                            </div>
                            <div class="card-body">
                                <div class="row g-3">
                                    @foreach ($documents as $document)
                                    @php
                                        $saved_doc = null;
                                    if($documentDetails != null){
                                        $saved_doc = array_reduce($documentDetails, function($carry, $item) use($document) {
                                            if ($item['document_id'] === $document->id) {
                                                return $item; // Return the item if found
                                            }
                                            return $carry; // Otherwise, return the carry (which is initially null)
                                        }, null);
                                    }

                                    @endphp
                                    <div class="mb-3">
                                        <span class="fw-bold"><b>{{ $document->name }}</b></span>
                                        <div class="border rounded p-3">
                                        @if ($document->is_text)
                                            <div class="mb-3">
                                            <small>{{ $document->name }} ID/Number
                                                @if ($document->is_text_required)
                                                <span class="text-danger">*</span>
                                                @endif
                                            </small>
                                            <input type="text" class="form-control" data-type="{{ strtolower($document->name) }}" name="{{ $document->text_input_name }}" value="{{ $saved_doc != null ? $saved_doc['text_value'] : (old($document->text_input_name) ?? null) }}" placeholder="{{ $document->name }} ID/Number" @if ($document->is_text_required) required @endif>
                                            </div>
                                        @endif
                                        @if ($document->is_media)
                                            <div class="mb-3">
                                            <small>Upload {{ $document->name }}
                                                @if ($document->is_media_required)
                                                <span class="text-danger">*</span>
                                                @endif
                                            </small>
                                            <div class="input-group" role="button" data-toggle="FileUploader" data-type="image" id="{{ $document->media_input_name }}" data-preview="#{{ $document->media_input_name }}_preview">
                                                {{-- <div class="input-group-text bg-soft-secondary">Browse</div> --}}
                                                <input type="file"  class="form-control" name="{{ $document->media_input_name }}"  class="selected-files">
                                                {{-- <div class="form-control file-amount text-truncate">Choose Files</div> --}}
                                            </div>
                                            <div id="{{ $document->media_input_name }}_preview" data-parent="#{{ $document->media_input_name }}"></div>
                                            </div>
                                        @endif
                                        @if ($document->has_expiry_date)
                                            <div class="mb-3">
                                            <small>Expire Date</small>
                                            <input type="date" class="form-control" name="{{ $document->expire_date_input_name }}" value="{{  $saved_doc != null ? $saved_doc['expire_date'] : ( old($document->expire_date_input_name) ?? null) }}">
                                            </div>
                                        @endif
                                        </div>
                                    </div>
                                    @endforeach
                                </div>
                            </div>
                        </div>
                        <div class="btn-container justify-content-end mt-3">
                            <button type="reset" id="reset_btn" class="btn btn-outline-secondary">Reset</button>
                            <button type="submit" class="btn btn-outline-primary">Submit</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
@push('javascript')

<script src="{{asset('assets/vendor/image-uploader/dist/image-uploader.min.js')}}"></script>


<script>
@if($dm->identity_image)
const preloaded = [
    @php($images = json_decode($dm->identity_image))
    @foreach ($images as $image)
        {id : '{{$image}}' , src : "{{asset('delivery-man/'.$image)}}"  },
    @endforeach
];
@endif
$('.input-images').imageUploader({
  extensions: ['.jpg', '.jpeg', '.png', '.gif', '.svg'],
  mimes: ['image/jpeg', 'image/png', 'image/gif', 'image/svg+xml'],
  maxSize: undefined,
  maxFiles: undefined,
  imagesInputName: 'identity_image',
  preloadedInputName: 'preloaded',
  label: 'Drag & Drop files here or click to browse',
  @if ($dm->identity_image)
  preloaded: preloaded,
  @endif
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
