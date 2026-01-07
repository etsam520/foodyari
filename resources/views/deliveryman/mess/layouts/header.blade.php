
<header class="section-header">
    <section class="header-main shadow-sm bg-white">
        <div class="container">
            <div class="row align-items-center">
                <div class="col-1">
                    <a href="home.html" class="brand-wrap mb-0">
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
                        <a href="{{route('user.mess.checkout')}}" class="widget-header me-4 text-dark">
                            <div class="icon d-flex align-items-center">
                                <i class="feather-shopping-cart h6 me-2 mb-0"></i> 
                                <span>Cart <sup><b class="badge bg-primary text-white  rounded-circle">7</b></sup></span>
                            </div>
                        </a>
                        <a class="toggle" href="javascript:void(0)">
                            <span></span>
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

