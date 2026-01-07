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
            <a href="{{route('deliveryman.admin.order-list',['state'=>'delivered'])}}"><i class="feather-shopping-bag me-2"></i> Deliverd Orders</a>
        </li>
        <li>
            <a href="{{route('deliveryman.wallet.index')}}">
                <i class="icon">
                    <svg  width="15" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <path fill-rule="evenodd" clip-rule="evenodd" d="M21.9964 8.37513H17.7618C15.7911 8.37859 14.1947 9.93514 14.1911 11.8566C14.1884 13.7823 15.7867 15.3458 17.7618 15.3484H22V15.6543C22 19.0136 19.9636 21 16.5173 21H7.48356C4.03644 21 2 19.0136 2 15.6543V8.33786C2 4.97862 4.03644 3 7.48356 3H16.5138C19.96 3 21.9964 4.97862 21.9964 8.33786V8.37513ZM6.73956 8.36733H12.3796H12.3831H12.3902C12.8124 8.36559 13.1538 8.03019 13.152 7.61765C13.1502 7.20598 12.8053 6.87318 12.3831 6.87491H6.73956C6.32 6.87664 5.97956 7.20858 5.97778 7.61852C5.976 8.03019 6.31733 8.36559 6.73956 8.36733Z" fill="currentColor"></path>
                        <path opacity="0.4" d="M16.0374 12.2966C16.2465 13.2478 17.0805 13.917 18.0326 13.8996H21.2825C21.6787 13.8996 22 13.5715 22 13.166V10.6344C21.9991 10.2297 21.6787 9.90077 21.2825 9.8999H17.9561C16.8731 9.90338 15.9983 10.8024 16 11.9102C16 12.0398 16.0128 12.1695 16.0374 12.2966Z" fill="currentColor"></path>
                        <circle cx="18" cy="11.8999" r="1" fill="currentColor"></circle>
                    </svg>

                </i>
                 Wallet</a>
        </li>
        <li>
            <a href="{{route('deliveryman.cash.histories')}}">
                <i class="icon">
                    <svg width="15" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <g id="SVGRepo_bgCarrier" stroke-width="0"></g>
                        <g id="SVGRepo_tracerCarrier" stroke-linecap="round" stroke-linejoin="round"></g>
                        <g id="SVGRepo_iconCarrier">
                            <path
                                d="M18 6H21C21.5523 6 22 6.44772 22 7V17C22 17.5523 21.5523 18 21 18H10M13 6H3C2.44772 6 2 6.44772 2 7V17C2 17.5523 2.44772 18 3 18H6M6 9.5V14.5M18 9.5V14.5M14.5 12C14.5 13.3807 13.3807 14.5 12 14.5C10.6193 14.5 9.5 13.3807 9.5 12C9.5 10.6193 10.6193 9.5 12 9.5C13.3807 9.5 14.5 10.6193 14.5 12Z"
                                stroke="#000000" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"></path>
                        </g>
                    </svg>
                </i> Cash
            </a>
        </li>

        {{-- <li><a href="javascript:void(0)"><i class="feather-slack me-2"></i> Diet History</a></li> --}}

        {{-- <li>
            <a href="javascript:void(0)"><i class="feather-layout me-2"></i> Pages</a>
            <ul>
                <li><a href="{{route('user.mess.meal-page')}}">Meal</a></li>
                <li><a href="{{route('user.contact-us')}}">Contact Us</a></li>
                <li><a href="{{route('user.pages',['name' =>'terms_and_conditions' ])}}">T&C</a></li>
                <li><a href="{{route('user.pages',['name' =>'privacy_policy' ])}}">Privacy Policy</a></li>
                <li><a href="{{route('user.pages',['name' =>'about_us' ])}}">About Us</a></li>
            </ul>
        </li> --}}
        {{-- <li>
            <a href="javascript:void(0)"><i class="feather-message-circle me-2"></i> Support</a>
            <ul>
                <li><a href="javascript:void(0)">Live Chat</a></li>
                <li><a href="javascript:void(0)">Whatsapp</a></li>
                <li><a href="javascript:void(0)">Call Us</a></li>
            </ul>
        </li> --}}
        {{-- <li>
            <a href="javascript:void(0)"><i class="feather-user-plus me-2"></i> Join us</a>
            <ul>
                <li><a href="javascript:void(0)">Restaurant</a></li>
                <li><a href="javascript:void(0)">Mess</a></li>
                <li><a href="javascript:void(0)">Delivery Boy</a></li>
            </ul>
        </li> --}}



        <li><a href="{{route('deliveryman.auth.logout')}}"><i class="feather-log-out me-2"></i> Sign Out</a></li>
        @else
        <li><a href="{{route('deliveryman.auth.login')}}"><i class="feather-log-in me-2"></i> Sign In</a></li>
        @endif

    </ul>
    {{-- <ul class="bottom-nav">
        <li class="email">
            <a class="text-danger" href="{{route('user.dashboard')}}">
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

