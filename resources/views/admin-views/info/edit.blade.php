@extends('layouts.dashboard-main')

@section('content')

<div class="conatiner-fluid content-inner mt-n5 py-0">
    <div>
        <div class="row">
            <div class="col-sm-12 col-lg-12">
                <div class="card">
                    <div class="card-header d-flex justify-content-between">
                        <div class="header-title">
                            <h4 class="card-title">{{ __('Update Info') }}</h4>
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
                        <form action="{{ route('admin.profile.update') }}" method="POST"
                            enctype="multipart/form-data" class="mt-3 text-center">
                            @csrf
                            <div class="row">
                                <div class="col-md-4 mt-3">
                                    <div class="form-group">
                                        <label class="input-label" for="name">First Name</label>
                                        <input id="name" type="text" name="f_name"
                                            class="form-control h--45px" placeholder="Enter Restaurant Name"
                                            value="{{$admin->f_name?? old('f_name') }}">
                                    </div>
                                </div>
                                <div class="col-md-4 mt-3">
                                    <div class="form-group">
                                        <label class="input-label" for="name">Last Name</label>
                                        <input id="name" type="text" name="l_name"
                                            class="form-control h--45px" placeholder="Enter Restaurant Name"
                                            value="{{$admin->l_name?? old('l_name') }}">
                                    </div>
                                </div>
                                <div class="col-md-4 mt-3">
                                    <div class="form-group">
                                        <label class="input-label" for="email">Email</label>
                                        <input id="email" type="text" name="email"
                                            class="form-control h--45px" placeholder="example@gmail.com"
                                            value="{{$admin->email?? old('email') }}">
                                    </div>
                                </div>
                                <div class="col-md-4 mt-3">
                                    <div class="form-group">
                                        <label class="input-label" for="email">Phone</label>
                                        <input id="email" type="text" name="phone"
                                            class="form-control h--45px" placeholder="9876543210"
                                            value="{{$admin->phone?? old('phone') }}">
                                    </div>
                                </div>
                                <div class="col-md-4 mt-3">
                                    <div class="form-group">
                                        <label class="input-label" for="password">Password</label>
                                        <input id="password" type="password" name="password" class="form-control h--45px" placeholder="Enter Password">
                                    </div>
                                </div>
                                <div class="col-md-4 mt-3">
                                    <div class="form-group">
                                        <label class="input-label" for="password">Confirm Password</label>
                                        <input id="password" type="password" name="password_confirmation" class="form-control h--45px" placeholder="Enter Password">
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-12 mt-3">
                                    <input type="submit" name="submit"
                                        class="btn btn-primary next action-button float-end" value="Update">
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
        autocomplete.addListener('place_changed', function() {
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
        google.maps.event.addListener(myMap.marker, 'dragend', function() {
            myMap.map.setCenter(myMap.marker.getPosition().toJSON());
            myMap.marker.setPosition(myMap.marker.getPosition().toJSON());
            myMap.setElementsPosition(myMap.marker.getPosition().toJSON());
        });

    });
}
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
