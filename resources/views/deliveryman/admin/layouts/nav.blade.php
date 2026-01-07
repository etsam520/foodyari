<div class="offcanvas offcanvas-end sidebar" tabindex="-1" id="offcanvasExample"
    aria-labelledby="offcanvasExampleLabel">
    <div class="offcanvas-header bg-warning text-white">
        <h5 class="offcanvas-title fw-bolder" id="offcanvasExampleLabel">Foodyari</h5>
        <button type="button" class="btn-close" data-bs-dismiss="offcanvas" aria-label="Close"></button>
    </div>
    <div class="offcanvas-body p-0">
        <div class="d-flex align-content-between flex-wrap h-100">
            <div class="w-100">
                @if (Auth::guard('delivery_men')->check())
                    @php($dm = auth('delivery_men')->user())
                    <div class="bg-white rounded ">
                        <a href="{{ route('deliveryman.dashboard') }}" class="">
                            <div class="d-flex align-items-center p-3">
                                <div class="left me-3">
                                    <img alt="#" src="{{ asset('assets/user/img/user2.png') }}" class="rounded-circle">
                                </div>
                                <div class="right">
                                    <h6 class="mb-1 fw-bold">Hello
                                        {{Str::ucfirst($dm->f_name) . ' ' . Str::ucfirst($dm->l_name)}}
                                        <i class="feather-check-circle text-success"></i>
                                    </h6>
                                    <p class="text-muted m-0 small">+91 {{Str::ucfirst($dm->phone)}}
                                    </p>
                                </div>
                            </div>
                        </a>
                    </div>
                @else
                    <div class="py-3 px-4 fs-6 border-bottom border-top">
                        <a class="text-dark fw-bolder" href="{{ route('deliveryman.auth.login') }}">
                            <i class="fa-solid fa-right-to-bracket me-2"></i>Sign In
                        </a>
                    </div>
                @endif
                {{-- <a class="text-dark fw-bolder" href="{{route('deliveryman.dashboard')}}">
                    <div class="py-3 px-4 fs-6 border-bottom border-top">
                        <i class="fa-solid fa-home me-2"></i> Homepage
                    </div>
                </a> --}}
                <a class="text-dark fw-bolder" href="{{route('deliveryman.profile')}}">
                    <div class="py-3 px-4 fs-6 border-bottom border-top">
                        <i class="fa-solid fa-user me-2"></i> Profile
                    </div>
                </a>
                <a class="text-dark fw-bolder" href="{{route('deliveryman.attendance')}}">
                    <div class="py-3 px-4 fs-6 border-bottom border-top">
                        <i class="fa-solid fa-check-circle me-2"></i> Attendance
                    </div>
                </a>
                <a class="text-dark fw-bolder notification-sidebar-link" href="{{route('deliveryman.admin.notifications')}}">
                    <div class="py-3 px-4 fs-6 border-bottom border-top position-relative notification-sidebar-item">
                        <i class="fa-solid fa-bell me-2 text-primary"></i> 
                        <span class="notification-text">Notifications</span>
                        <span class="badge bg-danger rounded-pill position-absolute notification-badge-sidebar" id="sidebarNotificationCount" style="top: 50%; right: 15px; transform: translateY(-50%); font-size: 0.65rem; min-width: 18px; height: 18px; display: none; line-height: 16px;">0</span>
                        <span class="notification-indicator" id="sidebarNotificationIndicator" style="display: none;"></span>
                    </div>
                </a>
                <a class="text-dark fw-bolder" href="{{route('deliveryman.admin.order-delivered-list')}}">
                    <div class="py-3 px-4 fs-6 border-bottom border-top">
                        <i class="fa-solid fa-shopping-bag me-2"></i>Delivered Orders
                    </div>
                </a>
                <a class="text-dark fw-bolder" href="{{route('deliveryman.wallet.index')}}">
                    <div class="py-3 px-4 fs-6 border-bottom border-top">
                        <i class="fa-solid fa-wallet me-2"></i>Wallet
                    </div>
                </a>
                <a class="text-dark fw-bolder" href="{{route('deliveryman.cash.histories')}}">
                    <div class="py-3 px-4 fs-6 border-bottom border-top">
                        <i class="fa-solid fa-coins me-2"></i> Cash
                    </div>
                </a>
                {{-- <a class="text-dark fw-bolder" href="{{route('deliveryman.admin.report')}}">
                    <div class="py-3 px-4 fs-6 border-bottom border-top">
                        <i class="fa-solid fa-rectangle-list me-2"></i>Order History
                    </div>
                </a> --}}
                {{-- <a class="text-dark fw-bolder" href="{{route('deliveryman.cash.payment-history')}}">
                    <div class="py-3 px-4 fs-6 border-bottom border-top">
                        <i class="fa-solid fa-money-check me-2"></i>Payment History
                    </div>
                </a> --}}
                <div class="accordion border-bottom border-top" id="accordionExample">
                    <div class="accordion-item border-0">
                        <h2 class="accordion-header">
                            <a class="accordion-button collapsed shadow-none py-3 px-4 fw-bolder" type="button"
                                data-bs-toggle="collapse" data-bs-target="#collapseThree" aria-expanded="false"
                                aria-controls="collapseThree">
                                <i class="fa-solid fa-user me-2"></i>More
                            </a>
                        </h2>
                        <div id="collapseThree" class="accordion-collapse collapse" data-bs-parent="#accordionExample">
                            <div class="accordion-body p-0">
                                <a>
                                    <div class="px-5 py-2 fs-6" class="text-dark">
                                        - Delivery support
                                    </div>
                                </a>
                                <a href="" class="text-dark">
                                    <div class="px-5 py-2 fs-6">
                                        - Contact Us
                                    </div>
                                </a>
                                <a href="" class="text-dark">
                                    <div class="px-5 py-2 fs-6">
                                        - Terms of use
                                    </div>
                                </a>
                                <a href="" class="text-dark">
                                    <div class="px-5 py-2 fs-6">
                                        - Privacy & Policy
                                    </div>
                                </a>
                                <a href="" class="text-dark">
                                    <div class="px-5 py-2 fs-6">
                                        - Delete Account
                                    </div>
                                </a>
                            </div>
                        </div>
                    </div>
                </div>

                @if (Auth::guard('delivery_men')->user())
                    <a class="text-dark fw-bolder" href="{{ route('deliveryman.auth.logout') }}">
                        <div class="py-3 px-4 fs-6 border-bottom border-top text-center" style="color:#ff810a;">
                            <i class="fa-solid fa-right-from-bracket me-2"></i>Sign Out
                        </div>
                    </a>
                @endif
            </div>

            {{-- <div class="w-100 text-center d-lg-block d-none">
                <a href="{{route('download')}}" class="btn btn-primary"><i class="feather-download"></i> Download</a>

            </div> --}}

            <div class="w-100 text-center">
                Devloped & Maintained by
                <svg fill="#ff810a" width="30px" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"
                    stroke="#ff810a">
                    <g id="SVGRepo_bgCarrier" stroke-width="0"></g>
                    <g id="SVGRepo_tracerCarrier" stroke-linecap="round" stroke-linejoin="round"></g>
                    <g id="SVGRepo_iconCarrier">
                        <path
                            d="M12 20a1 1 0 0 1-.437-.1C11.214 19.73 3 15.671 3 9a5 5 0 0 1 8.535-3.536l.465.465.465-.465A5 5 0 0 1 21 9c0 6.646-8.212 10.728-8.562 10.9A1 1 0 0 1 12 20z">
                        </path>
                    </g>
                </svg>
                <br>
                <a href="https://givni.in/">Givni Private Limited</a>
            </div>
        </div>
    </div>
</div>
