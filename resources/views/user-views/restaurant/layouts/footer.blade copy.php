<nav id="main-nav">
    <ul class="second-nav">
        <li><a href="{{route('user.dashboard')}}"><i class="feather-home me-2"></i> Homepage</a></li>
        @if(Auth::guard('customer')->user())
            <a href="{{route('user.auth.logout')}}" class="widget-header me-4 text-dark m-none">
                <div class="icon d-flex align-items-center">
                    <i class="feather-log-out h6 me-2 mb-0"></i> <span>Sign Out</span>
                </div>
            </a>
        @else
        <li>
            <a href="{{route('user.auth.login')}}" class="widget-header me-4 text-dark m-none">
                <div class="icon d-flex align-items-center">
                    <i class="feather-sign-in h6 me-2 mb-0"></i> <span>Sign in</span>
                </div>
            </a>
        </li>
        @endif
        <li><a href="{{route('user.restaurant.check-out')}}"><i class="feather-shopping-cart me-2"></i> Checkout</a></li>
        <li><a href="{{route('user.restaurant.order-list', ['status' => 'all'])}}"><i class="feather-list me-2"></i> My Orders</a></li>
        <li>
            <a href="javascript:void(0)"><i class="feather-user me-2"></i> Profile</a>
            <ul>
                @if(Session::has('userInfo'))
                <li><a  href="{{route('user.view')}}">My account</a></li>
                <li><a href="{{route('user.pages',['name' =>'about_us' ])}}">About Us</a></li>
                <li><a  href="{{route('user.contact-us')}}">Contant us</a></li>
                <li><a href="{{route('user.pages',['name' =>'privacy_policy' ])}}">Privacy Policy</a></li>
                <li><a href="{{route('user.pages',['name' =>'terms_and_conditions' ])}}">T&C</a></li>
                <li><a  href="{{route('user.auth.logout')}}"><i class="fas fa-sign-out h6 me-2 mb-0"></i>Logout</a></li>
                @else
                <li><a href="{{route('user.auth.login')}}">Login</a></li>
                @endif
            </ul>
        </li>
       
        <li><a href="javascript:void(0)"><i class="feather-heart me-2"></i> Favorites</a></li>
        @if(Session::has('userInfo'))
        <li>
            <a href="javascript:void(0)"><i class="feather-table me-2"></i> Payments & Wallet</a>
            <ul>
                <li>
                    <a  href="{{route('user.wallet.get')}}"><i class="fa-solid fa-wallet text-warning me-2"></i> Wallet</a>
                </li>
                <li>
                    <a  href="{{route('user.loyalty.get')}}"><i class="fa-solid fa-star text-success me-2"></i> Loyalty Points</a>
                </li>
                <li>
                    <a  href="{{route('user.payments.online')}}"><i class="fa-solid fa-money-check text-warning me-2"></i> Online</a>
                </li>
                <li>
                    <a  href="{{route('user.wallet.get')}}"><i class="fa-solid fa-money-bill text-warning me-2"></i> Cash</a>
                </li>
            </ul>
        </li>
        @endif
       
    </ul>
    <ul class="bottom-nav d-flex justify-content-start">
        <li class="email">
            <a class="text-danger" href="{{route('user.dashboard')}}">
                <p class="h5 m-0"><i class="feather-home text-danger"></i></p>
                Home
            </a>
        </li>
        <li class="github">
            <a href="javascript:void(0)">
                <p class="h5 m-0"><i class="feather-message-circle"></i></p>
                FAQ
            </a>
        </li>
        {{-- <li class="ko-fi">
            <a href="contact-us.html">
                <p class="h5 m-0"><i class="feather-phone"></i></p>
                Help
            </a>
        </li> --}}
    </ul>
</nav>
<div class="modal fade" id="delivery_boy_order_update" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-sm modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-body">
                <div class="osahan-filter">
                    <div class="filter">
                        <p class="h1 text-primary text-center"><i class="fas fa-clipboard-check"></i></p>
                        <h6 class="text-center">New Order Request From a Customer with 2 items.</h6>
                        <p class="mb-0"><b>Item 1 : </b> Chicken Lollipop (×1)</p>
                        <p class="mb-0"><b>Item 2 : </b> Chicken Roll (×1)</p>
                        <hr>
                        <div class="row mt-3">
                            <div class="col-6">
                                <button type="button" class="btn btn-secondary border-top btn-lg w-100 py-1"
                                    data-bs-dismiss="modal">Reject</button>
                            </div>
                            <div class="col-6">
                                <button type="button" class="btn btn-primary btn-lg w-100 py-1">Accept</button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
