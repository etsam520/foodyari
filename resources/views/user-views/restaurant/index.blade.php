@php
// dd(1);
// dd(auth('customer')->user());
    $user = null;
    $userLocation = [];
    $redis = new \App\CentralLogics\Redis\RedisHelper();
    $locationPoint1 = [];
    if (auth('customer')->check()) {
        $user = auth('customer')->user();
        $redisUserLocation  = $redis->get("user:{$user->id}:user_location");

        if($redisUserLocation != NULL){
            $us = json_decode($redisUserLocation);
            $userLocation = $us;    
            $locationPoint1['lat'] = $us->lat??0;
            $locationPoint1['lon'] = $us->lng??0;
        }
    }else{

        $userLocation = Helpers::getGuestSession('guest_location');
        if($userLocation){
            $default_address['type'] = $userLocation['type'];
            $default_address['address'] = $userLocation['address'];
            $locationPoint1['lat'] = $userLocation['lat'];
            $locationPoint1['lon'] = $userLocation['lng'];
        }
    }
    $zone = Helpers::findZoneByLocation($locationPoint1['lat'], $locationPoint1['lon']);
    $cart = App\Http\Controllers\User\Restaurant\CartHelper::getCart();


@endphp



@extends('user-views.restaurant.layouts.main')

@push('sub-header')
    @include('user-views.restaurant.layouts.sub-header')
@endpush

@push('slider')
    @include('user-views.restaurant.layouts.slider')
@endpush


@push('css')
    <style>
        .filter-container {
            display: flex;
            align-items: center;
            gap: 10px;
            flex-wrap: wrap;
        }

        .filter-button {
            background: #f0f0f0;
            border: 1px solid #ccc;
            border-radius: 20px;
            padding: 8px 15px;
            cursor: pointer;
        }

        .clear-all {
            color: #007bff;
            text-decoration: none;
            cursor: pointer;
        }

        .selected-filters {
            display: flex;
            flex-wrap: wrap;
            gap: 8px;
        }

        .filter-tag {
            background: black;
            color: white;
            border-radius: 20px;
            padding: 5px 10px;
            display: flex;
            align-items: center;
            font-size: 14px;
        }

        .filter-tag .remove-filter {
            margin-left: 5px;
            cursor: pointer;
            font-weight: bold;
        }


        /* Dropdown button styling */
        .sort-button {
            background-color: #ff810a3d;
            border: 1px solid #ff810a;
            border-radius: 5px;
            padding: 10px 15px;
            font-size: 14px;
            cursor: pointer;
            display: inline-flex;
            align-items: center;
            justify-content: space-between;
        }

        .sort-button .arrow-down {
            margin-left: 10px;
        }

        /* Dropdown menu styling */
        .dropdown-menu {
            display: none;
            position: absolute;
            margin-top: 10px;
            background-color: #fff;
            border: 1px solid #dcdcdc;
            border-radius: 5px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            z-index: 1000;
            width: 300px;
            padding: 10px;
        }

        .dropdown-item {
            display: flex !important;
            align-items: top !important;
            justify-content: space-between;
            padding: 8px 10px;
            border-bottom: 1px solid #f0f0f0;
        }

        .dropdown-item:last-child {
            border-bottom: none;
        }

        .dropdown-item:active {
            background-color: white !important;
        }

        .dropdown-item span {
            font-size: 14px;
            color: #333;
        }

        /* Switch styling */
        .switch {
            position: relative;
            display: inline-block;
            width: 40px;
            height: 20px;
        }

        .switch input {
            opacity: 0;
            width: 0;
            height: 0;
        }

        .slider {
            position: absolute;
            cursor: pointer;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background-color: #ccc;
            transition: 0.4s;
            border-radius: 20px;
        }

        .slider:before {
            position: absolute;
            content: "";
            height: 14px;
            width: 14px;
            left: 3px;
            bottom: 3px;
            background-color: white;
            transition: 0.4s;
            border-radius: 50%;
        }

        input:checked+.slider {
            background-color: #4caf50;
        }

        input:checked+.slider:before {
            transform: translateX(20px);
        }

        /* Dropdown show class */
        .dropdown-menu.show {
            display: block;
            right: 165px;
            width: auto;
            border-top: 7px solid #ff810a;
        }

        @media only screen and (max-width: 767px) {
            .dropdown-menu {
                display: block;
                right: 25px;
                width: 90% !important;
                border-top: 7px solid #ff810a;
            }

            .dropdown-menu.show {
                display: block;
                right: 0px;
                width: 100%;
                border-top: 7px solid #ff810a;
            }
        }

        .skeleton-shimmer {
            /* This gradient is key to the shimmering effect */
            background: linear-gradient(
                90deg, 
                rgba(230, 230, 230, 1) 0%, 
                rgba(245, 245, 245, 1) 20%, 
                rgba(230, 230, 230, 1) 40%
            );
            background-size: 200% 100%;
            animation: shimmer 1.5s infinite linear;
        }

        /* The animation moves the background gradient from left to right */
        @keyframes shimmer {
            0% {
                background-position: -200% 0;
            }
            100% {
                background-position: 200% 0;
            }
        }
    </style>
@endpush



@section('containt')
    <!-- All Categories -->
    <div class="container pb-3" id="category-container">
        <div class="pt-2 pb-3 title d-flex align-items-center justify-content-center">
            <h4 class="m-0 fw-bolder">Categories</h4>
        </div>
        {{-- phone view category section --}}
        <div class="d-lg-none d-block">
            @php($categories = $zone?->id!= null ? App\Models\Category::categoriesByzonesHavingAtLeastOneProduct($zone->id)->isActive(true)->limit(3)->latest()->get() : null)
            {{-- @dd($categories) --}}
            <div class="row">
                @foreach ($categories??[] as $category)
                    <div class="category-sec col-3 px-1" data-filter="category" data-category-id="{{ $category->id }}">
                        <a href="javascript:void(0)">
                            <div class="category-card" onclick="highlightCategory(event)">
                                <div class="rounded d-block text-center shadow-sm">
                                    <div style="height: 100%; object-fit:contain;">
                                        <img alt="#"
                                            onerror="this.src='{{ asset('assets/images/icons/food-default-image.png') }}'"
                                            src="{{ asset('Category/' . $category->image) }}" class="img-fluid rounded m-0">
                                        {{-- <div class="overlay-two"></div> --}}
                                    </div>
                                </div>
                                <div class="py-2"
                                    style="bottom: 30px;
                                                color: black;
                                                width: 100%; justify-content: center;">
                                    <h4 style="font-size: 13px;
                                                        font-weight: bolder;
                                                        margin: 0px;"
                                        class="text-center">
                                        {{ Str::ucfirst($category->name) }}
                                    </h4>
                                </div>
                            </div>
                        </a>
                    </div>
                @endforeach
                @if ($categories?->count() == 3)
                    <div class="category-sec col-3 px-1" data-filter="category" data-category-id="{{ $category->id }}">
                        <a class="rounded d-block text-center shadow-sm" data-bs-toggle="modal" data-bs-target="#category"
                            href="javascript:void(0)">
                            <div style="height: 100%; object-fit:contain;position: relative;">
                                <img alt="#" src="{{ asset('Category/' . $category->image) }}"
                                    class="img-fluid rounded m-0">
                                <div class="overlay-two"></div>
                                <div class="position-absolute"
                                    style="bottom: 40%;
                                                color: white;
                                                width: 100%; justify-content: center;">
                                    <h4 style="font-size: 13px;
                                                        font-weight: bolder;"
                                        class="text-center mb-0">View All</h4>
                                </div>
                            </div>
                        </a>
                    </div>
                @endif
            </div>
        </div>
        {{-- desktop view category section --}}
        <div class="d-lg-block d-none">
            @php($categories =  $zone?->id != null ? App\Models\Category::categoriesByzonesHavingAtLeastOneProduct($zone->id)->isActive(true)->limit(9)->latest()->get() :null)
            <div class="row justify-content-center">
                @foreach ($categories??[] as $category)
                    <div class="category-sec col-1 px-1" data-filter="category" data-category-id="{{ $category->id }}">
                        <a href="javascript:void(0)">
                            <div class="category-card" onclick="highlightCategory(event)">
                                <div class="rounded d-block text-center shadow-sm">
                                    <div style="height: 100%; object-fit:contain;">
                                        <img alt="#"
                                            onerror="this.src='{{ asset('assets/images/icons/food-default-image.png') }}'"
                                            src="{{ asset('Category/' . $category->image) }}" class="img-fluid rounded m-0"
                                            style="border-bottom-left-radius: 0; border-bottom-right-radius: 0;">
                                        {{-- <div class="overlay-two"></div> --}}
                                    </div>
                                </div>
                                <div class="py-2"
                                    style="bottom: 30px;
                                                color: black;
                                                width: 100%; justify-content: center;">
                                    <h4 style="font-size: 13px;
                                                        font-weight: bolder;
                                                        margin: 0px;"
                                        class="text-center">
                                        {{ Str::ucfirst($category->name) }}
                                    </h4>
                                </div>
                            </div>
                        </a>
                    </div>
                @endforeach
                @if ($categories?->count() == 9)
                    <div class="category-sec col-1 px-1" data-filter="category" data-category-id="{{ $category->id }}">
                        <a class="rounded d-block text-center shadow-sm" data-bs-toggle="modal" data-bs-target="#category"
                            href="javascript:void(0)">
                            <div style="height: 100%; object-fit:contain;position: relative;">
                                <img alt="#" src="{{ asset('Category/' . $category->image) }}"
                                    class="img-fluid rounded m-0">
                                <div class="overlay-two"></div>
                                <div class="position-absolute"
                                    style="bottom: 40%;
                                                color: white;
                                                width: 100%; justify-content: center;">
                                    <h4 style="font-size: 13px;
                                                        font-weight: bolder;"
                                        class="text-center mb-0">View All</h4>
                                </div>
                            </div>
                        </a>
                    </div>
                @endif
            </div>
        </div>
    </div>

    <!-- new Notice Bar -->
    @include('user-views.restaurant.layouts.marquee')
    {{-- <div class="container mt-5">
    <div class="notice-slider">
        <div class="notice-item m-2 ">
            <a class="d-block text-center" href="javascript:void(0)">
                <img src="{{ asset('assets/images/notice.gif') }}" alt="" class="w-100 rounded-4">
            </a>
        </div>
        <div class="notice-item m-2 ">
            <a class="d-block text-center" href="javascript:void(0)">
                <img src="{{ asset('assets/images/notice.gif') }}" alt="" class="w-100 rounded-4">
            </a>
        </div>
        <div class="notice-item m-2 ">
            <a class="d-block text-center" href="javascript:void(0)">
                <img src="{{ asset('assets/images/notice.gif') }}" alt="" class="w-100 rounded-4">
            </a>
        </div>
        <div class="notice-item m-2 ">
            <a class="d-block text-center" href="javascript:void(0)">
                <img src="{{ asset('assets/images/notice.gif') }}" alt="" class="w-100 rounded-4">
            </a>
        </div>
        <div class="notice-item m-2 ">
            <a class="d-block text-center" href="javascript:void(0)">
                <img src="{{ asset('assets/images/notice.gif') }}" alt="" class="w-100 rounded-4">
            </a>
        </div>
    </div>
</div> --}}
{{-- @dd($zone); --}}
    {{-- START -- TOP ORDER SECTION (EXTRA WORK) --}}

    <div class="container pb-3" id="top-products">

    </div>
    {{-- END -- TOP ORDER SECTION (EXTRA WORK) --}}
    
    {{-- START -- SCHEDULED ORDERS WIDGET --}}
    @auth('customer')
        @if(isset($scheduledOrdersCount) && $scheduledOrdersCount > 0)
            <div class="container pb-3">
                <div class="bg-warning bg-gradient rounded-4 shadow-sm p-3">
                    <div class="d-flex align-items-center justify-content-between">
                        <div class="d-flex align-items-center">
                            <div class="bg-white rounded-circle p-2 me-3">
                                <i class="feather-clock text-warning fs-4"></i>
                            </div>
                            <div>
                                <h6 class="mb-0 fw-bold text-white">You have {{ $scheduledOrdersCount }} scheduled order{{ $scheduledOrdersCount > 1 ? 's' : '' }}</h6>
                                <p class="mb-0 text-white opacity-75 small">Manage your upcoming deliveries</p>
                            </div>
                        </div>
                        <div>
                            <a href="{{ route('user.restaurant.scheduled-orders') }}" 
                               class="btn btn-white btn-sm fw-bold">
                                View All
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        @endif
    @endauth
    {{-- END -- SCHEDULED ORDERS WIDGET --}}
    
    <!-- All Restaurant -->
    <div class="container position-relative mt-3 pb-3" data-products="all">

    </div>
    <div class="container">
        <div class="pt-3 pb-3 title d-flex align-items-center justify-content-center"
            style="border-bottom: 2px dashed #dee2e6; border-top: 2px dashed #dee2e6;">
            <h2 class="m-0 fw-bolder d-flex align-items-center">
                <span>Restaurants</span>


                <small type="button" class="badge badge-sm bg-primary fw-bolder ms-2" data-filter="all">0</small>
            </h2>

        </div>

        {{-- New Filtering option --}}
        <div class="pt-3 px-3 pb-3 title bg-white mt-3 rounded-4 shadow-sm">
            <div class="d-flex justify-content-between align-items-center">
                <div class="">
                    <a class="fw-bolder text-nowrap" href="javascript:void(0)" data-bs-toggle="modal" data-bs-target="#filters"
                        style="font-size: 19px;">Filters <i class="fa-solid fa-filter fs-6"></i></a>
                </div>
                <div class="d-flex align-items-center">
                    <div>
                        <input type="radio" class="btn-check" name="options-outlined" id="all-option" autocomplete="off"
                            checked>
                        <label class="btn btn-outline-warning" for="all-option"
                            style="border-radius: 5px 0px 0px 5px;">All</label>
                    </div>
                    <div>
                        <input type="radio" class="btn-check" name="options-outlined" id="nearest-option"
                            autocomplete="off">
                        <label class="btn btn-outline-warning" for="nearest-option"
                            style="border-radius: 0px 5px 5px 0px;">Nearest</label>
                    </div>
                </div>
                <div class="sort-dropdown align-self-center">
                    <button class="sort-button fs-6 py-1 px-1 px-md-2" onclick="toggleDropdown()">
                        Veg Mode <span class="arrow-down">▼</span>
                    </button>
                    <div class="dropdown-menu rounded-4 mt-4 me-3" id="dropdownMenu">
                        <div class="dropdown-item">
                            <span class="fs-5 text-wrap">Veg Restaurant with veg items</span>
                            <label class="switch mt-2">
                                <input type="checkbox" id="pureVegSwitch">
                                <span class="slider"></span>
                            </label>
                        </div>
                        <hr>
                        <div class="dropdown-item">
                            <span class="fs-5 text-wrap">Non-Veg Restaurant with veg items</span>
                            <label class="switch mt-2 ms-lg-3 ms-0">
                                <input type="checkbox" id="vegSwitch">
                                <span class="slider"></span>
                            </label>
                        </div>
                    </div>
                </div>
            </div>
            <div class="selected-filters pt-3 mt-3 border-top">
                <div class="">
                </div>
            </div>

        </div>



        <div class="most_sale">
            <div class="row" data-view="restaurants">
                {{-- preloader here for food in card --}}
                @for ($i = 0; $i < 4; $i++)
                    <div class="col-md-6 mt-3 rounded">
                        <div class="card card-body shadow border-0">
                            <svg class="skeleton-shimmer" viewBox="0 0 400 150" preserveAspectRatio="xMinYMin meet"width="100%" height="auto" xmlns="http://www.w3.org/2000/svg">
                                <rect x="10" y="10" rx="8" ry="8" width="130" height="130" fill="#E0E0E0" />
                                <rect x="150" y="15" rx="4" ry="4" width="220" height="20" fill="#E0E0E0" />
                                <rect x="150" y="45" rx="4" ry="4" width="40" height="15" fill="#F0F0F0" />
                                <rect x="150" y="70" rx="4" ry="4" width="60" height="20" fill="#E0E0E0" />
                                <rect x="220" y="74" rx="4" ry="4" width="80" height="12" fill="#F0F0F0" />
                                <rect x="150" y="105" rx="4" ry="4" width="150" height="15" fill="#E0E0E0" />
                            </svg>
                        </div>
                    </div>
                    
                @endfor
            </div>
        </div>
    </div>
    <div class="container {{ count($cart) == 0 ? 'res-nocart' : 'res-cart' }}">
        <div class="text-center">
            <h1 class="fw-bolder text-muted" style="font-size: 62px;">Just Order!</h1>
            <p class="">Crafted with <span class="text-danger"><i class="fas fa-heart"></i></span> in Madhepura,
                Bihar</p>
        </div>
    </div>
    <!-- Sticky Footer -->
    {{-- <div class="osahan-menu-fotter fixed-bottom bg-white px-3 py-2 text-center">
    <div class="d-flex justify-content-around">
        <div class="align-self-center">
            <a href="javascript:();" class="text-dark fw-bolder text-decoration-none">
                <div class="fw-bolder fs-4 m-0"><i class="fas fa-biking"></i></div>
                Delivery
            </a>
        </div>
        <div class="align-self-center">
            <a href="javascript:();" class="text-dark fw-bolder text-decoration-none">
                <p class="fw-bolder fs-4 m-0"><i class="fas fa-store"></i></p>
                Restaurant
            </a>
        </div>

        <div class="mt-n4">
            <a href="javascript:();" class="text-white fs-4 fw-bold text-decoration-none p-3 rounded-circle shadow "
                style="background:#ff810a;">
                <i class="fas fa-qrcode"></i>
            </a>
        </div>
        <div class="align-self-center">
            <a href="javascript:();" class="text-dark fw-bolder text-decoration-none">
                <p class="fw-bolder fs-4 m-0"><i class="fas fa-heart"></i></p>
                Favorite
            </a>
        </div>
        <div class="align-self-center">
            <a href="{{route('user.mess.index')}}" class="text-dark fw-bolder text-decoration-none">
                <p class="fw-bolder fs-4 m-0"><i class="fas fa-utensils"></i></p>
                Mess/Tiffin
            </a>
        </div>
    </div>
</div> --}}
@endsection


@push('modal')

    <!-- Menu popup -->
    <div class="modal fade" id="category" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog modal-dialog-end modal-dialog-scrollable">
            <div class="modal-content">
                <div class="modal-header">
                    <div class="text-start">
                        <h5 class="modal-title">Select Product Category </h5>
                        <a class="fw-bold small  ms-auto" href="javascript:void(0)" data-filter="all">Categoty All <i
                                class="feather-chevrons-right"></i></a>
                    </div>
                    <button type="button" class="btn-close shadow-none" data-bs-dismiss="modal"
                        aria-label="Close"></button>
                </div>
                <div class="modal-body p-0">
                    <div class="osahan-filter">
                        <div class="filter">
                            <div class="p-4">
                                <div class="row " style="max-height: 30vh">
                                    @if ($categories?->count() > 5)
                                        @php($categories = App\Models\Category::isActive(true)->latest()->get())

                                        @foreach ($categories as $category)
                                            <div class="col-3 px-1 pb-2" data-filter="category"
                                                data-category-id="{{ $category->id }}">
                                                <a href="javascript:void(0)">
                                                    <div class="rounded d-block text-center shadow-sm">
                                                        <div style="height: 100%; object-fit:contain;">
                                                            <img alt="#"
                                                                src="{{ asset('Category/' . $category->image) }}"
                                                                onerror="this.src='{{ asset('assets/images/icons/food-default-image.png') }}'"
                                                                class="img-fluid rounded m-0">
                                                            {{-- <div class="overlay-two"></div> --}}
                                                        </div>
                                                    </div>
                                                    <div class="mt-3"
                                                        style="bottom: 30px;
                                                                        color: black;
                                                                        width: 100%; justify-content: center;">
                                                        <h4 style="font-size: 13px;
                                                                                font-weight: bolder;
                                                                                margin: 0px;"
                                                            class="text-center">
                                                            {{ Str::ucfirst($category->name) }}
                                                        </h4>
                                                    </div>
                                                </a>
                                            </div>
                                        @endforeach
                                    @endif
                                </div>
                            </div>

                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- Custom Item popup -->

    <div class="offcanvas offcanvas-end" tabindex="-1" id="custom_item" aria-labelledby="customizeCartLabel">
        <div class="offcanvas-header">
            <h5 class="offcanvas-title" id="customizeCartLabel">Customize Cart</h5>
            <button type="button" class="btn-close" data-bs-dismiss="offcanvas" aria-label="Close"></button>
        </div>
        <div class="offcanvas-body" data-products="single">
            <p>Some placeholder content for the customize cart offcanvas.</p>
        </div>
    </div>

    <!-- Filter popup -->
    <div class="modal fade" id="filters" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog modal-dialog-end modal-dialog-scrollable">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Filters</h5>

                    <button type="button" class="btn-close shadow-none" data-bs-dismiss="modal"
                        aria-label="Close"></button>
                </div>
                <div class="modal-body p-0">
                    <div class="osahan-filter">
                        <div class="filter" id="restaurantCustomFilteration">
                            <div class="d-flex align-items-start">
                                <div class="nav flex-column nav-pills" id="v-pills-tab" role="tablist"
                                    aria-orientation="vertical">
                                    <button class="nav-link text-dark active" id="v-pills-home-tab"
                                        data-filter-key="sortBy" data-bs-toggle="pill" data-bs-target="#v-pills-home"
                                        type="button" role="tab" aria-controls="v-pills-home"
                                        aria-selected="true">Sort</button>
                                    <button class="nav-link text-dark" id="v-pills-profile-tab" data-filter-key="type"
                                        data-bs-toggle="pill" data-bs-target="#v-pills-profile" type="button"
                                        role="tab" aria-controls="v-pills-profile"
                                        aria-selected="false">Veg/Non-Veg</button>
                                    <button class="nav-link text-dark" id="v-pills-disabled-tab" data-filter-key="rating"
                                        data-bs-toggle="pill" data-bs-target="#v-pills-disabled" type="button"
                                        role="tab" aria-controls="v-pills-disabled"
                                        aria-selected="false">Ratings</button>
                                    <button class="nav-link text-dark" id="v-pills-messages-tab"
                                        data-filter-key="deliveryTime" data-bs-toggle="pill"
                                        data-bs-target="#v-pills-messages" type="button" role="tab"
                                        aria-controls="v-pills-messages" aria-selected="false">Delivery Time</button>
                                    <button class="nav-link text-dark" id="v-pills-settings-tab"
                                        data-filter-key="costForTwo" data-bs-toggle="pill"
                                        data-bs-target="#v-pills-settings" type="button" role="tab"
                                        aria-controls="v-pills-settings" aria-selected="false">Cost
                                        For Two</button>
                                </div>
                                <div class="tab-content p-3 border-start" id="v-pills-tabContent">
                                    <div class="tab-pane fade show active" data-filter-contains="sortBy"
                                        id="v-pills-home" role="tabpanel" aria-labelledby="v-pills-home-tab"
                                        tabindex="0">
                                        <small class="text-black-50">Sort By</small>
                                        <div class="mt-2 mb-2">
                                            <input class="form-check-input" name="sortBy" type="radio"
                                                data-filter-item="relevance" id="flexCheckDefault"
                                                style="font-size: 20px;border:1px solid #ff810a;">
                                            <label class="form-check-label ms-3 fx-6 mt-1" for="flexCheckDefault">
                                                Relevance (Default)
                                            </label>
                                        </div>
                                        <div class="mb-2">
                                            <input class="form-check-input" name="sortBy" type="radio"
                                                data-filter-item="deliveryTime" id="flexCheckDefault"
                                                style="font-size: 20px;border:1px solid #ff810a;">
                                            <label class="form-check-label ms-3 fx-6 mt-1" for="flexCheckDefault">
                                                Delivery Time
                                            </label>
                                        </div>
                                        <div class="mb-2">
                                            <input class="form-check-input" name="sortBy" type="radio"
                                                data-filter-item="rating" id="flexCheckDefault"
                                                style="font-size: 20px;border:1px solid #ff810a;">
                                            <label class="form-check-label ms-3 fx-6 mt-1" for="flexCheckDefault">
                                                Rating
                                            </label>
                                        </div>
                                        <div class="mb-2">
                                            <input class="form-check-input" name="sortBy" type="radio"
                                                data-filter-item="lowToHigh" id="flexCheckDefault"
                                                style="font-size: 20px;border:1px solid #ff810a;">
                                            <label class="form-check-label ms-3 fx-6 mt-1" for="flexCheckDefault">
                                                Cost : Low to High
                                            </label>
                                        </div>
                                        <div class="mb-2">
                                            <input class="form-check-input" name="sortBy" type="radio"
                                                data-filter-item="highTOLow" id="flexCheckDefault"
                                                style="font-size: 20px;border:1px solid #ff810a;">
                                            <label class="form-check-label ms-3 fx-6 mt-1" for="flexCheckDefault">
                                                Cost : High to Low
                                            </label>
                                        </div>
                                    </div>
                                    <div class="tab-pane fade" data-filter-contains="type" id="v-pills-profile"
                                        role="tabpanel" aria-labelledby="v-pills-profile-tab" tabindex="0">
                                        <small class="text-black-50">Filter By</small>
                                        <div class="mt-2 mb-2">
                                            <input class="form-check-input" name="type" type="radio"
                                                data-filter-item="veg" id="flexCheckDefault"
                                                style="font-size: 20px;border:1px solid #ff810a;">
                                            <label class="form-check-label ms-3 fx-6 mt-1" for="flexCheckDefault">
                                                Veg
                                            </label>
                                        </div>
                                        <div class="mb-2">
                                            <input class="form-check-input" name="type" type="radio"
                                                data-filter-item="nonVeg" id="flexCheckDefault"
                                                style="font-size: 20px;border:1px solid #ff810a;">
                                            <label class="form-check-label ms-3 fx-6 mt-1" for="flexCheckDefault">
                                                Non Veg
                                            </label>
                                        </div>
                                    </div>
                                    <div class="tab-pane fade" id="v-pills-disabled" data-filter-contains="rating"
                                        role="tabpanel" aria-labelledby="v-pills-disabled-tab" tabindex="0">
                                        <small class="text-black-50">Filter By</small>
                                        <div class="mt-2 mb-2">
                                            <input class="form-check-input" name="rating" type="radio"
                                                data-filter-item="threeDotFivePlus" id="flexCheckDefault"
                                                style="font-size: 20px;border:1px solid #ff810a;">
                                            <label class="form-check-label ms-3 fx-6 mt-1" for="flexCheckDefault">
                                                Ratings 3.5+
                                            </label>
                                        </div>
                                        <div class="mb-2">
                                            <input class="form-check-input" name="rating" type="radio"
                                                data-filter-item="foutDotZeroPlus" id="flexCheckDefault"
                                                style="font-size: 20px;border:1px solid #ff810a;">
                                            <label class="form-check-label ms-3 fx-6 mt-1" for="flexCheckDefault">
                                                Ratings 4.0+
                                            </label>
                                        </div>
                                    </div>
                                    <div class="tab-pane fade" data-filter-contains="deliveryTime" id="v-pills-messages"
                                        role="tabpanel" aria-labelledby="v-pills-messages-tab" tabindex="0">
                                        <small class="text-black-50">Filter By</small>
                                        <div class="mt-2 mb-2">
                                            <input class="form-check-input" name="deliveryTime" type="radio"
                                                data-filter-item="beforeThirtyMins" id="flexCheckDefault"
                                                style="font-size: 20px;border:1px solid #ff810a;">
                                            <label class="form-check-label ms-3 fx-6 mt-1" for="flexCheckDefault">
                                                Less than 30 mins
                                            </label>
                                        </div>
                                        <div class="mb-2">
                                            <input class="form-check-input" name="deliveryTime" type="radio"
                                                data-filter-item="beforFourtyfiveMins" id="flexCheckDefault"
                                                style="font-size: 20px;border:1px solid #ff810a;">
                                            <label class="form-check-label ms-3 fx-6 mt-1" for="flexCheckDefault">
                                                Less than 45 mins
                                            </label>
                                        </div>
                                    </div>
                                    <div class="tab-pane fade" data-filter-contains="costForTwo" id="v-pills-settings"
                                        role="tabpanel" aria-labelledby="v-pills-settings-tab" tabindex="0">
                                        <small class="text-black-50">Filter By</small>
                                        <div class="mt-2 mb-2">
                                            <input class="form-check-input" name="costForTwo" type="radio"
                                                data-filter-item="0To300" id="flexCheckDefault"
                                                style="font-size: 20px;border:1px solid #ff810a;">
                                            <label class="form-check-label ms-3 fx-6 mt-1" for="flexCheckDefault">
                                                Less than rs 300
                                            </label>
                                        </div>
                                        <div class="mb-2">
                                            <input class="form-check-input" name="costForTwo" type="radio"
                                                data-filter-item="300To400" id="flexCheckDefault"
                                                style="font-size: 20px;border:1px solid #ff810a;">
                                            <label class="form-check-label ms-3 fx-6 mt-1" for="flexCheckDefault">
                                                Rs300 - Rs400
                                            </label>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer p-0 border-0">
                    <div class="col-6 m-0 p-0">
                        <a href="javascript:void(0)" id="clearFilters" class="btn border-top btn-lg w-100"
                            data-bs-dismiss="modal">Clear Filters</a>
                    </div>
                    <div class="col-6 m-0 p-0">
                        <a href="javascript:void(0);" id="applyFilters" class="btn btn-primary btn-lg w-100">Apply</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
    @if ($user != null)
        <!-- Add TO Collection modal Item -->
        <div class="modal fade" id="collectionModal" tabindex="-1" aria-labelledby="collectionModalLabel"
            aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="collectionModalLabel">My Collections</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <!-- List of collections -->
                        <h6>Your Collections:</h6>
                        <ul id="collectionList" class="list-group mb-3">
                            <!-- Dynamic Collection List -->
                            @php($collections = App\Models\Collection::where('user_id', $user->id)->get())
                            @foreach ($collections as $collection)
                                <li class="list-group-item" data-id="{{ $collection->id }}">
                                    {{ $collection->name }}
                                </li>
                            @endforeach
                        </ul>

                        <!-- Button to trigger the "Add Collection" form -->
                        <button class="btn btn-success" id="addCollectionButton">Add Collection</button>

                        <!-- Add Collection Form (Initially Hidden) -->
                        <form id="addCollectionForm" style="display: none; margin-top: 20px;">
                            <div class="mb-3">
                                <label for="name" class="form-label">Collection Name</label>
                                <input type="text" class="form-control" id="collectionName" name="name" required>
                            </div>
                            <button type="button" id="saveCollectionButton" class="btn btn-primary">Save
                                Collection</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    @endif

    <!-- Sticky Foot cart -->
    <div class="order-bar">

        <div class="container cart-bar fixed-bottom rounded {{ count($cart) == 0 ? 'd-none' : null }}  mb-3"
            id="view-cart" onclick="location.href='{{ route('user.restaurant.check-out') }}'"
            style="box-shadow: none !important;">
            <div class="d-flex justify-content-between py-3 rounded-4 px-3" style="background-color:#ff810a;">
                <a href="javascript:void(0)" class="align-self-center">
                    <h6 class="text-white fw-bolder mb-0 align-self-center">{{ count($cart) }} Item Added</h6>
                </a>
                <div class="d-flex">
                    <h5 class="text-white fw-bolder mb-0 align-self-center">View Cart<i
                            class="fas fa-arrow-right ms-2"></i>
                    </h5>
                </div>
            </div>
        </div>
        <div class="container fixed-bottom rounded {{ count($cart) == 0 ? 'liveorder-nocart' : 'liveorder-cart' }}"
            id="liveOrder" style="display: none;box-shadow: none !important;">
        </div>
    </div>

    @include('user-views.restaurant.layouts.bottom-navigation')
@endpush



@push('javascript')
    <script>
        function highlightCategory(event) {
            // Prevent default behavior
            if (event) {
                event.preventDefault();
            }
            let toggleValue = event?.currentTarget.classList.contains('highlighted') == true ? false : true;


            // Ensure the category cards exist before proceeding
            const categoryCards = document.querySelectorAll('.category-card');
            if (categoryCards.length > 0) {
                // Remove highlight from all categories
                categoryCards.forEach(category => {
                    category.classList.remove('highlighted');
                    category.classList.remove('shadow');
                    category.style.border = ''; // Reset border
                    category.style.borderRadius = '';
                    category.querySelector('img').classList.add('rounded');
                    category.querySelector('img').style.borderTopLeftRadius = '';
                    category.querySelector('img').style.borderTopRightRadius = '';
                    category.querySelector('img').style.borderBottomLeftRadius = '';
                    category.querySelector('img').style.borderBottomRightRadius = '';
                    category.querySelector('img').style.padding = '';
                });
            }

            // Add highlight to the clicked category if it exists
            const selectedCategory = event?.currentTarget;
            if (selectedCategory && toggleValue) {
                selectedCategory.classList.add('highlighted');
                selectedCategory.classList.add('shadow');
                selectedCategory.querySelector('img').classList.remove('rounded');
                selectedCategory.querySelector('img').style.borderTopLeftRadius = '8px';
                selectedCategory.querySelector('img').style.borderTopRightRadius = '8px';
                selectedCategory.querySelector('img').style.borderBottomLeftRadius = '0';
                selectedCategory.querySelector('img').style.borderBottomRightRadius = '0';
                selectedCategory.querySelector('img').style.padding = '3px';
                selectedCategory.style.border = '2px solid #ff810a'; // Add 2px solid green border
                selectedCategory.style.borderRadius = '8px';
            }
        }
    </script>
    {{--
    <script>

        document.addEventListener("DOMContentLoaded", function () {
            let cartCount = {{ count($cart)
        }};
        let liveOrder = document.getElementById("liveOrder");

        if (cartCount > 0) {
            liveOrder.style.marginBottom = "145px"; // Adjust for larger screens
        }

        if (window.innerWidth <= 576) {
            liveOrder.style.marginBottom = cartCount > 0 ? "75px" : "92px"; // Adjust for small screens
        }
                });
    </script> --}}
    <script src="{{ asset('assets/user/vendor/flip-master/dist/jquery.flip.min.js') }}"></script>
    <script>
        const CUSTOM_FILTER = 'customFilter';
        const RESTAURANT_VEG_MODE = 'restaurantVegMode';
        const RESTAURANT_PURE_VEG_MODE = 'restaurantPureVegMode';
    </script>

    <script>
        // Get references to the dropdown menu and switches
        const dropdownMenu = document.getElementById("dropdownMenu");

        // Toggle dropdown visibility
        function toggleDropdown() {
            dropdownMenu.classList.toggle("show");
        }

        // Add event listeners for switches (optional: if you want additional functionality)
        const pureVegSwitch = document.getElementById("pureVegSwitch");
        if (localStorage.hasOwnProperty(RESTAURANT_PURE_VEG_MODE)) {
            pureVegSwitch.checked = localStorage.getItem(RESTAURANT_PURE_VEG_MODE) == "true";
        }
        pureVegSwitch.addEventListener("change", function() {
            if (this.checked) {
                // console.log("Veg mode enabled");
                localStorage.setItem(RESTAURANT_PURE_VEG_MODE, true);
                getRestaurants();
            } else {
                localStorage.setItem(RESTAURANT_PURE_VEG_MODE, false);
                getRestaurants();
                // console.log("Veg mode disabled");
            }
        });

        const vegSwitch = document.getElementById("vegSwitch");
        if (localStorage.hasOwnProperty(RESTAURANT_VEG_MODE)) {
            vegSwitch.checked = localStorage.getItem(RESTAURANT_VEG_MODE) == "true";
        }
        vegSwitch.addEventListener("change", function() {
            if (this.checked) {
                localStorage.setItem(RESTAURANT_VEG_MODE, true);
                getRestaurants();
                // console.log("Non-veg mode enabled");
            } else {
                localStorage.setItem(RESTAURANT_VEG_MODE, false);
                getRestaurants();
                // console.log("Non-veg mode disabled");
            }
        });
    </script>

    <script type="text/javascript">
        // Register Service worker for Add to Home Screen option to work
        //     if ('serviceWorker' in navigator) {
        //         navigator.serviceWorker.register('/service-worker.js') .then(function(registration){
        //       console.log('ServiveWorker Field',registration);

        //     //   toastr.error('df;djf');
        //     }, function(err){
        //       console.log('ServiveWorker Field',err);
        //     })
        //    }

        let deferredPrompt;
        const addBtn = document.querySelector('.add-button');
        if (addBtn != null) {
            addBtn.style.display = 'none';
        }

        window.addEventListener('beforeinstallprompt', (e) => {
            // Prevent Chrome 67 and earlier from automatically showing the prompt
            e.preventDefault();
            // Stash the event so it can be triggered later.
            deferredPrompt = e;
            // Update UI to notify the user they can add to home screen
            if (addBtn != null) {
                addBtn.style.display = 'block';

                addBtn.addEventListener('click', (e) => {
                    // hide our user interface that shows our A2HS button
                    addBtn.style.display = 'none';
                    // Show the prompt
                    deferredPrompt.prompt();
                    // Wait for the user to respond to the prompt
                    deferredPrompt.userChoice.then((choiceResult) => {
                        if (choiceResult.outcome === 'accepted') {
                            console.log('User accepted the A2HS prompt');
                        } else {
                            console.log('User dismissed the A2HS prompt');
                        }
                        deferredPrompt = null;
                    });
                });
            }
        });
    </script>

    <script>
        async function getRestaurants(filter = "all", options = {}) {
            let url = `{{ route('user.restaurant.get-restaurants') }}?filter=${filter}`;
            if (options.category_id) {
                url += `&category_id=${options.category_id}`;
            }
            if (options.nearest) {
                url += `&nearest=1`;
            }
            url += localStorage.hasOwnProperty(CUSTOM_FILTER) ? '&customFilter=' + localStorage.getItem(CUSTOM_FILTER) :
                '';
            url += localStorage.hasOwnProperty(RESTAURANT_PURE_VEG_MODE) && localStorage.getItem(
                RESTAURANT_PURE_VEG_MODE) == "true" ? '&pureVegMode=' + true : '';
            url += localStorage.hasOwnProperty(RESTAURANT_VEG_MODE) && localStorage.getItem(RESTAURANT_VEG_MODE) ==
                "true" ? '&vegMode=' + true : '';
            const categoryContainer = document.getElementById('category-container');
            const sliderContainer = document.getElementById('slider-container');
            try {
                const resp = await fetch(url);
                if (!resp.ok) {
                    const error = await resp.json();
                    throw new Error(error.message);
                }
                const result = await resp.json();
                document.querySelector('[data-view="restaurants"]').innerHTML = result.view;
                document.querySelector('[data-filter=all]').textContent = result.count;
                // foods
                if (result.foods != null) {
                    document.querySelector('[data-products=all]').innerHTML = result.foods;
                    changer();
                    viewSingleFood();
                    if (!categoryContainer.classList.contains('d-none')) {
                        categoryContainer.classList.add('d-none');
                    }
                    if (sliderContainer != null && !sliderContainer.classList.contains('d-none')) {
                        sliderContainer.classList.add('d-none');
                    }

                } else {
                    document.querySelector('[data-products=all]').innerHTML = null;
                    if (categoryContainer.classList.contains('d-none')) {
                        categoryContainer.classList.remove('d-none');
                    }
                    if (sliderContainer != null && sliderContainer.classList.contains('d-none')) {
                        sliderContainer.classList.remove('d-none');
                    }
                }
            } catch (error) {
                console.error('Error:', error);
                toastr.warning(error.message || 'An error occurred while fetching restaurants.');
            }
        }

        document.querySelectorAll('[data-filter]').forEach(element => {
            element.addEventListener('click', () => {
                let options = {}
 
                if (element.dataset.categoryId) {
                    element.getAttribute('data-toggler') == 'true' ? element.dataset.toggler = false : element.dataset.toggler = true;
                    if(element.dataset.toggler == 'true'){
                        options.category_id = element.dataset.categoryId;
                    }else{
                        options.category_id = null;
                    }
                }
                getRestaurants(element.dataset.filter, options);
            });
        });

        getRestaurants();

        const debounce = (func, delay) => {
            let timer;
            return function(...args) {
                clearTimeout(timer);
                timer = setTimeout(() => func.apply(this, args), delay);
            };
        };

        document.querySelectorAll(".food-search-container input").forEach(searchInput => {
            const debouncedGetRestaurants = debounce((value) => {
                if (value.length > 2) {
                    getRestaurants(value, {});
                    showPreloader();
                } else {
                    document.querySelector('[data-products=all]').innerHTML = '';
                    const categoryContainer = document.getElementById('category-container');
                    const sliderContainer = document.getElementById('slider-container');

                    if (categoryContainer.classList.contains('d-none')) {
                        categoryContainer.classList.remove('d-none');
                    }
                    if (sliderContainer != null && sliderContainer.classList.contains('d-none')) {
                        sliderContainer.classList.remove('d-none');
                    }
                }
            }, 500);

            searchInput.addEventListener('keyup', (event) => {
                debouncedGetRestaurants(event.target.value);
            });
        });

        function showPreloader() {

            const categoryContainer = document.getElementById('category-container');
            const sliderContainer = document.getElementById('slider-container');
            if (!categoryContainer.classList.contains('d-none')) {
                categoryContainer.classList.add('d-none');
            }
            if (sliderContainer != null && !sliderContainer.classList.contains('d-none')) {
                sliderContainer.classList.add('d-none');
            }

            const preloaderHTML = `
                <div class="overflow-x-scroll w-100 d-flex">
                    @for ($i = 0; $i < 4; $i++)
                        <div class="d-flex gap-2 mb-2 p-0 bg-secondary-subtle rounded-5" style="min-width: 500px; max-width: 600px;">
                            <div class="border-0 w-100">
                                <svg class="skeleton-shimmer rounded" viewBox="0 0 400 150" preserveAspectRatio="xMinYMin meet"width="100%" height="auto" xmlns="http://www.w3.org/2000/svg">
                                    <rect x="10" y="10" rx="8" ry="8" width="130" height="130" fill="#E0E0E0" />
                                    <rect x="150" y="15" rx="4" ry="4" width="220" height="20" fill="#E0E0E0" />
                                    <rect x="150" y="45" rx="4" ry="4" width="40" height="15" fill="#F0F0F0" />
                                    <rect x="150" y="70" rx="4" ry="4" width="60" height="20" fill="#E0E0E0" />
                                    <rect x="220" y="74" rx="4" ry="4" width="80" height="12" fill="#F0F0F0" />
                                    <rect x="150" y="105" rx="4" ry="4" width="150" height="15" fill="#E0E0E0" />
                                </svg>
                            </div>
                        </div>
                    @endfor
                </div>
            `;
            document.querySelector('[data-products=all]').innerHTML = preloaderHTML;
        }

        // document.querySelectorAll(".food-search-container input").forEach(searchButton => {
        //     searchButton.addEventListener('keyup', () => {
        //         getRestaurants(event.target.value, {})
        //     });
        // });
    </script>

    <script>
        // customer filteratiaons

        const filterationFragment = document.getElementById('restaurantCustomFilteration');
        const customFilterButtons = filterationFragment.querySelectorAll('[data-filter-key]');
        let customerFilter = {};
        if (localStorage.hasOwnProperty(CUSTOM_FILTER)) {
            customerFilter = JSON.parse(localStorage.getItem(CUSTOM_FILTER));
        }

        customFilterButtons.forEach((filterButton, fbIndex) => {

            const fragments = filterationFragment.querySelectorAll(
                `[data-filter-contains='${filterButton.dataset.filterKey}']`);

            customerFilter[fbIndex] = {
                filterKey: filterButton.dataset.filterKey,
                filterValues: {}
            };

            fragments.forEach(_fragment => {
                const _fragmentButtons = _fragment.querySelectorAll('[data-filter-item]');

                _fragmentButtons.forEach((_fragmentButton, fgBIndex) => {
                    customerFilter[fbIndex].filterValues[fgBIndex] = {
                        itemName: _fragmentButton.dataset.filterItem
                        // value: 0
                    }
                    const _tempFilterItem = customerFilter[fbIndex].filterValues[fgBIndex];
                    if (_tempFilterItem.hasOwnProperty('value')) {
                        _fragmentButton.checked = _tempFilterItem.value || false;
                    } else {
                        customerFilter[fbIndex].filterValues[fgBIndex].value = 0
                    }
                    _fragmentButton.addEventListener('change', (e) => {

                        customerFilter[fbIndex].filterValues[fgBIndex].value = e.target
                            .checked ? 1 : 0;
                    });
                });
            });
        });

        document.getElementById('applyFilters').addEventListener('click', () => {
            try {
                if (customerFilter && typeof customerFilter === 'object') {
                    localStorage.setItem(CUSTOM_FILTER, JSON.stringify(customerFilter));
                    showcustomFilters();
                    $('#filters').modal('hide');
                } else {
                    console.error("customerFilter is not a valid object.");
                }
            } catch (error) {
                console.error("Error saving to localStorage:", error);
            }
        });

        document.getElementById('clearFilters').addEventListener('click', () => {
            localStorage.removeItem(CUSTOM_FILTER);
            showcustomFilters();

            $('#filters').modal('hide');
        });


        const FILTER_NAME = {
            'highTOLow': "Cost : High to Low",
            'lowToHigh': "Cost : Low to High",
            'rating': "Rating",
            'deliveryTime': "Delivery Time",
            'relevance': "Relevance (Default)",
            'nonVeg': "Non Veg",
            'veg': "Veg",
            'foutDotZeroPlus': "Ratings 4.0+",
            'threeDotFivePlus': "Ratings 3.5+",
            'beforeThirtyMins': "Less than 30 mins",
            'beforFourtyfiveMins': "Less than 45 mins",
            '0To300': "Less than rs 300",
            '300To400': "Rs300 - Rs400",
        };

        function showcustomFilters() {
            let filterTags = [];
            if (localStorage.hasOwnProperty(CUSTOM_FILTER)) {
                const cFilter = JSON.parse(localStorage.getItem(CUSTOM_FILTER));
                Object.entries(cFilter).forEach(([key, filter]) => {
                    // console.log(`Key: ${key}, Filter Key: ${filter.filterKey}`);
                    Object.entries(filter.filterValues).forEach(([itemKey, filterValue]) => {
                        let _fragmentButton = document.querySelector(
                            `input[data-filter-item="${filterValue.itemName}"]`);

                        if (filterValue.value == 1) {
                            _fragmentButton.checked = true;
                            // filterTags += `<span class="badge bg-primary ms-2"> ${FILTER_NAME[filterValue.itemName]}</span>`;
                            filterTags.push(filterValue.itemName);
                        }

                    });
                });

                renderFilters(filterTags, cFilter);
            }
            // document.getElementById('filter-tags').innerHTML = filterTags;

            getRestaurants();
        }

        if (localStorage.hasOwnProperty(CUSTOM_FILTER)) {
            setTimeout(showcustomFilters, 1000);
        }

        //show nearest = true
        document.getElementById("all-option").addEventListener("change", function() {
            if (this.checked) {
                getRestaurants();
                // Add logic to display all restaurants here
            }
        });

        document.getElementById("nearest-option").addEventListener("change", function() {
            if (this.checked) {
                getRestaurants("all", {
                    nearest: true
                });
                // Add logic to display nearest restaurants here
            }
        });

        function updateFilterByItemName(data, itemName, newValue) {
            // Loop through the main object
            for (const key in data) {
                const filterValues = data[key].filterValues;

                // Loop through the filterValues object
                for (const subKey in filterValues) {
                    if (filterValues[subKey].itemName === itemName) {
                        // Update the value dynamically
                        filterValues[subKey].value = newValue ? 1 : 0;
                        return data; // Exit and return updated data once the item is found and updated
                    }
                }
            }
            return data; // Return data even if no matching itemName is found
        }

        // Function to render filters
        function renderFilters(filterTags, cFilter) {
            const filterContainer = document.querySelector(".selected-filters");
            filterContainer.innerHTML = ""; // Clear existing filters
            console.log(filterTags);
            // return true;
            filterTags.forEach((filter, index) => {
                const filterTag = document.createElement("div");
                filterTag.className = "filter-tag bg-warning";
                filterTag.innerHTML =
                    `${FILTER_NAME[filter]} <span class="remove-filter" data-tag="${filter}" data-index="${index}">×</span>`;
                filterContainer.appendChild(filterTag);
            });

            // Add event listeners for removing filters
            document.querySelectorAll(".remove-filter").forEach((btn) =>
                btn.addEventListener("click", (event) => {
                    const index = event.target.dataset.index;
                    filterTags.splice(index, 1);
                    renderFilters(filterTags); // Re-render filters
                    cFilter = updateFilterByItemName(cFilter, event.target.dataset.tag, false);
                    localStorage.setItem(CUSTOM_FILTER, JSON.stringify(cFilter));
                    showcustomFilters();
                })
            );
        }
    </script>

    <script>
        function favoriteRestaurant(item) {
            fetch(`{{ route('user.restaurant.favorite.restaurant') }}?restaurant_id=${item.dataset.id}`, {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                    }
                }).then(response => response.json())
                .then(data => {
                    console.log(data)
                    // item.parentElement.innerHTML = `<img src="{{ asset('assets/user/img/favourite.png') }}" onclick="unfavoriteRestaurant(this)" data-id="${item.dataset.id}}" class="img-fluid" style="width: 30px" alt="non-fav-food">`
                    item.parentElement.innerHTML =
                        `<span onclick="unfavoriteRestaurant(this)" data-id="${item.dataset.id}"><i class="fas fa-heart text-danger"></i></span>`

                });
        }

        function unfavoriteRestaurant(item) {
            fetch(`{{ route('user.restaurant.unfavorite.restaurant') }}?restaurant_id=${item.dataset.id}`, {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                    }
                }).then(response => response.json())
                .then(data => {
                    console.log(data)
                    // item.parentElement.innerHTML = ` <img src="{{ asset('assets/user/img/non_favourite.png') }}" onclick="favoriteRestaurant(this)" data-id="${item.dataset.id}}" class="img-fluid" style="width: 30px" alt="fav-food">`
                    item.parentElement.innerHTML =
                        ` <span  onclick="favoriteRestaurant(this)" data-id="${item.dataset.id}"><i class="feather-heart text-muted"></i></span>`
                });
        }
    </script>

    <script>
        const topFoodContainer = document.getElementById('top-products');
        if (topFoodContainer) {
            (async () => {
                try {
                    // Fetch the top foods from the server
                    const resp = await fetch('{{ route('user.restaurant.top-foods') }}');

                    // Check if the response is OK
                    if (!resp.ok) {
                        throw new Error(`HTTP error! Status: ${resp.status}`);
                    }

                    // Parse the response as JSON
                    const result = await resp.json();

                    // Ensure the result contains the expected view
                    if (result && result.view) {
                        topFoodContainer.innerHTML = result.view;
                        changer();
                        viewSingleFood();

                        $(".top-order-item").flip({
                            axis: 'y',
                            trigger: 'hover'
                        });

                        // Initialize the Slick slider
                        $(".top-order-slider").slick({
                            autoplay: true, // Enable autoplay
                            autoplaySpeed: 2500, // Set autoplay speed
                            slidesToShow: 4, // Number of slides to show
                            arrows: true, // Enable navigation arrows
                            responsive: [{
                                    breakpoint: 768,
                                    settings: {
                                        arrows: true,
                                        centerMode: false,
                                        centerPadding: "40px",
                                        slidesToShow: 3, // Adjust slides for smaller screens
                                    },
                                },
                                {
                                    breakpoint: 480,
                                    settings: {
                                        arrows: false, // Disable arrows for extra small screens
                                        centerMode: true,
                                        centerPadding: "0px",
                                        slidesToShow: 2, // Adjust slides for very small screens
                                    },
                                },
                            ],
                        });
                    } else {
                        console.error("Unexpected response format or missing view property.");
                    }
                } catch (error) {
                    console.error("Error fetching or processing top foods:", error);
                }
            });
        }

        function changer() {
            document.querySelectorAll('[data-changer-target]').forEach(element => {
                element.addEventListener('click', () => {
                    const targetSelector = `[data-changer="${element.dataset.changerTarget}"]`;
                    const targetElement = document.querySelector(targetSelector);

                    if (targetElement) {
                        targetElement.classList.remove('d-none');
                    }
                    const packageView = element.closest('.package-view');

                    if (packageView) {
                        const quantityElement = targetElement.closest('.package-view').querySelector(
                            '[data-product-price]');
                        if (quantityElement.value > 0 && quantityElement.value < 2) {
                            const currentPrice = {
                                quantity: quantityElement.value,
                                price: parseFloat(quantityElement.dataset.productPrice),
                                priceCalculate: function() {
                                    let tempPrice = parseFloat(this.price) * this.quantity;
                                    return tempPrice;
                                },
                            };
                            // console.log(targetElement);
                            addToCart(quantityElement.dataset.foodId, currentPrice.quantity, currentPrice
                                .priceCalculate(), {});
                        }
                        packageView.remove();

                    }
                });
            });

            /*********** //food quantity increment decrement //****************/
            document.querySelectorAll('[data-food-increment]').forEach(item => {
                const currentPrice = {
                    quantity: 1,
                    price: parseFloat(item.closest('.package-view').querySelector('[data-product-price]')
                        .dataset.productPrice),
                    priceCalculate: function() {
                        let tempPrice = parseFloat(this.price) * this.quantity;
                        return tempPrice;
                    },

                };
                item.addEventListener('click', () => {
                    try {
                        let quantityElement = item.closest('.package-view').querySelector(
                            '[data-product-qty]');
                        let currentQuantity = parseInt(quantityElement.value);

                        if (item.dataset.foodIncrement === "1") {
                            quantityElement.value = currentQuantity + 1;
                        } else {
                            if (currentQuantity <= 0) {
                                throw new Error("Product quantity should not be zero");
                            } else {
                                quantityElement.value = currentQuantity - 1;
                            }
                        }
                        currentPrice.quantity = parseInt(quantityElement.value);
                        addToCart(quantityElement.dataset.foodId, currentPrice.quantity, currentPrice
                            .priceCalculate(), {})

                    } catch (error) {
                        console.error('Error fetching data:', error);
                        toastr.error(error.message);
                    }
                });
            });
        }

        var customizeSingelFood = new bootstrap.Offcanvas(document.getElementById('custom_item'));

        function viewSingleFood() {
            document.querySelectorAll('[data-customize]').forEach(element => {
                element.addEventListener('click', async () => {
                    const foodId = element.dataset.foodId;
                    let url = `{{ route('user.restaurant.get-food') }}?food_id=${foodId}`;

                    try {
                        const resp = await fetch(url);
                        if (!resp.ok) {
                            const error = await resp.json();
                            toastr.error(error.message);
                            return;
                        }

                        const result = await resp.json();
                        document.querySelector('[data-products=single]').innerHTML = result.view;
                        // $('#custom_item').modal('show');
                        customizeSingelFood.show()

                        const productAddon = [];
                        const options = [];
                        const currentPrice = {
                            quantity: 0,
                            priceElement: document.querySelector('[data-current-price]'),
                            price: parseFloat(document.querySelector('[data-current-price]').dataset
                                .currentPrice),
                            priceCalculate: function() {
                                let tempPrice = 0;
                                for (let addon of productAddon) {
                                    tempPrice += (parseFloat(addon.price) * addon.qty);
                                }
                                for (let option of options) {
                                    for (let value of option.values) {
                                        tempPrice += parseFloat(value.price) * value.qty;
                                    }
                                }
                                return tempPrice;
                            },
                            priceChanger: function() {
                                const price = this.priceCalculate();
                                this.priceElement.textContent = currencySymbolsuffix(price);
                                this.priceElement.dataset.currentPrice = price;
                            }
                        };



                        /*********** //addon quantity increment decrement //****************/
                        document.querySelectorAll('[data-addon-increment]').forEach(item => {

                            const addonId = item.dataset.addonId;
                            let quantityElement = document.querySelector(
                                `input[data-addon-id="${addonId}"]`);
                            const addonPrice = parseFloat(quantityElement.dataset.price);
                            item.addEventListener('click', () => {
                                try {
                                    if (item.dataset.addonIncrement == "1") {
                                        quantityElement.value = parseInt(quantityElement
                                            .value) + 1;
                                    } else {
                                        if (quantityElement.value == 0) {
                                            throw new Error(
                                                "Addon quantity can'\t be less than zero"
                                            );
                                        } else {
                                            quantityElement.value = parseInt(
                                                quantityElement.value) - 1;
                                        }
                                    }

                                    if (quantityElement.value == 0) {
                                        const index = productAddon.findIndex(addon =>
                                            addon.id === addonId);
                                        if (index !== -1) {
                                            productAddon.splice(index, 1);
                                        }
                                    } else {
                                        const index = productAddon.findIndex(addon =>
                                            addon.id === addonId);
                                        if (index == -1) {
                                            productAddon.push({
                                                id: addonId,
                                                price: addonPrice,
                                                qty: quantityElement.value,
                                            });
                                        } else {
                                            productAddon[index].qty = quantityElement
                                                .value;
                                        }
                                    }
                                    currentPrice.priceChanger();
                                } catch (error) {
                                    console.error('Error fetching data:', error);
                                    toastr.error(error.message);
                                }
                            })
                            if (quantityElement.value == 0) {
                                const index = productAddon.findIndex(addon => addon.id ===
                                    addonId);
                                if (index !== -1) {
                                    productAddon.splice(index, 1);
                                }
                            } else {
                                const index = productAddon.findIndex(addon => addon.id ===
                                    addonId);
                                if (index == -1) {
                                    productAddon.push({
                                        id: addonId,
                                        price: addonPrice,
                                        qty: quantityElement.value,
                                    });
                                } else {
                                    productAddon[index].qty = quantityElement.value;
                                }
                            }
                            currentPrice.priceChanger();
                        });

                        /*********** //option quantity increment decrement //****************/
                        document.querySelectorAll('[data-variation-increment]').forEach(item => {
                            const optionLabel = item.dataset.optionLabel;
                            let quantityElement = document.querySelector(
                                `input[name="${optionLabel}"]`);
                            const variationName = quantityElement.dataset.variationName;
                            const optionPrice = parseFloat(quantityElement.dataset.price);

                            item.addEventListener('click', () => {
                                try {

                                    if (item.dataset.variationIncrement == "1") {
                                        quantityElement.value = parseInt(quantityElement
                                            .value) + 1;
                                    } else {
                                        if (quantityElement.value == 0) {
                                            throw new Error(optionLabel +
                                                " quantity can'\t be less than zero"
                                            );
                                        } else {
                                            quantityElement.value = parseInt(
                                                quantityElement.value) - 1;
                                        }
                                    }

                                    const variationIndex = options.findIndex(opt => opt
                                        .option === variationName);
                                    if (quantityElement.value > 0) {
                                        if (variationIndex === -1) {
                                            options.push({
                                                option: variationName,
                                                values: [{
                                                    label: optionLabel,
                                                    price: optionPrice,
                                                    qty: quantityElement
                                                        .value
                                                }]
                                            });
                                        } else {

                                            const optionIndex = options[variationIndex]
                                                .values.findIndex(val => val.label ===
                                                    optionLabel);

                                            if (optionIndex !== -1) {
                                                options[variationIndex].values[
                                                        optionIndex].qty =
                                                    quantityElement.value;
                                            } else {
                                                options[variationIndex].values.push({
                                                    label: optionLabel,
                                                    price: optionPrice,
                                                    qty: quantityElement.value
                                                });
                                            }
                                        }
                                    } else {
                                        if (variationIndex !== -1) {
                                            const optionIndex = options[variationIndex]
                                                .values.findIndex(val => val.label ===
                                                    optionLabel);
                                            if (optionIndex !== -1) {
                                                options[variationIndex].values.splice(
                                                    optionIndex, 1);

                                                if (options[variationIndex].values
                                                    .length === 0) {
                                                    options.splice(variationIndex, 1);
                                                }
                                            }
                                        }
                                    }

                                    currentPrice.priceChanger();


                                } catch (error) {
                                    console.error('Error fetching data:', error);
                                    toastr.error(error.message);
                                }
                            })
                            const variationIndex = options.findIndex(opt => opt.option ===
                                variationName);
                            if (quantityElement.value > 0) {
                                if (variationIndex === -1) {
                                    options.push({
                                        option: variationName,
                                        values: [{
                                            label: optionLabel,
                                            price: optionPrice,
                                            qty: quantityElement.value
                                        }]
                                    });
                                } else {

                                    const optionIndex = options[variationIndex].values
                                        .findIndex(val => val.label === optionLabel);

                                    if (optionIndex !== -1) {
                                        options[variationIndex].values[optionIndex].qty =
                                            quantityElement.value;
                                    } else {
                                        options[variationIndex].values.push({
                                            label: optionLabel,
                                            price: optionPrice,
                                            qty: quantityElement.value
                                        });
                                    }
                                }
                            } else {
                                if (variationIndex !== -1) {
                                    const optionIndex = options[variationIndex].values
                                        .findIndex(val => val.label === optionLabel);
                                    if (optionIndex !== -1) {
                                        options[variationIndex].values.splice(optionIndex, 1);

                                        if (options[variationIndex].values.length === 0) {
                                            options.splice(variationIndex, 1);
                                        }
                                    }
                                }
                            }

                            currentPrice.priceChanger();
                        });

                        /*********** //addining to cart //****************/

                        document.querySelector('[data-add-to-cart]').addEventListener('click',
                            function() {
                                addToCart(foodId, currentPrice.quantity, currentPrice
                                    .priceCalculate(), {
                                        addons: productAddon,
                                        variation: options
                                    })
                                element.textContent = "Added";
                            })


                    } catch (error) {
                        console.error('Error fetching data:', error);
                        toastr.error(error.message);
                    }
                });
            });
        }

        /*============//Add to cart//=================*/
        async function addToCart(product_id, qty = 1, price, options = Null) {
            const url = "{{ route('user.restaurant.add-to-cart') }}"
            var PRODUCT_ID = product_id;
            var QTY = qty;
            var PRICE = price;
            var OPTIONS = options;
            try {
                const resp = await fetch(url, {
                    method: "post",
                    headers: {
                        'X-Requested-With': 'XMLHttpRequest',
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    body: JSON.stringify({
                        id: PRODUCT_ID,
                        qty: QTY,
                        price: PRICE,
                        options: OPTIONS
                    })
                });

                if (resp.status == 201) {
                    const warning = await resp.json();
                    Swal.fire(warning.message);
                    return true;
                } else if (!resp.ok) {
                    const error = await resp.json();

                    throw new Error(error.message);
                }
                const result = await resp.json();
                // $('#custom_item').modal('hide');
                customizeSingelFood.hide();
                const viewCart = document.getElementById('view-cart');
                if (result.message) {
                    // toastr.success(result.message)
                    viewCart.querySelector('h6').textContent = result.message;
                }
                if (result.confirm) {
                    Swal.fire({
                        title: "Are you sure?",
                        text: result.confirm,
                        icon: "warning",
                        showCancelButton: true,
                        confirmButtonColor: "#3085d6",
                        cancelButtonColor: "#d33",
                        confirmButtonText: "Yes"
                    }).then((sw_resp) => {
                        if (sw_resp.isConfirmed) {
                            addToCart(PRODUCT_ID, QTY, PRICE, OPTIONS);
                        } else {
                            location.reload();
                        }

                    });
                }
                if (viewCart.classList.contains('d-none')) {
                    viewCart.classList.remove('d-none');
                }

            } catch (error) {
                console.error('Error:', error);
                Swal.fire({
                    title: error.message,
                    icon: "info"
                }).then((result) => {
                    if (result.isConfirmed) {
                        location.reload();
                    }
                });
            }
        }


        function customizeCart() {
            document.querySelectorAll('[customizeCart]').forEach(element => {
                element.addEventListener('click', async () => {
                    const foodId = element.getAttribute('food-id');

                    let url = "{{ route('user.restaurant.get-foods') }}?food_id=" + foodId; //
                    try {
                        const resp = await fetch(url);
                        if (!resp.ok) {
                            const error = await resp.json();
                            toastr.error(error.message);
                        }
                        const result = await resp.json();
                        document.querySelector('[data-products=all]').innerHTML = result.view;
                        changer();
                        viewSingleFood();
                    } catch (error) {
                        console.error('Error fetching data:', error);
                    }

                });
            });
        }
    </script>
    <script>
        // Fetch latest Order Tracker and display
        const liveOrder = document.getElementById('liveOrder');
        if (liveOrder) {
            liveOrder.style.display = "block";
        }

        (async () => {
            try {
                // Perform the fetch and await the response
                const res = await fetch("{{ route('user.restaurant.live-order') }}");

                // Check if the response is not OK
                if (!res.ok) {
                    const err = await res.json();
                    throw new Error(err.message || 'Failed to fetch live orders.');
                }

                // Parse the JSON response
                const result = await res.json();

                // Update the live order container with the fetched view
                if (liveOrder) {
                    if (result.view != null) {

                        liveOrder.innerHTML = result.view;

                        // Start countdown if timer exists in the response
                        const countdownDuration = result
                            .countdownMinutes; // Ensure this is provided in the response
                        if (countdownDuration && !isNaN(countdownDuration)) {
                            startCountdown(countdownDuration);
                        } else {
                            document.getElementById("countdownButton").parentElement.innerHTML = "";
                        }
                    }
                }

                console.log(result);
            } catch (error) {
                console.error(error);
                // toastr.error(error.message || 'An error occurred while fetching live orders.');
            }
        })();

        // Countdown Timer Function
        function startCountdown(duration) {
            // Convert minutes to seconds
            let timeRemaining = duration;

            // Function to update the timer
            function updateTimer() {
                // Calculate minutes and seconds
                const minutes = Math.floor(timeRemaining / 60);
                const seconds = timeRemaining % 60;

                // Update the timer display
                const timerElement = document.getElementById("countdownTimer");
                if (timerElement) {
                    timerElement.textContent =
                        `${minutes} min${minutes !== 1 ? "s" : ""} ${seconds} sec${seconds !== 1 ? "s" : ""}`;
                }

                // Decrease the time remaining
                timeRemaining--;

                // Stop the countdown when time runs out
                if (timeRemaining < 0) {
                    clearInterval(countdownInterval);
                    if (timerElement) {
                        timerElement.textContent = "Arrived"; // Display "Arrived" when countdown ends
                        if (!liveOrder.classList.contains('d-none')) {
                            liveOrder.classList.add('d-none');
                        }
                    }

                } else {
                    if (liveOrder.classList.contains('d-none')) {
                        liveOrder.classList.remove('d-none');
                    }
                }
            }

            // Start the countdown and update every second
            const countdownInterval = setInterval(updateTimer, 1000);
            updateTimer(); // Call it once immediately
        }

        // Call the function to fetch live orders
    </script>
    <script>
        function addToCollection(item) {
            $('#collectionModal').modal('show');
            const itemId = item.dataset.id;
            const type = item.dataset.type;
            const collectionListContainer = document.getElementById('collectionList');

            // Ensure all `li` elements are targeted
            const listItems = collectionListContainer.querySelectorAll('li');

            listItems.forEach(list => {
                const newList = list.cloneNode(true);
                list.replaceWith(newList);
                newList.addEventListener('click', () => {
                    collectionId = newList.dataset.id;
                    // console.log(collectionId);
                    saveInCollection(itemId, collectionId, type, item)
                });
            });
        }
        document.addEventListener('DOMContentLoaded', function() {
            const addCollectionButton = document.getElementById('addCollectionButton');
            const addCollectionForm = document.getElementById('addCollectionForm');
            const saveCollectionButton = document.getElementById('saveCollectionButton');
            const collectionList = document.getElementById('collectionList');
            const collectionNameInput = document.getElementById('collectionName');

            // Show the form when "Add Collection" button is clicked
            addCollectionButton?.addEventListener('click', function() {
                addCollectionForm.style.display = 'block';
                addCollectionButton.style.display = 'none';
            });

            // Handle Save Collection
            saveCollectionButton?.addEventListener('click', function() {
                const collectionName = collectionNameInput.value.trim();

                if (collectionName === '') {
                    alert('Please enter a collection name.');
                    return;
                }

                // Perform AJAX request to save the collection
                fetch("{{ route('user.restaurant.collection.save') }}", {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                        },
                        body: JSON.stringify({
                            name: collectionName
                        }),
                    })
                    .then(response => {
                        if (!response.ok) {
                            throw new Error('Network response was not ok');
                        }
                        return response.json();
                    })
                    .then(data => {
                        // Append the new collection to the list
                        const newCollection = document.createElement('li');
                        newCollection.classList.add('list-group-item');
                        newCollection.textContent = data.name;
                        collectionList.appendChild(newCollection);

                        // Hide the form and reset the input
                        addCollectionForm.style.display = 'none';
                        addCollectionButton.style.display = 'block';
                        collectionNameInput.value = '';
                    })
                    .catch(error => {
                        console.error('Error:', error);
                        alert('Failed to save the collection.');
                    });
            });
        });

        async function saveInCollection(itemId, collectionId, type = "food", item) {
            const data = {
                itemId: itemId,
                collectionId: collectionId,
                type: type
            }
            const resp = await fetch("{{ route('user.restaurant.collection.add-item') }}", {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                },
                body: JSON.stringify(data),
            })
            if (!resp.ok) {
                const err = await resp.json();
                console.error(err.message)
            }
            const result = await resp.json();
            toastr.success(result.message);
            $('#collectionModal').modal('hide');
            const newItem = item.cloneNode(true);
            newItem.src = "{{ asset('assets/user/img/saved-collection.png') }}";
            newItem.dataset.collectionId = collectionId;
            newItem.dataset.type = type;
            newItem.setAttribute('onclick', `removeFromCollection(this)`);
            item.replaceWith(newItem);
        }

        async function undoFromCollection(item) {
            const itemId = item.dataset.id;
            const type = item.dataset.type;
            const collection_id = item.dataset.collectionId;
            const resp = await fetch("{{ route('user.restaurant.collection.undo-item') }}", {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                },
                body: JSON.stringify({
                    itemId: itemId,
                    type: type,
                    collectionId: collection_id
                }),
            })
            if (!resp.ok) {
                const err = await resp.json();
                console.error(err.message)
            }
            const result = await resp.json();
            toastr.success(result.message);
            const newItem = item.cloneNode(true);
            newItem.src = "{{ asset('assets/user/img/save-collection.png') }}";
            newItem.setAttribute('onclick', `addToCollection(this)`);
            item.replaceWith(newItem);
        }
    </script>
@endpush
