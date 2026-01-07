@extends('user-views.layouts.main')
@section('content')
    <div class="osahan-home-page">
        <div class="bg-primary p-3 d-none"style="min-height: 60px;">
            <div class="d-flex">
                {{-- <div class="text-white ">
            <div class="title">
                <a class="toggle" href="javascript:void(0)">
                    <span></span>
                </a>    
                
            </div>
        </div> --}}
                <button class="btn btn-primary align-self-start p-0 me-2" type="button" data-bs-toggle="offcanvas" data-bs-target="#offcanvasExample"
                    aria-controls="offcanvasExample">
                    <i class="feather-menu fs-2"></i>
                </button>
                <div class="d-flex align-items-center justify-content-between w-100">
                    @if (Session::has('userInfo'))
                        <div class="me-1">
                            <a class="text-white py-3" role="button" data-bs-toggle="modal" data-bs-target="#userMap">
                                <div class="text-break d-flex">
                                    <i class="feather-map-pin me-2 text-white fs-3 icofont-size mt-1"></i>
                                    <div class="location-bar">@php($userAddress = json_decode(Session::get('userInfo')->address))
                                    {{ $userAddress->street . ' ' . $userAddress->city . ' - ' . $userAddress->pincode }}</div>
                                </div>
                                <div>

                                </div>
                            </a>

                        </div>
                    @endif
                    {{-- <div class="me-0"></div> --}}
                    <div class="d-flex">
                        <div class=" me-1">
                            <a class="text-white  py-3" role="button" data-bs-toggle="modal" data-bs-target="#userMap">
                                <div><i
                                        class="feather-user text-primary me-2 bg-light rounded-pill p-2 fs-3 icofont-size"></i>
                                </div>
                            </a>
                        </div>
                        <div class=" me-0">
                            <a class="text-white py-3" role="button" data-bs-toggle="modal" data-bs-target="#userMap">
                                <div><i
                                        class="feather-bell text-primary me-2 bg-light rounded-pill p-2 fs-3 icofont-size"></i>
                                </div>
                            </a>
                        </div>
                    </div>
                </div>

            </div>
        </div>

        <div class="main ">
            <div class="container">
                <div class="offer-slider">
                    <div class="cat-item px-1 py-3">
                        <a class="d-block text-center shadow-sm" href="trending.html">
                            <img alt="#" src="{{ asset('assets/user/img/banner-1.jpg') }}" class="img-fluid rounded">
                        </a>
                    </div>
                    <div class="cat-item px-1 py-3">
                        <a class="d-block text-center shadow-sm" href="trending.html">
                            <img alt="#" src="{{ asset('assets/user/img/banner-2.jpg') }}" class="img-fluid rounded">
                        </a>
                    </div>
                    <div class="cat-item px-1 py-3">
                        <a class="d-block text-center shadow-sm" href="trending.html">
                            <img alt="#" src="{{ asset('assets/user/img/banner-3.png') }}" class="img-fluid rounded">
                        </a>
                    </div>
                    <div class="cat-item px-1 py-3">
                        <a class="d-block text-center shadow-sm" href="trending.html">
                            <img alt="#" src="{{ asset('assets/user/img/banner-2.jpg') }}" class="img-fluid rounded">
                        </a>
                    </div>
                    <div class="cat-item px-1 py-3">
                        <a class="d-block text-center shadow-sm" href="trending.html">
                            <img alt="#" src="{{ asset('assets/user/img/banner-1.jpg') }}" class="img-fluid rounded">
                        </a>
                    </div>
                    <div class="cat-item px-1 py-3">
                        <a class="d-block text-center shadow-sm" href="trending.html">
                            <img alt="#" src="{{ asset('assets/user/img/banner-3.png') }}" class="img-fluid rounded">
                        </a>
                    </div>
                </div>
                <div class="box bg-white mb-3 mt-3 shadow-sm rounded">
                    <div class="overflow-hidden border-top d-flex align-items-center p-2">
                        <div class="marquee-container">
                            <marquee scrollamount="12">
                                <div class="marquee-item">
                                    <div class="d-flex">
                                        <div class="text-warning">This is notice</div>
                                    </div>
                                </div>
                                <div class="marquee-item">
                                    <div class="d-flex">
                                        <div class="text-warning">This is notice</div>
                                    </div>
                                </div>
                                <div class="marquee-item">
                                    <div class="d-flex">
                                        <div class="text-warning">This is notice</div>
                                    </div>
                                </div>
                            </marquee>
                        </div>
                    </div>
                </div>
                <div class="pt-2 pb-3 title d-flex align-items-center">
                    <h5 class="m-0">Total Mess <span class="badge bg-primary rounded-pill">{{ $messes->count() }}</span>
                    </h5>
                    <a class="fw-bold ms-auto" href="javascript:void(0)" data-bs-toggle="modal" data-bs-target="#filters">Filters <i
                            class="feather-chevrons-right"></i></a>
                    <!-- <a href="javascript:void(0)" data-bs-toggle="modal" data-bs-target="#filters" class="ms-auto btn btn-primary">Filters</a> -->
                </div>
                <div class="most_sale">
                    <div class="row mb-3">
                        @foreach ($messes as $mess)
                            {{-- @dd($mess) --}}
                            <div class="col-md-6 mb-3">
                                <div
                                    class="d-flex align-items-center list-card bg-white h-100 rounded overflow-hidden position-relative shadow-sm">
                                    <div class="list-card-image">
                                        <div class="star mess-info position-absolute"><span
                                                class="badge text-bg-success"><i class="feather-user"></i> Mess ID</span>
                                        </div>
                                        <div class="favourite-heart text-danger position-absolute rounded-circle"><a
                                                href="javascript:void(0)"><i class="feather-heart"></i></a></div>
                                        <div class="member-plan position-absolute"><span class=""><img
                                                    alt="#" src="{{ asset('assets/user/img/veg.png') }}"
                                                    class="img-fluid item-img w-100"></span>
                                        </div>
                                        <a href="{{ route('user.mess.view', $mess->id) }}">
                                            <img alt="#" src="{{ asset("vendorMess/$mess->logo") }}"
                                                class="img-fluid item-img w-100">
                                        </a>
                                    </div>
                                    <div class="p-3 position-relative">
                                        <div class="list-card-body">
                                            <h6 class="mb-1">
                                                <a href="{{ route('user.mess.view', $mess->id) }}" class="text-black">
                                                    {{ Str::upper($mess->name) }}
                                                </a>
                                            </h6>
                                            <div class="list-card-badge mb-1">
                                                <span class="badge text-bg-danger me-1">Badge One</span>
                                                <small>Speciality/Description</small>
                                            </div>
                                            <div class="d-flex mb-1">
                                                <ul class="rating-stars list-unstyled mb-0">
                                                    <li>
                                                        <i class="feather-star star_active"></i>
                                                        <i class="feather-star star_active"></i>
                                                        <i class="feather-star star_active"></i>
                                                        <i class="feather-star star_active"></i>
                                                        <i class="feather-star"></i>
                                                    </li>
                                                </ul>
                                            </div>
                                            @php($distanceKM = number_format((float) $mess->distance, 2, '.', ''))
                                            <a href="https://www.google.com/maps?q={{ $mess->latitude . ',' . $mess->longitude }}"
                                                class="mb-0"><i class="feather-map-pin me-1"></i>Distance from Room :
                                                {{ $distanceKM > 1 ? $distanceKM . ' KM' : $distanceKM * 1000 . ' Meters' }}</a>
                                            <div class="list-card-badge mb-1">
                                                <span class="badge text-bg-danger badge-two me-1">Badge One</span>
                                                <small>Normal/Special</small>
                                            </div>
                                            <p class="text-gray mb-0">Lunch/Breakfast/Dinner</p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endforeach

                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- <div class="container-fluid fixed-bottom qr-position" style="box-shadow:none;">
    <div class="d-flex justify-content-center">
        <div class="text-center">
            <div class="shadow rounded bg-white p-2">
                <img alt="#" src="{{asset('assets/user/img/qr-code.png')}}" class="img-fluid" style="height:40px;">
                <p class="mb-0">QR Code</p>
            </div>
        </div>
    </div>
</div> --}}
    <div class="bg-white container-fluid fixed-bottom">
        <div class="row">
            <div class="col-4 text-center">
                <h1 class="text-warning counter mb-0 mt-1" style="visibility: visible;"><i class="fas fa-biking"></i>
                </h1>
                <p class="text-warning mb-1">Delivery</p>
            </div>
            <div class="col-4 text-center">
                <h1 class="text-warning counter mb-0 mt-1" style="visibility: visible;"><i class="fas fa-utensils"></i>
                </h1>
                <p class="text-warning mb-1">Mess/Tiffin</p>
            </div>
            <div class="col-4 text-center">
                <h1 class="text-warning counter mb-0 mt-1" style="visibility: visible;"><i class="fas fa-store"></i></h1>
                <p class="text-warning mb-1">Restaurant</p>
            </div>
        </div>
    </div>
    <div class="modal fade" id="filters" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog modal-dialog-scrollable">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Filters</h5>
                    <button type="button" class="btn-close shadow-none" data-bs-dismiss="modal"
                        aria-label="Close"></button>
                </div>
                <div class="modal-body p-0">
                    <div class="osahan-filter">
                        <div class="filter">
                            <div class="p-3 bg-light border-bottom">
                                <h6 class="m-0">FILTER</h6>
                            </div>
                            <div class="px-3 pt-3">
                                <div class="mt-2">
                                    <div class="d-flex justify-content-between">
                                        <label class="form-label mb-0 align-self-center" for="email">No. of Normal
                                            Diet</label>
                                        <div class="mess-custom-input">
                                            <button class="decrease-btn" id="decrease-btn">-</button>
                                            <input type="text" id="input-value" value="0">
                                            <button class="increase-btn" id="increase-btn">+</button>
                                        </div>
                                    </div>
                                </div>
                                <div class="mt-2">
                                    <div class="d-flex justify-content-between">
                                        <label class="form-label mb-0 align-self-center" for="email">No. of Special
                                            Diet</label>
                                        <div class="mess-custom-input">
                                            <button class="decrease-btn" id="decrease-btn">-</button>
                                            <input type="text" id="input-value" value="0">
                                            <button class="increase-btn" id="increase-btn">+</button>
                                        </div>
                                    </div>
                                </div>
                                <div class="mt-2">
                                    <div class="d-flex justify-content-between">
                                        <label class="form-label mb-0 align-self-center" for="email">No. of Close
                                            Days</label>
                                        <div class="mess-custom-input">
                                            <button class="decrease-btn" id="decrease-btn">-</button>
                                            <input type="text" id="input-value" value="0">
                                            <button class="increase-btn" id="increase-btn">+</button>
                                        </div>
                                    </div>
                                </div>
                                <div class="mt-2">
                                    <div class="d-flex justify-content-between">
                                        <label class="form-label mb-0 align-self-center" for="email">No. of Coupons
                                            Sells</label>
                                        <div class="mess-custom-input">
                                            <button class="decrease-btn" id="decrease-btn">-</button>
                                            <input type="text" id="input-value" value="0">
                                            <button class="increase-btn" id="increase-btn">+</button>
                                        </div>
                                    </div>
                                </div>
                                <div class="mt-3">
                                    <input type="text" class="form-control form-control-sm" placeholder="Diet Cost">
                                </div>
                                <div class="mt-3">
                                    <input type="text" class="form-control form-control-sm" placeholder="Price Range">
                                </div>
                                <div class="mt-3">
                                    <select class="form-select form-select-sm mb-3 shadow-none">
                                        <option selected="">Select Food Type</option>
                                        <option value="1">Veg</option>
                                        <option value="2">Non Veg</option>
                                    </select>
                                </div>
                                <div class="mt-3">
                                    <select class="form-select form-select-sm mb-3 shadow-none">
                                        <option selected="">Select Service</option>
                                        <option value="1">Delivery</option>
                                        <option value="2">Dine in</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer p-0 border-0">
                    <div class="col-6 m-0 p-0">
                        <a href="javascript:void(0)" class="btn border-top btn-lg w-100" data-bs-dismiss="modal">Close</a>
                    </div>
                    <div class="col-6 m-0 p-0">
                        <a href="most_popular.html" class="btn btn-primary btn-lg w-100">Apply</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('javascript')
    <script>
        //increment value
        document.addEventListener('DOMContentLoaded', function() {
            const decreaseBtns = document.querySelectorAll('.decrease-btn');
            const increaseBtns = document.querySelectorAll('.increase-btn');
            const inputValues = document.querySelectorAll('.input-value');

            decreaseBtns.forEach(function(btn) {
                btn.addEventListener('click', function(event) {
                    event.preventDefault();
                    let input = btn.nextElementSibling;
                    let value = parseInt(input.value);
                    if (value > 0) {
                        input.value = value - 1;
                    }
                });
            });

            increaseBtns.forEach(function(btn) {
                btn.addEventListener('click', function(event) {
                    event.preventDefault();
                    let input = btn.previousElementSibling;
                    let value = parseInt(input.value);
                    input.value = value + 1;
                });
            });
        });
    </script>
@endpush
