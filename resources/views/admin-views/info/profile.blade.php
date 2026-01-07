@extends('layouts.dashboard-main')
@push('css')
<style>
     .gradient-custom {
            background: linear-gradient(to right bottom, rgba(246, 211, 101, 1), rgba(253, 160, 133, 1));
        }

        .badge-custom {
            background-color: #007bff;
            color: white;
            padding: 5px 10px;
            border-radius: 20px;
            margin-right: 5px;
        }

        .map-container {
            height: 200px;
            margin-top: 20px;
            border-radius: 10px;
            overflow: hidden;
            position: relative;
        }

        .map-container iframe {
            width: 100%;
            height: 100%;
            border: none;
        }

        .cover-photo {
            width: 100%;
            height: 200px;
            object-fit: cover;
            border-radius: 0.5rem 0.5rem 0 0;
        }
</style>
@endpush

@section('content')

    <div class="conatiner-fluid content-inner mt-n5 py-0">
        <div class="row"style="background-color: #f4f5f7;">
            <div class="container py-5 h-100">
                <div class="row d-flex justify-content-center align-items-center h-100">
                    <div class="col col-lg-8 mb-4 mb-lg-0">
                        <div class="card mb-3" style="border-radius: .5rem;">

                            <!-- Cover Photo -->
                            @php($icon = \App\Models\BusinessSetting::where('key', 'icon')->first())
                            @php($icon = $icon->value ?? '')
                            <img src="{{!empty($icon) ? asset('business/'.$icon) : asset('assets/images/icons/restaurant-default-image.png') }}"
                                alt="Cover Photo" class="cover-photo">

                            <div class="row g-0">
                                <!-- Left Column -->
                                <div class="col-md-4 gradient-custom text-center text-white"
                                    style="border-top-left-radius: .5rem; border-bottom-left-radius: .5rem;">
                                    @php($logo = \App\Models\BusinessSetting::where('key', 'logo')->first())
                                    @php($logo = $logo->value ?? '')
                                    <img src="{{!empty($logo) ? asset('business/' . $logo) : asset('assets/images/icons/300x100/restaurant-default-image.png') }}"
                                        alt="Business Logo" class="img-fluid my-5" style="width: 100px; border-radius: 10px;">
                                    <h5></h5>

                                    <p>
                                        {{-- @foreach ($zones as $zone)
                                         {{$zone->id == $restaurant->zone_id? Str::ucfirst($zone->name): null}}
                                        @endforeach --}}
                                    </p>
                                    <a href="{{route('admin.profile.edit')}}" class="btn btn-sm btn-soft-info">Edit</a>
                                    <a href="{{route('admin.business-settings.business-setup')}}" class="btn btn-sm btn-soft-info">Business Setting</a>
                                </div>

                                <!-- Right Column -->
                                <div class="col-md-8">
                                    <div class="card-body p-4">
                                        <!-- Contact Information -->
                                        <h6>Profile Information</h6>
                                        <hr class="mt-0 mb-4">
                                        <div class="row pt-1">
                                            <div class="col-6 mb-2">
                                                <h6>Business Name</h6>
                                                @php($name = \App\Models\BusinessSetting::where('key', 'business_name')->first())
                                                <h4 class="text-muted">{{Str::ucfirst($name->value ?? '')}}</h4>
                                            </div>
                                            <div class="col-6 mb-2">
                                                <h6>Admin Name</h6>
                                                <p class="text-muted">{{Str::ucfirst($admin->f_name ?? '')}} {{Str::ucfirst($admin->l_name ?? '')}}</p>
                                            </div>
                                            <div class="col-6 mb-2">
                                                <h6>Admin Phone</h6>
                                                <p class="text-muted">{{Str::ucfirst($admin->phone ?? '')}}</p>
                                            </div>
                                            <div class="col-6 mb-2">
                                                <h6>Admin Email</h6>
                                                <p class="text-muted">{{Str::ucfirst($admin->email ?? '')}}</p>
                                            </div>

                                            <div class="col-6 mb-2">
                                                <h6>Phone</h6>
                                                @php($phone = \App\Models\BusinessSetting::where('key', 'phone')->first())
                                                <p class="text-muted">{{$phone->value ?? ''}}</p>
                                            </div>
                                            <div class="col-6 mb-2">
                                                <h6>Email</h6>
                                                @php($email = \App\Models\BusinessSetting::where('key', 'email_address')->first())
                                                <p class="text-muted">{{ $email->value ?? '' }}</p>
                                            </div>
                                            <div class="col-6 mb-2">
                                                <h6>Address</h6>
                                                @php($address = \App\Models\BusinessSetting::where('key', 'address')->first())
                                                <p class="text-muted">{{Str::ucfirst($address->value ?? '')}}</p>
                                            </div>
                                        </div>

                                        <!-- Location -->
                                        <hr class="mt-0 mb-2 hr-horizontal">
                                        <h6>Location</h6>
                                        @php($default_location = \App\Models\BusinessSetting::where('key', 'default_location')->first())
                                        @php($default_location = isset($default_location) ? json_decode($default_location->value, true) :0)
                                        <div class="row pt-1">
                                            <div class="col-6 mb-2">
                                                <h6>Latitude</h6>
                                                <p class="text-muted">{{ $default_location ? $default_location['lat'] : 0 }}</p>
                                            </div>
                                            <div class="col-6 mb-2">
                                                <h6>Longitude</h6>
                                                <p class="text-muted">{{ $default_location ? $default_location['lng'] : 0 }}</p>
                                            </div>
                                        </div>

                                        <!-- Map -->
                                        <div class="map-container">
                                            <iframe
                                                src="https://www.google.com/maps/embed/v1/place?key=AIzaSyBPVVxuVkmdE1GzSgIqm_9dx64tea7Wltk&q={{ $default_location ? $default_location['lat'] : 0 }},{{ $default_location ? $default_location['lng'] : 0 }}"
                                                allowfullscreen>
                                            </iframe>
                                        </div>

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

