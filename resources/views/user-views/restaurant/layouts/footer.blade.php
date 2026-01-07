@if (Helpers::visibleToThisDevice() == false)
    <style>
        .visible-to-device {
            display: none !important;
        }
    </style>
@endif
<style>

    .footer-item {
        background: #ffc895;
        border-radius: 50%;
        padding: 8px;
        width: 50px;
        font-size: 24px;
        height: 50px;
        color: #000;
    }

    .footer-item.active {
        background: #fca350;
        color: #fff;
    }
</style>
<div class="position-fixed end-0 top-50 d-block d-lg-none visible-to-device"
    style="z-index: 1050; right: 0px; transform: translateX(40%) rotate(270deg);">
    <a href="{{ route('download') }}" class="btn p-0">
        <span
            style="writing-mode: vertical-rl; background: #ff810a; padding: 10px; display: inline-block; border-radius: 8px; box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);">
            <span style="writing-mode: vertical-rl; font-weight: bolder; color: #fff;">
                D<br>O<br>W<br>N<br>L<br>O<br>A<br>D
            </span>
        </span>
    </a>
</div>
<div class="position-fixed top-50 d-lg-block d-none visible-to-device" style="z-index: 1050; right: 0px; transform: translateX(40%) rotate(270deg);">
    <button class="btn p-0" data-bs-toggle="collapse" data-bs-target="#qrAccordion" aria-expanded="false" aria-controls="qrAccordion" style="transition: all 0.3s ease;">
        <span
            style="writing-mode: vertical-rl; background: #ff810a; padding: 10px; display: inline-block; border-radius: 8px; box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1); transition: all 0.3s ease;">
            <span style="writing-mode: vertical-rl; font-weight: bolder; color: #fff; transition: all 0.3s ease;">
                D<br>O<br>W<br>N<br>L<br>O<br>A<br>D
            </span>
        </span>
    </button>

    <div class="collapse position-fixed" id="qrAccordion"
        style="bottom: 42px; width:100%; background: #fff; padding: 15px; border-radius: 8px; box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);">

        <div style="transform:rotate(90deg);">
            <h6 class="text-center">Scan QR Code</h6>
            <img src="{{ asset('assets/images/icons/foodYariLogo.png') }}" alt="QR Code" class="img-fluid"
                style="max-width: 100px; display: block; margin: auto;">
        </div>
    </div>
</div>



<div class="offcanvas offcanvas-end sidebar" tabindex="-1" id="offcanvasExample"
    aria-labelledby="offcanvasExampleLabel">
    <div class="offcanvas-header bg-warning text-white">
        <h5 class="offcanvas-title fw-bolder" id="offcanvasExampleLabel">Foodyari</h5>
        <button type="button" class="btn-close" data-bs-dismiss="offcanvas" aria-label="Close"></button>
    </div>
    <div class="offcanvas-body p-0">
        <div class="d-flex align-content-between flex-wrap h-100">
            <div class="w-100">
                @if (Auth::guard('customer')->user())
                    <div class="bg-white rounded ">
                        <a href="{{ route('user.view') }}" class="">
                            <div class="d-flex align-items-center p-3">
                                <div class="left me-3">
                                    <img alt="#" src="{{ asset('assets/user/img/user2.png') }}" class="rounded-circle">
                                </div>
                                <div class="right">
                                    <h6 class="mb-1 fw-bold">Hello
                                        {{Str::ucfirst(Session::get('userInfo')->f_name) . ' ' . Str::ucfirst(Session::get('userInfo')->l_name)}}
                                        <i class="feather-check-circle text-success"></i>
                                    </h6>
                                    <p class="text-muted m-0 small">+91 {{Str::ucfirst(Session::get('userInfo')->phone)}}
                                    </p>
                                </div>
                            </div>
                        </a>
                    </div>
                @else
                    <div class="py-3 px-4 fs-6 border-bottom border-top">
                        <a class="text-dark fw-bolder" href="{{ route('user.auth.login') }}">
                            <i class="fa-solid fa-right-to-bracket me-2"></i>Sign In
                        </a>
                    </div>
                @endif
                <a class="text-dark fw-bolder" href="{{ route('user.view') }}">
                    <div class="py-3 px-4 fs-6 border-bottom border-top">
                        <i class="fa-solid fa-user me-2"></i> Profile
                    </div>
                </a>
                <a class="text-dark fw-bolder" href="{{route('user.auth.list-user-address')}}">
                    <div class="py-3 px-4 fs-6 border-bottom border-top">
                        <i class="fa-solid fa-map-pin me-2"></i>My Address
                    </div>
                </a>
                <a class="text-dark fw-bolder" href="{{ route('user.restaurant.order-list', ['status' => 'all']) }}">
                    <div class="py-3 px-4 fs-6 border-bottom border-top">
                        <i class="fa-solid fa-box me-2"></i>My orders
                    </div>
                </a>
                <a class="text-dark fw-bolder" href="{{ route('user.refund.index') }}">
                    <div class="py-3 px-4 fs-6 border-bottom border-top">
                        <i class="fa-solid fa-box me-2"></i>My Refunds
                    </div>
                </a>
                <a class="text-dark fw-bolder" href="{{ route('user.restaurant.scheduled-orders') }}">
                    <div class="py-3 px-4 fs-6 border-bottom border-top">
                        <i class="fa-solid fa-box me-2"></i>My Scheduled Orders
                    </div>
                </a>
                <a class="text-dark fw-bolder" href="javascript:void(0)" ahref="{{ route('user.chat.index') }}">
                    <div class="py-3 px-4 fs-6 border-bottom border-top">
                        <i class="fa-solid fa-comments me-2"></i>Chats
                    </div>
                </a>
                <a class="text-dark fw-bolder" href="{{ route('user.wallet.get') }}">
                    <div class="py-3 px-4 fs-6 border-bottom border-top">
                        <i class="fa-solid fa-wallet me-2"></i>Payment & Wallet
                    </div>
                </a>
                <a class="text-dark fw-bolder" href="{{ route('user.loyalty.get') }}">
                    <div class="py-3 px-4 fs-6 border-bottom border-top">
                        <i class="fa-solid fa-star text-warning me-2"></i>Loyalty Points
                    </div>
                </a>
               <!-- referral -->
                <a class="text-dark fw-bolder" href="{{ route('user.referral.index') }}">
                    <div class="py-3 px-4 fs-6 border-bottom border-top">
                        <i class="fa-solid fa-indian-rupee-sign me-2"></i> Refer & Earn
                    </div>


                {{-- <div class="accordion border-bottom border-top" id="accordionExample">
                    <div class="accordion-item border-0">
                        <h2 class="accordion-header">
                            <a class="accordion-button collapsed shadow-none py-3 px-4 fw-bolder" type="button"
                                data-bs-toggle="collapse" data-bs-target="#collapseOne" aria-expanded="false"
                                aria-controls="collapseTwo">
                                <i class="fa-solid fa-wallet me-2"></i>Payment & Wallet
                            </a>
                        </h2>
                        <div id="collapseOne" class="accordion-collapse collapse" data-bs-parent="#accordionExample">
                            <div class="accordion-body">
                                <div class="px-5 mb-3 fs-6">
                                    <a href="{{ route('user.wallet.get') }}">- Wallet</a>
                                </div>
                                <div class="px-5 mb-3 fs-6">
                                    <a href="{{ route('user.payments.online') }}">- Online</a>
                                </div>
                                <div class="px-5 mb-3 fs-6">
                                    <a href="{{ route('user.wallet.get') }}">- Cash</a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div> ---->
                <a class="text-dark fw-bolder" href="javascript:void(0)">
                    <div class="py-3 px-4 fs-6 border-bottom border-top">
                        <i class="fa-solid fa-ticket me-2"></i> Coupons
                    </div>
                </a>
                <a class="text-dark fw-bolder" href="{{ route('user.restaurant.favorite.') }}">
                    <div class="py-3 px-4 fs-6 border-bottom border-top">
                        <i class="fa-solid fa-heart me-2"></i> Favorites
                    </div>
                </a>
                <a class="text-dark fw-bolder" href="{{ route('user.restaurant.collection.') }}">
                    <div class="py-3 px-4 fs-6 border-bottom border-top">
                        <i class="fa-solid fa-heart me-2"></i>  Collections
                    </div>
                </a>
                <!--div class="py-3 px-4 fs-6 border-bottom border-top">
                    <a class="text-dark fw-bolder" href="javascript:void(0)"><i class="fa-solid fa-indian-rupee-sign me-2"></i>
                        Refer & Earn
                    </a>
                <div --> --}}
                <div class="accordion border-bottom border-top" id="accordionExample">
                    <div class="accordion-item border-0">
                        <h2 class="accordion-header">
                            <a class="accordion-button collapsed shadow-none py-3 px-4 fw-bolder" type="button"
                                data-bs-toggle="collapse" data-bs-target="#collapseThree" aria-expanded="false"
                                aria-controls="collapseThree">
                                <i class="fa-regular fa-handshake me-2"></i>Join us as
                            </a>
                        </h2>
                        <div id="collapseThree" class="accordion-collapse collapse" data-bs-parent="#accordionExample">
                            <div class="accordion-body p-0">
                                <a href="{{ route('join-as.restaurant') }}" class="text-dark">
                                    <div class="px-5 py-2 fs-6">
                                        - Restaurant
                                    </div>
                                </a>
                                <a>
                                    <div class="px-5 py-2 fs-6" class="text-dark">
                                        - Mess
                                    </div>
                                </a>
                                <a href="{{ route('join-as.deliveryman') }}" class="text-dark">
                                    <div class="px-5 py-2 fs-6">
                                        - Delivery Boy
                                    </div>
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
                <!--div class="py-3 px-4 fs-6 border-bottom border-top">
                    <a class="text-dark fw-bolder" href="javascript:void(0)"><i class="fa-solid fa-location-dot me-2"></i>
                        List your nearby
                    </a>
                </div -->

                <div class="accordion border-bottom border-top" id="accordionExample">
                    <div class="accordion-item border-0">
                        <h2 class="accordion-header">
                            <a class="accordion-button collapsed shadow-none py-3 px-4 fw-bolder" type="button"
                                data-bs-toggle="collapse" data-bs-target="#collapseTwo" aria-expanded="false"
                                aria-controls="collapseTwo"> <i class="fa-regular fa-square-plus me-2"></i>More
                            </a>
                        </h2>
                        <div id="collapseTwo" class="accordion-collapse collapse" data-bs-parent="#accordionExample">
                            <div class="accordion-body p-0">
                                <a href="{{ route('user.pages', ['name' => 'terms_and_conditions']) }}"
                                    class="text-dark">
                                    <div class="px-5 py-2 fs-6">
                                        - Terms & Conditions
                                    </div>
                                </a>
                                <a href="{{ route('user.pages', ['name' => 'privacy_policy']) }}" class="text-dark">
                                    <div class="px-5 py-2 fs-6">
                                        - Privacy Policy
                                    </div>
                                </a>
                                <a href="{{ route('user.pages', ['name' => 'refund_policy']) }}" class="text-dark">
                                    <div class="px-5 py-2 fs-6">
                                        - Refund & Cancellation
                                    </div>
                                </a>
                                <a href="{{ route('user.contact-us') }}" class="text-dark">
                                    <div class="px-5 py-2 fs-6">
                                        - Contact Us
                                    </div>
                                </a>
                                <a href="{{ route('user.pages', ['name' => 'about_us']) }}" class="text-dark">
                                    <div class="px-5 py-2 fs-6">
                                        - About Us
                                    </div>
                                </a>
                                <a href="{{ route('user.auth.delete-account') }}" class="text-dark">
                                    <div class="px-5 py-2 fs-6">
                                        - Delete Account
                                    </div>
                                </a>
                            </div>
                        </div>
                    </div>
                </div>


                {{-- <div class="accordion border-bottom border-top" id="accordionExample">
                    <div class="accordion-item border-0">
                        <h2 class="accordion-header">
                            <a class="accordion-button collapsed shadow-none py-3 px-4 fw-bolder" type="button"
                                data-bs-toggle="collapse" data-bs-target="#collapseFour" aria-expanded="false"
                                aria-controls="collapseThree">
                                <i class="fa-solid fa-location-dot me-2"></i>List your nearby
                            </a>
                        </h2>
                        <div id="collapseFour" class="accordion-collapse collapse" data-bs-parent="#accordionExample">
                            <div class="accordion-body">
                                <div class="px-5 mb-3 fs-6">
                                    <a>- Restaurant</a>
                                </div>
                                <div class="px-5 mb-3 fs-6">
                                    <a>- Mess</a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div> --}}
                {{-- <div class="py-3 px-4 fs-6 border-bottom border-top">
                    <a class="text-dark fw-bolder" href="javascript:();">
                        <i class="fa-solid fa-download me-2"></i>Download Bill
                    </a>
                </div> --}}
                @if (Auth::guard('customer')->user())
                    <a class="text-dark fw-bolder" href="{{ route('user.auth.logout') }}">
                        <div class="py-3 px-4 fs-6 border-bottom border-top text-center" style="color:#ff810a;">
                            <i class="fa-solid fa-right-from-bracket me-2"></i>Sign Out
                        </div>
                    </a>
                @endif
            </div>

            <div class="w-100 text-center d-lg-block d-none">
                <a href="{{route('download')}}" class="btn btn-primary"><i class="feather-download"></i> Download</a>
            </div>

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
