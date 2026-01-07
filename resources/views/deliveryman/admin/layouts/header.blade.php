
<header class="section-header">
    <section class="header-main shadow-sm bg-white">
        <div class="container">
            <div class="row align-items-center">
                <div class="col-1">
                    <a href="{{route('deliveryman.dashboard')}}" class="brand-wrap mb-0">
                        <img alt="#" class="img-fluid" src="{{asset('assets/user/img/logo_web.png')}}">
                    </a>
                    <!-- brand-wrap.// -->
                </div>
                @if(Session::has('deliveryMan'))
                <div class="col-3 d-flex align-items-center m-none">
                    <div class=" me-3">
                        <a class="text-dark d d-flex align-items-center py-3" role="button" data-bs-toggle="modal" data-bs-target="#userMap">
                            <div><i class="feather-map-pin me-2 bg-light rounded-pill p-2 icofont-size"></i></div>
                            <div class="location-bar" data-address="1">
                            </div>
                        </a>
                    </div>
                </div>
                @endif
                <!-- col.// -->
                <div class="col-8">
                    <div class="d-flex align-items-center justify-content-end pe-5">
                        <!-- signin -->
                        @php($deliveryMan = Auth::guard('delivery_men')->user())
                        @if($deliveryMan)


                        <!-- Notification Bell -->
                        <div class="dropdown me-3 m-none">
                            <a href="javascript:void(0)" class="text-dark py-3 d-block position-relative notification-bell-header" id="notificationDropdown" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false" title="Notifications">
                                <i class="feather-bell h4 mb-0 text-primary"></i>
                                <span class="badge bg-danger rounded-pill position-absolute notification-badge-header" id="notificationCount" style="top: 5px; right: -5px; font-size: 0.65rem; min-width: 18px; height: 18px; display: none; line-height: 16px;">0</span>
                                <span class="notification-pulse" id="notificationPulse" style="display: none;"></span>
                            </a>
                            <div class="dropdown-menu dropdown-menu-end notification-dropdown" style="width: 320px; max-height: 400px; overflow-y: auto;">
                                <div class="dropdown-header d-flex justify-content-between align-items-center">
                                    <h6 class="mb-0">Notifications</h6>
                                    <div>
                                        <button class="btn btn-sm btn-link p-0 me-2" id="markAllRead" title="Mark all as read">
                                            <i class="feather-check-circle"></i>
                                        </button>
                                        <a href="{{ route('deliveryman.admin.notifications') }}" class="btn btn-sm btn-link p-0" title="View all">
                                            <i class="feather-external-link"></i>
                                        </a>
                                    </div>
                                </div>
                                <div class="dropdown-divider"></div>
                                <div id="notificationList">
                                    <div class="text-center py-3">
                                        <div class="spinner-border spinner-border-sm text-primary" role="status">
                                            <span class="visually-hidden">Loading...</span>
                                        </div>
                                    </div>
                                </div>
                                <div class="dropdown-divider"></div>
                                <div class="text-center">
                                    <a href="{{ route('deliveryman.admin.notifications') }}" class="btn btn-sm btn-primary">View All Notifications</a>
                                </div>
                            </div>
                        </div>

                        <div class="dropdown me-4 m-none">
                            <a href="javascript:void(0)" class="text-dark py-3 d-block" id="dropdownMenuButton" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">

                                <i class="feather-user img-fluid rounded-circle header-user me-2 header-user"></i>{{Str::ucfirst($deliveryMan->f_name)}} {{Str::ucfirst($deliveryMan->l_name)}}
                            </a>
                            <div class="dropdown-menu dropdown-menu-right">
                                <a class="dropdown-item" href="{{route('user.view')}}">My account</a>
                                <a class="dropdown-item" href="javascript::void(0)">Delivery support</a>
                                {{-- <a class="dropdown-item" href="{{route('user.contact-us')}}">Contant us</a>
                                <a class="dropdown-item"href="{{route('user.pages',['name' =>'about_us' ])}}">About Us</a>
                                <a class="dropdown-item"href="{{route('user.pages',['name' =>'privacy_policy' ])}}">Privacy Policy</a>
                                <a class="dropdown-item"href="{{route('user.pages',['name' =>'terms_and_conditions' ])}}">T&C</a> --}}
                                <a class="dropdown-item" href="{{route('deliveryman.auth.logout')}}"><i class="fas fa-sign-out h6 me-2 mb-0"></i>Logout</a>
                            </div>
                        </div>
                        @else
                        <a href="{{route('user.auth.login')}}" class="widget-header me-4 text-dark m-none">
                            <div class="icon d-flex align-items-center">
                                <i class="feather-user h6 me-2 mb-0"></i> <span>Sign in</span>
                            </div>
                        </a>
                        @endif
                        <!-- my account -->
                        <!-- signin -->
                        {{-- <a class="toggle" href="javascript:void(0)">
                            <span></span>
                        </a> --}}
                        <a class="toggle text-dark fs-5 toggle-btn align-self-center" data-bs-toggle="offcanvas" href="#offcanvasExample" href="javascript:void(0)" aria-controls="offcanvasExample">
                            <i class="fa-solid fa-bars"></i>
                        </a>
                    </div>
                    <!-- widgets-wrap.// -->
                </div>
                <!-- col.// -->
            </div>
            <!-- row.// -->
        </div>
        <!-- container.// -->
    </section>
    <!-- header-main .// -->
</header>

<div class="d-lg-none d-block">
    <div class="bg-white p-3 d-flex justify-content-between align-items-center">
        <a href="{{route('deliveryman.dashboard')}}" class="brand-wrap mb-0">
            <img alt="#" class="img-fluid" src="{{asset('assets/user/img/logo_web.png')}}">
        </a>

        <a class="toggle fs-5 toggle-btn align-self-center" data-bs-toggle="offcanvas" href="#offcanvasExample"
            href="javascript:void(0)" aria-controls="offcanvasExample" style="color:#ff8a00 !important;">
            <i class="fa-solid fa-bars"></i>
        </a>
    </div>
</div>

