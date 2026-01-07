@extends('vendor-views.layouts.dashboard-main')
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
    <div class="row d-flex justify-content-center align-items-center h-100">
        <div class="d-flex justify-content-between mb-3">
            <h4 class="text-primary fw-bolder">Profile</h4>
            <a href="{{route('vendor.profile.edit')}}" class="btn btn-sm btn-primary">Update Profile</a>
        </div>
        <div class="col col-lg-12 mb-4 mb-lg-0">
            <div class="card" style="border-radius: 10px;">
                <div class="row g-0">
                    <!-- Right Column -->
                    <div class="col-md-4 text-center text-white" style="background-color: #d8ddfa;border-radius: 10px;">
                        <div class="py-5 px-4" style="">
                            <div class="">
                                <img src="{{ $restaurant->logo ? asset('restaurant/' . $restaurant->logo) : asset('assets/images/icons/restaurant-default-image.png') }}"
                                    alt="Restaurant Logo" class="img-fluid mb-5"
                                    style="height: 200px; border-radius: 10px;">
                                <h5>{{Str::ucfirst($restaurant->name)}}</h5>
                                <p class="text-primary fw-bolder">
                                    @foreach ($zones as $zone)
                                        {{$zone->id == $restaurant->zone_id ? Str::ucfirst($zone->name) : null}}
                                    @endforeach
                                </p>
                                <a href="{{ route('vendor.profile.view-document') }}" class="btn btn-sm btn-primary">View Document</a>
                                <a href="{{route('vendor.business-settings.restaurant-setup')}}"
                                    class="btn btn-sm btn-primary">Business Setting</a>
                            </div>
                        </div>
                    </div>
                    <!-- Left Column -->
                    <div class="col-md-8">
                        <div class="card-body" style="border-radius: 10px;">
                            <!-- Contact Information -->
                            <h4 class="text-primary fw-bolder">Contact Information</h4>
                            <hr style="border: 1px solid #cecbcb;">
                            <div>
                                <div class="d-flex justify-content-between mb-3">
                                    <h6 class="w-25">Email</h6>
                                    <div class="text-muted text-end">{{$restaurant->email}}</div>
                                </div>
                                @php($address = json_decode($restaurant->address))
                                <div class="d-flex justify-content-between mb-3">
                                    <h6 class="w-25">Address</h6>
                                    <div class="text-muted text-end">{{Str::ucfirst($address->street ?? '')}}</div>
                                </div>
                                <div class="d-flex justify-content-between mb-3">
                                    <h6 class="w-25">Zone</h6>
                                    <div class="text-muted text-end">{{Str::ucfirst($address->city ?? '')}}</div>
                                </div>
                                <div class="d-flex justify-content-between mb-3">
                                    <h6 class="w-25">Pincode</h6>
                                    <div class="text-muted text-end">{{ $address->pincode ?? ''}}</div>
                                </div>
                                @php($badges = json_decode($restaurant->badges))
                                @if($badges != null)
                                    <div class="d-flex justify-content-between mb-3">
                                        <h6 class="w-25">Badges</h6>
                                        <div class="badges text-end">
                                            @if($badges->b2 ?? false)
                                                <span class="badge-custom">{{Str::ucfirst($badges->b2)}}</span>
                                            @endif
                                            @if($badges->b3 ?? false)
                                                <span class="badge-custom">{{Str::ucfirst($badges->b3)}}</span>
                                            @endif
                                        </div>
                                    </div>
                                @endif
                                <div class="d-flex justify-content-between mb-3">
                                    <h6 class="w-25 text-nowrap">Description </h6>
                                    <div class="text-muted text-end">{{$restaurant->description}}</div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="card" style="border-radius: 10px;">
                <div class="row g-0">
                    <!-- Right Column -->
                    <div class="col-md-4 text-center text-white" style="background-color: #d8ddfa;border-radius: 10px;">
                        <div class="p-3" style="border-radius: 10px;">
                            <div class="">
                                <div class="map-container bg-white p-2 mt-0" style="border-radius: 10px;">
                                    <iframe
                                        src="https://www.google.com/maps/embed/v1/place?key=AIzaSyBPVVxuVkmdE1GzSgIqm_9dx64tea7Wltk&q={{$restaurant->latitude}},{{$restaurant->longitude}}"
                                        style="height: 100%;" allowfullscreen>
                                    </iframe>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- Left Column -->
                    <div class="col-md-8">
                        <div class="card-body" style="border-radius: 10px;">
                                <h4 class="text-primary fw-bolder">Location</h4>
                                <hr style="border: 1px solid #cecbcb;">
                                <div>
                                    <div class="d-flex justify-content-between">
                                        <h6 class="w-25">Latitude</h6>
                                        <p class="text-muted text-end">{{$restaurant->latitude}}</p>
                                    </div>
                                    <div class="d-flex justify-content-between">
                                        <h6 class="w-25">Longitude</h6>
                                        <p class="text-muted text-end">{{$restaurant->longitude}}</p>
                                    </div>
                                    <div class="d-flex justify-content-between">
                                        <h6 class="w-25 text-nowrap">Service Area (radius - km.)</h6>
                                        <p class="text-muted text-end">{{$restaurant->radius}}</p>
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
</div>

@endsection
