
<div class="offcanvas offcanvas-start" tabindex="-1" id="offcanvasExample" aria-labelledby="offcanvasExampleLabel">
    <div class="offcanvas-header">
      <h5 class="offcanvas-title" id="offcanvasExampleLabel">Offcanvas</h5>
      <button type="button" class="btn-close" data-bs-dismiss="offcanvas" aria-label="Close"></button>
    </div>
    <div class="offcanvas-body"> 
        <nav id="">
            <ul class="bottom-nav d-flex flex-wrap">
                <li class="email">
                    <a class="text-primary" href="{{route('user.dashboard')}}">
                        <p class="h5 m-0"><i class="feather-home text-primary me-2"></i></p>
                        Home
                    </a>
                </li>
                <li class="profile">
                    <a class="text-primary" href="{{route('user.view')}}">
                        <p class="h5 m-0"><i class="feather-user text-primary"></i> </p>
                        Profile
                    </a>
                </li>
                <li class="order">
                    <a class="text-primary" href="{{route('user.mess.order-list',['status'=>'all'])}}">
                        <p class="h5 m-0"><i class="fa-solid fa-money-bill"></i></p>
                        My Order
                    </a>
                </li><li class="map">
                    <a class="text-primary" href="javascript:void(0)">
                        <p class="h5 m-0"><i class="feather-map-pin text-primary"></i></p>
                        Live Map
                    </a>
                </li><li class="success">
                    <a class="text-primary" href="javascript:void(0)">
                        <p class="h5 m-0"><i class="feather-check-circle text-primary"></i></p>
                        Successful
                    </a>
                </li><li class="checkout">
                    <a class="text-primary" href="javascript:void(0)">
                        <p class="h5 m-0"><i class="feather-list text-primary"></i></p>
                        Checkout
                    </a>
                </li><li class="mess-details">
                    <a class="text-primary" href="javascript:void(0)">
                        <p class="h5 m-0"><i class="feather-paperclip text-primary"></i></p>
                        Mess Detail
                    </a>
                </li><li class="most-popular">
                    <a class="text-primary" href="javascript:void(0)">
                        <p class="h5 m-0"><i class="feather-award text-primary"></i></p>
                         Most Popular
                    </a>
                </li><li class="trending">
                    <a class="text-primary" href="javascript:void(0)">
                        <p class="h5 m-0"><i class="feather-trending-up text-primary"></i> </p>
                        Trending
                    </a>
                </li><li class="favorites">
                    <a class="text-primary" href="javascript:void(0)">
                        <p class="h5 m-0"><i class="feather-heart text-primary"></i></p>
                        Favorites
                    </a>
        
                <li class="github">
                    <a href="javascript:void(0)">
                        <p class="h5 m-0"><i class="feather-message-circle"></i></p>
                        FAQ
                    </a>
                </li>
                <li class="ko-fi">
                    <a href="javascript:void(0)">
                        <p class="h5 m-0"><i class="feather-phone"></i></p>
                        Help
                    </a>
                </li>
            </ul>
        </nav>
    </div>
</div>

{{-- sticky bottom --}}
<div class="bg-white container-fluid fixed-bottom" >
    <div class="row">
        <div class="col-4 text-center"  onclick="location.href = '{{route('user.dashboard')}}'">
            <h1 class="text-warning counter mb-0 mt-1" style="visibility: visible;">
                <i class="fas fa-biking"></i>
            </h1>
            <span class="text-primary mb-1">Delivery</span>
        </div>
        <div class="col-4 text-center">
            <h1 class="text-primary counter mb-0 mt-1" style="visibility: visible;"><i class="fas fa-utensils"></i>
            </h1>
            <span class="text-primary mb-1">Mess/Tiffin</span>
        </div>
        <div class="col-4 text-center" onclick="location.href = '{{route('user.dashboard')}}'">
            <h1 class="text-primary counter mb-0 mt-1" style="visibility: visible;"><i class="fas fa-store"></i></h1>
            <span class="text-primary mb-1">Restaurant</span>
        </div>
    </div>
</div>

{{-- <div class="osahan-menu-fotter fixed-bottom bg-white px-3 py-2 text-center d-none">
    <div class="row">
        <div class="col">
            <a href="{{route('user.dashboard')}}" class="text-primary small fw-bold text-decoration-none">
                <p class="h4 m-0"><i class="feather-home "></i></p>
                Home
            </a>
        </div>
        <div class="col selected">
            <a href="trending.html" class="text-primary small fw-bold text-decoration-none">
                <p class="h4 m-0"><i class="fas fa-biking"></i></p>
                Delivery
            </a>
        </div>
        <div class="col bg-white rounded-circle mt-n4 px-3 py-2">
            <div class="bg-primary rounded-circle mt-n0 shadow">
                <a href="{{route('user.mess.checkout')}}" class="text-white small fw-bold text-decoration-none">
                    <i class="feather-shopping-cart">

                        <span>Cart <sup><b class="badge bg-dark text-white  rounded-circle">7</b></sup></span>
                    </i>

                </a>
            </div>
        </div>
        <div class="col">
            <a href="favorites.html" class="text-primary small fw-bold text-decoration-none">
                <p class="h4 m-0"><i class="fas fa-utensils"></i></i></p>
                Mess
            </a>
        </div>
        <div class="col">
            <a href="{{route('user.view')}}" class="text-primary small fw-bold text-decoration-none">
                <p class="h4 m-0"><i class="fas fa-store"></i></p>
                Restaurant
            </a>
        </div>
    </div>
</div> --}}

