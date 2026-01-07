@php

    if (!isset($appliedCoupons)) {
        $appliedCoupons = Helpers::getOrderSessions(auth('customer')->user()->id, 'applied_coupons');
    }

@endphp
@foreach ($appliedCoupons??[] as $coupon)
    <div class="p-3 m-0 bg-white rounded-end-top-4" style="border-left: 5px solid #ff810a;">
        <div class="d-flex justify-content-between align-self-center" v>
            <div>
                <!-- <div class="mb-0 d-flex align-self-center"> -->
                <h6 class="fw-bolder mb-0 align-self-center">{{ $coupon['code'] }}</h6>
                <p class="mb-0" style="color:#09c4b2;">{{ Str::ucfirst($coupon['title']) }}</p>
                <!-- </div> -->
            </div>
            <a class="text-warning fs-6 fw-bold align-self-center"
                href="{{ route('user.restaurant.remove-applied-coupon', $coupon['id']) }}">Remove</a>
        </div>
        {{-- <p class="mb-0">{{Str::ucfirst($coupon->description??null)}}</p> --}}
    </div>
@endforeach

