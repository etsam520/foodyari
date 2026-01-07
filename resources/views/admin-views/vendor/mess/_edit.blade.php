@extends('layouts.dashboard-main')

@section('content')

    <div class="conatiner-fluid content-inner mt-n5 py-0">
        <div>
            <div class="row">
                <div class="col-sm-12 col-lg-12">
                    <div class="card">
                        <div class="card-header d-flex justify-content-between">
                            <div class="header-title">
                                <h4 class="card-title">{{ __('Update Mess Info') }}</h4>
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
                            <form action="{{ route('admin.mess.update') }}" method="POST"
                                enctype="multipart/form-data" class="mt-3 text-center">
                                @csrf
                                <input type="hidden" name="id" id="messId" value="{{$mess->id}}">
                               <div class="row">
                                    <div class="col-md-6 mt-3">
                                        <img class="initial-57-2" id="viewer" data-preview="1"
                                            src="{{ $mess->logo? asset('vendorMess/'.$mess->logo): asset('assets/images/icons/restaurant-default-image.png') }}"
                                            alt="delivery-man image">

                                        <div class="form-group pt-3">
                                            <label class="input-label">Mess Logo<small class="text-danger">
                                                    (Ratio 1:1)</small></label>
                                            <div class="custom-file">
                                                <input type="file" name="logo" id="customFileEg1" data-image-input="1"
                                                    class="custom-file-input"
                                                    accept=".jpg, .png, .jpeg, .gif, .bmp, .tif, .tiff|image/*"
                                                    >
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-6 mt-3">

                                        <img class="initial-57-2 mw-100" id="coverImageViewer"
                                            src="{{ $mess->cover_photo? asset('vendorMess/cover/'.$mess->cover_photo): asset('assets/images/icons/300x100/restaurant-default-image.png') }}"
                                            alt="Product thumbnail" data-preview="2">
                                            
                                        <div class="form-group pt-3">
                                            <label for="name" class="input-label text-capitalize">Cover
                                                photo <span class="text-danger">(Ratio 3:1)</span></label>
                                            <div class="custom-file">
                                                <input type="file" name="cover_photo" id="coverImageUpload"
                                                    class="custom-file-input" data-image-input="2" accept=".jpg, .png, .jpeg, .gif, .bmp, .tif, .tiff|image/*">
                                            </div>
                                        </div>
                                    </div>
                               </div>
                               <hr class="hr-horizontal">
                                <div class="row">
                                    <div class="col-md-4 mt-3">
                                        <div class="form-group">
                                            <label class="input-label" for="name">Mess Name</label>
                                            <input id="name" type="text" name="name"
                                                class="form-control h--45px" placeholder="Enter Mess Name"
                                                value="{{$mess->name?? old('name') }}">
                                        </div>
                                    </div>
                                    <div class="col-md-4 mt-3">
                                        <div class="form-group">
                                            <label class="input-label" for="mess_no">Mess Id No.</label>
                                            <input id="mess_no" type="text" name="mess_no"
                                                class="form-control h--45px" placeholder="Enter Mess Name"
                                                value="{{$mess->mess_no?? old('mess_no') }}">
                                        </div>
                                    </div>
                                    @php($address = json_decode($mess->address))
                                    {{-- @dd($address) --}}
                                    <div class="col-md-4 mt-3">
                                        <div class="form-group">
                                            <label class="input-label" for="street">Street</label>
                                            <input id="street" type="text" name="street"
                                                class="form-control h--45px" placeholder="Enter Address"
                                                value="{{ $address->street??old('street') }}">
                                        </div>
                                    </div>
                                    <div class="col-md-4 mt-3">
                                        <div class="form-group">
                                            <label class="input-label" for="city">City</label>
                                            <input id="city" type="text" name="city"
                                                class="form-control h--45px" placeholder="Enter City"
                                                value="{{ $address->city??old('city') }}">
                                        </div>
                                    </div>
                                    <div class="col-md-4 mt-3">
                                        <div class="form-group">
                                            <label class="input-label" for="pincode">Pincode</label>
                                            <input id="pincode" type="text" name="pincode"
                                                class="form-control h--45px" placeholder="Enter Pincode"
                                                value="{{ $address->pincode??old('pincode') }}">
                                        </div>
                                    </div>
                                </div>
                                <hr class="hr-horizontal">
                                <hr class="hr-horizontal">
                                <div class="row">
                                    <div class="col-md-3 mt-5 ">
                                        <div class="form-group">
                                            <label class="input-label" for="zone">Zone</label>
                                        <select name="zone_id" id="zone_id" class="form-control"  data-placeholder="Select Zone"  tabindex="-1" aria-hidden="true">
                                            <option value=""  >Select Zone</option>
                                            @foreach ($zones as $zone)
                                            <option value="{{$zone->id}}"  {{$zone->id == $mess->zone_id?'selected': null}} >{{$zone->name}}</option>
                                            @endforeach
                                        </select>
                                        </div>
                                        <div class="form-group">
                                            <label class="input-label" for="radius">Service Area (radius - km.)</label>
                                            <input type="number" name="radius" id="radius" value="{{$mess->radius??old('radius')}}" class="form-control" >
                                        </div> 
                                        <div class="form-group">
                                            <label class="input-label" for="latitude">Latitude</label>
                                            <input type="text" id="latitude" name="latitude" class="form-control h--45px disabled" value="{{$mess->latitude}}"
                                                placeholder="Ex : -94.22213" value="" required readonly>
                                        </div>
                                        <div class="form-group mb-md-0">
                                            <label class="input-label" for="longitude">Longitude</label>
                                            <input type="text" name="longitude" class="form-control h--45px disabled" placeholder="Ex : 103.344322"
                                                id="longitude" value="{{$mess->longitude}}" required readonly>
                                        </div>
                                    </div>
                                    <div class="col-md-9 mt-5">
                                        <div class="row">
                                            <div class="col-md-6 mt-3 mx-auto">
                                                <div class="form-group d-flex">
                                                    <input type="text" id="search-address-input" class="form-control rounded-0" placeholder="Enter Address or Place">
                                                   
                                                </div>
                                            </div>
                                        </div>
                                        <div id="map-canvas" style=" width:100%;height: 50vh"></div>
                                    </div>
                                </div>

                                <hr class="hr-horizontal">
                                <div class="row">
                                    @php($badges = json_decode($mess->badges))
                                    <div class="col-md-6 col-12">
                                        <div class="form-group">
                                            <label class="input-label" for="badge_one">badge
                                                one</label>
                                            <input id="badge_one" type="text" value="{{$badges->b1??null}}" name="badge_one" class="form-control h--45px" placeholder="Enter Badge One">
                                        </div>
                                    </div>
                                    <div class="col-md-6 col-12">
                                        <div class="form-group">
                                            <label class="input-label" for="badge_two">badge
                                                two</label>
                                            <input id="badge_two" type="text" value="{{$badges->b2??null}}" name="badge_two" class="form-control h--45px" placeholder="Enter Badge Two">
                                        </div>
                                    </div>
                                </div>
                                    
                                <div class="row">
                                    <div class="col-md-12 mt-3">
                                        <input type="submit" name="submit"
                                            class="btn btn-primary next action-button float-end" value="submit">
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
<script 
src="https://maps.googleapis.com/maps/api/js?key=AIzaSyBPVVxuVkmdE1GzSgIqm_9dx64tea7Wltk&libraries=geometry,places">
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
    // navigator.geolocation.getCurrentPosition((position) => {
        const currentLocation = {
            lat: parseFloat("{{$mess->latitude}}"),
            lng: parseFloat("{{$mess->longitude}}")
        };

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
        console.log(input)
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

    // });
}

initMap();


</script>

<script type="module">
    import { readImage } from "{{asset('assets/js/Helpers/helper.js')}}";
   
    document.querySelector('[data-image-input="1"]').addEventListener('change',(event)=> {
        readImage(event.target, '[data-preview="1"]');
    })
    document.querySelector('[data-image-input="2"]').addEventListener('change',(event)=> {
        readImage(event.target, '[data-preview="2"]');
    })
    
</script>

@endpush
