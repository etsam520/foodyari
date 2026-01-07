<nav id="main-nav">
    <ul class="second-nav">
        <li><a href="{{route('user.dashboard')}}"><i class="feather-home me-2"></i> Homepage</a></li>
        @if(Auth::guard('customer')->check())
        <li><a href="{{route('user.mess.meal-page')}}">Meal</a></li>
        <li>
            <a href="javascript:void(0)"><i class="feather-user me-2"></i> Profile</a>
            <ul>
                <li><a href="{{route('user.view')}}">Profile</a></li>
                <li><a href="favorites.html">Delivery support</a></li>
                <li><a href="contact-us.html">Contact Us</a></li>
                <li><a href="terms.html">Terms of use</a></li>
                <li><a href="privacy.html">Privacy & Policy</a></li>
            </ul>
        </li>
        <li>
            <a  href="{{route('user.mess.order-list',['status','all'])}}"><i class="feather-shopping-bag me-2"></i> My Orders</a>
        </li>
        <li>
            <a href="javascript:void(0)"><i class="feather-shopping-bag me-2"></i> My Package</a>
            <ul>
                <li><a href="javascript:void(0)">Active</a></li>
                <li><a href="{{route('user.mess.mess-package-history')}}">Package History</a></li>
            </ul>
        </li>
        {{-- <li><a href="javascript:void(0)"><i class="feather-slack me-2"></i> Diet History</a></li> --}}
        <li>
            <a href="javascript:void(0)"><i class="feather-map-pin me-2"></i> My Address</a>
            <ul>
                <li><a href="javascript:void(0)">Tiffin Delivery Address</a></li>
                <li><a href="javascript:void(0)">Order Address</a></li>
                <li><a href="javascript:void(0)">List Of Address</a></li>
            </ul>
        </li>
        <li>
            <a href="javascript:void(0)"><i class="feather-credit-card me-2"></i> Payments</a>
            <ul>
                <li><a href="{{ route('user.wallet.get') }}">Wallet</a></li>
                <li><a href="{{ route('user.loyalty.get') }}">Loyalty Points</a></li>
                <li><a href="javascript:void(0)">Transaction History</a></li>
                <li><a href="javascript:void(0)">Buy Gift Card</a></li>
                <li><a href="javascript:void(0)">Referal Balance</a></li>
            </ul>
        </li>
        <li>
            <a href="javascript:void(0)"><i class="me-2 feather-gift"></i>Coupons</a>
            <ul>
                <li><a href="javascript:void(0)">Collected Coupons</a></li>
                <li><a href="javascript:void(0)">Restaurant Coupons</a></li>
                <li><a href="javascript:void(0)">Buy Gift Card</a></li>
                <li><a href="javascript:void(0)">Offer Coupons</a></li>
            </ul>
        </li>
        <li>
            <a href="javascript:void(0)"><i class="me-2 feather-tag"></i> Premium Subscription</a>
            <ul>
                <li><a href="javascript:void(0)">Free Delivery</a></li>
                <li><a href="javascript:void(0)">History</a></li>
                <li><a href="javascript:void(0)">Active</a></li>
            </ul>
        </li>
        <li>
            <a href="javascript:void(0)"><i class="feather-heart me-2"></i> Favorites</a>
        </li>

        <li><a href="{{route('user.contact-us')}}">Contact Us</a></li>
        <li><a href="{{route('user.pages',['name' =>'terms_and_conditions' ])}}">T&C</a></li>
        <li><a href="{{route('user.pages',['name' =>'privacy_policy' ])}}">Privacy Policy</a></li>
        <li><a href="{{route('user.pages',['name' =>'about_us' ])}}">About Us</a></li>
           
        <li>
            <a href="javascript:void(0)"><i class="feather-message-circle me-2"></i> Support</a>
            <ul>
                {{-- <li><a href="javascript:void(0)">Live Chat</a></li> --}}
                <li><a href="{{"https://wa.me/".Helpers::get_business_settings('phone')."?text=Hello%2C%20I%20need%20help%20regarding%20my%20recent%20order."}}">Whatsapp</a></li>
                <li><a href="tel:{{Helpers::get_business_settings('phone')}}">Call Us</a></li>
            </ul>
        </li>
        <li>
            <a href="javascript:void(0)"><i class="feather-user-plus me-2"></i> Join us</a>
            <ul>
                <li><a href="javascript:void(0)">Restaurant</a></li>
                <li><a href="javascript:void(0)">Mess</a></li>
                <li><a href="javascript:void(0)">Delivery Boy</a></li>
            </ul>
        </li>

        <li><a href="{{route('user.mess.checkout')}}"><i class="feather-list me-2"></i> Checkout</a></li>

        <li><a href="{{route('user.auth.logout')}}"><i class="feather-log-out me-2"></i> Sign Out</a></li>
        @else
        <li><a href="{{route('user.auth.login')}}"><i class="feather-log-in me-2"></i> Sign In</a></li>
        @endif
      
    </ul>
</nav>

