@extends('vendor-views.layouts.dashboard-main')

@section('content')

<div class="conatiner-fluid content-inner mt-n5 py-0">
    <div>
        <div class="row">
            <div class="col-sm-12 col-lg-12">
                <div class="card">
                    <div class="card-header d-flex justify-content-between">
                        <div class="header-title">
                            <h5 class="page-header-title">{{ __('Update Info') }}</h5>
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
                        <form action="{{ route('vendor.profile.update') }}" method="POST" enctype="multipart/form-data"
                            class="mt-3 ">
                            @csrf
                            <input type="hidden" name="id" id="restaurantId" value="{{$restaurant->id}}">
                            <div class="row">
                                <div class="col-md-6 mt-3">
                                    <img class="initial-57-2" id="viewer" data-preview="1"
                                        src="{{ $restaurant->logo ? asset('restaurant/' . $restaurant->logo) : asset('assets/images/icons/restaurant-default-image.png') }}"
                                        alt="delivery-man image">

                                    <div class="form-group pt-3">
                                        <label class="input-label">Restaurant Logo<small class="text-danger">
                                                (Ratio 1:1)</small></label>
                                        <div class="custom-file">
                                            <input type="file" name="logo" id="customFileEg1" data-image-input="1"
                                                class="custom-file-input" style="width: 220px;"
                                                accept=".jpg, .png, .jpeg, .gif, .bmp, .tif, .tiff|image/*">
                                        </div>
                                    </div>
                                </div>
                                {{-- <div class="col-md-6 mt-3">

                                    <img class="initial-57-2 mw-100" id="coverImageViewer"
                                        src="{{ $restaurant->cover_photo ? asset('restaurant/cover/' . $restaurant->cover_photo) : asset('assets/images/icons/300x100/restaurant-default-image.png') }}"
                                        alt="Product thumbnail" data-preview="2">

                                    <div class="form-group pt-3">
                                        <label for="name" class="input-label text-capitalize">Cover
                                            photo <span class="text-danger">(Ratio 3:1)</span></label>
                                        <div class="custom-file">
                                            <input type="file" name="cover_photo" id="coverImageUpload"
                                                style="width: 220px;" class="custom-file-input" data-image-input="2"
                                                accept=".jpg, .png, .jpeg, .gif, .bmp, .tif, .tiff|image/*">
                                        </div>
                                    </div>
                                </div> --}}
                            </div>
                            <hr style="border: 1px solid #cecbcb;">
                            <div class="row">
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label class="input-label" for="name">Restaurant Name</label>
                                        <input id="name" type="text" name="name" class="form-control h--45px"
                                            placeholder="Enter Restaurant Name"
                                            value="{{$restaurant->name ?? old('name') }}">
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label class="input-label" for="email">Email</label>
                                        <input id="email" type="text" name="email" class="form-control h--45px"
                                            placeholder="example@gmail.com"
                                            value="{{$restaurant->email ?? old('email') }}">
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label class="form-label" for="mobno">Phone</label>
                                        <input type="text" class="form-control" id="mobno" name="phone"
                                            value="{{$restaurant->phone ?? old('phone') }}" placeholder="Phone Number">
                                    </div>
                                </div>
                                @php($address = json_decode($restaurant->address))
                                {{-- @dd($address) --}}
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label class="input-label" for="tax">GST (%)</label>
                                        <input id="tax" type="number" name="tax" class="form-control h--45px"
                                            placeholder="Enter GST" min="0" step=".01"
                                            value="{{$restaurant->tax ?? old('tax') }}">
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label class="input-label" for="city">City</label>
                                        <input id="city" type="text" name="city" class="form-control h--45px"
                                            placeholder="Enter City" value="{{ $address->city ?? old('city') }}">
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label class="input-label" for="pincode">Pincode</label>
                                        <input id="pincode" type="text" name="pincode" class="form-control h--45px"
                                            placeholder="Enter Pincode"
                                            value="{{ $address->pincode ?? old('pincode') }}">
                                    </div>
                                </div>

                                <div class="col-md-12">
                                    <div class="form-group">
                                        <label class="input-label" for="street">Address</label>
                                        {{-- <input id="street" type="text" name="street" class="form-control h--45px"
                                            placeholder="Enter Address" value="{{ $address->street ?? old('street') }}">
                                        --}}
                                        <textarea name="street" id="street" class="form-control h--45px"
                                            placeholder="Enter Address">{{ $address->street ?? old('street') }}</textarea>
                                    </div>
                                </div>
                            </div>
                            <hr style="border: 1px solid #cecbcb;">
                            <div class="row">
                                <div class="col-md-3">
                                    {{-- <div class="form-group d-flex">
                                        <input type="text" id="search-address-input" class="form-control rounded-0"
                                        placeholder="Enter Address or Place">
                                    </div> --}}
                                    <label class="input-label" for="">Search</label>
                                    <div class="input-group mb-3">
                                        <span class="input-group-text" id="inputGroup-sizing-sm" style="border: 1px solid #bfbfbfba;"><i class="feather-search"></i></span>
                                        <input type="text" class="form-control" id="search-address-input">
                                    </div>
                                    <div class="form-group">
                                        <label class="input-label" for="zone">Zone</label>
                                        <select name="zone_id" id="zone_id" class="form-control"
                                            data-placeholder="Select Zone" tabindex="-1" aria-hidden="true">
                                            <option value="">Select Zone</option>
                                            @foreach ($zones as $zone)
                                                <option value="{{$zone->id}}" {{$zone->id == $restaurant->zone_id ? 'selected' : null}}>{{$zone->name}}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="form-group">
                                        <label class="input-label" for="radius">Service Area (radius - km.)</label>
                                        <input type="number" name="radius" id="radius"
                                            value="{{$restaurant->radius ?? old('radius')}}" class="form-control">
                                    </div>
                                    <div class="form-group">
                                        <label class="input-label" for="latitude">Latitude</label>
                                        <input type="text" id="latitude" name="latitude"
                                            class="form-control h--45px disabled" value="{{$restaurant->latitude}}"
                                            placeholder="Ex : -94.22213" value="" required readonly>
                                    </div>
                                    <div class="form-group mb-md-0">
                                        <label class="input-label" for="longitude">Longitude</label>
                                        <input type="text" name="longitude" class="form-control h--45px disabled"
                                            placeholder="Ex : 103.344322" id="longitude"
                                            value="{{$restaurant->longitude}}" required readonly>
                                    </div>
                                </div>
                                <div class="col-md-9">
                                    <div class="row">
                                        <div class="col-md-6 mt-3 mx-auto">

                                        </div>
                                    </div>
                                    <div id="map-canvas" class="rounded-4" style=" width:100%;height: 375px;"></div>
                                </div>
                            </div>

                            <hr style="border: 1px solid #cecbcb;">
                            <div class="row">
                                @php($badges = json_decode($restaurant->badges))
                                <div class="col-md-4 col-12">
                                    <div class="form-group">
                                        <label class="input-label" for="badge_one">Badge
                                            One</label>
                                        <input id="badge_one" type="text" value="{{$badges->b1 ?? null}}"
                                            name="badge_one" class="form-control h--45px" placeholder="Enter Badge One">
                                    </div>
                                </div>
                                <div class="col-md-4 col-12">
                                    <div class="form-group">
                                        <label class="input-label" for="badge_two">Badge
                                            Two</label>
                                        <input id="badge_two" type="text" value="{{$badges->b2 ?? null}}"
                                            name="badge_two" class="form-control h--45px" placeholder="Enter Badge Two">
                                    </div>
                                </div>
                                <div class="col-md-4 col-12">
                                    <div class="form-group">
                                        <label class="input-label" for="badge_three">Badge
                                            Three</label>
                                        <input id="badge_three" type="text" value="{{$badges->b3 ?? null}}"
                                            name="badge_three" class="form-control h--45px"
                                            placeholder="Enter Badge Three">
                                    </div>
                                </div>
                                <div class="col-md-12">
                                    <label class="input-label" for="description">Desciption</label>
                                    <textarea name="description" id="description" class="form-control" cols="30"
                                     placeholder="Write here" rows="2"> {{$restaurant->description}}</textarea>
                                </div>
                            </div>
                            <hr style="border: 1px solid #cecbcb;">
                            <div class="row">
                                <div class="col-md-12">
                                    <input type="submit" name="submit"
                                        class="btn btn-primary next action-button float-end" value="Submit">
                                </div>
                            </div>

                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@endsection


@push('javascript')
    <script async
        src="https://maps.googleapis.com/maps/api/js?key=AIzaSyBPVVxuVkmdE1GzSgIqm_9dx64tea7Wltk&loading=async&callback=initMap&libraries=geometry,places">
        </script>
    <script src="{{ asset('assets/js/Helpers/mapHelper.js') }}"></script>
    <script>
        document.querySelector("#ownertype1").addEventListener('click', () => {
            document.querySelector("#New-owner").classList.toggle('d-none');
            document.querySelector("#Old-owner").classList.toggle('d-none');
        });
        document.querySelector("#ownertype2").addEventListener('click', () => {
            document.querySelector("#New-owner").classList.toggle('d-none');
            document.querySelector("#Old-owner").classList.toggle('d-none');
        });
    </script>

    <script>
        function initMap() {
            navigator.geolocation.getCurrentPosition((position) => {
                @if(isset($restaurant->latitude) && isset($restaurant->longitude))
                    const currentLocation = {
                        lat: parseFloat("{{$restaurant->latitude}}"),
                        lng: parseFloat("{{$restaurant->longitude}}")
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
                myMap.setElementsPosition(myMap.marker.getPosition().toJSON());
                // search location
                var input = document.getElementById('search-address-input');
                var autocomplete = new google.maps.places.Autocomplete(input);
                autocomplete.addListener('place_changed', function () {
                    var place = autocomplete.getPlace();

                    if (!place.geometry) {
                        console.log("No details available for input: '" + place.name + "'");
                        return;
                    }
                    gmyMap.map.setCenter(place.geometry.location.toJSON());
                    myMap.marker.setPosition(place.geometry.location.toJSON());
                    myMap.setElementsPosition(place.geometry.location.toJSON());
                })
                //drag marker to get posion
                google.maps.event.addListener(myMap.marker, 'dragend', function () {
                    myMap.map.setCenter(myMap.marker.getPosition().toJSON());
                    myMap.marker.setPosition(myMap.marker.getPosition().toJSON());
                    myMap.setElementsPosition(myMap.marker.getPosition().toJSON());
                });

            });
        }
    </script>

    <script type="module">
        import { readImage } from "{{asset('assets/js/Helpers/helper.js')}}";

        document.querySelector('[data-image-input="1"]').addEventListener('change', (event) => {
            readImage(event.target, '[data-preview="1"]');
        })
        document.querySelector('[data-image-input="2"]').addEventListener('change', (event) => {
            readImage(event.target, '[data-preview="2"]');
        })

    </script>

@endpush
