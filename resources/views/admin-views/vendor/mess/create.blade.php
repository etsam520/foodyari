@extends('layouts.dashboard-main')

@section('content')

    <div class="conatiner-fluid content-inner mt-n5 py-0">
        <div>
            <div class="row">
                <div class="col-sm-12 col-lg-12">
                    <div class="card">
                        <div class="card-header d-flex justify-content-between">
                            <div class="header-title">
                                <h4 class="card-title">{{ __('messages.add-mess') }}</h4>
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
                            <form id="form-wizard1" action="{{ route('admin.mess.store') }}" method="POST"
                                enctype="multipart/form-data" class="mt-3 text-center">
                                @csrf
                                <ul id="top-tab-list" class="p-0 row list-inline">
                                    <li class="mb-2 col-lg-3 col-md-6 text-start active" id="account">
                                        <a href="javascript:void();">
                                            <div class="iq-icon me-3">
                                                <svg fill="#3a57e8" width="20" class="svg-icon icon-20"
                                                    viewBox="0 -2.89 122.88 122.88" version="1.1" id="Layer_1"
                                                    xmlns="http://www.w3.org/2000/svg"
                                                    xmlns:xlink="http://www.w3.org/1999/xlink"
                                                    style="enable-background:new 0 0 122.88 117.09" xml:space="preserve"
                                                    stroke="#3a57e8">
                                                    <g id="SVGRepo_bgCarrier" stroke-width="0"></g>
                                                    <g id="SVGRepo_tracerCarrier" stroke-linecap="round"
                                                        stroke-linejoin="round"></g>
                                                    <g id="SVGRepo_iconCarrier">
                                                        <style type="text/css">
                                                            .st0 {
                                                                fill-rule: evenodd;
                                                                clip-rule: evenodd;
                                                            }
                                                        </style>
                                                        <g>
                                                            <path class="st0"
                                                                d="M36.82,107.86L35.65,78.4l13.25-0.53c5.66,0.78,11.39,3.61,17.15,6.92l10.29-0.41c4.67,0.1,7.3,4.72,2.89,8 c-3.5,2.79-8.27,2.83-13.17,2.58c-3.37-0.03-3.34,4.5,0.17,4.37c1.22,0.05,2.54-0.29,3.69-0.34c6.09-0.25,11.06-1.61,13.94-6.55 l1.4-3.66l15.01-8.2c7.56-2.83,12.65,4.3,7.23,10.1c-10.77,8.51-21.2,16.27-32.62,22.09c-8.24,5.47-16.7,5.64-25.34,1.01 L36.82,107.86L36.82,107.86z M29.74,62.97h91.9c0.68,0,1.24,0.57,1.24,1.24v5.41c0,0.67-0.56,1.24-1.24,1.24h-91.9 c-0.68,0-1.24-0.56-1.24-1.24v-5.41C28.5,63.53,29.06,62.97,29.74,62.97L29.74,62.97z M79.26,11.23 c25.16,2.01,46.35,23.16,43.22,48.06l-93.57,0C25.82,34.23,47.09,13.05,72.43,11.2V7.14l-4,0c-0.7,0-1.28-0.58-1.28-1.28V1.28 c0-0.7,0.57-1.28,1.28-1.28h14.72c0.7,0,1.28,0.58,1.28,1.28v4.58c0,0.7-0.58,1.28-1.28,1.28h-3.89L79.26,11.23L79.26,11.23 L79.26,11.23z M0,77.39l31.55-1.66l1.4,35.25L1.4,112.63L0,77.39L0,77.39z">
                                                            </path>
                                                        </g>
                                                    </g>
                                                </svg>
                                            </div>
                                            <span class="dark-wizard">{{ __('Mess Info') }}</span>
                                        </a>
                                    </li>
                                    <li id="personal" class="mb-2 col-lg-3 col-md-6 text-start">
                                        <a href="javascript:void();">
                                            <div class="iq-icon me-3">
                                                <svg class="svg-icon icon-20" xmlns="http://www.w3.org/2000/svg"
                                                    width="20" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                        d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                                                </svg>
                                            </div>
                                            <span class="dark-wizard">{{ __('messages.owner-info') }}</span>
                                        </a>
                                    </li>

                                    <li id="confirm" class="mb-2 col-lg-3 col-md-6 text-start">
                                        <a href="javascript:void();">
                                            <div class="iq-icon me-3">
                                                <svg class="svg-icon icon-20" xmlns="http://www.w3.org/2000/svg"
                                                    width="20" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                        d="M5 13l4 4L19 7" />
                                                </svg>
                                            </div>
                                            <span class="dark-wizard">Finish</span>
                                        </a>
                                    </li>
                                </ul>
                                <!-- fieldsets -->
                                <fieldset>
                                    <div class="form-card text-start">
                                        <div class="row">
                                            <div class="col-7">
                                                <h3 class="mb-4">Mess Information:</h3>
                                            </div>
                                            <div class="col-5">
                                                {{-- <h2 class="steps">Step 1 - 4</h2> --}}
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-md-4 mt-3">
                                                <div class="form-group">
                                                    <label class="input-label" for="name">Mess Name</label>
                                                    <input id="name" type="text" name="name"
                                                        class="form-control h--45px" placeholder="Enter Mess Name"
                                                        value="{{ old('name') }}">
                                                </div>
                                            </div>
                                            <div class="col-md-4 mt-3">
                                                <div class="form-group">
                                                    <label class="input-label" for="mess_no">Mess Id No.</label>
                                                    <input id="mess_no" type="text" name="mess_no"
                                                        class="form-control h--45px" placeholder="Enter Mess no "
                                                        value="{{$mess->mess_no?? old('mess_no') }}">
                                                </div>
                                            </div>
                                            <div class="col-md-4 mt-3">
                                                <div class="form-group">
                                                    <label class="input-label" for="tax">GST (%)</label>
                                                    <input id="tax" type="number" name="tax"
                                                        class="form-control h--45px" placeholder="Enter GST"
                                                        min="0" step=".01" value="{{ old('tax') }}">
                                                </div>
                                            </div>
                                            <div class="col-md-4 mt-3">
                                                <div class="form-group">
                                                    <label class="input-label" for="street">Street</label>
                                                    <input id="street" type="text" name="street"
                                                        class="form-control h--45px" placeholder="Enter Address"
                                                        value="{{ old('street') }}">
                                                </div>
                                            </div>
                                            <div class="col-md-4 mt-3">
                                                <div class="form-group">
                                                    <label class="input-label" for="city">City</label>
                                                    <input id="city" type="text" name="city"
                                                        class="form-control h--45px" placeholder="Enter City"
                                                        value="{{ old('city') }}">
                                                </div>
                                            </div>
                                            <div class="col-md-4 mt-3">
                                                <div class="form-group">
                                                    <label class="input-label" for="pincode">Pincode</label>
                                                    <input id="pincode" type="text" name="pincode"
                                                        class="form-control h--45px" placeholder="Enter Pincode"
                                                        value="{{ old('pincode') }}">
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-md-3 mt-5 ">
                                                <div class="form-group">
                                                    <label class="input-label" for="zone">Zone</label>
                                                    <select name="zone_id" id="zone_id" class="form-control"  data-placeholder="Select Zone"  tabindex="-1" aria-hidden="true">
                                                        <option value=""  >Select Zone</option>
                                                        @foreach ($zones as $zone)
                                                        @php( $position = json_decode($zone->coordinates))
                                                        <option value="{{$zone->id}}"  data-latitude="{{$position->latitude}}" data-longitude="{{$position->longitude}}" >{{$zone->name}}</option>
                                                        @endforeach
                                                    </select>
                                                </div>
                                                <div class="form-group">
                                                    <label class="input-label" for="radius">Service Area (radius - km.)</label>
                                                    <input type="number" name="radius" id="radius" value="{{old('radius')}}" class="form-control" >
                                                </div> 
                                                <div class="form-group">
                                                    <label class="input-label" for="latitude">Latitude</label>
                                                    <input type="text" id="latitude" name="latitude" class="form-control h--45px disabled"
                                                        placeholder="Ex : -94.22213" value="" required >
                                                </div>
                                                <div class="form-group mb-md-0">
                                                    <label class="input-label" for="longitude">Longitude</label>
                                                    <input type="text" name="longitude" class="form-control h--45px disabled" placeholder="Ex : 103.344322"
                                                        id="longitude" value="" required >
                                                </div>
                                            </div>
                                            <div class="col-md-8 mt-5">
                                                <div class="row">
                                                    <div class="col-md-6 mt-3 mx-auto">
                                                        <div class="form-group d-flex">
                                                            <input type="text" id="search-address-input" class="form-control rounded-0" placeholder="Enter Address or Place">
                                                        </div>
                                                    </div>
                                                </div>
                                                <div id="map-canvas" style=" width:100%;height: 50vh"></div>
                                            </div>

                                            <div class="col-md-4 mt-3">
                                                <img class="initial-57-2" id="viewer"
                                                    src="{{ asset('assets/images/icons/restaurant-default-image.png') }}"
                                                    alt="delivery-man image">

                                                <div class="form-group pt-3">
                                                    <label class="input-label">Restaurant Logo<small class="text-danger">
                                                            (Ratio 1:1)</small></label>
                                                    <div class="custom-file">
                                                        <input type="file" name="logo" id="customFileEg1"
                                                            class="custom-file-input"
                                                            accept=".jpg, .png, .jpeg, .gif, .bmp, .tif, .tiff|image/*"
                                                            >
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-md-4 mt-3">

                                                <img class="initial-57-2 mw-100" id="coverImageViewer"
                                                    src="{{ asset('assets/images/icons/300x100/restaurant-default-image.png') }}"
                                                    alt="Product thumbnail">
                                                    
                                                <div class="form-group pt-3">
                                                    <label for="name" class="input-label text-capitalize">cover
                                                        photo <span class="text-danger">(Ratio 3:1)</span></label>
                                                    <div class="custom-file">
                                                        <input type="file" name="cover_photo" id="coverImageUpload"
                                                            class="custom-file-input" accept=".jpg, .png, .jpeg, .gif, .bmp, .tif, .tiff|image/*">
                                                    </div>
                                                </div>
                                            </div>


                                        </div>
                                    </div>
                                    <span class="btn btn-primary next action-button float-end">Next</span>
                                </fieldset>
                                <fieldset>
                                    <div class="form-card text-start">
                                        <div class="row">
                                            <div class="col-7">
                                                <h3 class="mb-4">Owner Type:</h3>
                                            </div>
                                            <div class="col-5">
                                                {{-- <h2 class="steps">Step 1 - 4</h2> --}}
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <div class="form-check d-block">
                                                        <input class="form-check-input" type="radio" name="ownertype"
                                                            value="new" id="ownertype1">
                                                        <label class="form-check-label" for="ownertype1">
                                                            New Owner
                                                        </label>
                                                    </div>
                                                    <div class="form-check d-block">
                                                        <input class="form-check-input" type="radio" name="ownertype"
                                                            value="old" id="ownertype2" checked>
                                                        <label class="form-check-label" for="ownertype2">
                                                            Old Owner
                                                        </label>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row d-none" id="New-owner">
                                            <div class="col-12">
                                                <h3 class="mb-4">Account Information:</h3>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label class="form-label">Email: *</label>
                                                    <input type="email" class="form-control" name="email"
                                                        value="{{ old('email') }}" placeholder="Email Id" />
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label class="form-label">Password: *</label>
                                                    <input type="password" class="form-control" name="password"
                                                        autocomplete value="{{ old('password') }}"
                                                        placeholder="Password" />
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label class="form-label">Confirm Password: *</label>
                                                    <input type="password" class="form-control" autocomplete
                                                        name="cpwd" placeholder="Confirm Password" />
                                                </div>
                                            </div>
                                            <div class="col-md-12">
                                                <div class="form-card text-start">
                                                    <div class="row">
                                                        <div class="col-7">
                                                            <h3 class="mb-4">Personal Information:</h3>
                                                        </div>
                                                        <div class="col-5">
                                                            {{-- <h2 class="steps">Step 2 - 4</h2> --}}
                                                        </div>
                                                    </div>
                                                    <div class="row">
                                                        <div class="col-md-6">
                                                            <div class="form-group">
                                                                <label class="form-label">First Name: *</label>
                                                                <input type="text" class="form-control" name="fname"
                                                                    value="{{ old('fname') }}"
                                                                    placeholder="First Name" />
                                                            </div>
                                                        </div>
                                                        <div class="col-md-6">
                                                            <div class="form-group">
                                                                <label class="form-label">Last Name: *</label>
                                                                <input type="text" class="form-control" name="lname"
                                                                    value="{{ old('lname') }}"
                                                                    placeholder="Last Name" />
                                                            </div>
                                                        </div>
                                                        <div class="col-md-6">
                                                            <div class="form-group">
                                                                <label class="form-label">Contact No.: *</label>
                                                                <input type="text" class="form-control" name="phone"
                                                                    value="{{ old('phone') }}"
                                                                    placeholder="Contact No." />
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row" id="Old-owner">
                                            <div class="col-12">
                                                <h3 class="mb-4">Vendor Information:</h3>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <div class="mb-3">
                                                        <label for="" class="form-label">Selcect Vedor Name</label>
                                                        <select class="form-select form-select-lg" name="vendor_id"
                                                            id="">
                                                            <option selected value="">Select one</option>
                                                            @foreach ($vendors as $vendor)
                                                                <option value="{{ $vendor->id }}">{{ $vendor->f_name }}
                                                                    {{ $vendor->l_name }}</option>
                                                            @endforeach
                                                            </select>
                                                            
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-md-12">
                                                <input type="submit" name="submit"
                                                    class="btn btn-primary next action-button float-end" value="submit">
                                                <span
                                                    class="btn btn-dark previous action-button-previous float-end me-1">Previous</span>
                                            </div>
                                        </div>
                                    </div>



                                </fieldset>
                                
                                <fieldset>
                                    <div class="form-card">
                                        <div class="row">
                                            <div class="col-7">
                                                <h3 class="mb-4 text-left">Finish:</h3>
                                            </div>
                                            <div class="col-5">
                                                {{-- <h2 class="steps">Step 4 - 4</h2> --}}
                                            </div>
                                        </div>
                                        <br><br>
                                        <h2 class="text-center text-success"><strong>SUCCESS !</strong></h2>
                                        <br>
                                        <div class="row justify-content-center">
                                            <div class="col-3"> <img src="{{asset('assets/images/pages/img-success.png')}}"
                                                    class="img-fluid" alt="fit-image"> </div>
                                        </div>
                                        <br><br>
                                        <div class="row justify-content-center">
                                            <div class="text-center col-7">
                                                <h5 class="text-center purple-text">You Have Successfully Signed Up</h5>
                                            </div>
                                        </div>
                                    </div>
                                </fieldset>
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
        const currentLocation = {
            lat: position.coords.latitude,
            lng: position.coords.longitude
        };

        myMap.CreateMap(currentLocation, {
            selector: "#map-canvas", // corrected syntax for object properties
            marker: {
                location: currentLocation,
                img: "{{ asset('assets/user/img/icons/marker-icon.png') }}",
                draggable: true
            }
        });
        // search location
        var input = document.getElementById('search-address-input');
       
        var autocomplete = new google.maps.places.Autocomplete(input);
        autocomplete.addListener('place_changed', function() {
            var place = autocomplete.getPlace();

            if (!place.geometry) {
                console.log("No details available for input: '" + place.name + "'");
                return;
            }
            myMap.map.setCenter(place.geometry.location.toJSON());
            myMap.marker.setPosition(place.geometry.location.toJSON());
            myMap.setElementsPosition(place.geometry.location.toJSON());
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

@endpush
