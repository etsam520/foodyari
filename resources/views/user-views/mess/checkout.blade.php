@php
    // $userLocations = [];
    if (auth('customer')->check()) {
        $userLocations = auth('customer')->user()->customerAddress()->orderByDesc('is_default')->orderByDesc('id')->get();
        $default_address = [
            'type' => $userLocations[0]?->type ?? 'Select Address',
            'address' => $userLocations[0]?->address ?? '',
        ];
    }else{
        $userLocation = Helpers::getGuestSession('guest_location');
        if($userLocation){
            $default_address['type'] = $userLocation['type'];
            $default_address['address'] = $userLocation['address'];
        }
    }

   $cart =  App\Http\Controllers\User\Mess\CartHelper::getCart();
   $mess = App\Models\VendorMess::find($cart[0]['package_data']['mess_id']) ;

@endphp
@extends('user-views.layouts.main')
@section('content')

<div class="osahan-home-page">
    <!-- Moblile header -->
    @include('user-views.layouts.m-header')
    <!-- Moblile header end -->
    <div class="main ">
        <div class="container position-relative">
            @include('user-views.layouts.slider')
        </div>

        <div class="container  position-relative">
            <div class="row">
                <div class="col-12 pt-3">
                    <div class="shadow-sm rounded offer-section overflow-hidden p-2">
                        <div class="row">
                            <div class="col-5 col-lg-2">
                                <div class="position-relative list-card">
                                    <img alt="#" src="{{ asset('vendorMess/' . $mess->logo) }}" class="restaurant-pic">
                                    <div class="star mess-info position-absolute"><span class="badge text-bg-success"><i class="feather-user"></i> Mess ID : #{{ Str::upper($mess->mess_no ?? 'NA') }}</span></div>
                                    <div class="member-plan position-absolute"><span class=""><img alt="#" src="{{ asset('assets/user/img/veg.png') }}" class="img-fluid item-img w-100"></span>
                                    </div>
                                </div>
                            </div>
                            <div class="col-7 col-lg-10 px-0 px-lg-2">
                                <div class="text-white">
                                    <h2 class="fw-bolder mess-title">{{ Str::upper($mess->name) }}</h2>

                                    <div class="position-relative">
                                        <div class="mb-1 text-wrap ">{{Str::ucfirst($mess->description??'')}}</div>
                                        <div class="bookmark-icon bookmark-icon-two pe-2 ps-3 text-nowrap position-absolute" style="top: 0px;right:0px;">
                                            @php($badges = json_decode($mess->badges))
                                            @if ($badges)
                                                <span class="text-warning" style="border-radius: 8px 0px 0px 8px !important;font-size: 14px;">{{ Str::ucfirst($badges->b1) }}</span>
                                            @endif
                                        </div>
                                    </div>
                                    @php($address = json_decode($mess->address))
                                    <a href="https://www.google.com/maps?q={{ $mess->latitude . ',' . $mess->longitude }}" class="text-white m-0"><i class="feather-map-pin me-1"></i>
                                        {{ Str::ucfirst($address->street ?? null) }}, {{ Str::ucfirst($address->city ?? null) }} - {{ Str::ucfirst($address->pincode ?? null) }}
                                    </a>
                                </div>
                                <div class="d-flex mb-1">

                                    <div class="bg-success text-white rounded px-2 me-1">
                                        <p class="mb-0 text-white py-1" style="font-size: 15px;"><i class="feather-star star_active me-2"></i>5.0</p>
                                    </div>
                                    <a href="javascript:void(0)" class="badge text-white one-diet-info" style="font-size: 15px;" data-bs-toggle="modal" data-bs-target="#one_diet_cost">
                                        One Diet Cost<i class="feather-eye ms-2"></i>
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="container position-relative">
            <div class="row">
                <div class="col-md-8 pt-3">
                    <div class="shadow-sm rounded bg-white mb-3 overflow-hidden">
                        <div class="d-flex item-aligns-center">
                            <h6 class="p-3 m-0 bg-light w-100">Package Details</h6>
                        </div>
                        <div class="row m-0">
                            <div class="col-md-12 px-0 border-top">
                                <div class="">
                                    @if ($errors->any())
                                    <div class="alert alert-danger">
                                        <ul>
                                            @foreach ($errors->all() as $error)
                                                <li>{{ $error }}</li>
                                            @endforeach
                                        </ul>
                                    </div>
                                    @endif
                                    <form action="{{route('user.mess.mess-pacakge-order')}}" method="POST" class="order-request-form">
                                        @csrf
                                        @foreach ($cart as $item)
                                        {{-- @dd($item) --}}
                                        @if (!empty($item['package_data']))
                                        @php($package = $item['package_data'])
                                        <div class="d-flex gap-2 p-3 border-bottom gold-members">
                                            <img alt="#" src="{{$package['type'] == 'veg'? asset('assets/user/img/veg.png') : asset('assets/user/img/non-veg.png')}}" class="img-fluid package-img">

                                            <div class="w-100">
                                                <div class="d-flex gap-2 mb-2">
                                                    <div>
                                                            <h6 class="mb-1">{{Str::upper($package['title'])}}
                                                                <a href=""><i class="fas fa-eye ms-2 text-warning"></i></a>
                                                                &nbsp; - &nbsp;
                                                                <span class="text-danger mb-0"> <strike>{{App\CentralLogics\Helpers::format_currency($package['price'])}}</strike></span>
                                                                <span class="text-success mb-0"> {{App\CentralLogics\Helpers::format_currency(App\CentralLogics\Helpers::food_discount($package['price'] , $package['discount']))}} x {{$item['quantity']}}</span>
                                                            </h6>

                                                    </div>
                                                    <div class="ms-auto">
                                                        <button type="button" data-cart-id="{{$item['uuid']}}" class="fa fa-trash text-danger"></button>
                                                    </div>
                                                </div>

                                                <div class="border-top pt-2">
                                                    @php ( $diets = json_decode($package['diets']))
                                                    {{-- @dd($diets) --}}
                                                    <div class="row">
                                                        <div class="col-lg-4">
                                                            <p class="text-fw-bold mb-0">No. of Normal Diet -
                                                                <span class="text-muted mb-0">{{(int)$diets->breakfast + (int) $diets->lunch + (int) $diets->dinner}} </span>
                                                            </p>
                                                        </div>
                                                        <div class="col-lg-4">
                                                            <p class="text-fw-bold mb-0">No. of Special Diet -
                                                                <span class="text-muted mb-0">{{(int)$diets->special}} </span>
                                                            </p>
                                                        </div>
                                                        <div class="col-lg-4">
                                                            <p class="text-fw-bold mb-0">Total Diet -
                                                                <span class="text-muted mb-0">{{(int)$diets->breakfast + (int) $diets->lunch + (int) $diets->dinner + (int) $diets->special}} </span>
                                                            </p>
                                                        </div>
                                                        <div class="col-lg-4">
                                                            <p class="text-fw-bold mb-0">Total Breakfast -
                                                                <span class="text-muted mb-0">{{(int)$diets->breakfast}} </span>
                                                            </p>
                                                        </div>
                                                        <div class="col-lg-4">
                                                            <p class="text-fw-bold mb-0">Total Lunch -
                                                                <span class="text-muted mb-0">{{(int) $diets->lunch}} </span>
                                                            </p>
                                                        </div>
                                                        <div class="col-lg-4">
                                                            <p class="text-fw-bold mb-0">Total Dinner -
                                                                <span class="text-muted mb-0">{{ (int) $diets->dinner}} </span>
                                                            </p>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        @endif
                                        @endforeach

                                        <h6 class="p-3 m-0 bg-light w-100 border-bottom">Meal Collection</h6>
                                        <div class="d-flex gap-2 border-bottom gold-members">
                                            <div class="w-100">
                                                <!-- <div> -->
                                                <div class="row px-4 py-3">
                                                    <div class="col-6">
                                                        <div class="form-check custom-checkbox d-flex align-items-end">
                                                            <input class="form-check-input" type="radio" value="delivery" name="meal_collection"
                                                                id="flexCheckDefault"
                                                                style="font-size: 20px;border:1px solid #ff810a;" checked>
                                                            <label class="form-check-label ms-3 fx-6" for="flexCheckDefault">
                                                                Delivery
                                                            </label>
                                                        </div>
                                                    </div>
                                                    <div class="col-6">
                                                        <div class="form-check custom-checkbox d-flex align-items-end">
                                                            <input class="form-check-input" type="radio" value="dine_in" name="meal_collection"
                                                                id="flexCheckDefault"
                                                                style="font-size: 20px;border:1px solid #ff810a;">
                                                            <label class="form-check-label ms-3 fx-6" for="flexCheckDefault">
                                                                Dine-in
                                                            </label>
                                                        </div>
                                                    </div>
                                                    <div class="col-12 p-0 mt-3">
                                                        <div class="input-group">
                                                            <span class="input-group-text" id="message"><i
                                                                    class="feather-message-square"></i></span>
                                                            <textarea placeholder="Any Special Requirement?" name="message"
                                                                aria-label="With textarea" class="form-control"></textarea>
                                                        </div>
                                                    </div>
                                                    <div class="col-12 p-0 mt-3">
                                                        <span class="btn btn-sm my-2 btn-primary" id="setLocation">Set Location</span>
                                                        <div class="input-group d-none">
                                                            <span class="input-group-text" id="message1"><i
                                                                    class="feather-message-square"></i></span>
                                                            <input placeholder="Delivery Address" name="address"
                                                                aria-label="With textarea" id="del-address" class="form-control p-3"/>
                                                        </div>
                                                    </div>
                                                    <div class="col-12">
                                                        <div class="input-group">
                                                            <input type="text" hidden name="latitude1" id="latitude1" placeholder="Latitude">
                                                            <input type="text" hidden name="longitude1" id="longitude1" placeholder="Longitude">
                                                        </div>
                                                    </div>
                                                    <div class="col-md-12 py-2 mx-auto d-none">
                                                        <div class="form-group d-flex">
                                                            {{-- <input type="text" id="search-input2" class="form-control rounded-0" placeholder="Enter Address or Place"> --}}
                                                            {{-- <span data-address="search2" class="btn btn-primary rounded-0">Search</span> --}}
                                                        </div>
                                                        <div id="map-canvas2" style=" width:100%;height: 50vh"></div>
                                                    </div>
                                                </div>
                                                <!-- </div> -->
                                            </div>
                                        </div>
                                        <h6 class="p-3 m-0 bg-light w-100 border-bottom">Billing Section </h6>
                                        <div class="bg-white p-3 clearfix border-bottom">
                                            <p class="mb-1 text-success"> Sub Total<span
                                                    class="float-end text-success">{{App\CentralLogics\Helpers::format_currency($billing->subtotal)}}</span></p>
                                            {{-- <p class="mb-1">Coupon <span class="text-info ms-1"><i
                                                        class="feather-info"></i></span><span
                                                    class="float-end text-dark">₹3140</span></p>
                                            <p class="mb-1 text-success">Custom Discount <span
                                                    class="float-end text-success">₹62.8</span></p>
                                            <p class="mb-1">GST & Mess Charges<span class="text-info ms-1"><i
                                                        class="feather-eye"></i></span><span class="float-end text-dark">₹10</span>
                                            </p>
                                            <p class="mb-1">Delivery Fee<span class="float-end text-dark">₹1884</span></p>
                                            <p class="mb-1  text-warning">Donation<span class="float-end text-warning">₹1884</span>
                                            </p> --}}
                                            <p class="mb-1  text-success">You Save<span class="float-end text-success">{{App\CentralLogics\Helpers::format_currency($billing->saved)}}</span>
                                            </p>
                                            <hr>
                                            <h6 class="fw-bold mb-0">TOTAL <span class="float-end">{{App\CentralLogics\Helpers::format_currency($billing->total())}}</span></h6>
                                        </div>
                                        <h6 class="p-3 m-0 bg-light w-100 border-bottom">Address</h6>
                                        <div class="osahan-card-body border-bottom p-3">

                                            <p class="mb-0" data-address="myaddress"><i class="feather-map-pin me-1"></i> {{$default_address['address']}}</p>
                                        </div>


                                        <div class="p-2 rounded-bottom-4 bg-white">
                                            <button class="btn btn-success btn-lg rounded-4 d-flex justify-content-around align-items-center" href="{{ route('user.restaurant.payment-options') }}">
                                                <div>Place Order<i class="feather-arrow-right ms-2"></i>
                                                </div>
                                            </button>
                                        </div>

                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                @include('user-views.mess.partials._total-mess')
            </div>
        </div>

    </div>
</div>

{{-- one diet cost modal --}}
<div class="modal fade" id="one_diet_cost" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-sm modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-body">
                <div class="osahan-filter">
                    <div class="filter">
                        <div class="border px-4 py-3">
                        <p class="h1 text-primary text-center"><i class="fas fa-clipboard-check"></i></p>
                        <h6 class="text-center">One Diet Cost</h6>
                        <hr>
                        @if ($mess->diet_cost)
                            @php($diet_cost = json_decode($mess->diet_cost))
                            <p class="mb-0 normal"><b>Normal : {{App\CentralLogics\Helpers::format_currency($diet_cost->normal)}}</b> </p>
                            <p class="mb-0 special"><b>Special :  {{App\CentralLogics\Helpers::format_currency($diet_cost->special)}} </b></p>
                        @else
                            <p class="mb-0 normal"><b>Normal : NA</b> </p>
                            <p class="mb-0 special"><b>Special : </b> NA</p>
                        @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>


{{-- one diet cost modal --}}
<div class="modal fade" id="one_diet_cost" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-sm modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-body">
                <div class="osahan-filter">
                    <div class="filter">
                        <div class="border px-4 py-3">
                        <p class="h1 text-primary text-center"><i class="fas fa-clipboard-check"></i></p>
                        <h6 class="text-center">One Diet Cost</h6>
                        <hr>
                        @if ($mess->diet_cost)
                            @php($diet_cost = json_decode($mess->diet_cost))
                            <p class="mb-0 normal"><b>Normal : {{App\CentralLogics\Helpers::format_currency($diet_cost->normal)}}</b> </p>
                            <p class="mb-0 special"><b>Special :  {{App\CentralLogics\Helpers::format_currency($diet_cost->special)}} </b></p>
                        @else
                            <p class="mb-0 normal"><b>Normal : NA</b> </p>
                            <p class="mb-0 special"><b>Special : </b> NA</p>
                        @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@endsection

@push('javascript')

<script src="{{asset('assets/js/Helpers/mapHelperClass.js')}}"></script>
<script>
    function initMap2() {
        document.getElementById('setLocation').addEventListener('click', (event) => {
            event.target.classList.add('d-none');
           event.target.parentElement.querySelector('.input-group').classList.remove('d-none');
            document.querySelector('#map-canvas2').parentElement.classList.remove('d-none')
            const map2 = new CreateMap();
            navigator.geolocation.getCurrentPosition((position) => {
                const currentLocation = {
                    lat: position.coords.latitude,
                    lng: position.coords.longitude
                };

                map2.createMap(currentLocation, {
                        selector: "#map-canvas2",
                        marker: {
                            location: currentLocation,
                            img: "http://localhost:8080/foodyari_etsam/public/assets/user/img/icons/map-icon.png",
                            draggable: true
                        },
                        mapClick: false,
                        mapDrag: false
                    });

                const searchInput = document.querySelector('#del-address');
                const autocomplete = new google.maps.places.Autocomplete(searchInput);

                autocomplete.addListener('place_changed', () => {
                    const place = autocomplete.getPlace();
                    const position = {latitude : place.geometry.location.lat(), longitude : place.geometry.location.lng()}
                    map2.map.setCenter(place.geometry.location);
                    map2.marker.setPosition(place.geometry.location);

                    map2.setElementsPosition(map2.marker.getPosition(), {latitude : '#latitude1', longitude : "#longitude1"});

                    if (!place.geometry) {
                        console.log("Autocomplete's returned place contains no geometry");
                        return;
                    }

                });


                // drag marker to get position
                google.maps.event.addListener(map2.marker, 'dragend', function() {
                    map2.setElementsPosition(map2.marker.getPosition(), {latitude : '#latitude1', longitude : "#longitude1"});
                });

                map2.setElementsPosition(map2.marker.getPosition(), {latitude : '#latitude1', longitude : "#longitude1"});


            });
        });
    }

        initMap2(); // Call the initMap function to initialize the map
</script>
<script>
    document.querySelector('#del-address').addEventListener('input', event => {
    document.querySelector('[data-address="myaddress"]').innerHTML = `<i class="feather-map-pin me-1"></i> ${event.target.value}`;
});

document.querySelector('#del-address').addEventListener('focusout', event => {
    document.querySelector('[data-address="myaddress"]').innerHTML = `<i class="feather-map-pin me-1"></i> ${event.target.value}`;
});
</script>

@endpush

