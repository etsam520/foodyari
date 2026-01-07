@php

    $appliedCoupons = Helpers::getOrderSessions(auth('customer')->user()->id, "applied_coupons");
    // dd($appliedCoupons);
@endphp
<div class="row">
    <div class="input-group mb-3">
        <input type="text" class="form-control border-0 py-3" placeholder="Enter Coupon Code" aria-label="Recipient's username" aria-describedby="button-addon2">
        <button class="btn bg-white text-secondary fw-bolder fs-6" type="button" id="button-addon2">APPLY</button>
    </div>
    <p>Best Coupon</p>
    @foreach ($coupons as $coupon)
    <div class="col-12">
        <div class="p-3 m-0 bg-white border-bottom rounded-end-4" style="border-left: 5px solid #ff810a;">
            <div class="d-flex justify-content-between align-self-center" v>
                <div>
                    <!-- <div class="mb-0 d-flex align-self-center"> -->
                    <h6 class="fw-bolder mb-0 align-self-center">{{$coupon->code}}</h6>
                    <p class="mb-0" style="color:#09c4b2;">{{Str::ucfirst($coupon->title)}}</p>
                    <!-- </div> -->
                </div>
                @if (!in_array($coupon->id, array_column($appliedCoupons??[], 'id')))
                <a class="text-warning fs-6 fw-bold align-self-center" couponId="{{$coupon->id}}" couponCode="{{$coupon->code}}" data-coupon="apply" href="javascript:void(0)">Apply</a>
                @else
                <a class="text-warning fs-6 fw-bold align-self-center" href="javascript:void(0)">Applied</a>
                @endif
            </div>
            <hr>
            <p class="mb-0">{{Str::ucfirst($coupon->description??null)}}</p>
        </div>
    </div>
    @endforeach
</div>
