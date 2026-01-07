@extends('layouts.dashboard-main')
@push('css')
    <link rel="stylesheet" href="{{asset('assets/vendor/select2/select2.min.css')}}">
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
                        {{__('messages.edit')}} {{__('messages.banner')}}
                        </h4>
                    </div>
                </div>
                <div class="card-body">
                    <form action="{{route('admin.banner.update')}}" method="post" id="banner_form" enctype="multipart/form-data">
                        @csrf
                        <input type="hidden" name="banner_id" value="{{$banner->id}}">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="input-label" for="exampleFormControlInput1">{{__('messages.title')}}</label>
                                    <input type="text" name="title" value="{{$banner->title}}" class="form-control" placeholder="{{__('messages.Banner Name')}}" required>
                                </div>

                                <div class="form-group">
                                    {{-- @dd($banner) --}}
                                    <label class="input-label" for="zone_select">{{__('messages.banner')}} {{__('messages.zone')}}</label>
                                    @php($zones = App\Models\Zone::isActive()->get())
                                    <select name="zone_id" id="zone_select" class="form-control">
                                        <option value="" selected>Select Zone</option>
                                        @foreach ($zones as $zone)

                                        <option value="{{$zone->id}}" {{$zone->id == $banner->zone_id ? 'selected': null}}>{{Str::ucfirst($zone->name)}}</option>
                                        @endforeach
                                    </select>
                                </div>

                                <div class="form-group">
                                    <label class="input-label" for="exampleFormControlInput1">{{__('messages.banner')}} {{__('messages.type')}}</label>
                                    <select name="banner_type" id="banner_type" class="form-control " data-banner-type="true">
                                        <option value="">Select One</option>
                                        <option value="zone" {{$banner->type == "zone"  ? 'selected': null}}>{{__('Zone wise')}}</option>
                                        <option value="restaurant" {{$banner->type == "restaurant"  ? 'selected': null}}>{{__('Restaurant wise')}}</option>
                                        <option value="food" {{$banner->type == "food"  ? 'selected': null}}>{{__('Food Wise')}}</option>
                                        <option value="location"{{$banner->type == "location"  ? 'selected': null}}>{{__('Location Wise')}}</option>
                                    </select>
                                </div>

                                <div id="banner_relation"></div>

                            </div>

                            <div class="col-md-6">
                                <div class="h-100 d-flex flex-column justify-content-center">
                                    <div class="form-group mt-auto">
                                        <label class="d-block text-center">{{__('messages.campaign')}} {{__('messages.image')}} <small class="text-danger">* ( {{__('messages.ratio')}} 1000x300 )</small></label>
                                    </div>
                                    <div class="form-group mt-auto">
                                        <center>
                                            <label class="custom-file-label" for="customFileEg1">
                                                <img class="initial-2" id="viewer" src="{{Helpers::getUploadFile($banner->image, 'banner')}}"
                                                 width="200px" alt="campaign image"/>
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


</div>


@endsection

@push('javascript')
<script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyBPVVxuVkmdE1GzSgIqm_9dx64tea7Wltk&libraries=geometry,places">

<script src="{{asset('assets/vendor/select2/select2.min.js')}}"></script>
<script src="{{asset('assets/js/Helpers/mapHelper.js')}}"></script>

<script>
    function initMap() {
        navigator.geolocation.getCurrentPosition((position) => {
            const currentLocation = {
                lat: position.coords.latitude,
                lng: position.coords.longitude
            };

            @if($banner->type == 'location')
            currentLocation.lat = {{$banner->latitude}},
            currentLocation.lng = {{$banner->longitude}},
            @endif

            myMap.CreateMap(currentLocation, {
                selector: "#map-canvas", // corrected syntax for object properties
                marker: {
                    location: currentLocation,
                    img: "{{asset('assets/user/img/icons/marker-icon.png')}}",
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
                // getAddress(place.geometry.location)

                    myMap.map.setCenter(place.geometry.location);
                    myMap.marker.setPosition(place.geometry.location);
                    myMap.setElementsPosition(myMap.marker.getPosition().toJSON());
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


<script>
    let zone = {{$banner->zone_id}};
    const typeName = "banner";
    const typeID = {{$banner->id}}

    document.querySelector('#zone_select').addEventListener('change', (event) => {
        zone = event.target.value;
    })

    document.querySelector('#banner_type').addEventListener('change',  event => {
            const bannerType = event.target.value;
            getPartials(bannerType) ;

    });

    async function getPartials(bannerType) {
        try {
            if (!zone) {
                throw new Error('Please Select Zone');
            }
            const resp = await fetch(`{{ route('admin.banner.get-partials-saved') }}?name=${bannerType}&zone=${zone}&type=${typeName}&type_id=${typeID}`);
            if(!resp.ok){
                const err =  await resp.json();
                throw new Error(err.message)
            }
            const result = await resp.json();

            document.querySelector('#banner_relation').innerHTML = result.view;
            if(bannerType != 'location'){
                $(`#${bannerType}_choose`).select2();
            }

            console.log(bannerType);

            if(bannerType == 'location'){
                document.querySelector('#location_choose').classList.remove('d-none');
                initMap();
            }

        } catch (error) {
            toastr.info(error.message);
            toastr.error('An error occurred while fetching data. Please try again.');
        }
    }
    getPartials("{{$banner->type}}");
</script>

<script>
$('[data-zone]').select2({}).on('change', async (event) => {
    try {
        const resp = await fetch(`{{ route('admin.banner.get-partials') }}?name=${bannerType}&zone=${zone}&type=${typeName}&type_id${typeID}`);
        const result = await resp.json();
        let dataToAppend = [
            {
                id: '',
                text: 'Select One',
                element: $('<span>').addClass('text-primary').text('Select One')[0]
            }
        ];
        const mappedResults = result.map(item => {
            return {
                id: item.id,
                text: item.name,
                element: $('<span>').addClass('text-primary text-uppercase').text(item.name)[0]
            };
        });
        dataToAppend = dataToAppend.concat(mappedResults);
    } catch (error) {
        toastr.error(error.message);
        toastr.error('An error occurred while fetching data. Please try again.');
    }
});

function readURL(input,selector) {
    if (input.files && input.files[0]) {
        var reader = new FileReader();

        reader.onload = function (e) {
            $(selector).attr('src', e.target.result);
        }
        reader.readAsDataURL(input.files[0]);
    }
}

$("#customFileEg1").change(function () {
    readURL(this,'#viewer');
});
document.querySelectorAll('[data-edit-button]').forEach(item => {
    item.addEventListener('click', async () => {
        try {
            const bannerId = item.dataset.editButton;
            console.log(`Fetching data for banner ID: ${bannerId}`);
            const resp = await fetch(`{{ route('admin.banner.edit') }}?banner_id=${bannerId}`);

            if (!resp.ok) {
                const errorData = await resp.json();
                // console.error('Error response:', errorData);
                throw new Error(errorData.message || 'Error fetching banner data');
            }

            const result = await resp.json();
            const targetModal =document.querySelector('#edit-banner-modal');
            $('#edit-banner-modal').modal('show');
            targetModal.querySelector('.modal-body').innerHTML = result.view;

            //submit form initiailisation
            submitEditForm('#banner-edit-form');

            console.log('Fetched data:', result);
        } catch (error) {
            console.error('Error fetching data:', error);
            toastr.error(error.message || 'An unexpected error occurred');
        }
    });
});
/*============/sumit edit form function //===================*/
async function submitEditForm(selector) {
    const form = document.querySelector(selector);
    form.addEventListener('submit', async (e) => {
        e.preventDefault();
        const formdata = new FormData(form);
        const url = "{{ route('admin.banner.update') }}";
        try {
            const resp = await fetch(url, {
                method: "POST",
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                },
                body: formdata
            });

            if (!resp.ok) {
                const errorData = await resp.json();
                throw new Error(errorData.message || 'Something went wrong');
            }
            const result = await resp.json();

            if (result) {
                toastr.success(result.message);
                $('#edit-banner-modal').modal('hide');
                setTimeout(() => {
                    location.reload();
                }, 5000);
            }
        } catch (error) {
            console.error('Error:', error);
            toastr.error(error.message || 'An unexpected error occurred');
        }
    });
}

</script>



@endpush
