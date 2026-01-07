@extends('user-views.restaurant.layouts.main')

<?php

$schedule = $restaurant->schedules->first();
$restaurantTiming = $schedule != null ? App\CentralLogics\Helpers::remainingTime($schedule->opening_time, $schedule->closing_time) : null;
$badges = json_decode($restaurant->badges);
$user = Session::get('userInfo');
?>
@push('css')
    <style>
        #food-search-container {
            visibility: visible;
            margin-top: -15px;
        }

        #mobile-header {
            visibility: hidden;
            position: absolute;
            z-index: 1;
        }

        #toggle-bar {
            visibility: visible;
            position: absolute;
        }

        .cat-item {
            padding: 2px 0 !important;
        }

        /* Modern Restaurant Header Card - Level Up */
        .restaurant-header-card {
            background: linear-gradient(135deg, #ff6b35 0%, #ff810a 50%, #ff9500 100%);
            border-radius: 24px;
            padding: 30px;
            box-shadow: 0 12px 40px rgba(255, 129, 10, 0.25), 0 0 0 1px rgba(255, 255, 255, 0.1);
            position: relative;
            overflow: hidden;
            backdrop-filter: blur(10px);
        }

        .restaurant-header-card::before {
            content: '';
            position: absolute;
            top: -60%;
            right: -15%;
            width: 400px;
            height: 400px;
            background: radial-gradient(circle, rgba(255, 255, 255, 0.15) 0%, transparent 70%);
            border-radius: 50%;
            animation: float-decoration 8s ease-in-out infinite;
        }

        .restaurant-header-card::after {
            content: '';
            position: absolute;
            bottom: -40%;
            left: -10%;
            width: 300px;
            height: 300px;
            background: radial-gradient(circle, rgba(255, 255, 255, 0.08) 0%, transparent 70%);
            border-radius: 50%;
            animation: float-decoration 10s ease-in-out infinite reverse;
        }

        @keyframes float-decoration {
            0%, 100% { transform: translate(0, 0) scale(1); }
            50% { transform: translate(20px, 20px) scale(1.1); }
        }

        .restaurant-info-wrapper {
            position: relative;
            z-index: 1;
        }

        .restaurant-main-info {
            margin-bottom: 24px;
            text-align: center;
        }

        .restaurant-name {
            font-size: 2rem;
            font-weight: 900;
            color: white;
            margin-bottom: 10px;
            line-height: 1.2;
            text-transform: capitalize;
            text-shadow: 0 2px 10px rgba(0, 0, 0, 0.15);
            letter-spacing: -0.5px;
        }

        .restaurant-address {
            color: rgba(255, 255, 255, 0.98);
            font-size: 0.8rem;
            margin-bottom: 0;
            display: inline-flex;
            align-items: baseline;
            gap: 2px;
            font-weight: 500;
            background: rgba(255, 255, 255, 0.12);
            padding: 5px 12px;
            border-radius: 16px;
            backdrop-filter: blur(10px);
        }

        .restaurant-address i {
            font-size: 0.85rem;
            flex-shrink: 0;
            margin-right: 0;
        }

        /* Stats Row - Enhanced */
        .restaurant-stats-row {
            display: flex;
            gap: 14px;
            margin-bottom: 20px;
            flex-wrap: wrap;
            justify-content: center;
        }

        .stat-item {
            background: rgba(255, 255, 255, 0.98);
            border-radius: 18px;
            padding: 14px 18px;
            display: flex;
            align-items: center;
            gap: 12px;
            flex: 1;
            min-width: 150px;
            max-width: 200px;
            box-shadow: 0 6px 20px rgba(0, 0, 0, 0.1);
            transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1);
            border: 1px solid rgba(255, 255, 255, 0.5);
            position: relative;
            overflow: hidden;
        }

        .stat-item::before {
            content: '';
            position: absolute;
            top: 0;
            left: -100%;
            width: 100%;
            height: 100%;
            background: linear-gradient(90deg, transparent, rgba(255, 255, 255, 0.3), transparent);
            transition: left 0.5s ease;
        }

        .stat-item:hover::before {
            left: 100%;
        }

        .stat-item:hover {
            transform: translateY(-4px) scale(1.02);
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.15);
        }

        .stat-icon-wrapper {
            width: 48px;
            height: 48px;
            border-radius: 14px;
            display: flex;
            align-items: center;
            justify-content: center;
            flex-shrink: 0;
            position: relative;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
        }

        .stat-icon-wrapper::after {
            content: '';
            position: absolute;
            top: 50%;
            left: 50%;
            width: 100%;
            height: 100%;
            border-radius: 14px;
            background: inherit;
            transform: translate(-50%, -50%);
            opacity: 0.3;
            animation: pulse-icon 2s ease-in-out infinite;
        }

        @keyframes pulse-icon {
            0%, 100% { transform: translate(-50%, -50%) scale(1); opacity: 0.3; }
            50% { transform: translate(-50%, -50%) scale(1.2); opacity: 0; }
        }

        .rating-icon {
            background: linear-gradient(135deg, #28a745, #20c997);
        }

        .delivery-icon {
            background: linear-gradient(135deg, #ff810a, #ffb347);
        }

        .distance-icon {
            background: linear-gradient(135deg, #667eea, #764ba2);
        }

        .stat-icon-wrapper i {
            font-size: 1.3rem;
            color: white;
            position: relative;
            z-index: 1;
        }

        .stat-content {
            display: flex;
            flex-direction: column;
            gap: 3px;
        }

        .stat-value {
            font-size: 1.15rem;
            font-weight: 900;
            color: #1a1a1a;
            line-height: 1;
            background: linear-gradient(135deg, #333, #555);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
        }

        .stat-label {
            font-size: 0.72rem;
            color: #888;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        /* Status Badge - Premium */
        .restaurant-status-wrapper {
            display: flex;
            justify-content: center;
        }

        .status-badge {
            display: inline-flex;
            align-items: center;
            gap: 10px;
            padding: 12px 24px;
            border-radius: 50px;
            font-weight: 800;
            font-size: 0.92rem;
            box-shadow: 0 6px 20px rgba(0, 0, 0, 0.15);
            position: relative;
            overflow: hidden;
            letter-spacing: 0.3px;
        }

        .status-badge::before {
            content: '';
            position: absolute;
            top: 0;
            left: -100%;
            width: 100%;
            height: 100%;
            background: linear-gradient(90deg, transparent, rgba(255, 255, 255, 0.3), transparent);
            animation: shimmer 3s infinite;
        }

        @keyframes shimmer {
            0% { left: -100%; }
            100% { left: 100%; }
        }

        .status-badge i {
            font-size: 1.15rem;
            position: relative;
            z-index: 1;
        }

        .status-open {
            background: linear-gradient(135deg, #28a745, #20c997);
            color: white;
        }

        .status-closed {
            background: linear-gradient(135deg, #dc3545, #c82333);
            color: white;
        }

        .status-closing-soon {
            background: linear-gradient(135deg, #ffc107, #ff9800);
            color: #1a1a1a;
        }

        .status-reopens {
            background: linear-gradient(135deg, #b86b17, #965513);
            color: white;
        }

        /* Mobile Responsive - Enhanced */
        @media (max-width: 768px) {
            .restaurant-header-card {
                border-radius: 20px;
                padding: 24px 20px;
            }

            .restaurant-header-card::before {
                width: 300px;
                height: 300px;
            }

            .restaurant-main-info {
                margin-bottom: 20px;
            }

            .restaurant-name {
                font-size: 1.6rem;
            }

            .restaurant-address {
                font-size: 0.75rem;
                padding: 4px 10px;
                gap: 2px;
                border-radius: 14px;
            }

            .restaurant-address i {
                font-size: 0.8rem;
            }

            .restaurant-stats-row {
                gap: 10px;
                margin-bottom: 18px;
            }

            .stat-item {
                min-width: calc(50% - 5px);
                max-width: none;
                padding: 12px 14px;
            }

            .stat-icon-wrapper {
                width: 44px;
                height: 44px;
            }

            .stat-icon-wrapper i {
                font-size: 1.15rem;
            }

            .stat-value {
                font-size: 1.05rem;
            }

            .stat-label {
                font-size: 0.68rem;
            }

            .status-badge {
                padding: 10px 18px;
                font-size: 0.88rem;
            }
        }

        @media (max-width: 480px) {
            .restaurant-header-card {
                padding: 20px 16px;
                border-radius: 18px;
            }

            .restaurant-main-info {
                margin-bottom: 18px;
            }

            .restaurant-name {
                font-size: 1.35rem;
            }

            .restaurant-address {
                font-size: 0.65rem;
                padding: 4px 8px;
                gap: 0;
                border-radius: 12px;
            }

            .restaurant-address i {
                font-size: 0.7rem;
                margin-right: 1px;
            }

            .restaurant-stats-row {
                gap: 6px;
                margin-bottom: 16px;
                overflow-x: auto;
                overflow-y: hidden;
                scrollbar-width: none;
                -ms-overflow-style: none;
                padding-bottom: 5px;
            }

            .restaurant-stats-row::-webkit-scrollbar {
                display: none;
            }

            .stat-item {
                min-width: 110px;
                max-width: 120px;
                /* flex-direction: column; */
                padding: 10px 8px;
                flex-shrink: 0;
                text-align: center;
            }

            .stat-icon-wrapper {
                width: 40px;
                height: 40px;
                margin: 0 auto 6px;
            }

            .stat-icon-wrapper i {
                font-size: 1.1rem;
            }

            .stat-content {
                align-items: center;
            }

            .stat-value {
                font-size: 0.95rem;
            }

            .stat-label {
                font-size: 0.65rem;
            }

            .status-badge {
                padding: 9px 16px;
                font-size: 0.82rem;
            }

            .status-badge i {
                font-size: 1.05rem;
            }
        }
    </style>
@endpush

@push('slider')

    @if (!empty($filterBanners))
        {{-- @include('user-views.restaurant.layouts.slider',$banners) --}}
        <div class="container py-0" id="slider-container">
            <div class="popular-slider">
                @foreach ($filterBanners as $banner)
                    <div class="cat-item py-0">
                        <a class="d-block text-center shadow-sm" href="javascript:void(0)">
                            <img alt="#" src="{{ Helpers::getUploadFile($banner->image, 'banner') }}"
                                class="img-fluid rounded-3">
                        </a>
                    </div>
                @endforeach
                @if (!empty($restaurant->cover_photo))
                    <div class="cat-item py-0">
                        <a class="d-block text-center shadow-sm" href="javascript:void(0)">

                            <img alt="#"
                                src="{{ Helpers::getUploadFile($restaurant->cover_photo, 'restaurant-cover') }}"
                                class="img-fluid rounded-3">
                        </a>
                    </div>
                @endif
            </div>
        </div>
    @endif
@endpush

@push('mobile-header')
    <div class="d-lg-none d-block">
        <div class="bg-primary p-3 d-flex justify-content-between align-items-center rounded-bottom-4">
            <div class="col-1 text-white fw-bolder fs-4 me-2 align-self-center rounded-bottom-4"
                onclick="window.history.back()">
                <i class="fas fa-arrow-left"></i>
            </div>

            <div class="col-10 input-group bg-white rounded-4 shadow-sm mt-1 food-search-container"
                id="food-search-container" style="opacity: 0.8;width:75%;">
                <span class="input-group-text bg-transparent border-0 rounded-0"><i class="feather-search"></i></span>
                <input type="text" class="form-control bg-transparent border-0 rounded-0 px-0 shadow-none fs-4"
                    placeholder="Search Cake" aria-label="" style="height: 50px;">
            </div>
            <div class="bg-white rounded-circle align-self-center footer-item ms-2 text-center" id="shareRestaurant"
                style="background:white;">
                <i class="fas fa-share-nodes text-dark text-warning"></i>
            </div>

            {{-- <a class="col-1 toggle text-dark fs-5 toggle-btn align-self-center ms-3" data-bs-toggle="offcanvas" href="#offcanvasExample" href="javascript:void(0)" aria-controls="offcanvasExample">
                <i class="fa-solid fa-bars"></i>
            </a> --}}

        </div>
    </div>
@endpush

@section('containt')

    <!-- Restaurant Detail - Modern Design -->
    <div class="container mt-3 pb-3" {!! !isset($restaurantTiming) ||
    $restaurantTiming['isClosed'] == true ||
    $restaurant->isClosed == true ||
    $restaurant->zone->status == 0
        ? 'style="filter: grayscale(100%);"'
        : null !!} id="restaurant-details-container">
        <div class="restaurant-header-card">
            <div class="restaurant-info-wrapper">
                <!-- Restaurant Name & Address -->
                <div class="restaurant-main-info">
                    <h1 class="restaurant-name">{{ $restaurant->name }}</h1>
                    @php($address = json_decode($restaurant->address))
                    <p class="restaurant-address">
                        <i class="feather-map-pin"></i>
                        <span>{{ Str::ucfirst($address->street) }}, {{ Str::ucfirst($address->city) }} - {{ Str::ucfirst($address->pincode) }}</span>
                    </p>
                </div>

                <!-- Stats Row -->
                <div class="restaurant-stats-row">
                    <!-- Rating -->
                    <div class="stat-item rating-stat">
                        <div class="stat-icon-wrapper rating-icon">
                            <i class="feather-star"></i>
                        </div>
                        <div class="stat-content">
                            <span class="stat-value">5.0</span>
                            <span class="stat-label">Rating</span>
                        </div>
                    </div>

                    <!-- Delivery Time -->
                    <div class="stat-item delivery-stat">
                        <div class="stat-icon-wrapper delivery-icon">
                            <i class="fas fa-motorcycle"></i>
                        </div>
                        <div class="stat-content">
                            <span class="stat-value">{{ \Carbon\Carbon::parse($restaurant['min_delivery_time'])->format('i') }} min</span>
                            <span class="stat-label">Delivery</span>
                        </div>
                    </div>

                    <!-- Distance -->
                    @if ($restaurant->distance > 0)
                        <div class="stat-item distance-stat">
                            <div class="stat-icon-wrapper distance-icon">
                                <i class="feather-navigation"></i>
                            </div>
                            <div class="stat-content">
                                <span class="stat-value">{{ App\CentralLogics\Helpers::formatDistance($restaurant->distance) }}</span>
                                <span class="stat-label">Away</span>
                            </div>
                        </div>
                    @endif
                </div>

                <!-- Status Badge -->
                <div class="restaurant-status-wrapper">
                    @if ($restaurant->zone->status == 0)
                        <div class="status-badge status-closed">
                            <i class="fas fa-times-circle"></i>
                            <span>Zone Closed</span>
                        </div>
                    @elseif($restaurant->temp_close)
                        <div class="status-badge status-closed">
                            <i class="fas fa-pause-circle"></i>
                            <span>Temporarily Closed</span>
                        </div>
                    @elseif ($restaurantTiming == null)
                        <div class="status-badge status-closed">
                            <i class="fas fa-moon"></i>
                            <span>Closed Today</span>
                        </div>
                    @else
                        @if ($restaurantTiming['isClosed'])
                            <div class="status-badge status-reopens">
                                <i class="fas fa-clock"></i>
                                <span>Reopens {!! $restaurantTiming['format'] !!}</span>
                            </div>
                        @else
                            @if ($restaurantTiming['closingDifferance']->h < 1)
                                <div class="status-badge status-closing-soon">
                                    <i class="fas fa-hourglass-half"></i>
                                    <span>Closing {!! $restaurantTiming['format'] !!}</span>
                                </div>
                            @else
                                <div class="status-badge status-open">
                                    <i class="fas fa-check-circle"></i>
                                    <span>Open Now</span>
                                </div>
                            @endif
                        @endif
                    @endif
                </div>
            </div>
        </div>
    </div>

    <!-- new Notice Bar -->

    @if (isset($filterMarquees[0]))
        <div class="container mt-3" id="notice-bar-container">
            <div class="notice-slider">
                @foreach ($filterMarquees as $marquee)
                    <div class="notice-item m-2 ">
                        <a class="d-block text-center" href="{{ $marquee->link ?? 'javascript:void(0)' }}">
                            <img src="{{ asset("marquee/$marquee->file") }}" alt="" class="w-100 rounded-4">
                        </a>
                    </div>
                @endforeach
            </div>
        </div>
    @endif

    <!-- Menu List -->
    <div class="container position-relative mt-3 pb-3" data-products="all" {!! !isset($restaurantTiming) ||
    $restaurantTiming['isClosed'] == true ||
    $restaurant->isClosed == true ||
    $restaurant->zone->status == 0
        ? 'style="filter: grayscale(100%);"'
        : null !!}>

    </div>

    <div class="position-fixed w-100 qr-position text-center" style="height: 0;z-index: 1021; justify-items:center;"
        {!! !isset($restaurantTiming) ||
        $restaurantTiming['isClosed'] == true ||
        $restaurant->isClosed == true ||
        $restaurant->zone->status == 0
            ? 'style="filter: grayscale(100%);"'
            : null !!}>
        <button class="btn btn-primary p-2 shadow d-flex justify-content-center align-items-center" data-bs-toggle="modal"
            data-bs-target="#menu" style="width: auto; height: auto;">
            <i class="fas fa-book-open align-self-center h4 mb-0 me-2"></i>
            <div class="mb-0 text-white fw-bolder">Menu</div>
        </button>
    </div>
    <!-- Sticky Foot cart -->
    @php($cart = App\Http\Controllers\User\Restaurant\CartHelper::getCart())
    <div class="container fixed-bottom shadow-none rounded {{ count($cart) == 0 ? 'd-none' : null }}  mb-3" id="view-cart"
        onclick="location.href='{{ route('user.restaurant.check-out') }}'">
        <div class="d-flex justify-content-between py-3 rounded-4 px-3 cart-section" style="background-color:#ff810a;">
            <a href="javascript:void(0)" class="align-self-center">
                <h6 class="text-white fw-bolder mb-0 align-self-center">{{ count($cart) }} Item Added</h6>
            </a>
            <div class="d-flex">
                <h5 class="text-white fw-bolder mb-0 align-self-center">View Cart<i class="fas fa-arrow-right ms-2"></i>
                </h5>
            </div>
        </div>
    </div>
@endsection


@push('modal')
    <!-- Menu popup -->
    <div class="modal fade" id="menu" tabindex="-1" role="dialog" aria-hidden="true"
        style="z-index: 9999999;justify-items:center;">
        <div class="modal-dialog modal-dialog-end modal-dialog-scrollable modal-menu-items">
            <div class="modal-content menu-items">
                <div class="modal-header">
                    <h6 class="modal-title fw-bolder">Select Menu</h6>
                    <button type="button" class="btn-close shadow-none" data-bs-dismiss="modal"
                        aria-label="Close"></button>
                </div>
                <div class="modal-body p-0">
                    <div class="osahan-filter bg-light">
                        <div class="filter">
                            <div class="p-4" id="showMenu">
                                <div class="row" style="filter: blur(2px)">
                                    <div class="col-4 text-center" style="padding:4px;">
                                        <div class="bg-white shadow rounded-3 pb-2">
                                            <img src="{{ asset('assets/user/img/banner-1.jpg') }}" class="rounded"
                                                alt="" style="height: 60px; width:100%;">
                                            <div>Chicken (Gravy)</div>
                                        </div>
                                    </div>
                                    <div class="col-4 text-center" style="padding:4px;">
                                        <div class="bg-white shadow rounded-3 pb-2">
                                            <img src="{{ asset('assets/user/img/banner-1.jpg') }}" class="rounded"
                                                alt="" style="height: 60px; width:100%;">
                                            <div>Chicken (Gravy)</div>
                                        </div>
                                    </div>
                                    <div class="col-4 text-center" style="padding:4px;">
                                        <div class="bg-white shadow rounded-3 pb-2">
                                            <img src="{{ asset('assets/user/img/banner-1.jpg') }}" class="rounded"
                                                alt="" style="height: 60px; width:100%;">
                                            <div>Chicken (Gravy)</div>
                                        </div>
                                    </div>
                                    <div class="col-4 text-center" style="padding:4px;">
                                        <div class="bg-white shadow rounded-3 pb-2">
                                            <img src="{{ asset('assets/user/img/banner-1.jpg') }}" class="rounded"
                                                alt="" style="height: 60px; width:100%;">
                                            <div>Chicken (Gravy)</div>
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
    <!-- Filter Item -->
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
                        <div class="filter">
                            <div class="d-flex align-items-start">
                                <div class="nav flex-column nav-pills" id="v-pills-tab" role="tablist"
                                    aria-orientation="vertical">
                                    {{-- <button class="nav-link text-dark" id="v-pills-profile-tab" data-bs-toggle="pill" data-bs-target="#v-pills-profile" type="button" role="tab" aria-controls="v-pills-profile" aria-selected="false">Veg/Non-Veg</button> --}}
                                    <button class="nav-link text-dark active" id="v-pills-disabled-tab"
                                        data-bs-toggle="pill" data-bs-target="#v-pills-disabled" type="button"
                                        role="tab" aria-controls="v-pills-disabled" aria-selected="false">Top
                                        Picks</button>
                                    <button class="nav-link text-dark" id="v-pills-messages-tab" data-bs-toggle="pill"
                                        data-bs-target="#v-pills-messages" type="button" role="tab"
                                        aria-controls="v-pills-messages" aria-selected="false">Sort By</button>
                                </div>
                                <div class="tab-content p-3 border-start" id="v-pills-tabContent">
                                    {{-- <div class="tab-pane fade" id="v-pills-profile" role="tabpanel" aria-labelledby="v-pills-profile-tab" tabindex="0">
                                        <small class="text-black-50">Filter By</small>
                                        <div class="mt-2 mb-2">
                                            <input class="form-check-input" type="radio" value="" id="flexCheckDefault" style="font-size: 20px;border:1px solid #ff810a;" checked="">
                                            <label class="form-check-label ms-3 fx-6 mt-1" for="flexCheckDefault">
                                                Veg
                                            </label>
                                        </div>
                                        <div class="mb-2">
                                            <input class="form-check-input" type="radio" value="" id="flexCheckDefault" style="font-size: 20px;border:1px solid #ff810a;">
                                            <label class="form-check-label ms-3 fx-6 mt-1" for="flexCheckDefault">
                                                Non Veg
                                            </label>
                                        </div>
                                    </div> --}}
                                    <div class="tab-pane fade show active" id="v-pills-disabled" role="tabpanel"
                                        aria-labelledby="v-pills-disabled-tab" tabindex="0">
                                        <small class="text-black-50">Filter By</small>
                                        <div class="mt-2 mb-2">
                                            <input class="form-check-input" type="radio" value=""
                                                id="flexCheckDefault" style="font-size: 20px;border:1px solid #ff810a;"
                                                checked="">
                                            <label class="form-check-label ms-3 fx-6 mt-1" for="flexCheckDefault">
                                                Ratings 3.5+
                                            </label>
                                        </div>
                                        <div class="mb-2">
                                            <input class="form-check-input" type="radio" value=""
                                                id="flexCheckDefault" style="font-size: 20px;border:1px solid #ff810a;">
                                            <label class="form-check-label ms-3 fx-6 mt-1" for="flexCheckDefault">
                                                Ratings 4.0+
                                            </label>
                                        </div>
                                    </div>
                                    <div class="tab-pane fade" id="v-pills-messages" role="tabpanel"
                                        aria-labelledby="v-pills-messages-tab" tabindex="0">
                                        <small class="text-black-50">Filter By</small>
                                        <div class="mt-2 mb-2">
                                            <input class="form-check-input" type="radio" value=""
                                                id="flexCheckDefault" style="font-size: 20px;border:1px solid #ff810a;">
                                            <label class="form-check-label ms-3 fx-6 mt-1" for="flexCheckDefault">
                                                Cost : Low to High
                                            </label>
                                        </div>
                                        <div class="mb-2">
                                            <input class="form-check-input" type="radio" value=""
                                                id="flexCheckDefault" style="font-size: 20px;border:1px solid #ff810a;">
                                            <label class="form-check-label ms-3 fx-6 mt-1" for="flexCheckDefault">
                                                Cost : High to Low
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
                        <a href="javascript:void(0)" class="btn border-top btn-lg w-100" data-bs-dismiss="modal">Clear
                            Filters</a>
                    </div>
                    <div class="col-6 m-0 p-0">
                        <a href="javascript:();" class="btn btn-primary btn-lg w-100">Apply</a>
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

    @include('user-views.restaurant.layouts.bottom-navigation')
@endpush

@push('javascript')
    <script>
        $(document).on('click', '.menu_item', function() {
            let _this = $(this);
            let menu_id = _this.data('menu-target');
            let target = $(menu_id);

            // $('#menu').modal('hide');

            if (target.length) {
                $('html, body').animate({
                    scrollTop: target.offset().top
                }, 300);
            }
        });
        $(document).on('click', '.submenu_item', function() {
            let _this = $(this);
            let menu_id = _this.data('submenu-id');
            let target = $(`[data-sub-menu="${menu_id}"]`);

            $('#menu').modal('hide');
            // if (target.length) {
            $('html, body').animate({
                scrollTop: target.offset().top
            }, 300);
            // }
        });


        //collapse
        document.addEventListener('DOMContentLoaded', function() {
            const collapseButtons = document.querySelectorAll('[id^="collapse-btn"]');

            collapseButtons.forEach(button => {
                button.addEventListener('click', function() {
                    const targetId = this.getAttribute('id').replace('collapse-btn',
                        'collapse-content');
                    const collapseContent = document.getElementById(targetId);

                    if (collapseContent.classList.contains('collapsed')) {
                        collapseContent.classList.remove('collapsed');
                        collapseContent.classList.add('expanded');
                    } else {
                        collapseContent.classList.remove('expanded');
                        collapseContent.classList.add('collapsed');
                    }
                });

                // Ensure that the collapse content starts in the collapsed state
                const targetId = button.getAttribute('id').replace('collapse-btn', 'collapse-content');
                const collapseContent = document.getElementById(targetId);
                collapseContent.classList.add('collapsed');
            });
        });


        $(document).ready(function() {
            $('.slick-group').slick({
                arrows: false,
                infinite: true,
                speed: 300,
                fade: true,
                autoplay: true,
                cssEase: 'linear'
            });

        });
    </script>

    <script>
        /*====================// Fet Food //=====================*/
        async function getFoods(filter = null) {
            let url = "{{ route('user.restaurant.get-foods') }}";
            const restaurantName = "{{ Str::slug($restaurant->name) }}";
            url += `?restaurant_name=${restaurantName}`;

            if (filter) {
                url += `&filter=${filter}`;
            }
            try {
                const resp = await fetch(url);
                if (!resp.ok) {
                    const error = await resp.json();
                    toastr.error(error.message);
                }
                const result = await resp.json();
                document.querySelector('[data-products=all]').innerHTML = result.view;
                getmenu(result.menu)
                changer();
                viewSingleFood();


            } catch (error) {
                console.error('Error fetching data:', error);
            }
        }
        getFoods();

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


        async function getmenu(menudata) {
            // console.log(menudata)
            // return 1;
            let url = `{{ route('user.restaurant.get-menu') }}`
            try {
                const resp = await fetch(url, {
                    method: 'post',
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    body: JSON.stringify({
                        menu: menudata
                    })
                });
                if (!resp.ok) {
                    const error = await resp.json();
                    toastr.error(error.message);
                    return;
                }
                const result = await resp.json();
                document.getElementById('showMenu').innerHTML = result.view;
            } catch (error) {
                toastr.error(error.message);
            }
        }

        const debounce = (func, delay) => {
            let timer;
            return function(...args) {
                clearTimeout(timer);
                timer = setTimeout(() => func.apply(this, args), delay);
            };
        };

        document.querySelectorAll(".food-search-container input").forEach(searchButton => {
            const debouncedHandler = debounce(function(event) {
                const target = event.target;
                const notice_bar_container = document.getElementById('notice-bar-container');
                const restaurant_details_container = document.getElementById(
                'restaurant-details-container');
                const slider_container = document.getElementById('slider-container');

                if (target.value === '') {
                    if (notice_bar_container) {
                        notice_bar_container.classList.add('d-none');
                    }
                    restaurant_details_container.classList.remove('d-none');
                    slider_container && slider_container.classList.remove('d-none');
                } else {
                    if (notice_bar_container) {
                        notice_bar_container.classList.remove('d-none');
                    }
                    restaurant_details_container.classList.add('d-none');
                    slider_container && slider_container.classList.add('d-none');
                }

                getFoods(target.value);
            }, 800);

            searchButton.addEventListener('keyup', debouncedHandler);
        });
    </script>

    <script>
        function favoriteFood(item) {
            fetch(`{{ route('user.restaurant.favorite.food') }}?food_id=${item.dataset.id}`, {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                    }
                }).then(response => response.json())
                .then(data => {
                    console.log(data)
                    item.outerHTML = `<div onclick="unfavoriteFood(this)" data-id="${item.dataset.id}" style="font-size:23px;">
                                                    <i class="fas fa-heart text-danger bg-white"></i>
                                                </div>`

                });
        }

        function unfavoriteFood(item) {
            fetch(`{{ route('user.restaurant.unfavorite.food') }}?food_id=${item.dataset.id}`, {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                    }
                }).then(response => response.json())
                .then(data => {
                    console.log(item)
                    item.outerHTML = ` <div onclick="favoriteFood(this)" data-id="${item.dataset.id}" style="font-size:23px;">
                                                    <i class="feather-heart text-dark"></i>
                                                </div>`
                });
        }
        var addToCollectionItem = null;
        const collectionModal = bootstrap.Modal.getOrCreateInstance(document.getElementById('collectionModal'));


        function addToCollection(item) {
            // $('#collectionModal').modal('show');
            collectionModal.show();
            addToCollectionItem = item;
            setEventsToCollectionList(item);
        }

        function setEventsToCollectionList(item) {
            const itemId = item.dataset.id;
            const type = item.dataset.type;
            const collectionListContainer = document.getElementById('collectionList');

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
                        newCollection.setAttribute('data-id', data.id);
                        collectionList.appendChild(newCollection);

                        setEventsToCollectionList(
                            addToCollectionItem
                        ); // setting fresh events to collection name + newly added name
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
            // $('#collectionModal').modal('hide');
            collectionModal.hide();

            const newItem = item.cloneNode(true);

            newItem.innerHTML = `<i class="fas fa-bookmark text-success bg-white">`;
            newItem.removeAttribute('onclick');
            newItem.setAttribute('data-collection-id', collectionId);
            newItem.setAttribute('onclick', 'undoFromCollection(this)');
            item.replaceWith(newItem);
            addToCollectionItem = null // free up collection item
        }

        async function undoFromCollection(item) {
            const collectionId = item.dataset.collectionId;
            const itemId = item.dataset.id;
            const type = item.dataset.type;
            const data = {
                itemId: itemId,
                collectionId: collectionId,
                type: type
            }
            const resp = await fetch("{{ route('user.restaurant.collection.undo-item') }}", {
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
            collectionModal.hide();

            const newItem = item.cloneNode(true);

            newItem.innerHTML = `<i class="feather-bookmark text-dark">`;
            newItem.setAttribute('onclick', 'addToCollection(this)');
            item.replaceWith(newItem);
        }


        //share REstaurant
        document.getElementById('shareRestaurant').addEventListener('click', () => {
            const urlToShare = location.href;

            // Copy the URL to the clipboard
            navigator.clipboard.writeText(urlToShare).then(() => {
                console.log('URL copied to clipboard:', urlToShare);

                // Check if the Web Share API is supported
                if (navigator.share) {
                    navigator.share({
                            title: 'Check out this restaurant!',
                            text: 'Explore Champaran Meat House on Foodyari:',
                            url: urlToShare
                        })
                        .then(() => console.log('Shared successfully!'))
                        .catch(error => console.error('Error sharing:', error));
                } else {
                    alert('URL copied to clipboard! Share it manually.');
                }
            }).catch(err => {
                console.error('Failed to copy URL:', err);
                alert('Failed to copy URL. Please try again.');
            });
        });
    </script>
@endpush
