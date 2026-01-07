@extends('user-views.restaurant.layouts.main')
@section('containt')
    <div
        style="background: url(https://static.vecteezy.com/system/resources/previews/009/715/641/non_2x/abstract-gradient-geometric-background-dynamic-orange-poster-graphics-abstract-background-texture-design-vector.jpg);
    background-color: #ffffff;
    background-blend-mode: darken;">
        <div class="container position-relative" style="background: #ffffff63;max-width: 100%;">
            <div class="py-5 osahan-profile row d-flex justify-content-center" style="backdrop-filter: blur(6px);">
                <div class="col-md-8 mb-3">
                    <div class="rounded shadow-sm p-4 bg-white">
                        <h5 class="mb-4 border-bottom pb-3 fw-bolder">Join us as Restaurant</h5>
                        <div id="edit_profile">
                            @if ($errors->any())
                                <div class="alert alert-danger">
                                    <ul>
                                        @foreach ($errors->all() as $error)
                                            <li>{{ $error }}</li>
                                        @endforeach
                                    </ul>
                                </div>
                            @endif
                            <div>
                                <form action="{{route('join-as.restaurant-save')}}" method="POST" enctype="multipart/form-data">
                                    @csrf
                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="form-group mb-3">
                                                <label class="pb-1">Restaurant Name <span class="text-danger">*</span></label>
                                                <input type="text" class="form-control"  name="restaurant_name" required value="{{old('restaurant_name')}}">
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group mb-3">
                                                <label class="pb-1">Phone No.<span class="text-danger">*</span></label>
                                                <input type="number" class="form-control" name="restaurant_phone" required value="{{old('restaurant_phone')}}">
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group mb-3">
                                                <label class="pb-1">Email ID <span class="text-danger">*</span></label>
                                                <input type="email" class="form-control" name="restaurant_email" required value="{{old('restaurant_email')}}">
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group mb-3">
                                                <label class="pb-1">Full Address <span class="text-danger">*</span></label>
                                                <input type="text" class="form-control" name="restaurant_address" required value="{{old('restaurant_address')}}">
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group mb-3">
                                                <label class="pb-1">Owner Name <span class="text-danger">*</span></label>
                                                <input type="text" class="form-control" name="restaurant_owner_name" required value="{{old('restaurant_owner_name')}}">
                                            </div>
                                        </div>

                                        @foreach ($documents as $document)
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
                                                <input type="text" class="form-control" data-type="{{ strtolower($document->name) }}" name="{{ $document->text_input_name }}" value="{{ old($document->text_input_name) ?? null }}" placeholder="{{ $document->name }} ID/Number" @if ($document->is_text_required) required @endif>
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
                                                <input type="date" class="form-control" name="{{ $document->expire_date_input_name }}" value="{{ old($document->expire_date_input_name) ?? null }}">
                                                </div>
                                            @endif
                                            </div>
                                            <input type="hidden" id="g-recaptcha-response" name="g-recaptcha-response">
                                        </div>
                                        @endforeach



                                    </div>
                                    <div class="text-center mt-4">
                                        <button type="submit" class="btn btn-primary w-100">Save Changes</button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('javascript')
<script src="https://www.google.com/recaptcha/api.js?render=6Le6_pcrAAAAAF9-gOTHOZO_hNVCTgEV7wpzSuxE"></script>

    <script>
  grecaptcha.ready(function() {
    grecaptcha.execute('6Le6_pcrAAAAAF9-gOTHOZO_hNVCTgEV7wpzSuxE', {action: 'submit'}).then(function(token) {
      document.getElementById('g-recaptcha-response').value = token;
    });
  });
</script>
@endpush
