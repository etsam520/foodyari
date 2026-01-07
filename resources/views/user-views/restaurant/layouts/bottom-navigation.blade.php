<div class="osahan-menu-fotter fixed-bottom bg-white px-3 py-2 text-center d-lg-none d-block">
    <div class="d-flex justify-content-around align-items-center">

        <a href="{{ route('userHome') }}" class="fw-bolder text-decoration-none">
            <div class="text-center">
                <div class="text-center w-100">
                    <label class="text-center footer-item active">
                        <i class="fas fa-home"></i>
                    </label>
                    <p class="text-dark small mb-0"> Home</p>
                </div>
            </div>
        </a>
        <a href="{{ route('user.notifications') }}" class="fw-bolder text-decoration-none position-relative">
            <div class="text-center">
                <div class="text-center w-100">
                    <label class="text-center footer-item">
                        <i class="fas fa-bell"></i>
                    </label>
                    <p class="text-dark small mb-0"> Notifications</p>
                    @auth('customer')
                        @php($unreadCount = auth('customer')->user()->unreadNotifications()->count())
                        @if($unreadCount > 0)
                            <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger notification-badge" style="font-size: 10px;">
                                {{ $unreadCount > 99 ? '99+' : $unreadCount }}
                                <span class="visually-hidden">unread notifications</span>
                            </span>
                        @else
                            <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger notification-badge d-none" style="font-size: 10px;">
                                0
                                <span class="visually-hidden">unread notifications</span>
                            </span>
                        @endif
                    @endauth
                </div>
            </div>
        </a>
        {{-- <a href="{{ route('user.restaurant.favorite.') }}" class="fw-bolder text-decoration-none">
            <div class="text-center">
                <div class="text-center w-100">
                    <label class="text-center footer-item">
                        <i class="fas fa-heart"></i>
                    </label>
                    <p class="text-dark small mb-0"> Favorite</p>
                </div>
            </div>
        </a> --}}
        {{-- <div class="" id="toggle-bars">
            <a href="{{ route('user.notifications') }}"class="btn rounded-circle p-2 toggle hc-nav-trigger hc-nav-1" type="button" aria-controls="hc-nav-1" role="button">
                <i class="fa-solid fa-bell fs-2" style="color: white;"></i>
            </a>
        </div> --}}
        <a href="{{ route('user.restaurant.order-list', ['status' => 'all']) }}" class="fw-bolder text-decoration-none position-relative">
            <div class="text-center">
                <div class="text-center w-100">
                    <label class="text-center footer-item">
                        <i class="fas fa-clipboard"></i>
                    </label>
                    <p class="text-dark small mb-0"> Order</p>
                    @auth('customer')
                        @php($scheduledCount = \App\Models\Order::where('customer_id', auth('customer')->id())->where('order_status', 'scheduled')->count())
                        @if($scheduledCount > 0)
                            <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-warning text-dark" style="font-size: 10px;">
                                {{ $scheduledCount }}
                                <span class="visually-hidden">scheduled orders</span>
                            </span>
                        @endif
                    @endauth
                </div>
            </div>
        </a>
        <a href="https://api.whatsapp.com/send?phone={{ \App\CentralLogics\Helpers::getBusinessPhone() }}&text=Hi FoodYari" class="d-flex align-items-center fw-bolder text-decoration-none fs-5 btn py-2 px-3 rounded-3 shadow text-white rounded-pill" style="background: #25D366">
            <i class="fa-brands fa-whatsapp me-3 border-left"></i>
            <div class="mb-0"> Whatsapp</div>
        </a>
        {{-- <a href="javascript:();" class="fw-bolder text-decoration-none">
            <div class="text-center">
                <div class="text-center w-100">
                    <label class="text-center footer-item">
                        <i class="fas fa-bell"></i>
                    </label>
                    <p class="text-dark small mb-0"> Notificationss</p>
                </div>
            </div>
        </a>
        <a data-bs-toggle="offcanvas" href="#offcanvasExample" aria-controls="offcanvasExample"class="toggle toggle-btn fw-bolder text-decoration-none">
            <div class="text-center">
                <div class="text-center w-100">
                    <label class="text-center footer-item">
                        <i class="fas fa-lock"></i>
                    </label>
                    <p class="text-dark small mb-0"> Account</p>
                </div>
            </div>
        </a> --}}
    </div>
</div>
