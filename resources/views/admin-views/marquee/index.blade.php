@extends('layouts.dashboard-main')
@push('css')
    <link rel="stylesheet" href="{{asset('assets/vendor/select2/select2.min.css')}}">
        <link rel="stylesheet" href="{{asset('assets/vendor/toggle/toggle-switch.css')}}">
        <style>
        <style>
        span.select2.select2-container{
            width: 100% !important;
        }
    </style>
@endpush

@section('content')
<div class="conatiner-fluid content-inner mt-n5 py-0">
    <div class="row">
        <div class="col-sm-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between">
                    <div class="header-title">
                       <h4 class="card-title">
                        <svg width="30px" viewBox="-2.1 -2.1 25.20 25.20" version="1.1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" fill="#000000"><g id="SVGRepo_bgCarrier" stroke-width="0"></g><g id="SVGRepo_tracerCarrier" stroke-linecap="round" stroke-linejoin="round"></g><g id="SVGRepo_iconCarrier"> <title>plus_circle [#1441]</title> <desc>Created with Sketch.</desc> <defs> </defs> <g id="Page-1" stroke-width="0.315" fill="none" fill-rule="evenodd"> <g id="Dribbble-Light-Preview" transform="__(-419.000000, -520.000000)" fill="currentColor"> <g id="icons" transform="__(56.000000, 160.000000)"> <path d="M374.55,369 L377.7,369 L377.7,371 L374.55,371 L374.55,374 L372.45,374 L372.45,371 L369.3,371 L369.3,369 L372.45,369 L372.45,366 L374.55,366 L374.55,369 Z M373.5,378 C368.86845,378 365.1,374.411 365.1,370 C365.1,365.589 368.86845,362 373.5,362 C378.13155,362 381.9,365.589 381.9,370 C381.9,374.411 378.13155,378 373.5,378 L373.5,378 Z M373.5,360 C367.70085,360 363,364.477 363,370 C363,375.523 367.70085,380 373.5,380 C379.29915,380 384,375.523 384,370 C384,364.477 379.29915,360 373.5,360 L373.5,360 Z" id="plus_circle-[#1441]"> </path> </g> </g> </g> </g></svg>
                        {{__('messages.add')}} {{__('messages.new')}} {{__('messages.marquee')}}
                        </h4>
                    </div>
                </div>
                <div class="card-body">
                    <form action="{{route('admin.marquee.store')}}" method="post" id="marquee_form" enctype="multipart/form-data">
                        @csrf
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="input-label" for="exampleFormControlInput1">{{__('messages.title')}}</label>
                                    <input type="text" name="title" class="form-control" placeholder="{{__('messages.marquee_name')}}" required>
                                </div>

                                <div class="form-group">
                                    <label class="input-label" for="zone_select">{{__('messages.marquee')}} {{__('messages.zone')}}</label>
                                    @php($zones = App\Models\Zone::isActive()->get())
                                    <select name="zone_id" id="zone_select" class="form-control">
                                        <option value="" selected>Select Zone</option>
                                        @foreach ($zones as $zone)
                                        <option value="{{$zone->id}}">{{Str::ucfirst($zone->name)}}</option>
                                        @endforeach
                                    </select>
                                </div>

                                <div class="form-group">
                                    <label class="input-label" for="exampleFormControlInput1">{{__('messages.marquee')}} {{__('messages.type')}}</label>
                                    <select name="marquee_type" id="marquee_type" class="form-control " data-banner-type="true">
                                        <option value="">Select One</option>
                                        <option value="zone" selected>{{__('Zone wise')}}</option>
                                        <option value="restaurant">{{__('Restaurant wise')}}</option>
                                        <option value="food">{{__('Food Wise')}}</option>
                                        <option value="location">{{__('Location Wise')}}</option>
                                    </select>
                                </div>

                                <div id="marquee_relation"></div>
                            </div>
                            <div class="col-md-6">
                                <div class="h-100 d-flex flex-column justify-content-center">
                                    <div class="form-group mt-auto">
                                        <label class="d-block text-center">{{__('messages.campaign')}} {{__('messages.image')}} <small class="text-danger">* ( {{__('messages.ratio')}} 1000x300 )</small></label>
                                    </div>
                                    <div class="form-group mt-auto">
                                        <center>
                                            <label class="custom-file-label" for="customFileEg1">
                                                <img class="initial-2" id="viewer"
                                                src="{{asset('assets/images/icons/banner.jpg')}}" width="200px" alt="campaign image"></img>
                                            </label>
                                        </center>
                                    </div>
                                    <div class="form-group mt-auto">
                                        <div class="custom-file">
                                            <input type="file" name="image" id="customFileEg1" class="form-control"
                                                accept=".jpg, .png, .jpeg, .gif, .bmp, .tif, .tiff|image/*" >
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="col-sm-12 d-none" id="location_choose">
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
                        <div class="btn--container justify-content-end">
                            <button id="reset_btn" type="reset" class="btn btn--reset">{{__('messages.reset')}}</button>
                            <button type="submit" class="btn btn--primary">{{__('messages.submit')}}</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-sm-12 col-lg-12 mb-3 mb-lg-2">
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title">{{__('messages.marquee')}} {{__('messages.list')}}<span class="badge badge-soft-dark ml-2" id="itemCount">{{$marquees->count()}}</span></h5>

                </div>
                <!-- Table  -->
                <div class="table-responsive">
                    <table id="datatable"
                    class="table" data-toggle="data-table">
                        <thead class="thead-light">
                            <tr>
                                <th>{{ __('messages.sl') }}</th>
                                <th>{{__('messages.title')}}</th>
                                <th>{{__('messages.type')}}</th>
                                <th>{{__('messages.status')}}</th>
                                <th class="text-center">{{__('messages.action')}}</th>
                            </tr>
                        </thead>

                        <tbody id="set-rows">
                        @foreach($marquees as $key=>$marquee)
                            <tr>
                                <td>{{$key+$marquees->firstItem()}}</td>
                                <td>
                                    <span class="media d-flex align-items-center" >

                                        <div class="media-body mx-2" type="button" @if(!empty($marquee->file)) onclick="location.href=`{{asset('/marquee')}}/{{$marquee['file']}}`" @endif >
                                            <h6 class="text-hover-primary mb-0">{{Str::upper(Str::limit($marquee['title'], 25, '...'))}}</h6>
                                        </div>
                                    </span>
                                <span class="d-block font-size-sm text-body">

                                </span>
                                </td>
                                <td>{{__('messages.'.$marquee['type'])}}</td>
                                <td>
                                    <label class="form-check toggle-switch-sm" for="statusCheckbox{{$marquee->id}}">
                                        <input type="checkbox" onclick="location.href='{{route('admin.marquee.status',[$marquee['id'],$marquee->status?0:1])}}'" class="form-check-input" id="statusCheckbox{{$marquee->id}}" {{$marquee->status?'checked':''}}>
                                        <span class="toggle-switch-label">
                                            <span class="toggle-switch-indicator"></span>
                                        </span>
                                    </label>
                                </td>
                                <td>
                                    <div class="btn-container justify-content-center">
                                        <a class="btn"  href="{{route('admin.marquee.edit',['marquee_id' =>$marquee['id']])}}"title="{{__('messages.edit')}} {{__('messages.marquee')}}">
                                           <i class="fa fa-edit text-primary"></i>
                                        </a>
                                        <a class="btn"  href="{{route('admin.marquee.delete',$marquee['id'])}}" onclick="form_alert(this,'{{__('Want to delete this marquee')}}')" title="{{__('messages.delete')}} {{__('messages.marquee')}}">
                                           <i class="fa fa-trash text-danger"></i>
                                        </a>
                                        
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="edit-marquee-modal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
        <div class="modal-header">
            <h5 class="modal-title" id="exampleModalLabel">Modal title</h5>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body">
        </div>
        <div class="modal-footer">
            <button type="button" class="btn btn-gray" data-bs-dismiss="modal">Close</button>
        </div>
        </div>
    </div>
    </div>
@endsection
@push('javascript')
<script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyBPVVxuVkmdE1GzSgIqm_9dx64tea7Wltk&libraries=geometry,places"></script>
<script src="{{ asset('assets/vendor/select2/select2.min.js') }}"></script>
<script src="{{ asset('assets/js/Helpers/mapHelper.js') }}"></script>

<script>
    function initMap() {
        navigator.geolocation.getCurrentPosition((position) => {
            const currentLocation = {
                lat: position.coords.latitude,
                lng: position.coords.longitude
            };

            myMap.CreateMap(currentLocation, {
                selector: "#map-canvas",
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

                myMap.map.setCenter(place.geometry.location);
                myMap.marker.setPosition(place.geometry.location);
                myMap.setElementsPosition(myMap.marker.getPosition().toJSON());
            });

            // Drag marker to get position
            google.maps.event.addListener(myMap.marker, 'dragend', function() {
                myMap.map.setCenter(myMap.marker.getPosition().toJSON());
                myMap.marker.setPosition(myMap.marker.getPosition().toJSON());
                myMap.setElementsPosition(myMap.marker.getPosition().toJSON());
            });
        });
    }
</script>

<script>
    let zone = null;

    document.querySelector('#zone_select').addEventListener('change', (event) => {
        zone = event.target.value;
    });

    document.querySelector('#marquee_type').addEventListener('change', async (event) => {
        try {
            const bannerType = event.target.value;
            if (!zone) {
                throw new Error('Please Select Zone');
            }
            const resp = await fetch(`{{ route('admin.banner.get-partials') }}?name=${bannerType}&zone=${zone}`);
            if (!resp.ok) {
                const err = await resp.json();
                throw new Error(err.message);
            }
            const result = await resp.json();
            document.querySelector('#marquee_relation').innerHTML = result.view;

            if (bannerType !== 'location') {
                $(`#${bannerType}_choose`).select2();
            }

            if (bannerType === 'location') {
                document.querySelector('#location_choose').classList.remove('d-none');
                initMap();
            }

        } catch (error) {
            toastr.info(error.message);
            toastr.error('An error occurred while fetching data. Please try again.');
        }
    });
</script>

<script>
    function readURL(input, selector) {
        if (input.files && input.files[0]) {
            var reader = new FileReader();
            reader.onload = function(e) {
                $(selector).attr('src', e.target.result);
            }
            reader.readAsDataURL(input.files[0]);
        }
    }

    $("#customFileEg1").change(function() {
        readURL(this, '#viewer');
    });
</script>

<script>
    function form_alert(item, message) {
        event.preventDefault();  // Prevent the default anchor action (e.g., navigating to href)

        Swal.fire({
            title: '{{ __('messages.Are you sure ?') }}',
            text: message,
            icon: 'warning',  // Correct SweetAlert syntax
            showCancelButton: true,
            cancelButtonColor: 'default',
            confirmButtonColor: '#FC6A57',
            cancelButtonText: '{{ __('messages.No') }}',
            confirmButtonText: '{{ __('messages.Yes') }}',
            reverseButtons: true
        }).then((result) => {
            if (result.isConfirmed) {  // Use 'isConfirmed' to check confirmation
                window.location.href = item.getAttribute('href');  // Navigate to the href of the anchor tag
            }
        });
    }
</script>
@endpush

