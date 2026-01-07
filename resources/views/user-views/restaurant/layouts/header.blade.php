@php
    $userLocation = [];
    $default_address = [];
    if (auth('customer')->check()) {
        $userLocation = auth('customer')->user()->customerAddress()->orderByDesc('is_default')->orderByDesc('id')->first();
        $default_address = [
            'type' => !empty($userLocation) ? $userLocation->type : 'Select Address',
            'address' => !empty($userLocation) ? $userLocation->address : '',
        ];
    }else{
        $userLocation = Helpers::getGuestSession('guest_location');
        if($userLocation){
            $default_address['type'] = $userLocation['type'];
            $default_address['address'] = $userLocation['address'];
        }
    }


    $foodList =App\Models\Category::get('name')->pluck('name')->toArray();
    // dd($foodList);

@endphp

<header class="section-header">
    <section class="header-main shadow-sm bg-white">
        <div class="container">
            <div class="row align-items-center">
                <div class="col-1">
                    <a href="{{ route('user.dashboard') }}" class="brand-wrap mb-0">
                        <img alt="#" class="img-fluid" src="{{ asset('assets/user/img/logo_web.png') }}">
                    </a>
                </div>
                <div class="col-3 d-flex align-items-center m-none">
                    <div class=" me-3">
                        <a class="text-dark d-flex align-items-center alert alert-primary mb-0 p-1 rounded-pill" role="button" data-bs-toggle="offcanvas" data-bs-target="@if (isset($userLocation)) #userSavedLocation @else #userNewLocation @endif">
                            <div class="me-0">
                                <i class="feather-map-pin me-2 bg-light rounded-pill p-2 icofont-size text-primary"></i>
                            </div>
                            <div class="location-bar text-truncate ms-1 lh-1">
                                <h6 class="mb-0">{{ $default_address['type'] ?? 'Home' }}</h6>
                                <marquee scrollamount="3" class="mb-0 text-truncate">{{ array_key_exists('address', $default_address) ? $default_address['address'] : '' }}</marquee>
                            </div>
                        </a>
                    </div>
                </div>
                <div class="px-2 col-5 food-search-container">
                    <div class="input-group rounded alert alert-primary mb-0 rounded-pill p-0" style="opacity: 0.8">
                        <span class="input-group-text bg-transparent border-0 rounded-0"><i class="feather-search"></i></span>
                        <input type="text" id="header_food_search_input" class="form-control bg-transparent border-0 rounded-0 px-0 shadow-none" placeholder="Search {{Helpers::getRandomFood($foodList)}}" aria-label="" style="height: 45px;">
                    </div>
                </div>
                <div class="col-3">
                    <div class="d-flex align-items-center justify-content-end" style="min-height: 60px;">
                        <!-- signin -->
                        @php
                            $customer = Auth::guard('customer')->user();
                        @endphp
                        @if ($customer)
                            <div class="dropdown me-4 m-none">
                                <a href="javascript:void(0)" class="text-dark py-3 d-block" id="dropdownMenuButton" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">

                                    <i class="feather-user img-fluid rounded-circle header-user me-2 header-user"></i>{{ Str::ucfirst($customer->f_name) }} {{ Str::ucfirst($customer->l_name) }}
                                </a>
                                <div class="dropdown-menu dropdown-menu-right">
                                    <a class="dropdown-item" href="{{ route('user.view') }}">My account</a>
                                    <a class="dropdown-item" href="javascript::void(0)">Delivery support</a>
                                    <a class="dropdown-item" href="{{ route('user.contact-us') }}">Contant us</a>
                                    <a class="dropdown-item"href="{{ route('user.pages', ['name' => 'about_us']) }}">About Us</a>
                                    <a class="dropdown-item"href="{{ route('user.pages', ['name' => 'privacy_policy']) }}">Privacy Policy</a>
                                    <a class="dropdown-item"href="{{ route('user.pages', ['name' => 'terms_and_conditions']) }}">T&C</a>
                                    <a class="dropdown-item" href="{{ route('user.auth.logout') }}"><i class="fas fa-sign-out h6 me-2 mb-0"></i>Logout</a>
                                </div>
                            </div>
                        @else
                            <a href="{{ route('user.auth.login') }}" class="widget-header me-4 text-dark m-none">
                                <div class="icon d-flex align-items-center">
                                    <i class="feather-user h6 me-2 mb-0"></i> <span>Sign in</span>
                                </div>
                            </a>
                        @endif
                        <!-- my account -->
                        <!-- signin -->
                        <a href="{{ route('user.notifications') }}" class="widget-header me-4 text-dark">
                            <div class="icon d-flex align-items-center">
                                <div class="position-relative">
                                    <i class="feather-bell h6 me-2 mb-0 fs-5" style="color:goldenrod;font-weight:bolder;"></i>
                                    <small class="position-absolute top-0 start-100 translate-middle bg-danger rounded-circle p-1 text-white">
                                        @auth('customer')
                                        @php($unreadCount = auth('customer')->user()->unreadNotifications()->count())
                                        @if($unreadCount > 0)
                                        <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger notification-badge" style="font-size: 10px;">
                                            {{ $unreadCount > 99 ? '99+' : $unreadCount }}
                                        </span>
                                         @else
                                        <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger notification-badge d-none" style="font-size: 10px;">
                                            0
                                        </span>
                                        @endif
                                    @endauth
                                    </small>
                                </div>
                                {{-- <span>&nbsp;&nbsp;Notfications</span> --}}
                            </div>
                        </a>
                        {{-- <button id="darkModeToggle">D</button> --}}

                        {{-- <a class="toggle" href="javascript:void(0)">
                                <span></span>
                            </a> --}}
                        <a class="toggle text-dark fs-5 toggle-btn align-self-center" data-bs-toggle="offcanvas" href="#offcanvasExample" href="javascript:void(0)" aria-controls="offcanvasExample">
                            <i class="fa-solid fa-bars"></i>
                        </a>
                    </div>
                    <!-- widgets-wrap.// -->
                </div>
            </div>
        </div>
    </section>
</header>
