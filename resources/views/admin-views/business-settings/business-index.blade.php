@extends('layouts.dashboard-main')

@section('content')
<div class="conatiner-fluid content-inner mt-n5 py-0">
    <div class="row">
        <div class="col-sm-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between">
                    <div class="header-title">
                        <h4 class="card-title">
                            {{-- <svg width="30px" viewBox="-2.1 -2.1 25.20 25.20" version="1.1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" fill="#000000"><g id="SVGRepo_bgCarrier" stroke-width="0"></g><g id="SVGRepo_tracerCarrier" stroke-linecap="round" stroke-linejoin="round"></g><g id="SVGRepo_iconCarrier"> <title>plus_circle [#1441]</title> <desc>Created with Sketch.</desc> <defs> </defs> <g id="Page-1" stroke-width="0.315" fill="none" fill-rule="evenodd"> <g id="Dribbble-Light-Preview" transform="__(-419.000000, -520.000000)" fill="currentColor"> <g id="icons" transform="__(56.000000, 160.000000)"> <path d="M374.55,369 L377.7,369 L377.7,371 L374.55,371 L374.55,374 L372.45,374 L372.45,371 L369.3,371 L369.3,369 L372.45,369 L372.45,366 L374.55,366 L374.55,369 Z M373.5,378 C368.86845,378 365.1,374.411 365.1,370 C365.1,365.589 368.86845,362 373.5,362 C378.13155,362 381.9,365.589 381.9,370 C381.9,374.411 378.13155,378 373.5,378 L373.5,378 Z M373.5,360 C367.70085,360 363,364.477 363,370 C363,375.523 367.70085,380 373.5,380 C379.29915,380 384,375.523 384,370 C384,364.477 379.29915,360 373.5,360 L373.5,360 Z" id="plus_circle-[#1441]"> </path> </g> </g> </g> </g></svg> --}}
                          {{_('Bussiness Setting')}}
                        </h4>
                    </div>
                </div>
                <div class="card-body">
                    <!-- Zone-Specific Settings Notice -->
                                <div class="card mb-3">
                                    <div class="card-header">
                                        <h4 class="card-title m-0 d-flex align-items-center">
                                            <span class="card-header-icon mr-2"><i class="tio-info"></i></span>
                                            <span>{{ __('Zone-Specific Settings Available') }}</span>
                                        </h4>
                                    </div>
                                    <div class="card-body">
                                        <div class="alert alert-info mb-0">
                                            <div class="d-flex justify-content-between align-items-center">
                                                <div>
                                                    <h5 class="alert-heading">{{ __('Enhanced Zone Management') }}</h5>
                                                    <p class="mb-0">{{ __('Business settings including notification messages, operational settings, commissions, and more can now be configured separately for each zone. This allows for more flexible management across different service areas.') }}</p>
                                                </div>
                                                <div class="ml-3">
                                                    <a href="{{ route('admin.zone.business-settings.index') }}" class="btn btn-primary">
                                                        <i class="tio-settings-outlined"></i>
                                                        {{ __('Manage Zone Settings') }}
                                                    </a>
                                                </div>
                                            </div>
                                        </div>
                                        </div>
                                    </div>
                                </div>

                    <div class="row gx-2 ">

                        <div class="col-sm-12 col-lg-12 mb-3 mb-lg-2">
                            <!-- Display Success/Error Messages -->
                            @if(session('success'))
                                <div class="alert alert-success alert-dismissible fade show" role="alert">
                                    <i class="fas fa-check-circle me-2"></i>
                                    {{ session('success') }}
                                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                                </div>
                            @endif

                            @if(session('error'))
                                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                                    <i class="fas fa-exclamation-triangle me-2"></i>
                                    {{ session('error') }}
                                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                                </div>
                            @endif

                            @if(session('info'))
                                <div class="alert alert-info alert-dismissible fade show" role="alert">
                                    <i class="fas fa-info-circle me-2"></i>
                                    {{ session('info') }}
                                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                                </div>
                            @endif

                            @if($errors->any())
                                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                                    <i class="fas fa-exclamation-triangle me-2"></i>
                                    <strong>{{ __('Please fix the following errors:') }}</strong>
                                    <ul class="mb-0 mt-2">
                                        @foreach($errors->all() as $error)
                                            <li>{{ $error }}</li>
                                        @endforeach
                                    </ul>
                                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                                </div>
                            @endif

                            <form action="{{ route('admin.business-settings.update-setup') }}" method="post" enctype="multipart/form-data" id="business-setup-form">
                                @csrf
                                <div class="card mb-3">
                                    <div class="card-header">
                                        <h4 class="card-title m-0 d-flex align-items-center"> <span class="card-header-icon mr-2"><i class="tio-user"></i></span> <span>{{__('messages.general_info')}}</span></h4>
                                    </div>
                                    <div class="card-body">
                                        <!-- Name Email and Phone -->
                                        <div class="row">
                                            <div class="col-md-4">
                                                @php($name = \App\Models\BusinessSetting::where('key', 'business_name')->first())
                                                <div class="form-group">
                                                    <label class="input-label" for="restaurant_name">{{ __('messages.business') }}
                                                        {{ __('messages.name') }}</label>
                                                    <input type="text" 
                                                           id="restaurant_name"
                                                           name="restaurant_name" 
                                                           value="{{ old('restaurant_name', $name->value ?? '') }}" 
                                                           class="form-control @error('restaurant_name') is-invalid @enderror" 
                                                           placeholder="{{ __('messages.Enter Business Name') }}" 
                                                           required>
                                                    @error('restaurant_name')
                                                        <div class="invalid-feedback">{{ $message }}</div>
                                                    @enderror
                                                </div>
                                            </div>
                                            @php($phone = \App\Models\BusinessSetting::where('key', 'phone')->first())
                                            <div class="col-md-4">
                                                <div class="form-group">
                                                    <label class="input-label" for="phone">{{ __('messages.phone') }}</label>
                                                    <input type="text" 
                                                           id="phone"
                                                           value="{{ old('phone', $phone->value ?? '') }}" 
                                                           name="phone" 
                                                           class="form-control @error('phone') is-invalid @enderror" 
                                                           placeholder="{{ __('messages.Enter Contact Number') }}" 
                                                           required>
                                                    @error('phone')
                                                        <div class="invalid-feedback">{{ $message }}</div>
                                                    @enderror
                                                </div>
                                            </div>
                                            @php($email = \App\Models\BusinessSetting::where('key', 'email_address')->first())
                                            <div class="col-md-4">
                                                <div class="form-group">
                                                    <label class="input-label" for="email">{{ __('messages.email') }}</label>
                                                    <input type="email" 
                                                           id="email"
                                                           value="{{ old('email', $email->value ?? '') }}" 
                                                           name="email" 
                                                           class="form-control @error('email') is-invalid @enderror" 
                                                           placeholder="{{ __('messages.Enter Email ID') }}" 
                                                           required>
                                                    @error('email')
                                                        <div class="invalid-feedback">{{ $message }}</div>
                                                    @enderror
                                                </div>
                                            </div>
                                        </div>
                                        <!-- Map and Address -->
                                        <div class="row gy-3">
                                            <div class="col-lg-6">
                                                @php($address = \App\Models\BusinessSetting::where('key', 'address')->first())
                                                <div class="form-group">
                                                    <label class="input-label" for="address">{{ __('messages.address') }}</label>
                                                    <input type="text" 
                                                           id="address" 
                                                           name="address" 
                                                           class="form-control @error('address') is-invalid @enderror" 
                                                           placeholder="{{ __('messages.Enter Address') }}" 
                                                           value="{{ old('address', $address->value ?? '') }}"
                                                           required />
                                                    @error('address')
                                                        <div class="invalid-feedback">{{ $message }}</div>
                                                    @enderror
                                                </div>
                                                @php($footer_text = \App\Models\BusinessSetting::where('key', 'footer_text')->first())
                                                <div class="form-group">
                                                    <label class="input-label" for="footer_text">{{ __('messages.footer') }}
                                                        {{ __('messages.text') }}</label>
                                                    <textarea id="footer_text"
                                                              name="footer_text" 
                                                              class="form-control @error('footer_text') is-invalid @enderror" 
                                                              placeholder="{{ __('messages.Enter Footer Text') }}" 
                                                              rows="3" 
                                                              required>{{ old('footer_text', $footer_text->value ?? '') }}</textarea>
                                                    @error('footer_text')
                                                        <div class="invalid-feedback">{{ $message }}</div>
                                                    @enderror
                                                </div>
                                                @php($default_location = \App\Models\BusinessSetting::where('key', 'default_location')->first())
                                                @php($default_location = isset($default_location) ? json_decode($default_location->value, true) :0)
                                                <div class="form-group">
                                                    <label class="input-label text-capitalize d-flex alig-items-center" for="latitude">{{ __('messages.latitude') }}<span class="input-label-secondary" data-bs-toggle="tooltip" data-bs-placement="right" data-bs-title="{{ __('messages.click_on_the_map_select_your_defaul_location') }}">
                                                            &nbsp;<i style="font-size:18px;text-transform:lowercase;">&#9432;</i></span></label>
                                                    <input type="text" 
                                                           id="latitude" 
                                                           name="latitude" 
                                                           class="form-control @error('latitude') is-invalid @enderror" 
                                                           placeholder="{{ __('messages.Ex :') }} -94.22213" 
                                                           value="{{ old('latitude', $default_location ? $default_location['lat'] : 0) }}" 
                                                           required 
                                                           readonly>
                                                    @error('latitude')
                                                        <div class="invalid-feedback">{{ $message }}</div>
                                                    @enderror
                                                </div>
                                                <div class="form-group mb-0">
                                                    <label class="input-label text-capitalize d-flex alig-items-center" for="longitude">{{ __('messages.longitude') }}<span class="input-label-secondary" data-bs-toggle="tooltip" data-bs-placement="right" data-bs-title="{{ __('messages.click_on_the_map_select_your_defaul_location') }}">
                                                            &nbsp;<i style="font-size:18px;text-transform:lowercase;">&#9432;</i></span></label>
                                                    <input type="text" 
                                                           name="longitude" 
                                                           class="form-control @error('longitude') is-invalid @enderror" 
                                                           placeholder="{{ __('messages.Ex :') }} 103.344322" 
                                                           id="longitude" 
                                                           value="{{ old('longitude', $default_location ? $default_location['lng'] : 0) }}" 
                                                           required 
                                                           readonly>
                                                    @error('longitude')
                                                        <div class="invalid-feedback">{{ $message }}</div>
                                                    @enderror
                                                </div>
                                            </div>
                                            <div class="col-lg-6">
                                                <div class="row">
                                                    <div class="col-md-6 mt-3 mx-auto">
                                                        <div class="form-group d-flex">
                                                            <input type="text" id="search-address-input"
                                                                class="form-control rounded-0"
                                                                placeholder="Enter Address or Place">
                                                        </div>
                                                    </div>
                                                </div>
                                                <div id="map-canvas" style=" width:100%;height: 50vh"></div>
                                            </div>

                                            {{-- <div class="col-lg-6">
                                                <div class="mb-3">
                                                    <input type="text" class="form-control form-control-sm"  id="search-address-input" aria-describedby="helpId" placeholder="Enter Map Location Address" />
                                                    <div id="location_map_canvas" style="height: 25rem;"></div>
                                                </div>
                                            </div> --}}
                                        </div>
                                    </div>
                                </div>

                                <div class="card mb-3">
                                    <div class="card-header">
                                        <h4 class="card-title m-0 d-flex align-items-center"> <span class="card-header-icon mr-2"><i class="tio-neighborhood"></i></span> <span>{{__('messages.business')}} {{__('messages.info')}}</span></h4>
                                    </div>
                                    <div class="card-body pb-0">
                                        <div class="row">
                                            <div class="col-sm-6 col-md-4 mb-3 mx-auto ">
                                                @php($logo = \App\Models\BusinessSetting::where('key', 'logo')->first())
                                                @php($logo = $logo->value ?? '')
                                                <div class="form-group mb-0 d-flex">
                                                    <center class="h-100px">
                                                        <img class="initial-10" id="logo-viewer" src="{{!empty($logo) ? asset('business/' . $logo) : asset('assets/images/icons/300x100/restaurant-default-image.png') }}" alt="logo image" />
                                                    </center>
                                                    <div class="custom-file d-flex flex-column ms-2 mb-3">
                                                        <label class="input-label mt-2"> {{ __('messages.logo') }} <small class="text-danger">* ( {{ __('messages.ratio') }} 300x100 )</small></label>
                                                        <input type="file" 
                                                               name="logo" 
                                                               id="customFileEg1" 
                                                               class="custom-file-input @error('logo') is-invalid @enderror" 
                                                               accept=".jpg, .png, .jpeg, .gif, .bmp, .tif, .tiff|image/*" 
                                                               hidden 
                                                               onchange="readImage(this,'#logo-viewer')">
                                                        <label class="custom-file-label btn btn-soft-warning" for="customFileEg1">{{ __('messages.choose') }}
                                                            {{ __('messages.file') }}</label>
                                                        @error('logo')
                                                            <div class="text-danger small mt-1">{{ $message }}</div>
                                                        @enderror
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-sm-6 col-md-6 mb-3">
                                                @php($icon = \App\Models\BusinessSetting::where('key', 'icon')->first())
                                                @php($icon = $icon->value ?? '')
                                                {{-- @dd($icon) --}}
                                                <div class="form-group mb-0 d-flex">
                                                    <center class="h-100px">
                                                        <img class="initial-10" id="iconViewer" src="{{!empty($icon) ? asset('business/'.$icon) : asset('assets/images/icons/restaurant-default-image.png') }}" alt="Fav icon" />
                                                    </center>
                                                    <div class="custom-file d-flex flex-column ms-2 mb-3">
                                                        <label class="input-label mt-2"> {{ __('messages.Fav Icon') }}<small class="text-danger">* ( {{ __('messages.ratio') }} 150x150 )</small></label>
                                                        <input type="file" 
                                                               name="icon" 
                                                               id="favIconUpload" 
                                                               class="custom-file-input @error('icon') is-invalid @enderror" 
                                                               accept=".jpg, .png, .jpeg, .gif, .bmp, .tif, .tiff|image/*" 
                                                               hidden  
                                                               onchange="readImage(this,'#iconViewer')">
                                                        <label class="custom-file-label btn btn-soft-warning" for="favIconUpload">{{ __('messages.choose') }}
                                                            {{ __('messages.file') }}</label>
                                                        @error('icon')
                                                            <div class="text-danger small mt-1">{{ $message }}</div>
                                                        @enderror
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                
                                

                                <!-- Submit Button -->
                                <div class="card">
                                    <div class="card-body">
                                        <div class="d-flex justify-content-end">
                                            <button type="reset" class="btn btn-outline-secondary me-2">
                                                <i class="fas fa-undo me-1"></i>
                                                {{ __('Reset') }}
                                            </button>
                                            <button type="submit" class="btn btn-primary" id="submit-btn">
                                                <i class="fas fa-save me-1"></i>
                                                {{ __('Save Business Settings') }}
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </form>

                            </div>
                        </div>

                        <div class="col-12">
                            <div class="card">
                                <div class="card-header">
                                    <h4 class="card-title">{{ __('Email Configuration') }}</h4>
                                </div>
                                @php($mailConfig = \App\Models\BusinessSetting::where('key', 'mail_config')->first())
                                @php($mailConfig = json_decode($mailConfig?->value) ?? null)


                                <div class="card-body">
                                    <form action="{{route('admin.business-settings.email-setup')}}" class="row" method="POST">
                                        @csrf
                                        @method('POST')

                                        <div class="form-group col-md-6">
                                            <label for="mail_mailer">Mailer</label>
                                            <select class="form-control @error('mail_mailer') is-invalid @enderror" 
                                                    id="mail_mailer" 
                                                    name="mail_mailer" 
                                                    required>
                                                <option value="">{{ __('Select Mailer') }}</option>
                                                <option value="smtp" {{ old('mail_mailer', $mailConfig->mail_mailer ?? '') == 'smtp' ? 'selected' : '' }}>SMTP</option>
                                                <option value="sendmail" {{ old('mail_mailer', $mailConfig->mail_mailer ?? '') == 'sendmail' ? 'selected' : '' }}>Sendmail</option>
                                                <option value="mailgun" {{ old('mail_mailer', $mailConfig->mail_mailer ?? '') == 'mailgun' ? 'selected' : '' }}>Mailgun</option>
                                                <option value="ses" {{ old('mail_mailer', $mailConfig->mail_mailer ?? '') == 'ses' ? 'selected' : '' }}>SES</option>
                                                <option value="postmark" {{ old('mail_mailer', $mailConfig->mail_mailer ?? '') == 'postmark' ? 'selected' : '' }}>Postmark</option>
                                            </select>
                                            @error('mail_mailer')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>

                                        <div class="form-group col-md-6">
                                            <label for="mail_host">Host</label>
                                            <input type="text" 
                                                   class="form-control @error('mail_host') is-invalid @enderror" 
                                                   id="mail_host" 
                                                   name="mail_host" 
                                                   value="{{ old('mail_host', $mailConfig->mail_host ?? '') }}" 
                                                   placeholder="e.g., smtp.gmail.com"
                                                   required>
                                            @error('mail_host')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>

                                        <div class="form-group col-md-6">
                                            <label for="mail_port">Port</label>
                                            <input type="number" 
                                                   class="form-control @error('mail_port') is-invalid @enderror" 
                                                   id="mail_port" 
                                                   name="mail_port" 
                                                   value="{{ old('mail_port', $mailConfig->mail_port ?? '') }}" 
                                                   placeholder="e.g., 587"
                                                   min="1" 
                                                   max="65535"
                                                   required>
                                            @error('mail_port')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>

                                        <div class="form-group col-md-6">
                                            <label for="mail_username">Username</label>
                                            <input type="text" 
                                                   class="form-control @error('mail_username') is-invalid @enderror" 
                                                   id="mail_username" 
                                                   name="mail_username" 
                                                   value="{{ old('mail_username', $mailConfig->mail_username ?? '') }}" 
                                                   placeholder="Your email username"
                                                   required>
                                            @error('mail_username')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>

                                        <div class="form-group col-md-6">
                                            <label for="mail_password">Password</label>
                                            <div class="position-relative">
                                                <input type="password" 
                                                       class="form-control @error('mail_password') is-invalid @enderror" 
                                                       id="mail_password" 
                                                       name="mail_password" 
                                                       value="{{ old('mail_password', $mailConfig->mail_password ?? '') }}" 
                                                       placeholder="Your email password"
                                                       required>
                                                <button type="button" class="btn btn-link position-absolute end-0 top-50 translate-middle-y" onclick="togglePassword()">
                                                    <i class="fas fa-eye" id="toggleIcon"></i>
                                                </button>
                                            </div>
                                            @error('mail_password')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>

                                        <div class="form-group col-md-6">
                                            <label for="mail_encryption">Encryption</label>
                                            <select class="form-control @error('mail_encryption') is-invalid @enderror" 
                                                    id="mail_encryption" 
                                                    name="mail_encryption">
                                                <option value="">{{ __('Select Encryption') }}</option>
                                                <option value="tls" {{ old('mail_encryption', $mailConfig->mail_encryption ?? '') == 'tls' ? 'selected' : '' }}>TLS</option>
                                                <option value="ssl" {{ old('mail_encryption', $mailConfig->mail_encryption ?? '') == 'ssl' ? 'selected' : '' }}>SSL</option>
                                            </select>
                                            @error('mail_encryption')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>

                                        <div class="form-group col-md-6">
                                            <label for="mail_from_address">From Email</label>
                                            <input type="email" 
                                                   class="form-control @error('mail_from_address') is-invalid @enderror" 
                                                   id="mail_from_address" 
                                                   name="mail_from_address" 
                                                   value="{{ old('mail_from_address', $mailConfig->mail_from_address ?? '') }}" 
                                                   placeholder="noreply@yourdomain.com"
                                                   required>
                                            @error('mail_from_address')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>

                                        <div class="form-group col-md-6">
                                            <label for="mail_from_name">From Name</label>
                                            <input type="text" 
                                                   class="form-control @error('mail_from_name') is-invalid @enderror" 
                                                   id="mail_from_name" 
                                                   name="mail_from_name" 
                                                   value="{{ old('mail_from_name', $mailConfig->mail_from_name ?? '') }}" 
                                                   placeholder="Your Business Name"
                                                   required>
                                            @error('mail_from_name')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                        <div class="btn--container col-md-12 d-flex justify-content-end">
                                            <button type="reset" class="btn btn-outline-secondary me-2">
                                                <i class="fas fa-undo me-1"></i>
                                                {{ __('Reset') }}
                                            </button>
                                            <button type="submit" class="btn btn-primary" id="email-submit-btn">
                                                <i class="fas fa-save me-1"></i>
                                                {{ __('Save Email Configuration') }}
                                            </button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>

                </div>
            </div>
        </div>
    </div>
</div>
@endsection
@push('javascript')
<script src="{{asset('assets/js/plugins/spartan-multi-image-picker.min.js')}}"></script>
{{-- <script>
    function handleError(errorResponse) {
        console.log(errorResponse)
        if (errorResponse && errorResponse.errors) {
            if (Array.isArray(errorResponse.errors)) {
                const errorMessages = Object.values(errorResponse.errors);
                const errorList = errorMessages.map(item => `<li>${item.message}</li>`);
                return `<ul>${errorList.join('')}</ul>`;
            }
            if (typeof errorResponse.errors === 'string') {
                return errorResponse.errors;
            }
            if (typeof errorResponse.errors === 'object') {
                const errorMessages = Object.values(errorResponse.errors);
                const errorList = errorMessages.map(item => `<li>${item}</li>`);
                return `<ul>${errorList.join('')}</ul>`;
            }
        }
        return errorResponse.error;
    }
</script> --}}


<script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyBPVVxuVkmdE1GzSgIqm_9dx64tea7Wltk&libraries=geometry,places"></script>
<script src="{{asset('assets/js/Helpers/mapHelper.js')}}"></script>
<script>
function initMap() {
    navigator.geolocation.getCurrentPosition((position) => {
        @if($default_location)
        const currentLocation = {
            lat: {{$default_location['lat']}},
            lng: {{$default_location['lng']}}
        };
        @else
        const currentLocation = {
            lat: position.coords.latitude,
            lng: position.coords.longitude
        };
        @endif

        myMap.CreateMap(currentLocation, {
            selector: "#map-canvas", // corrected syntax for object properties
            marker: {
                location: currentLocation,
                img: "{{ asset('assets/user/img/icons/marker-icon.png') }}",
                draggable: true
            }
        });

        var input = document.getElementById('search-address-input');
        var autocomplete = new google.maps.places.Autocomplete(input);
        autocomplete.addListener('place_changed', function() {
            var place = autocomplete.getPlace();

            if (!place.geometry) {
                console.log("No details available for input: '" + place.name + "'");
                return;
            }
            getAddress(place.geometry.location)
        })

        //drag marker to get posion
        google.maps.event.addListener(myMap.marker, 'dragend', function() {
            myMap.map.setCenter(myMap.marker.getPosition().toJSON());
            myMap.marker.setPosition(myMap.marker.getPosition().toJSON());
            myMap.setElementsPosition(myMap.marker.getPosition().toJSON());
        });

    });
}

initMap();
</script>

<script>
    function readImage(input,selector) {
        try {
            const reader = new FileReader();
            reader.onload = function (e) {
                const imgSrc = e.target.result;
                document.querySelector(selector).src = imgSrc;
            };
            reader.readAsDataURL(input.files[0]);
        } catch(error) {
            console.error(error);
        }
    }

    function togglePassword() {
        const passwordField = document.getElementById('mail_password');
        const toggleIcon = document.getElementById('toggleIcon');
        
        if (passwordField.type === 'password') {
            passwordField.type = 'text';
            toggleIcon.classList.remove('fa-eye');
            toggleIcon.classList.add('fa-eye-slash');
        } else {
            passwordField.type = 'password';
            toggleIcon.classList.remove('fa-eye-slash');
            toggleIcon.classList.add('fa-eye');
        }
    }

    // Form submission with loading states
    document.addEventListener('DOMContentLoaded', function() {
        const businessForm = document.getElementById('business-setup-form');
        const businessSubmitBtn = document.getElementById('submit-btn');
        const emailSubmitBtn = document.getElementById('email-submit-btn');

        if (businessForm && businessSubmitBtn) {
            businessForm.addEventListener('submit', function() {
                businessSubmitBtn.disabled = true;
                businessSubmitBtn.innerHTML = '<i class="fas fa-spinner fa-spin me-1"></i>{{ __("Saving...") }}';
            });
        }

        if (emailSubmitBtn) {
            const emailForm = emailSubmitBtn.closest('form');
            if (emailForm) {
                emailForm.addEventListener('submit', function() {
                    emailSubmitBtn.disabled = true;
                    emailSubmitBtn.innerHTML = '<i class="fas fa-spinner fa-spin me-1"></i>{{ __("Saving...") }}';
                });
            }
        }

        // Auto-dismiss alerts after 5 seconds
        const alerts = document.querySelectorAll('.alert');
        alerts.forEach(function(alert) {
            setTimeout(function() {
                const bsAlert = new bootstrap.Alert(alert);
                bsAlert.close();
            }, 5000);
        });

        // File input validation
        const fileInputs = document.querySelectorAll('input[type="file"]');
        fileInputs.forEach(function(input) {
            input.addEventListener('change', function() {
                const file = this.files[0];
                if (file) {
                    // Check file size (2MB limit)
                    if (file.size > 2 * 1024 * 1024) {
                        alert('{{ __("File size must not exceed 2MB") }}');
                        this.value = '';
                        return false;
                    }
                    
                    // Check file type
                    const allowedTypes = ['image/jpeg', 'image/png', 'image/jpg', 'image/gif'];
                    if (!allowedTypes.includes(file.type)) {
                        alert('{{ __("Please select a valid image file (JPEG, PNG, JPG, GIF)") }}');
                        this.value = '';
                        return false;
                    }
                }
            });
        });
    });
</script>



@endpush
