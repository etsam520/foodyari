<nav id="main-nav">
    <ul class="second-nav">
        <li><a href="{{route('deliveryman.dashboard')}}"><i class="feather-home me-2"></i> Homepage</a></li>
        @if(Auth::guard('delivery_men')->check())
        <li>
            <a href="javascript:void(0)"><i class="feather-user me-2"></i> Profile</a>
            <ul>
                <li><a href="javascript:void(0)">Profile</a></li>
                <li><a href="javascript:void(0)">Delivery support</a></li>
                <li><a href="javascript:void(0)">Contact Us</a></li>
                <li><a href="javascript:void(0)">Terms of use</a></li>
                <li><a href="javascript:void(0)">Privacy & Policy</a></li>
            </ul>
        </li>
        <li>
            <a href="{{route('deliveryman.restaurant.order-list',['state'=>'delivered'])}}"><i class="feather-shopping-bag me-2"></i> Deliverd Orders</a>
          
        </li>
        
        <li><a href="javascript:void(0)"><i class="feather-slack me-2"></i> Diet History</a></li>
       
        <li>
            <a href="javascript:void(0)"><i class="feather-layout me-2"></i> Pages</a>
            <ul>
                <li><a href="{{route('user.mess.meal-page')}}">Meal</a></li>
                <li><a href="{{route('user.contact-us')}}">Contact Us</a></li>
                <li><a href="{{route('user.pages',['name' =>'terms_and_conditions' ])}}">T&C</a></li>
                <li><a href="{{route('user.pages',['name' =>'privacy_policy' ])}}">Privacy Policy</a></li>
                <li><a href="{{route('user.pages',['name' =>'about_us' ])}}">About Us</a></li>
            </ul>
        </li>
        <li>
            <a href="javascript:void(0)"><i class="feather-message-circle me-2"></i> Support</a>
            <ul>
                <li><a href="javascript:void(0)">Live Chat</a></li>
                <li><a href="javascript:void(0)">Whatsapp</a></li>
                <li><a href="javascript:void(0)">Call Us</a></li>
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
        


        <li><a href="{{route('user.auth.logout')}}"><i class="feather-log-out me-2"></i> Sign Out</a></li>
        @else
        <li><a href="{{route('user.auth.login')}}"><i class="feather-log-in me-2"></i> Sign In</a></li>
        @endif
      
    </ul>
    {{-- <ul class="bottom-nav">
        <li class="email">
            <a class="text-danger" href="home.html">
                <p class="h5 m-0"><i class="feather-home text-danger"></i></p>
                Home
            </a>
        </li>
        <li class="github">
            <a href="faq.html">
                <p class="h5 m-0"><i class="feather-message-circle"></i></p>
                FAQ
            </a>
        </li>
        <li class="ko-fi">
            <a href="contact-us.html">
                <p class="h5 m-0"><i class="feather-phone"></i></p>
                Help
            </a>
        </li>
    </ul> --}}
</nav>

